<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Rules\matchpassword;
use App\Models\ProjectData;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\CartData;
use App\Models\Models;
use App\Models\ProductSku;
use App\Models\ProductTag;
use App\Models\Wishlist;
use App\Models\Seo;
use App\Models\Tag;
use BackendHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Helpers\CookieHelper;

class DeviceController extends Controller
{

  public function index($name){  
    $model = Models::where('name',$name)->first();    
    if($model != null){
      $model_id = $model->id;
      $name = $model->name;
    }else{
        return view('errors.404');
    }
    return view('frontend.device',compact('model_id','name'));
  }

  public function load_model_data(Request $request){
    $id = $request->id;
    $model = Models::find($id);    
    if($model != null){
      $model_id = $model->id;
    }else{
      $out['type']      = 'error';
      $out['data']      = null;
      echo json_encode($out);
      die;
    }

    $out['type']      = 'error';    
    $user_id = '';
    $product_id='';
    $category_id = '';
    $search_category_id =  '';
    $search_data =  '';    
    $brand_id = '';
    $type = '';
    
    if(Auth::check()){
      $user_id = Auth::user()->id;
    }

    $data = [];
    $data['status'] = true;
    
    if(isset($model_id)){

      $product_skus = Product::getModelSkuData($model_id);
      //$product_skus = Product::getProducts('lvl3',$product_id,$brand_id);
      $dt_html = '<div class="card-header pdr_sku_list border-0 p-0"><ul class="list-group">';
      ob_start();

        
      if(isset($product_skus) && $product_skus != '' && $product_skus->count() > 0){
        foreach($product_skus as $v){
          $id = $v->id;
          $image = $v->imagepath[0];
          $name = $v->name;
          $sku = $v->sku;

          $cart_qty = '';
          $is_cart = 0;
          $in_wishlist = 0; 
          
          if($user_id != ''){
            if(isset($v->cartdata) && $v->cartdata != null){
              $cart_qty = $v->cartdata->qty;
              $is_cart = $v->cartdata->is_active;              
            }

            if(isset($v->wishlist) && $v->wishlist != null){
              $in_wishlist = $v->wishlist->is_active;
            }            
          }
          
          $tmp_data = array();
          $tmp_data['user_id'] = $user_id ;
          $tmp_data['variant_id'] = $id;
          $tmp_data['mrp'] = $v->mrp;
          $tmp_data['price'] = $v->price;
          $tmp_data['category_id'] = $v->product->category_id;

        
          $unit_price = ProductSku::getprice($tmp_data);

          $dt_arr = array(
            'product_id'=>$v->product_id, 
            'brand_id'=>$v->product_brand_id, 
            'variant_id'=>$id, 
            'unit_price'=>$unit_price, 
            'total_qty'=>$v->sku_stock, 
            'cart_qty'=>$cart_qty, 
            'is_cart'=>$is_cart,
            'in_whishlist' => $in_wishlist,
          );

          $rdata = $this->getHtmlCartButton($dt_arr);
          ?>
          <li class="list-group-item">
              <div class="row g-0">
                  <div class="col-md-9 col-sm-12 align-self-center">
                        <div class="row g-0">
                          <div class="col-auto align-self-center">
                              <figure class="text-center mb-0 avatar avatar-40 page-bg rounded p-0">
                                <img data-id="<?php echo $id;?>" src="<?php echo $image;?>" class="imgmodal" alt="<?php echo $name;?>">
                              </figure>
                          </div>
                        <div class="col align-self-center px-2 ps-3">
                          <div class="row">
                              <div class="col">
                                  <p class="py-1 mb-2"><?php echo $name;?></p>
                                  <!-- <small class="text-opac pt-1">SKU: <?php echo $sku;?></small> -->
                              </div>
                          </div>
                          <div class="row">
                              <div class="col">
                                  <p class="price_block_<?php echo $id;?>"><b><?php echo $rdata['price_block'];?></b></p>
                              </div>
                          </div>                       
                        </div>  
                      </div>
                  </div>
                  <div class="col-md-3 col-sm-12  align-self-centers text-md-end text-sm-start float-end">
                      <div class="row g-0 m-0" style="padding-right: 0 !important;">
                          <div class="col align-self-center">
                              <?php echo $rdata['heart_icon']; ?>
                          </div>
                          <div class="col-auto">
                              <?php echo $rdata['counter_block'];?>
                          </div>
                      </div>
                  </div>
              </div>
          </li>
        <?php } 
      }else{ ?>
        <li class="text-center"><h5>No Product Available</h5></li>
      <?php }
      $dt_html .= ob_get_contents();
      ob_end_clean();
      $dt_html .= '</ul></div>';
      $cnt_str = $this->get_cart_counter();
      $data['cart_count'] = $cnt_str;
      $data['html'] = $dt_html;
      $data['data'] = $dt_html;
    }
    $out['type']      = 'success';
    $out['data']      = $data;
    echo json_encode($out);
    die;
  }

