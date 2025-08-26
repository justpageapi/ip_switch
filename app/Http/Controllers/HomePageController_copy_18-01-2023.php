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
use App\Helpers\SessionHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;



class HomePageController extends Controller
{

  public function dashboard($type = null, $name = null)
  {
    $category = Cache::remember('category', env('time'), function () {
      return Category::getAllCategory();
    });
    return view('frontend.home', compact('category'));
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
            } ?>

            <div class="card-header main_header_1 border-0" data-bs-toggle="collapse" data-bs-target="#ball_<?php echo $id ?>" role="button" aria-expanded="true" data-placement="bottom" title="View Details" data-id="<?php echo $brand_id ?>" data-cid="<?php echo $category_id ?>" data-isclicked="0">
              <div class="row">
                <div class="col align-self-center">
                    <div class="row mb-1">
                        <div class="col-auto">
                            <figure class="text-center mb-0 avatar avatar-40 page-bg rounded p-0">
                                <img src="https://dcassetcdn.com/design_img/3330376/699722/699722_18254743_3330376_65fc449f_image.jpg" alt="">
                            </figure>
                        </div>
                        <div class="col align-self-center ps-0">
                            <h3 class="mb-0 text-uppercase"><?php echo $brand_name ?></h3>
                            
                        </div>
                    </div>
                  
                </div>
                <div class="col-auto align-self-center padding-bottom">
                  <i id="down" class="fa fa-chevron-down" aria-hidden="true"></i>
                </div>
              </div>
            </div>
            <div class="collapse" id="ball_<?php echo $id ?>">

