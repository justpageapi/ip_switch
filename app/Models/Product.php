<?php

namespace App\Models;

use App\Helpers\SessionHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Product extends Model
{
    use HasFactory;
    protected $appends = ['imagepath', 'productpricebyshop', 'allimage', 'product_group'];
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $guarded = ['*'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getunitPriceAttribute($val)
    {
        return sprintf('%.2f', $val);
    }

    public function getimagepathAttribute()
    {
        $out = null;
        if ($this->image != null) {
            $out = config('imageurl.product_img_url') . $this->image;
        } else {
            $out = config('imageurl.product_img_url') . 'dummyproduct.jpg';
        }
        return $out;
    }

    public static function getProductByCustomer()
    {

        $user_id = auth()->id();
        $prd_id = [];

        $wish_list_ids = Wishlist::getWishlistProductid();

        if (count($wish_list_ids) > 0) {
            foreach ($wish_list_ids as $ids) {
                array_push($prd_id, $ids);
            }
        }

        $order_data = Order::with('orderitem')->where('customer_id', $user_id)->latest()->take(5)->get();
        if ($order_data != null) {
            if (count($order_data) > 0) {
                $i = count($prd_id);
                foreach ($order_data as $key => $order) {
                    foreach ($order->orderitem as $item) {
                        if ($i <= 6) {
                            array_push($prd_id, $item->product_id);
                            $i = $i + 1;
                        }
                    }
                }
            }
        }
        if (count($prd_id) < 6) {
            $cart_session = Session::get('Cart');
            $cart_cat_id = $cart_session[0]['category_id'];

            $products = Product::getProductByCatId($cart_cat_id)->take(count($prd_id) - 6);
            foreach ($products as $p) {
                array_push($prd_id, $p->id);
            }
        }
        // dd($prd_id);
        return Product::whereIn('id', $prd_id)->get();
    }

    public function GetAllimageAttribute()
    {
        $out = array();
        if ($this->image != null) {
            $out[] = config('imageurl.product_img_url') . $this->image;
            if (isset($this->productimages) && $this->productimages != null) {
                foreach ($this->productimages as $img) {
                    $out[] = config('imageurl.product_img_url') . $img->image;
                }
            }
        } else {
            $out[] = config('imageurl.product_img_url') . 'dummyproduct.jpg';
        }
        return $out;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function brand()
    {
        return $this->belongsTo(Category::class, 'brand_id', 'id')->where('is_active', 1);
    }
    public function group()
    {
        return $this->belongsTo(Category::class, 'group_id', 'id');
    }
    public function shopcurrentstock()
    {
        return $this->hasMany(ShopCurrentStock::class, 'product_id', 'id');
    }

    public function getproductGroupAttribute()
    {
        $productGroup = ProductInfo::where('product_id', $this->id)->first();
        if ($productGroup != null) {
            return $productGroup->product_group_id;
        }
        return null;
    }



    public static function getBrandByCatId($category_id)
    {

        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }


        if (isset($shop_id) && $shop_id != null) {
            $product_ids = ShopCurrentStock::where('shop_id', $shop_id)->where('qty', '>', 0)->pluck('product_id');
        }

        // $product = Product::with('brand');
        // if (isset($product_ids) && $product_ids != null) {
        //     $product = $product->whereIn('id', $product_ids);
        // }
        // $product = $product->where(array('category_id' => $category_id, 'is_active' => 1, 'is_delete' => 0))->groupBy('brand_id')
        //     ->whereHas('brand', function ($query) {
        //         $query->OrderByRaw('CASE WHEN brand_sequence IS NULL OR brand_sequence = "" THEN 1 ELSE 0 END, brand_sequence ASC');
        //     })
        //     ->get();


        $product = Product::select('product.*')
            ->join('category', 'product.brand_id', '=', 'category.id')
            ->when(isset($product_ids) && $product_ids != null, function ($query) use ($product_ids) {
                return $query->whereIn('product.id', $product_ids);
            })
            ->where([
                'product.category_id' => $category_id,
                'product.is_active' => 1,
                'product.is_delete' => 0,
            ])
            ->groupBy('product.brand_id')
            ->orderByRaw('CASE WHEN category.brand_sequence IS NULL OR category.brand_sequence = "" THEN 1 ELSE 0 END, category.brand_sequence ASC')
            ->with('brand')
            ->get();


        return $product;
    }

    public static function getProductByCatAndBrand($brand_id, $category_id)
    {
        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }
        if (isset($shop_id) && $shop_id != null) {
            $product_ids = ShopCurrentStock::where('shop_id', $shop_id)->where('qty', '>', 0)->pluck('product_id');
        }


        $productGroupIds = [];
        $productGroup = ProductGroup::where('is_active', 1)->get();
        foreach ($productGroup as $group) {
            $a = ProductInfo::where('product_group_id', $group->id)->get();
            if ($a->count() > 1) {
                array_push($productGroupIds, $a[0]->product_id);
            }
        }
        // dd($productGroupIds);
        $productGroupIgnoreIds = ProductInfo::where('product_group_id', '!=', null)->wherenotin('product_id', $productGroupIds)->pluck('product_id');
        $product = Product::with('brand');
        if (isset($product_ids) && $product_ids != null) {
            $product = $product->whereIn('id', $product_ids);
        }
        $product = $product->wherenotin('id', $productGroupIgnoreIds)->where(array('brand_id' => $brand_id, 'category_id' => $category_id, 'is_active' => 1, 'is_delete' => 0))->orderBy('name', 'ASC')->get();
        return $product;
    }
    /*public static function getProductByCatAndBrand($brand_id,$category_id){
    return Product::with('brand')->where(array('brand_id'=>$brand_id,'category_id'=>$category_id,'is_active'=>1))->get();
    }*/
    public static function getProductSearch($term)
    {

        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }
        if (isset($shop_id) && $shop_id != null) {
            $product_ids = ShopCurrentStock::where('shop_id', $shop_id)->where('qty', '>', 0)->pluck('product_id');
        }


        $productGroupIds = [];
        $productGroup = ProductGroup::where('is_active', 1)->get();
        foreach ($productGroup as $group) {
            $a = ProductInfo::where('product_group_id', $group->id)->get();
            if ($a->count() > 1) {
                array_push($productGroupIds, $a[0]->product_id);
            }
        }
        // dd($productGroupIds);
        $productGroupIgnoreIds = ProductInfo::where('product_group_id', '!=', null)->wherenotin('product_id', $productGroupIds)->pluck('product_id');


        $product = Product::with('brand');
        if (isset($product_ids) && $product_ids != null) {
            $product = $product->whereIn('id', $product_ids);
        }
        $product = $product->where('name', 'like', '%' . $term . '%')->wherenotin('id', $productGroupIgnoreIds)->where('is_active', 1)->where('is_delete', 0)->get();
        return $product;
    }

    public static function getProductData($brand_id, $category_id, $name)
    {
        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }
        if (isset($shop_id) && $shop_id != null) {
            $product_ids = ShopCurrentStock::where('shop_id', $shop_id)->where('qty', '>', 0)->pluck('product_id');
        }
        $product = Product::with('brand');
        if (isset($product_ids) && $product_ids != null) {
            $product = $product->whereIn('id', $product_ids);
        }
        if ($name != null) {
            $product = $product->where('name', $name);
        }
        $product = $product->where(array('brand_id' => $brand_id, 'category_id' => $category_id, 'is_active' => 1, 'is_delete' => 0))->first();
        return $product;
    }

    public static function getProductByCatId($category_id, $product_id = null)
    {

        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }
        if (isset($shop_id) && $shop_id != null) {
            $product_ids = ShopCurrentStock::where('shop_id', $shop_id)->where('qty', '>', 0)->pluck('product_id');
        }

        $product = Product::with('brand');
        if (isset($product_ids) && $product_ids != null) {
            $product = $product->whereIn('id', $product_ids);
        }
        return $product = $product->where('id', '!=', $product_id)->where(array('category_id' => $category_id, 'is_active' => 1, 'is_delete' => 0))->inRandomOrder()->limit(5)->get();
    }

    public static function getProductByCatIdLimit6($category_id, $product_id = null)
    {

        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }
        if (isset($shop_id) && $shop_id != null) {
            $product_ids = ShopCurrentStock::where('shop_id', $shop_id)->where('qty', '>', 0)->pluck('product_id');
        }

        $product = Product::with('brand');
        if (isset($product_ids) && $product_ids != null) {
            $product = $product->whereIn('id', $product_ids);
        }
        return $product = $product->where('id', '!=', $product_id)->where(array('category_id' => $category_id, 'is_active' => 1, 'is_delete' => 0))->inRandomOrder()->limit(6)->get();
    }

    public static function getRadndomProduct($product_id = null)
    {

        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }
        if (isset($shop_id) && $shop_id != null) {
            $product_ids = ShopCurrentStock::where('shop_id', $shop_id)->where('qty', '>', 0)->pluck('product_id');
        }

        $product = Product::with('brand');
        if (isset($product_ids) && $product_ids != null) {
            $product = $product->whereIn('id', $product_ids);
        }
        return $product = $product->where('id', '!=', $product_id)->where(array('is_active' => 1, 'is_delete' => 0))->inRandomOrder()->limit(6)->get();
    }

    public function GetproductpricebyshopAttribute()
    {
        $price = 0;
        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }

        $site_product_price = SiteProduct::where(array('product_id' => $this->id, 'site_id' => config('site_setting.site_id')))->first();

        // dd($this->id, config('site_setting.site_id'), $site_product_price);

        if ($site_product_price != null) {
            $price = $site_product_price->unit_price;
        } else {
            $shop_product_price = ShopProductPrice::where(array('product_id' => $this->id, 'shop_id' => $shop_id, 'is_active' => 1))->first();
            if ($shop_product_price != null) {
                $price = $shop_product_price->unit_price;
            } else {
                $price = $this->unit_price;
            }
        }


        return $price;
    }

    public static function getCurrentStock($prd_id)
    {
        $rtn = 0;
        if ($prd_id != null) {
            $shop_id = Setting::getShopId();
            if ($shop_id == null) {
                $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
            }
            // $shop_id = null;
            // if (Session::has('shop_id')) {
            //     $shop_id = Setting::getShopId();
            // }
            // dd($shop_id);
            if ($shop_id == null) {
                $product = Product::find($prd_id);
                if ($product != null) {
                    $rtn = $product->warehouse_stock;
                }
            } elseif ($shop_id == 22) {

                $wr_stock = 0;
                $shop_stock = 0;
                $product = Product::find($prd_id);
                if ($product != null) {
                    $wr_stock = $product->warehouse_stock;
                }
                $shop_product = ShopCurrentStock::where(array('shop_id' => $shop_id, 'product_id' => $prd_id))->first();
                if ($shop_product != null) {
                    $shop_stock = $shop_product->qty;
                }

                $rtn = $wr_stock + $shop_stock;
            } else {
                $product = ShopCurrentStock::where(array('shop_id' => $shop_id, 'product_id' => $prd_id))->first();
                if ($product != null) {
                    $rtn = $product->qty;
                }
            }
        }
        return $rtn;
    }

    public static function getOffer($group_id)
    {
        // $discount = null;
        // $shop_id = Setting::getShopId();
        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }

        $offer = null;
        $list = SiteOffer::where(array('site_id' => config('site_setting.site_id'), 'group_id' => $group_id, 'is_active' => 1, 'is_check' => 1))->orderBy('qty', 'ASC')->take(3)->get();
        if ($list->count() > 0) {
            $offer = $list;
        }





        return $offer;
    }

    public static function getProductDataById($id)
    {
        $shop_id = null;
        $product = null;
        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }
        // if (Session::has('shop_id')) {
        //     $shop_id = Setting::getShopId();
        // }

        if ($shop_id == null) {
            $product = Product::find($id);
        } else {
            $product = ShopCurrentStock::where(array('shop_id' => $shop_id, 'product_id' => $id))->first();
            if ($product != null) {
                $product = $product->product;
            }
        }
        return $product;
    }

    public static function getProductDetailsById($id)
    {
        $shop_id = null;
        $product = null;
        // if (Session::has('shop_id')) {
        //     $shop_id = Setting::getShopId();
        // }
        $shop_id = Setting::getShopId();
        if ($shop_id == null) {
            $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
        }

        if ($shop_id == null) {
            $product = Product::find($id);
        } else {
            $product = ShopCurrentStock::where(array('shop_id' => $shop_id, 'product_id' => $id))->first();
            if ($product != null) {
                $product = $product->product;
            } else {
                $product = Product::find($id);
            }
        }

        return $product;
    }

    public static function updateStock($method, $type, $product_id, $qty)
    {
        $crdata = Product::where(array('id' => $product_id))->first();
        if ($crdata != null) {
            if ($type == 'add') {
                if ($method == 'warehouse_stock') {
                    $crdata->warehouse_stock = $crdata->warehouse_stock + $qty;
                } elseif ($method == 'current_stock') {
                    $crdata->current_stock = $crdata->current_stock + $qty;
                }

                $crdata->save();
            } else if ($type == 'remove') {
                if ($method == 'warehouse_stock') {
                    $crdata->warehouse_stock = $crdata->warehouse_stock - $qty;
                } elseif ($method == 'current_stock') {
                    $crdata->current_stock = $crdata->current_stock - $qty;
                }
                $crdata->save();
            }
        }
    }

    public static function generateSKU($product_id, $brand_id, $model_id, $other = '') {}

    public static function arrayCombinations($arrays)
    {

        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public static function getProductsWithParameter($lvl, $product_id = null, $brand_id = null, $category_id, $search_data, $model_id, $type, $prd_ids = null)
    {
        $user_id = null;
        $model_ids = [];
        $returnData = [];

        return $returnData;
    }

    public static function getModelSkuData($model_id) {}

    public static function getProductHtml($products) {}

    public function getthumbimagepathAttribute()
    {
        return env('imageurl') . 'productimage/' . $this->thumb_image;
    }

    public static function get_brands($pid)
    {
        $product = Product::with('notnullqty')->find($pid);
        $brd_ids = [];
        foreach ($product->notnullqty as $prd) {
            if (!in_array($prd->brand_id, $brd_ids)) {
                $brd_ids[] = $prd->brand_id;
            }
        }
        return $brd_ids;
    }

    public static function searchForId($id, $array)
    {
        if (!empty($array)) {
            foreach ($array as $key => $val) {
                if ($val['product_id'] == $id) {
                    return $key;
                }
            }
        }
        return 'no';
    }

    public static function arraySearch($cloumn, $value, $array)
    {
        if ($cloumn != '' && $value != '' && $array != '') {
            foreach ($array as $key => $val) {
                if ($val[$cloumn] === $value) {
                    return $key;
                }
            }
        }
        return null;
    }
}