  function getHtmlCartButton($dt){
    //dd($dt);
    $return_data = [];

    $user_id = '';

    $price = '';
    $data='';
    $heart_icon = '';
    

    if(Auth::check()){
      $user_id = Auth::user()->id;
    }

    $cart_items = [];
    $cart_items = $dt;
    $cart_items['user_id'] = $user_id;
    $ct_itm = base64_encode(json_encode($cart_items));

    //for price
    ob_start();
    if($user_id != ''){
      if($dt['is_cart'] == 1){
        $ttl = sprintf('%.2f',$dt['cart_qty'] * $dt['unit_price']);
        
        $price =  env('currency'). $dt['unit_price']. ' x '. $dt['cart_qty'] .' = '. env('currency') . $ttl;
      }else{
        $price = 'Price : '.env('currency').$dt['unit_price'];
      }

    }else{
      // user without login
      $price ='<a href="'.route('login').'" class="link text-color-theme text-start">Login to check price<i class="bi bi-chevron-right"></i></a>';
    }
    echo $price;
    $price_html = ob_get_contents();
    ob_end_clean();

    //for cart btn and counter
    ob_start();
    if($user_id != ''){
      if($dt['is_cart'] == 1){?>
        <div class="counter-number">        
          <button class="btn btn-sm avatar avatar-22 p-0 btn-minus" onclick="return plus_minusItems('minus', '<?php echo $ct_itm;?>');">
            <i class="bi bi-dash size-20"></i>
          </button>
          <span>
           <input type="number" id="count_<?php echo $dt['variant_id'];?>" class="form-control p-0 text-center" onchange="return plus_minusItems('qtyInput', '<?php echo $ct_itm;?>');" value="<?php echo $dt['cart_qty'];?>">
          </span>
          <button class="btn btn-sm avatar avatar-22 p-0 btn-plus" onclick="return plus_minusItems('plus', '<?php echo $ct_itm;?>');"  >
            <i class="bi bi-plus size-20"></i>
          </button>
        </div>
        <div style="position: relative;clear: both;top: 5px;">Stock : <?php echo $dt['total_qty'] ?></div>
        <!--<span class="text-danger max_qty_error" id="max_<?php echo $dt['variant_id']?>" hidden>The maximum you may purchase is <?php echo $dt['total_qty'] ?></span>-->
      <?php     
      }else{?>
        <button class="btn btn-xs btn-default btn-xs btn-theme shadow-sm mb-0 cart_btn add_to_cart_btn" onclick="return addItems('<?php echo $ct_itm;?>');" ><i class="fa fa-plus"></i> ADD</button>         
    <?php }
    }
    $btn_html = ob_get_contents();
    ob_end_clean();
    
    //for heart icon
    ob_start();
    if($user_id != ''){ ?>
        <div class="hom-hrt-icn">
            <a href="javascript:;"  data-id="<?php echo $dt['variant_id']?>" class="view_product_detail btn btn-link text-color-theme p-1 py-0"><i class="fa fa-eye size-18 "></i></a>
            <!--<a href="javascript:;" class="btn btn-link text-primary p-1 py-0"><i class="fa fa-heart size-18"></i></a>-->
            <a href="javascript:;" class="btn btn-link text-danger p-1 py-0" data-toggle="tooltip" data-placement="top" title="Wishlist" onclick="return add_to_wishlist('<?php echo $ct_itm;?>');"><i class="size-18 fa fa-heart<?php echo $dt['in_whishlist'] == 1 ? '' : '-o' ?>"></i></a>
        </div>
      <?php 

      //echo $check_box;
    }
    $heart_icon = ob_get_contents();
    ob_end_clean();

    $return_data['price_block'] = $price_html;
    $return_data['counter_block']= $btn_html;
    $return_data['heart_icon']= $heart_icon;

    return $return_data;
    die;
  } 


  
  /*function checkVariantInCart($variant_id) {
    $cart = Session::get('cart');

    //$cart_key = array_search($variant_id, array_column($cart, 'variant_id'));
    $cart_key = Product::arraySearch('variant_id', $variant_id, $cart);
    if($cart_key){
      echo 'itm in cart';
    }else{
      echo 'no itm in cart';
    }
    
    return $cart_key;
  }*/
  
  public function get_cart_counter(){
    $counts = 0;
    $user_id = '';
    if(Auth::check()){
      $user_id = Auth::user()->id;
    }
    if($user_id != ''){
      $counts = CartData::where('user_id',$user_id)->where('is_active',1)->count();
    }
    
    $cnt_str = '<small>';
    if($counts > 99){
      $cnt_str .= '99<sup>+</sup>';
    }else{
      $cnt_str .= $counts;
    }
    $cnt_str .= '</small>';

    return $cnt_str;
  }
}