            </div>
          <?php }
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
      if ($id != '' && $cid != null) {

        $products = Product::getProductByCatAndBrand($id, $cid);

        if ($products->count() > 0) {
          foreach ($products as $prd) {
            $prd_id = $prd->id;
            $prd_name = $prd->name;
            $price = $prd->unit_price;
            $image = $prd->imagepath;
            
          ?>
            <ul class="list-group">
                <?php /*?>
                <?php for($i=0;$i<3;$i++){?>
                <li class="list-group-item">
                    <div class="row mb-1">
                      <div class="col-auto img-thumbs">
                        <figure class="text-center mb-0 avatar avatar-100 page-bg rounded p-0">
                          <img src="<?php echo $image ?>" alt="">
                        </figure>
                      </div>
                      <div class="col align-self-center ps-0 m-block">
                        <p><a class="text-dark" href="#">Crystal - Fresh Menthol Mojito 2% Or 20mg Nic</a></p>
                        <div class="m-wrapper">
                          <div class="m-left-wrap">
                            <div class="price_box">
                              <div class="b-tag">EACH</div>
                              <div class="b-price">$4.16</div>
                            </div>
                            <div class="price_box">
                              <div class="b-tag">Mix &amp; Match</div>
                              <div class="b-price">$5 For 20</div>
                            </div><div class="price_box">
                              <div class="b-tag">Mix &amp; Match</div>
                              <div class="b-price">$10 For 38</div>
                            </div>
                          </div>
                          <div class="m-right-wrap">
                              <!--<div class="qty">
                                <input type="number" value="1" class="n-qty" />
                              </div>-->
                              <div class="m-btns">
                                <button class="btn btn-xs btn-theme shadow-sm mb-0 m-btn">ADD</button>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                </li>
                <li class="list-group-item">
                    <div class="row mb-1">
                      <div class="col-auto img-thumbs">
                        <figure class="text-center mb-0 avatar avatar-110 page-bg rounded p-0">
                          <img src="<?php echo $image ?>" alt="">
                        </figure>
                      </div>
                      <div class="col align-self-center ps-0 m-block">
                        <p><a class="text-dark" href="#">Crystal - Fresh Menthol Mojito 2% Or 20mg Nic</a></p>
                        <div class="m-wrapper">
                          <div class="m-left-wrap">
                            <div class="price_box">
                              <div class="b-tag">EACH</div>
                              <div class="b-price">$4.16</div>
                            </div>
                            <div class="price_box">
                              <div class="b-tag">Mix &amp; Match</div>
                              <div class="b-price">$5 For 20</div>
                            </div><div class="price_box">
                              <div class="b-tag">Mix &amp; Match</div>
                              <div class="b-price">$10 For 38</div>
                            </div>
                          </div>
                          <div class="m-right-wrap">
                            <div class="b-counter">
                                <div class="input-group plus-minus-input btn-groups">
                                  <div class="input-group-button">
                                    <button type="button" class="qt-btn button hollow circle">
                                      <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                  </div>
                                  <input class="input-group-field" type="number" name="quantity" value="0">
                                  <div class="input-group-button">
                                    <button type="button" class="qt-btn button hollow circle">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                  </div>
                                </div>
                            </div>


                              <!--<div class="qty">
                                <input type="number" value="1" class="n-qty" />
                              </div>
                              <div class="m-btns">
                                <button class="btn btn-outline-primary m-count-btn"><i class="bi bi-dash size-30"></i></button>
                                <button class="btn btn-outline-primary m-count-btn"><i class="bi bi-plus size-30"></i></button>
                              </div>-->
                          </div>
                        </div>
                      </div>
                    </div>
                </li>
                <?php }?>
                <?php */?>
                
              <li class="list-group-item">
                <div class="row g-0">
                  <div class="col-md-9 col-sm-12 align-self-center">
                    <div class="row g-0">
                      <div class="col-auto align-self-center">
                        <figure class="text-center mb-0 avatar avatar-40 page-bg rounded p-0">
                          <img src="<?php echo $image ?>" class="imgmodal" alt="<?php echo $prd_name  ?>">
                        </figure>
                      </div>
                      <div class="col align-self-center px-2 ps-3">
                        <div class="row">
                          <div class="col">
                            <p class="py-1 mb-2"><?php echo $prd_name  ?></p>
                          </div>
                        </div>              
                        <div class="row">
                          <div class="col">
                            <p class="price_block_279"><b>Price : <?php echo config('currency.currency') . $price ?> </b></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-12  align-self-centers text-md-end text-sm-start float-end">
                    <div class="row g-0 m-0" style="padding-right: 0 !important;">
                      <div class="col align-self-center">
                        <div class="hom-hrt-icn">
                          <a href="javascript:;" data-id="<?php echo $prd_id ?>" class="view_product_detail btn btn-link text-color-theme p-1 py-0"><i class="fa fa-eye size-18 "></i></a>
                          <!-- <a href="javascript:;" class="btn btn-link text-danger p-1 py-0" data-toggle="tooltip" data-placement="top" title="Wishlist" onclick="return add_to_wishlist('eyJwcm9kdWN0X2lkIjoiNSIsImJyYW5kX2lkIjoxLCJ2YXJpYW50X2lkIjoyNzksInVuaXRfcHJpY2UiOiIwLjk5IiwidG90YWxfcXR5Ijo4LCJjYXJ0X3F0eSI6IiIsImlzX2NhcnQiOjAsImluX3doaXNobGlzdCI6MCwidXNlcl9pZCI6MX0=');"><i class="size-18 fa fa-heart-o"></i></a> -->
                        </div>
                      </div>
                      <div class="col-auto" id="cart_btn_dspl_<?php echo $prd_id ?>">
                        <?php echo base64_decode($this->getHtmlCartButton($prd_id,'home')) ?>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              
            </ul>
        <?php }
        } else {
          echo '<div class="col-12 text-center">
            <h5>No Product Available</h5>
          </div>';
        }
      }
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
      if (isset($id)) {
        $product = Product::find($id);
        if (isset($product) && $product != '') {
          $id = $product->id;
          $name = $product->name;
          $price = $product->unit_price; 
          $image = $product->imagepath;
          $discription = $product->long_discription;

          ?>

          <div class="modal-content product border-0 shadow-sm">

            <div class="card shadow-sm">
              <div class="card-body pb-0 position-relative">
                <div class="swiper-slide text-center mb-0 px-5 py-3 swiper-slide-active" style="width: 268px; margin-right: 12px;"><img src="<?php echo $image ?>" alt="" class="mw-100"></div>                
              </div>

              <div class="collapse" id="collapseExample">
                <div class="card-footer justify-content-center text-center" id="share_urls">
                  <p class="mb-1 text-opac">Share product with</p>
                  <a target="_blank" href="https://twitter.com/intent/tweet/https://www.ourbulkshop.com/product/" class="btn btn-link text-color-theme"><i class="bi bi-twitter"></i></a>
                  <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https://www.ourbulkshop.com/product/" class="btn btn-link text-color-theme"><i class="bi bi-facebook"></i></a>
                  <a target="_blank" href="https://www.linkedin.com/sharing/share-offsite?=https://www.ourbulkshop.com/product/" class="btn btn-link text-color-theme"><i class="bi bi-linkedin"></i></a>
                  <a target="_blank" href="#" class="btn btn-link text-color-theme"><i class="bi bi-google"></i></a>
                </div>
              </div>              
            </div>

            <div class="cls_icon">
              <a class="btn btn-link text-color-theme p-1 py-0 " data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
              <div class="px-1" id="btn_share" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style=""><i class="fa fa-share-alt"></i></div>
            </div>

            <div class="modal-body" id="prd_details">
              <a href="#" class="text-normal">
                <h6 class="text-color-theme"><?php echo $name ?></h6>
              </a>
              <div class="row">
                <div class="col align-self-center ">
                  <p class="mb-0 price_block"><b>Price : <?php echo config('currency.currency') . $price ?></b></p>
                </div>
                <div class="col-auto" id="cart_btn_dspl_<?php echo $id ?>">                
                  <?php echo base64_decode($this->getHtmlCartButton($id,'home')) ?>                
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

    $out['type']  = 'success';
    $out['message']  = 'success fetch data.';
    $out['html'] = $dt_html;
    echo json_encode($out);
  }

  public function searchSuggetion(Request $request)
  {

    $out = array();
    $dt_html = '';

    $search_data =  $request->search_data;
    ob_start();
    if ($search_data != '') {
      $products = Product::getProductSearch($search_data);

      if ($products->count() > 0) {
        foreach ($products as $prd) {
          $prd_id = $prd->id;
          $prd_name = $prd->name;
          $price = $prd->unit_price;
          $image = $prd->imagepath;

        ?>
          <ul class="list-group">
            <li class="list-group-item">
              <div class="row g-0">
                <div class="col-md-9 col-sm-12 align-self-center">
                  <div class="row g-0">
                    <div class="col-auto align-self-center">
                      <figure class="text-center mb-0 avatar avatar-40 page-bg rounded p-0">
                        <img src="<?php echo $image ?>" class="imgmodal" alt="<?php echo $prd_name  ?>">
                      </figure>
                    </div>
                    <div class="col align-self-center px-2 ps-3">
                      <div class="row">
                        <div class="col">
                          <p class="py-1 mb-2"><?php echo $prd_name ?></p>
                          <!-- <small class="text-opac pt-1">SKU: GL-02-IP14PRO</small> -->
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <p class="price_block"><b>Price : <?php echo config('currency.currency') . $price ?> </b></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-12  align-self-centers text-md-end text-sm-start float-end">
                  <div class="row g-0 m-0" style="padding-right: 0 !important;">
                    <div class="col align-self-center">
                      <div class="hom-hrt-icn">
                        <a href="javascript:;" data-id="<?php echo $prd_id ?>" class="view_product_detail btn btn-link text-color-theme p-1 py-0"><i class="fa fa-eye size-18 "></i></a>                         
                        <!--<a href="javascript:;" class="btn btn-link text-danger p-1 py-0" data-toggle="tooltip" data-placement="top" title="Wishlist" onclick="return add_to_wishlist('eyJwcm9kdWN0X2lkIjoiNSIsImJyYW5kX2lkIjoxLCJ2YXJpYW50X2lkIjoyNzksInVuaXRfcHJpY2UiOiIwLjk5IiwidG90YWxfcXR5Ijo4LCJjYXJ0X3F0eSI6IiIsImlzX2NhcnQiOjAsImluX3doaXNobGlzdCI6MCwidXNlcl9pZCI6MX0=');"><i class="size-18 fa fa-heart-o"></i></a> -->
                      </div>
                    </div>
                    <div class="col-auto" id="cart_btn_dspl_<?php echo $prd_id ?>">                
                      <?php echo base64_decode($this->getHtmlCartButton($prd_id,'home')) ?>                
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        <?php }
      } else {
        echo '<div class="col-12 text-center">
            <h5>No Product Found</h5>
          </div>';
      }
    }

    $dt_html .= ob_get_contents();
    ob_end_clean();

    $out['type']  = 'success';
    $out['message']  = 'success fetch data.';
    $out['html'] = $dt_html;
    echo json_encode($out);
  }

  function getHtmlCartButton($prd_id,$page){
    
    $qty = 0;
    $is_cart = 0;
    $data = SessionHelper::getSessionData($prd_id);
    if($data != null){
      $is_cart = 1;
      $qty = $data['qty'];
    }

    ob_start();
    if($is_cart == 1){ ?>
        <!-- <div>
          <b><?php echo config('currency.currency').$data['price'].' X '.$data['qty'].' = '.config('currency.currency').$data['price']*$data['qty'] ?></b>
        </div> -->
        <div class="counter-number">        
          <button class="btn btn-sm avatar avatar-22 p-0 btn-minus" onclick="return addToCart('minus', '<?php echo $prd_id;?>',1,'<?php echo $page ?>');">
            <i class="bi bi-dash size-20"></i>
          </button>
          <span>
           <input type="number" class="form-control p-0 text-center" onchange="return addToCart('qtyInput', '<?php echo $prd_id;?>',$(this).val(),'<?php echo $page ?>');" value="<?php echo $qty ?>">
          </span>
          <button class="btn btn-sm avatar avatar-22 p-0 btn-plus" onclick="return addToCart('plus', '<?php echo $prd_id;?>',1,'<?php echo $page ?>');">
            <i class="bi bi-plus size-20"></i>
          </button>
        </div>

    <?php }else{ ?>

      <button class="btn btn-xs btn-default btn-xs btn-theme shadow-sm mb-0 cart_btn add_to_cart_btn" onclick="return addToCart('add', '<?php echo $prd_id;?>',1,'<?php echo $page ?>');"><i class="fa fa-plus"></i> ADD</button>

    <?php } 
     
    $btn_html = ob_get_contents();
    ob_end_clean();

    return base64_encode($btn_html);
    die;
  } 

  public function addCart(Request $request){

    $id = $request->id;
    $type = $request->type;
    $qty = $request->qty;

    if($id != null && $type != null && $qty != null){

      $product = Product::find($id); 

      if($product != null){

        $cart_qty = SessionHelper::getSessionCartQty($id);
        $current_qty = Product::getCurrentStock($id);

        $cart = Session::has('Cart');
        if(!$cart){
          $cart = Session('Cart');
        }else{
          $cart = Session::get('Cart');
        }
        
        //dd($cart);
        if($type == 'add'){
          
          if($cart_qty < $current_qty){    
            $rtn=0;        

            $id = $product->id;
            $name = $product->name;
            //$price = $product->productpricebyshop;
            $price = $product->unit_price;
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
            
            if(!Session::has('Cart')){
              Session::push('Cart', $p);
              Session::save();
              $rtn=1;
            }else{
              $chk_arrr = -1;
              foreach ($cart as $key => $val){
                if($val['id'] == $id) {
                  $chk_arrr = $key;
                }
              }
              if($chk_arrr < 0){
                Session::push('Cart', $p);
                Session::save();
                $rtn=1;
              }else{
                $c_qty = Session::get('Cart.'.$chk_arrr.'.qty');
                Session::put('Cart.'.$chk_arrr.'.qty', $cart_qty + $c_qty);
                Session::save();
                $rtn=1;
              } 
            }            
            $out['type'] = 'success';
            $out['message'] = 'session created';
          }else{
            $out['type'] = 'error';
            $out['message'] = 'This Product Currently Out of Stock..';
          }
        }
        else{
          $rtn = 0;
          $key = SessionHelper::getSessionKey($id);
          //dd($key);

          if($key >= 0){
            //for qty input
            if($type == 'qtyInput'){
            
              $id = Session::get('Cart.'.$key.'.id');                
              if($qty > $current_qty){
                $qty = $current_qty; 
                Session::put('Cart.'.$key.'.qty', $qty);
                Session::save();
                $rtn = 0;               
              }
              elseif($qty <= 0){
                Session::forget('Cart.'.$key);
                Session::save();
                $rtn = 1;
              }
              else{
                Session::put('Cart.'.$key.'.qty', $qty);
                Session::save();
                $rtn = 1;
              }        
              
            }else{
              //for plus and minus
              $new_qty = Session::get('Cart.'.$key.'.qty');
              if($type == 'plus'){
                
                $final_qty = $new_qty + $qty;
                $new_qty = Session::put('Cart.'.$key.'.qty', $final_qty);
                Session::save();
                $rtn = 1;

                if($final_qty > $current_qty){
                  $final_qty = $current_qty;
                  Session::put('Cart.'.$key.'.qty', $final_qty);
                  Session::save();
                  $rtn = 0;
                }        
                
              }else if($type == 'minus'){

                $final_qty = $new_qty - $qty; 

                if($final_qty <= 0){
                  Session::forget('Cart.'.$key);
                  Session::save();
                }else{
                  $new_qty = Session::put('Cart.'.$key.'.qty', $final_qty);
                  Session::save();
                }
                $rtn = 1;
              }                       
            }
            if($rtn == 0){
              $out['type'] = 'error';
              $out['message'] = 'Maximum Quentitiy Not Allowed...';   
            }else{
              $out['type'] = 'success';
              $out['message'] = 'added';   
            }             
          }else{
            $out['type'] = 'error';
            $out['message'] = 'error on add cart';
          }                        
        }                
      }else{
        $out['type'] = 'error';
        $out['message'] = 'Product not found...';
      }
    }
    echo json_encode($out);
    die;
  }

  public function cartcounter(){
    $counts = 0;
    if(Session::has('Cart')){
      $cart = Session::get('Cart');
      $counts = count($cart);
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

  public function set_cookie()
  {

    /*  CookieHelper::setCookie();
    $out['type']      = 'success';
    echo json_encode($out); */
  }
}
