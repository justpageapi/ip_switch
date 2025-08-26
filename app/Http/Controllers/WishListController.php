<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Rules\matchpassword;
use App\Models\ProjectData;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\CartData;
use App\Models\Wishlist;
use App\Models\ProductSku;
use BackendHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class WishListController extends Controller
{
    
  public function index(){
    
    return view('frontend.wishlist');    
    
  }

  public function getWishlistData()
  {
    $dt_html = '';
    $out = array();

    $user_id = '';
    if(Auth::check()){
      $user_id = Auth::user()->id;
    }

    if($user_id != ''){
      $product_ids = Wishlist::getWishlistProductid();
      ob_start();
      ?>
      <div class="card-body">
        <div class="row list-group">           
          <?php
          if(isset($product_ids) && count($product_ids) > 0){
            if (count($product_ids) > 0) {
              foreach ($product_ids as $prd_id) {
                $product = Product::getProductDataById($prd_id);
                if($product != null){
                  $prd_id = $product->id;
                  $product_name = $product->name;
                  $price = $product->productpricebyshop;
                  $image = $product->imagepath;
                  $group_id = $product->group_id;
                                            
                ?>
                
                    <div class="col-6 list-group-item">
                      <div class="row mb-1">
                        <div class="col-auto img-thumbs">
                          <figure class="text-center mb-0 avatar avatar-100 page-bg rounded p-0">
                            <img src="<?php echo $image; ?>" alt="<?php echo $product_name;  ?>" class="view_product_detail" data-page="wishlist" data-id="<?php echo $prd_id; ?>">
                          </figure>
                        </div>
                        <div class="col align-self-center ps-0 m-block">
                          <p><a class="view_product_detail text-dark" href="javascript:;" data-page="wishlist" data-id="<?php echo $prd_id; ?>"><?php echo $product_name;  ?></a></p>
                          
                          <div class="m-wrapper">
                            <div class="m-left-wrap">                            
                                <?php echo base64_decode(HomePageController::getOffers($group_id,$price)); ?>                            
                            </div>
                            <div class="m-right-wrap" id="cart_btn_dspl_<?php echo $prd_id; ?>">
                                <?php echo base64_decode(HomePageController::getHtmlCartButton($prd_id,'wishlist')); ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                                      
              <?php
                 } 
              }
              echo '<div class="clearfix"></div>';
        
            } else {
              echo '<div class="col-12 text-center">
                <img src="assets/product-not-found.jpg">
              </div>';
            }?>
        </div>
      </div>
    <?php } else { 
      echo '<div class="col-12 text-center">
              <img src="assets/product-not-found.jpg">
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
}