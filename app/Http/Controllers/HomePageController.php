<?php

namespace App\Http\Controllers;

use App\Helpers\CookieHelper;
use App\Helpers\SessionHelper;
use App\Models\BannerImage;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\PostCodeForShop;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductInfo;
use App\Models\Seo;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class HomePageController extends Controller
{

    public function dashboard($ref_code = null, $name = null)
    {
        //dd(Customer::GenerateRemainCustomerCode());
        //dd(env('APP_URL').'/auth/google/callback');
        //dd(SessionHelper::getRefSession());

        if (!Session::has('shop_id')) {
            Session::put('shop_id', 10);
            Session::save();
        }

        // if (Session::has('shop_id')) {
        //     Session::forget('shop_id');
        //     Session::save();
        // }

        // dd($ref_code);
        $category = Cache::remember('category', env('time'), function () {
            return Category::getAllCategory();
        });
        return view('frontend.home', compact('category'));
    }

    public function refcode($ref_code = null)
    {
        if ($ref_code != null) {
            $validate = Customer::ValidateCode($ref_code);
            if ($validate != null) {
                SessionHelper::setRefSession($ref_code);
            }
        }
        return redirect()->route('home');
    }

    public function getHomeData(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $cid = $request->cid;

        $out = array();
        $dt_html = '';

        if ($type == 'category') {
            ob_start();
            if ($id != '') {
                $brands = Product::getBrandByCatId($id);
                if ($brands->count() > 0) {
                    foreach ($brands as $b) {
                        if (isset($b->brand) && $b->brand != null) {
                            $brand_id = $b->brand->id;
                            $brand_name = $b->brand->name;
                            $category_id = $b->category_id;
                            $id = $brand_id . $category_id;
                            $brand_image = $b->brand->brandimagepath;
?>
                            <div class="card-header main_header_1 border-0" data-bs-toggle="collapse" data-bs-target="#ball_<?php echo $id ?>" role="button" aria-expanded="true" data-placement="bottom" title="View Details" data-id="<?php echo $brand_id ?>" data-cid="<?php echo $category_id ?>" data-isclicked="0">
                                <div class="row">
                                    <div class="col align-self-center">
                                        <div class="row mb-1">
                                            <div class="col-auto">
                                                <figure class="text-center mb-0 avatar avatar-40 page-bg rounded p-0">
                                                    <img src="<?php echo $brand_image; ?>" alt="">
                                                </figure>
                                            </div>
                                            <div class="col align-self-center ps-0">
                                                <h3 class="mb-0 text-uppercase"><?php echo $brand_name; ?></h3>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto align-self-center padding-bottom">
                                        <i id="down" class="fa fa-chevron-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="ball_<?php echo $id; ?>">

                            </div>
                        <?php }
                    }
                } else {
                    echo '<div class="col-12 text-center">
            <h5>No Brand Available</h5>
          </div>';
                }
            } else {

                $category = Category::getAllCategory();

                if ($category->count() > 0) {
                    foreach ($category as $cat) {
                        $category_id = $cat->id;
                        $category_name = $cat->name;
                        $category_image = $cat->imagepath;

                        ?>
                        <div class="col-12">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header main_header border-0 p-0" data-bs-toggle="collapse" data-bs-target="#coll_<?php echo $category_id; ?>" role="button" data-id="<?php echo $category_id; ?>" data-isclicked="0" data-placement="top" title="View Products" aria-expanded="true">
                                    <div class="row gx-0  avatar-category bg-whites text-white text-left" style="background-image: url(<?php echo $category_image ?>), linear-gradient(to right, #232323, #808080);">
                                        <div class="col align-self-center cat-name">
                                            <span class="mb-0 cat-name-text"><?php echo $category_name; ?></span>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <span class="px-1"><i id="down" class="fa fa-chevron-down" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse" id="coll_<?php echo $category_id; ?>">

                                </div>
                            </div>
                        </div>
            <?php }
                } else {
                    echo '<div class="col-12 text-center">
            <h3>No Product Available</h3>
          </div>';
                }
            }
        } elseif ($type == 'product') {
            ob_start();
            ?>
            <div class="card-body">
                <div class="row list-group">
                    <?php if ($id != '' && $cid != null) {
                        $products = Product::getProductByCatAndBrand($id, $cid);
                        if ($products->count() > 0) {
                            foreach ($products as $prd) {
                                $prd_id = $prd->id;
                                $prd_name = $prd->name;
                                $price = $prd->productpricebyshop;
                                $image = $prd->imagepath;
                                $group_id = $prd->group_id;
                                $product_group_id = $prd->product_group;
                                $product_group = ProductGroup::find($product_group_id);
                                $product_group_items = ProductInfo::where('product_group_id', $product_group_id)
                                    ->get();

                    ?>
                                <div class="col-6 list-group-item">
                                    <div class="row mb-2 r-view-small">
                                        <div class="col align-self-center m-block">
                                            <p><a class="view_product_detail text-dark" href="javascript:;" data-id="<?php echo $prd_id; ?>">
                                                    <?php
                                                    if ($product_group == null)
                                                        echo $prd_name;
                                                    else
                                                        echo $product_group->name


                                                    ?>
                                                </a></p>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-auto img-thumbs">
                                            <figure class="text-center mb-0 avatar avatar-100 page-bg rounded p-0">
                                                <img src="<?php echo $image; ?>" alt="<?php echo $prd_name; ?>" class="view_product_detail" data-page="home" data-id="<?php echo $prd_id; ?>">
                                            </figure>
                                        </div>
                                        <div class="col align-self-center ps-0 m-block">
                                            <p class="r-view-hide"><a class="view_product_detail text-dark" href="javascript:;" data-page="home" data-id="<?php echo $prd_id; ?>"><?php echo $prd_name; ?></a></p>

                                            <div class="m-wrapper">
                                                <div class="m-left-wrap">
                                                    <?php echo base64_decode($this->getOffers($group_id, $price)); ?>
                                                </div>
                                                <?php if ($product_group == null) { ?>
                                                    <div class="m-right-wrap" id="cart_btn_dspl_<?php echo $prd_id; ?>">
                                                        <?php echo base64_decode($this->getHtmlCartButton($prd_id, 'home')); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>



                                            <?php
                                            if ($product_group != null) {

                                                if ($product_group_items->count() > 0) {
                                                    foreach ($product_group_items as $key => $block_item) { ?>

                                                        <div class="container mt-2">
                                                            <div class="row align-items-center">

                                                                <div class="col-12 d-flex justify-content-between align-items-center">
                                                                    <!-- Left Column: Product Name -->
                                                                    <div class="view_product_detail" style="cursor: pointer;" data-id="<?php echo $block_item->product->id; ?>">
                                                                        <h6 class="mb-0"><?php echo $block_item->variant_name ?></h6>
                                                                    </div>

                                                                    <!-- Right Column: Button -->
                                                                    <div id="cart_btn_dspl_<?php echo $block_item->product->id; ?>">
                                                                        <?php echo base64_decode($this->getHtmlCartButton($block_item->product->id, 'home')); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                            <?php }
                                                }
                                            } ?>

                                        </div>
                                    </div>
                                </div>
                    <?php }
                            echo '<div class="clearfix"></div>';
                        } else {
                            echo '<div class="col-12 text-center"><h5>No Product Available</h5></div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        } elseif ($type == 'sidebar') {
            ob_start();
            $brands = Category::getAllBrand();
            if ($brands->count() > 0) { ?>
                <?php foreach ($brands as $brand) { ?>
                    <li class="list-group-item"><a class="pointer-class text-normal d-block" onclick="brand_name(<?php echo $brand->id ?>)"><?php echo $brand->name ?><i class="float-end bi bi-arrow-right"></i></a></li>
                <?php }
            }
        } elseif ($type == 'product_detail') {
            ob_start();
            $id = $request->id;
            $page = $request->page;

            if (!isset($page)) {
                $page = 'home';
            }

            if (isset($id)) {
                $product = Product::find($id);
                if (isset($product) && $product != '') {

                    $is_set = CookieHelper::checkCookieSet();
                    if ($is_set == 1) {
                        CookieHelper::setCookieData($product->id);
                    }

                    $id = $product->id;
                    $name = $product->name;
                    $price = $product->productpricebyshop;
                    //$image = $product->imagepath;
                    $images = $product->allimage;
                    $discription = $product->long_discription;
                    $category_name = $product->category->name;
                    $brand_name = $product->brand->name;

                    $slug_url = '#';
                    $seo = Seo::getSeoData('product', $id);
                    $slug_url = $seo['seo_slug_url'];

                ?>

                    <div class="modal-content product border-0 shadow-sm">
                        <div class="card shadow-sm">
                            <div class="card-body pb-0 position-relative">
                                <div class="swiper-container imageswiper">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($images as $image) { ?>
                                            <div class="swiper-slide text-center mb-0 px-5 py-3"><img src="<?php echo $image; ?>" alt="" class="mw-100"></div>
                                        <?php } ?>
                                    </div>
                                    <div class="swiper-pagination imageswiper-pagination"></div>
                                </div>
                            </div>

                            <div class="collapse" id="collapseExample">
                                <div class="card-footer justify-content-center text-center" id="share_urls">
                                    <p class="mb-1 text-opac">Share product with</p>
                                    <a target="_blank" href="<?php echo config('socialurls.twitter_url') . env('APP_URL') . '/products/' . $slug_url; ?>" class="btn btn-link text-color-theme"><i class="bi bi-twitter"></i></a>
                                    <a target="_blank" href="<?php echo config('socialurls.facebook_url') . env('APP_URL') . '/products/' . $slug_url; ?>" class="btn btn-link text-color-theme"><i class="bi bi-facebook"></i></a>
                                    <a target="_blank" href="<?php echo config('socialurls.linkedin_url') . env('APP_URL') . '/products/' . $slug_url; ?>" class="btn btn-link text-color-theme"><i class="bi bi-linkedin"></i></a>
                                    <a target="_blank" href="#" class="btn btn-link text-color-theme"><i class="bi bi-google"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="cls_icon">
                            <a class="btn btn-link text-color-theme p-1 py-0 " data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                            <div class="px-1" id="btn_share" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style=""><i class="fa fa-share-alt"></i></div>
                        </div>

                        <div class="modal-body" id="prd_details">
                            <a href="<?php echo url('products/' . $slug_url); ?>" class="text-normal">
                                <h6 class="text-color-theme"><?php echo $name ?></h6>
                            </a>
                            <div class="row">
                                <div class="col align-self-center ">
                                    <p class="mb-0 price_block"><b>Price : <?php echo config('currency.currency') . $price ?></b></p>
                                </div>
                                <div class="col-auto" id="cart_btn_dspl_<?php echo $id ?>">
                                    <?php echo base64_decode($this->getHtmlCartButton($id, $page)) ?>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col">
                                    <?php echo $discription ?>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-center p-1">
                            <button type="button" class="btn btn-link text-color-theme m-0 p-1" data-bs-dismiss="modal">Done</button>
                        </div>

                    </div>
        <?php
                }
            }
        }

        $dt_html .= ob_get_contents();
        ob_end_clean();

        $out['type'] = 'success';
        $out['message'] = 'success fetch data.';
        $out['html'] = $dt_html;
        echo json_encode($out);
    }

    public function getExtraCartData(Request $request)
    {
        $out = array();
        $dt_html = '';
        ob_start();
        ?>

        <div class="card-body">
            <div class="row list-group">
                <?php
                if (Auth::check()) {
                    $products = Product::getProductByCustomer();
                } else {
                    // $products = Product::getRadndomProduct();
                    $cart_session = Session::get('Cart');
                    $cart_cat_id = $cart_session[0]['category_id'];

                    $products = Product::getProductByCatIdLimit6($cart_cat_id);
                }

                if ($products->count() > 0) {
                    foreach ($products as $prd) {
                        $prd_id = $prd->id;
                        $prd_name = $prd->name;
                        $price = $prd->productpricebyshop;
                        $image = $prd->imagepath;
                        $group_id = $prd->group_id;

                ?>
                        <div class="col-6 list-group-item">
                            <div class="row mb-2 r-view-small">
                                <div class="col align-self-center m-block">
                                    <p><a class="view_product_detail text-dark" href="javascript:;" data-id="<?php echo $prd_id; ?>"><?php echo $prd_name; ?></a></p>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-auto img-thumbs">
                                    <figure class="text-center mb-0 avatar avatar-100 page-bg rounded p-0">
                                        <img src="<?php echo $image; ?>" alt="<?php echo $prd_name; ?>" class="view_product_detail" data-page="cart" data-id="<?php echo $prd_id; ?>">
                                    </figure>
                                </div>
                                <div class="col align-self-center ps-0 m-block">
                                    <p class="r-view-hide"><a class="view_product_detail text-dark" href="javascript:;" data-page="cart" data-id="<?php echo $prd_id; ?>"><?php echo $prd_name; ?></a></p>

                                    <div class="m-wrapper">
                                        <div class="m-left-wrap">
                                            <?php echo base64_decode($this->getOffers($group_id, $price)); ?>
                                        </div>
                                        <div class="m-right-wrap" id="cart_btn_dspl_<?php echo $prd_id; ?>">
                                            <?php echo base64_decode($this->getHtmlCartButton($prd_id, 'cart')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }
                    echo '<div class="clearfix"></div>';
                } else {
                    echo '<div class="col-12 text-center"><h5>No Product Available</h5></div>';
                }

                ?>
            </div>
        </div>

        <?php

        $dt_html .= ob_get_contents();
        ob_end_clean();

        $out['type'] = 'success';
        $out['message'] = 'success fetch data.';
        $out['html'] = $dt_html;
        echo json_encode($out);
    }

    public function searchSuggetion(Request $request)
    {
        $out = array();
        $dt_html = '';

        $search_data = $request->search_data;
        ob_start();
        if ($search_data != '') {
            $products = Product::getProductSearch($search_data); ?>

            <div class="card-body">
                <div class="row list-group">

                    <?php
                    if ($products->count() > 0) {
                        foreach ($products as $prd) {
                            $prd_id = $prd->id;
                            $prd_name = $prd->name;

                            $price = $prd->productpricebyshop;
                            $image = $prd->imagepath;
                            $group_id = $prd->group_id;
                            $product_group_id = $prd->product_group;
                            $product_group = ProductGroup::find($product_group_id);
                            $product_group_items = ProductInfo::where('product_group_id', $product_group_id)
                                ->get();

                    ?>
                            <div class="col-6 list-group-item">
                                <div class="row mb-1">
                                    <div class="col-auto img-thumbs">
                                        <figure class="text-center mb-0 avatar avatar-100 page-bg rounded p-0">
                                            <img src="<?php echo $image; ?>" alt="<?php echo $prd_name; ?>" class="view_product_detail" data-id="<?php echo $prd_id; ?>">
                                        </figure>
                                    </div>
                                    <div class="col align-self-center ps-0 m-block">
                                        <p>
                                            <a class="view_product_detail text-dark" href="javascript:;" data-id="<?php echo $prd_id; ?>">
                                                <?php
                                                if ($product_group == null)
                                                    echo $prd_name;
                                                else
                                                    echo $product_group->name


                                                ?>
                                            </a>
                                        </p>

                                        <div class="m-wrapper">
                                            <div class="m-left-wrap">
                                                <?php echo base64_decode($this->getOffers($group_id, $price)); ?>
                                            </div>

                                            <?php if ($product_group == null) { ?>
                                                <div class="m-right-wrap" id="cart_btn_dspl_<?php echo $prd_id; ?>">
                                                    <?php echo base64_decode($this->getHtmlCartButton($prd_id, 'home')); ?>
                                                </div>
                                            <?php } ?>


                                        </div>





                                        <?php
                                        if ($product_group != null) {

                                            if ($product_group_items->count() > 0) {
                                                foreach ($product_group_items as $key => $block_item) { ?>

                                                    <div class="container mt-2">
                                                        <div class="row align-items-center">

                                                            <div class="col-12 d-flex justify-content-between align-items-center">
                                                                <!-- Left Column: Product Name -->
                                                                <div class="view_product_detail" style="cursor: pointer;" data-id="<?php echo $block_item->product->id; ?>">
                                                                    <h6 class="mb-0"><?php echo $block_item->variant_name ?></h6>
                                                                </div>

                                                                <!-- Right Column: Button -->
                                                                <div id="cart_btn_dspl_<?php echo $block_item->product->id; ?>">
                                                                    <?php echo base64_decode($this->getHtmlCartButton($block_item->product->id, 'home')); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                        <?php }
                                            }
                                        } ?>



                                    </div>
                                </div>
                            </div>

                    <?php }
                        echo '<div class="clearfix"></div>';
                    } else {
                        echo '<div class="col-12 text-center">
                <h5>No Product Found</h5>
              </div>';
                    } ?>

                </div>
            </div>
        <?php }

        $dt_html .= ob_get_contents();
        ob_end_clean();

        $out['type'] = 'success';
        $out['message'] = 'success fetch data.';
        $out['html'] = $dt_html;
        echo json_encode($out);
    }

    public static function getHtmlCartButton($prd_id, $page)
    {

        $qty = 0;
        $is_cart = 0;
        $current_qty = 0;

        $data = SessionHelper::getSessionData($prd_id);
        $current_qty = Product::getCurrentStock($prd_id);

        $user_id = '';
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        }

        if ($data != null) {
            $is_cart = 1;
            $qty = $data['qty'];
        }

        ob_start();
        if ($is_cart == 1) { ?>
            <!-- <div>
          <b><?php echo config('currency.currency') . $data['price'] . ' X ' . $data['qty'] . ' = ' . config('currency.currency') . $data['price'] * $data['qty'] ?></b>
        </div> -->


            <!-- <p class="text-danger p-1 py-0 itm_text"><?php echo $data['qty']; ?> Item Added</p> -->
            <div class="b-counter">
                <div class="input-group plus-minus-input btn-groups">
                    <div class="input-group-button">
                        <button type="button" class="qt-btn button hollow circle" onclick="return addToCart('minus', '<?php echo $prd_id; ?>',1,'<?php echo $page ?>');">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                        </button>
                    </div>
                    <input class="input-group-field" type="number" name="quantity" onchange="return addToCart('qtyInput', '<?php echo $prd_id; ?>',$(this).val(),'<?php echo $page ?>');" value="<?php echo $qty; ?>">
                    <div class="input-group-button">
                        <button type="button" class="qt-btn button hollow circle" onclick="return addToCart('plus', '<?php echo $prd_id; ?>',1,'<?php echo $page ?>');">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="m-btns">
                <?php if ($current_qty > 0) { ?>
                    <button class="btn btn-xs btn-theme shadow-sm mb-0 m-btn cart_btn add_to_cart_btn" onclick="return addToCart('add', '<?php echo $prd_id; ?>',1,'<?php echo $page ?>');">ADD</button>
                <?php } else { ?>
                    <b><span class="text-danger">Out of Stock</span></b>
                <?php } ?>
            </div>

        <?php }

        if ($user_id != '') {
            $check_in_wishlist = Wishlist::checkinlist($prd_id);
        ?>
            <a href="javascript:;" class="btn btn-link text-danger p-1 py-0 wish_btn" data-toggle="tooltip" data-placement="top" title="Wishlist" onclick="addTowishlist(<?php echo $prd_id; ?>,'<?php echo $page ?>')"><i class="size-18 fa fa-heart<?php echo $check_in_wishlist == 1 ? '' : '-o' ?>"></i></a>
            <?php }

        $btn_html = ob_get_contents();
        ob_end_clean();

        return base64_encode($btn_html);
        die;
    }

    public static function getOffers($grp_id, $price)
    {
        $offer = '';

        ob_start();
        if ($grp_id != null) {
            $offers = Product::getOffer($grp_id);
            if ($offers != null && count($offers) > 0) {
                foreach ($offers as $offer) {
                    $offer_text = $offer->qty . ' for ' . config('currency.currency') . $offer->price;
            ?>
                    <div class="price_box">
                        <div class="b-tag">Mix &amp; Match</div>
                        <div class="b-price"><?php echo $offer_text; ?></div>
                    </div>
                <?php }
            } else { ?>
                <div class="price_box">
                    <div class="b-tag">EACH</div>
                    <div class="b-price"><?php echo config('currency.currency') . $price; ?></div>
                </div>
<?php
            }
        }

        $offer = ob_get_contents();
        ob_end_clean();

        return base64_encode($offer);
        die;
    }

    public function addCart(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $qty = $request->qty;
        $product = null;

        if ($id != null && $type != null && $qty != null) {
            $qty = (int) $qty;
            $product = Product::getProductDataById($id);

            if ($product != null) {

                $cart_qty = SessionHelper::getSessionCartQty($id);
                $current_qty = Product::getCurrentStock($id);

                $cart = Session::has('Cart');
                if (!$cart) {
                    $cart = Session('Cart');
                } else {
                    $cart = Session::get('Cart');
                }

                //dd($cart);
                if ($type == 'add') {

                    if ($cart_qty < $current_qty) {
                        $rtn = 0;

                        $id = $product->id;
                        $name = $product->name;
                        $price = $product->productpricebyshop;
                        //$price = $product->unit_price;
                        $category = $product->category->name;
                        $category_id = $product->category->id;
                        $group_id = $product->group->id;
                        $qty = 1;

                        $p['id'] = $id;
                        $p['name'] = $name;
                        $p['qty'] = $qty;
                        $p['price'] = $price;
                        $p['category'] = $category;
                        $p['category_id'] = $category_id;
                        $p['group_id'] = $group_id;

                        if (!Session::has('Cart')) {
                            Session::push('Cart', $p);
                            Session::save();
                            $rtn = 1;
                        } else {
                            $chk_arrr = -1;
                            foreach ($cart as $key => $val) {
                                if ($val['id'] == $id) {
                                    $chk_arrr = $key;
                                }
                            }
                            if ($chk_arrr < 0) {
                                Session::push('Cart', $p);
                                Session::save();
                                $rtn = 1;
                            } else {
                                $c_qty = Session::get('Cart.' . $chk_arrr . '.qty');
                                Session::put('Cart.' . $chk_arrr . '.qty', $cart_qty + $c_qty);
                                Session::save();
                                $rtn = 1;
                            }
                        }
                        $out['type'] = 'success';
                        $out['message'] = 'session created';
                    } else {
                        $out['type'] = 'error';
                        $out['message'] = 'This Product Currently Out of Stock..';
                    }
                } else {
                    $rtn = 0;
                    $key = SessionHelper::getSessionKey($id);
                    //dd($key);

                    if ($key >= 0) {
                        //for qty input
                        if ($type == 'qtyInput') {

                            $id = Session::get('Cart.' . $key . '.id');
                            if ($qty > $current_qty) {
                                $qty = $current_qty;
                                Session::put('Cart.' . $key . '.qty', $qty);
                                Session::save();
                                $rtn = 0;
                            } elseif ($qty <= 0) {
                                Session::forget('Cart.' . $key);
                                Session::save();
                                $rtn = 1;
                            } else {
                                Session::put('Cart.' . $key . '.qty', $qty);
                                Session::save();
                                $rtn = 1;
                            }
                        } else {
                            //for plus and minus
                            $new_qty = Session::get('Cart.' . $key . '.qty');
                            if ($type == 'plus') {

                                $final_qty = $new_qty + $qty;
                                $new_qty = Session::put('Cart.' . $key . '.qty', $final_qty);
                                Session::save();
                                $rtn = 1;

                                if ($final_qty > $current_qty) {
                                    $final_qty = $current_qty;
                                    Session::put('Cart.' . $key . '.qty', $final_qty);
                                    Session::save();
                                    $rtn = 0;
                                }
                            } else if ($type == 'minus') {

                                $final_qty = $new_qty - $qty;

                                if ($final_qty <= 0) {
                                    Session::forget('Cart.' . $key);
                                    Session::save();
                                } else {
                                    $new_qty = Session::put('Cart.' . $key . '.qty', $final_qty);
                                    Session::save();
                                }
                                $rtn = 1;
                            }
                        }
                        if ($rtn == 0) {
                            $out['type'] = 'error';
                            $out['message'] = 'Maximum Quantity Not Allowed...';
                        } else {
                            $out['type'] = 'success';
                            $out['message'] = 'added';
                        }
                    } else {
                        $out['type'] = 'error';
                        $out['message'] = 'error on add cart';
                    }
                }
            } else {
                $out['type'] = 'error';
                $out['message'] = 'Product not found...';
            }
        }
        echo json_encode($out);
        die;
    }

    public function addWishlist(Request $request)
    {
        $id = $request->id;
        $product = null;
        $user_id = '';
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        }

        if ($id != null && $user_id != null) {
            $product = Product::getProductDataById($id);
            if ($product != null) {
                $whishlist = Wishlist::where(['product_id' => $id, 'cus_id' => $user_id])->first();
                if ($whishlist) {
                    if ($whishlist->is_active == 1) {
                        $is_active = 0;
                    } else {
                        $is_active = 1;
                    }
                    $whishlist->is_active = $is_active;
                    $whishlist->save();
                } else {
                    $wishlist = new Wishlist;
                    $wishlist->product_id = $id;
                    $wishlist->cus_id = $user_id;
                    $wishlist->is_active = 1;
                    $wishlist->save();
                }
                $out['type'] = 'success';
                $out['message'] = 'Succesfully add to wishlist';
            } else {
                $out['type'] = 'error';
                $out['message'] = 'Failed to add in wishlist';
            }
        }

        echo json_encode($out);
        die;
    }

    public function cartcounter()
    {
        $counts = 0;
        if (Session::has('Cart')) {
            $cart = Session::get('Cart');
            if ($cart != [] && count($cart) > 0) {
                $counts = array_sum(array_column($cart, 'qty'));
            }
        }

        $cnt_str = '<small>';
        if ($counts > 99) {
            $cnt_str .= '99<sup>+</sup>';
        } else {
            $cnt_str .= $counts;
        }
        $cnt_str .= '</small>';

        return $cnt_str;
    }

    public function set_cookie()
    {

        CookieHelper::setCookie();
        $out['type'] = 'success';
        echo json_encode($out);
    }

    public function checkagesessionset()
    {
        $out['type'] = 'success';
        $out['isset'] = 0;
        if (Session::has('agepopup')) {
            $isset = Session::get('agepopup');
            if ($isset == 1) {
                $out['isset'] = 1;
            }
        }
        echo json_encode($out);
        die;
    }



    public function checkgeosessionset()
    {
        $out['type'] = 'success';
        $out['isset'] = 0;
        $out['shop_id'] = 22;
        if (isset($_COOKIE['geolocation'])) {
            unset($_SESSION['shop_id']);
            $cookie_data = $_COOKIE['geolocation'];
            $cookie_data = json_decode($cookie_data);
            $get_shop_id = PostCodeForShop::where('post_code', $cookie_data)->where('is_active', 1)->first()->shop_id ?? null;
            if ($get_shop_id != null) {
                $_SESSION['shop_id'] = $get_shop_id;
            }

            $out['isset'] = 1;
            $out['shop_id'] = $get_shop_id;
        }
        echo json_encode($out);
        die;
    }


    public function checkUsernameset()
    {
        $out['type'] = 'success';
        $out['isset'] = 0;
        $out['islogin'] = 0;
        if (Auth::check()) {
            $out['islogin'] = 1;
            if (Auth::user()->username != null) {
                $out['isset'] = 1;
            }
        }
        echo json_encode($out);
        die;
    }

    public function checkEmailset()
    {
        $out['type'] = 'success';
        $out['isset'] = 0;
        $out['islogin'] = 0;
        if (Auth::check()) {
            $out['islogin'] = 1;
            if (Auth::user()->email != null) {
                $out['isset'] = 1;
            }
        }
        echo json_encode($out);
        die;
    }

    public function setUsername(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        if (Auth::check()) {
            $cus_id = Auth::user()->id;
            $customer = Customer::find($cus_id);
            $customer->username = $request->name;
            $customer->save();

            return redirect()->back()->with(['success' => 'Username set successfully']);
        }
    }

    public function setemail(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);
        if (Auth::check()) {
            $cus_id = Auth::user()->id;
            $customer = Customer::find($cus_id);
            $customer->email = $request->email;
            $customer->save();

            return redirect()->back()->with(['success' => 'Email set successfully']);
        }
    }

    public function agesessionset(Request $request)
    {
        $value = $request->value;
        $out['type'] = 'success';
        $out['isset'] = 0;
        $out['banner_image'] = 0;

        $banner = BannerImage::getImageByShop();
        $out['banner_image'] = count($banner);

        if ($value != null) {
            if ($value == 'yes') {
                if (Session::has('agepopup')) {
                    Session::put('agepopup', 1);
                    Session::save();
                } else {
                    Session::put('agepopup', 1);
                    Session::save();
                }
                $out['isset'] = 1;
            }
        }
        echo json_encode($out);
        die;
    }




    public function geosessionset(Request $request)
    {
        $postcode = $request->postcode;
        $out['type'] = 'success';
        $out['isset'] = 0;
        if ($postcode != null) {
            // Session::put('geolocation', $postcode);
            // Cookie::queue('geolocation', $postcode, 60 * 24 * 365 * 10); 

            $get_shop_id = PostCodeForShop::where('post_code', $postcode)->first()->shop_id ?? null;
            if ($get_shop_id != null) {
                Session::put('shop_id', $get_shop_id);
            }


            setcookie('geolocation', json_encode($postcode), time() + (10 * 365 * 24 * 60 * 60), "/"); // 86400 = 1 day
            $out['isset'] = 1;
        }

        echo json_encode($out);
        die;
    }


    public function ordersuccess($order_no, $order_date)
    {
        if ($order_no != null && $order_date != null) {
            return view('frontend.order-success', compact("order_no", "order_date"));
        }
        return redirect()->route('dashboard');
    }

    public function product_detail($category, $brand, $product)
    {
        $rtn = 0;
        $relative_product = null;
        /* if(isset($category) && $category != null && isset($brand) && $brand != null && isset($product) && $product != null){
        $category = urldecode($category);
        $brand = urldecode($brand);
        $product = urldecode($product);

        $category = Category::where(array('type'=>'category','name'=>$category,'is_active'=>1))->first();
        $brand = Category::where(array('type'=>'brand','name'=>$brand,'is_active'=>1))->first();

        if($category != null && $brand != null){
        $product = Product::getProductData($brand->id,$category->id,$product);

        if($product != null){
        $relative_product = Product::getProductByCatId($category->id,$product->id);
        $rtn = 1;
        }
        }
        } */

        if ($rtn == 1) {
            return view('frontend.product', compact("product", "relative_product"));
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function product_details($slug_url)
    {
        $rtn = 0;
        $relative_product = null;
        if (isset($slug_url) && $slug_url != null) {
            $seo = Seo::getSeodatafromslugurl($slug_url);
            if ($seo != null) {
                $product = Product::getProductDetailsById($seo->slug);
                if ($product != null) {
                    $relative_product = Product::getProductByCatId($product->category_id, $product->id);
                    if ($relative_product->count() <= 0) {
                        $relative_product = Product::getRadndomProduct($product->id);
                    }
                    $rtn = 1;
                }
            }
        }

        if ($rtn == 1) {
            return view('frontend.product', compact("product", "relative_product"));
        } else {
            return redirect()->route('dashboard');
        }
    }
}
