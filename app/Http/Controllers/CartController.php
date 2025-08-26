<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HomePageController;

use Illuminate\Http\Request;
use Auth;
use App\Models\Product;
use App\Models\Order;
use App\Helpers\SessionHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

  public function index()
  {
    $sub_ttl = 0;
    $shipping = 0;
    $total = 0;
    $discount = 0;
    $referral = 0;
    $referral_percentage = 0;
    $offers = [];
    $session_data = [];

    $sesssion_cart_data = SessionHelper::ReCreateCart();
    $session_data = $sesssion_cart_data['session_data'];
    $sub_ttl = $sesssion_cart_data['sub_ttl'];
    $discount = $sesssion_cart_data['discount'];
    $shipping = $sesssion_cart_data['shipping'];
    $total = $sesssion_cart_data['total'];
    $offers = $sesssion_cart_data['offers'];
    $referral = $sesssion_cart_data['ref_dicount'];
    $referral_percentage = $sesssion_cart_data['ref_percentage'];
    $extra_discount = $sesssion_cart_data['extra_discount'];
    $extra_discount_percentage = $sesssion_cart_data['extra_discount_percentage'];
    // if(isset($session_data) && count($session_data) > 0){

    // }else{
    //   return redirect()->route('dashboard')->withErrors(['error' => 'The Cart is Empty Please Add Item in Cart']);
    // }

    $sub_ttl = number_format((float)$sub_ttl, 2, '.', '');
    $shipping = number_format((float)$shipping, 2, '.', '');
    $total = number_format((float)$total, 2, '.', '');
    $discount = number_format((float)$discount, 2, '.', '');
    $referral = number_format((float)$referral, 2, '.', '');

    return view('frontend.cart', compact('sub_ttl', 'shipping', 'total', 'discount', 'offers', 'referral', 'referral_percentage', 'extra_discount', 'extra_discount_percentage'));
  }

  public function getCartData()
  {
    $dt_html = '';
    $out = array();
    $sub_ttl = 0;
    $shipping = 0;
    $total = 0;
    $discount = 0;
    $referral = 0;
    $referral_percentage = 0;
    $offers = [];
    $session_data = [];

    $sesssion_cart_data = SessionHelper::ReCreateCart();
    $session_data = $sesssion_cart_data['session_data'];
    $sub_ttl = $sesssion_cart_data['sub_ttl'];
    $discount = $sesssion_cart_data['discount'];
    $shipping = $sesssion_cart_data['shipping'];
    $total = $sesssion_cart_data['total'];
    $offers = $sesssion_cart_data['offers'];
    $referral = $sesssion_cart_data['ref_dicount'];
    $referral_percentage = $sesssion_cart_data['ref_percentage'];
    $extra_discount = $sesssion_cart_data['extra_discount'];
    $extra_discount_percentage = $sesssion_cart_data['extra_discount_percentage'];

    ob_start();
?>
    <div class="card-body">
      <div class="row list-group">
        <?php
        if (isset($session_data) && count($session_data) > 0) {
          if (count($session_data) > 0) {
            foreach ($session_data as $prd) {
              $prd_id = $prd['id'];
              $prd_name = $prd['name'];
              $price = $prd['price'];
              $group_id = $prd['group_id'];
              $product = Product::find($prd['id']);
              $image = $product->imagepath;

        ?>

              <div class="col-6 list-group-item">
                <div class="row mb-1">
                  <div class="col-auto img-thumbs">
                    <figure class="text-center mb-0 avatar avatar-100 page-bg rounded p-0">
                      <img src="<?php echo $image; ?>" alt="<?php echo $prd_name;  ?>" class="view_product_detail" data-id="<?php echo $prd_id; ?>">
                    </figure>
                  </div>
                  <div class="col align-self-center ps-0 m-block">
                    <p><a class="view_product_detail text-dark" href="javascript:;" data-id="<?php echo $prd_id; ?>"><?php echo $prd_name;  ?></a></p>

                    <div class="m-wrapper">
                      <div class="m-left-wrap">
                        <?php echo base64_decode(HomePageController::getOffers($group_id, $price)); ?>
                      </div>
                      <div class="m-right-wrap" id="cart_btn_dspl_<?php echo $prd_id; ?>">
                        <?php echo base64_decode(HomePageController::getHtmlCartButton($prd_id, 'cart')); ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

          <?php }
            echo '<div class="clearfix"></div>';
          } else {
            echo '<div class="col-12 text-center">
                <h5>No Product Available</h5>
              </div>';
          } ?>
      </div>
    </div>
  <?php }

        $dt_html .= ob_get_contents();
        ob_end_clean();

        $out['type']  = 'success';
        $out['message']  = 'success fetch data.';
        $out['html'] = $dt_html;

        $out['sub_ttl'] = number_format((float)$sub_ttl, 2, '.', '');
        $out['shipping'] = number_format((float)$shipping, 2, '.', '');
        $out['discount'] = number_format((float)$discount, 2, '.', '');
        $out['total'] = number_format((float)$total, 2, '.', '');
        $out['offers'] = $offers;
        $out['referral'] = number_format((float)$referral, 2, '.', '');
        $out['referral_percentage'] = $referral_percentage;
        $out['extra_discount'] = $extra_discount;
        $out['extra_discount_percentage'] = $extra_discount_percentage;

        echo json_encode($out);
      }

      public function Applycoupon(Request $request)
      {
        $coupon = $request->coupon;
        if (isset($coupon) && $coupon != null) {
          $cart = Session::get('Cart');
          $groups = Order::CartToGroupByGroup($cart);
        }
      }


      function getHtmlCartButton($prd_id)
      {
        $qty = 0;
        $is_cart = 0;
        $data = SessionHelper::getSessionData($prd_id);
        if ($data != null) {
          $is_cart = 1;
          $qty = $data['qty'];
        }

        ob_start();
        if ($is_cart == 1) { ?>
    <!-- <div>
          <b><?php echo config('currency.currency') . $data['price'] . ' X ' . $data['qty'] . ' = ' . config('currency.currency') . $data['price'] * $data['qty'] ?></b>
        </div> -->
    <div class="counter-number">
      <button class="btn btn-sm avatar avatar-22 p-0 btn-minus" onclick="return addToCart('minus', '<?php echo $prd_id; ?>',1,page='cart');">
        <i class="bi bi-dash size-20"></i>
      </button>
      <span>
        <input type="number" class="form-control p-0 text-center" onchange="return addToCart('qtyInput', '<?php echo $prd_id; ?>',$(this).val());,page='cart'" value="<?php echo $qty ?>">
      </span>
      <button class="btn btn-sm avatar avatar-22 p-0 btn-plus" onclick="return addToCart('plus', '<?php echo $prd_id; ?>',1,page='cart');">
        <i class="bi bi-plus size-20"></i>
      </button>
    </div>

  <?php } else { ?>

    <button class="btn btn-xs btn-default btn-xs btn-theme shadow-sm mb-0 cart_btn add_to_cart_btn" onclick="return addToCart('add', '<?php echo $prd_id; ?>',1,page='cart');"><i class="fa fa-plus"></i> ADD</button>

<?php }

        $btn_html = ob_get_contents();
        ob_end_clean();

        return base64_encode($btn_html);
        die;
      }
    }
