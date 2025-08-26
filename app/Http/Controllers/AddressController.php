<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Address;
use App\Models\Wallet;
use App\Models\Customer;
use App\Models\ProductSku;
use BackendHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class AddressController extends Controller
{

  public function getShipingAddress(Request $request)
  {
    $cus_id = '';
    $dt_html = '';
    $shipping_addr = null;
    $method = $request->method;
    //dd($type);
    if (Auth::check()) {
      $cus_id = Auth::user()->id;
    }
    //$cus_id = 1;

    if ($cus_id != null) {
      $shipping_addr = Address::getAddressByType($cus_id, $type = 'shipping');
    }

    ob_start(); ?>
  
      <div class="col-12 col-md-6 col-lg-4 mx-auto">
        <div class="card shadow-sm product mb-3">
          <div class="card-header p-2">
            <div class="row">
              <div class="col align-self-center">
                <h5 class="mb-0">Shipping Address<br></h5>
              </div>
              <div class="col-auto align-self-center">
                <?php if ($cus_id != null && $method != 'add' && $shipping_addr->count() > 0) { ?>
                  <a class="btn btn-link text-color-theme py-0" Onclick="getShipingAddress(method='add')">
                    <i class="bi bi-plus "></i> ADD
                  </a>
                <?php } ?>
              </div>
            </div>
          </div>

          <?php if ($cus_id != null && $shipping_addr->count() > 0) {
            if(isset($method) && $method == 'add'){ ?>
            <div class="card-body">
                <input type="hidden" name="type" value="shipping">
                <input type="hidden" id="ship_csrf" name="_token">
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" id="name" name="name" placeholder="Name" required/>
                </div>
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" id="addr_line_1" name="addr_line_1" placeholder="Address Line 1" required/>
                </div>
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" id="addr_line_2" name="addr_line_2" placeholder="Address Line 2"/>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-floating m-field mb-2">
                      <input type="text" class="form-control p-2" id="city" name="city" placeholder="Town" required/>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-floating m-field mb-2">
                      <input type="text" class="form-control p-2" id="pin" name="pin" placeholder="Postcode" required/>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-6">
                    <div class="form-floating m-field">
                      <input type="text" class="form-control p-2" id="state" name="state" placeholder="County" required/>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-floating m-field">
                      <input type="text" class="form-control p-2" id="country" name="country" placeholder="Country" value="United Kingdom" required />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col align-self-center d-grid">
                      <a class="btn btn-secondary shadow-sm" Onclick="getShipingAddress()">Cancel</a>
                  </div>
                  <div class="col align-self-center d-grid">
                      <button id="add_new_shipping_btn" type="button" class="btn btn-default btn-theme shadow-sm">Save</button>
                  </div>                  
                </div>  
            </div>
         <?php }else{
              if ($shipping_addr != null && $shipping_addr->count() > 0) { ?>
                <div class="card-body">
                  <div class="card shadow-sm product mb-1">

                    <?php $i = 0; foreach ($shipping_addr as $s_addr) { ?>
                      <div class="card-header p-2">
                        <div class="row">
                          <div class="col-auto align-self-center">
                            <div class="form-check">
                              <input class="form-check-input ship_address_id" name="ship_address_id" type="radio" value="<?php echo $s_addr->id ?>" <?php echo $i==0 ? 'checked': ''?> >
                              <label class="form-check-label" for="address1"></label>
                            </div>
                          </div>
                          <div class="col align-self-center ps-0">
                            <p class="mb-1 fw-bold"><?php echo $s_addr->name ?></p>
                            <p class="text-opac">
                              <?php echo  $s_addr->address_line_1 . ',' ?>
                              <?php echo  $s_addr->address_line_2 . ',' ?>
                              <?php echo  $s_addr->city . ',' ?>
                              <?php echo  $s_addr->state . ',' ?>
                              <?php echo  $s_addr->country ?>
                            </p>
                          </div>
                          <!-- <div class="col-auto align-self-center"><a href="#" class="btn btn-link text-color-theme"><i class="bi bi-pencil "></i></a></div> -->
                        </div>
                      </div>
                    <?php $i++; } ?>

                  </div>
                </div>
              <?php }
            }
          } else { 
            $ship_name = '';
            $ship_addr_line_1 = '';
            $ship_addr_line_2 = '';
            $ship_city = '';
            $ship_pin = '';
            $ship_state = '';
            $ship_country = '';
            if(Session::has('AddressData')){
              $session_data = Session::get('AddressData');
              if(isset($session_data[0]) && count($session_data[0]) > 0){
                $session_data = $session_data[0];
                
                $ship_name = $session_data['ship_name'];
                $ship_addr_line_1 = $session_data['ship_addr_line_1'];
                $ship_addr_line_2 = $session_data['ship_addr_line_2'];
                $ship_city = $session_data['ship_city'];
                $ship_pin = $session_data['ship_pin'];
                $ship_state = $session_data['ship_state'] ;
                $ship_country = $session_data['ship_country'];                
              }
            }
                        
            ?>
            <div class="card-body">
              <div class="form-floating m-field mb-2">
                <input type="text" class="form-control p-2" name="ship_name" id="ship_name" value="<?php echo $ship_name; ?>" placeholder="Name" required/>
              </div>
              <div class="form-floating m-field mb-2">
                <input type="text" class="form-control p-2" name="ship_addr_line_1" id="ship_addr_line_1" value="<?php echo $ship_addr_line_1; ?>" placeholder="Address Line 1" required/>
              </div>
              <div class="form-floating m-field mb-2">
                <input type="text" class="form-control p-2" name="ship_addr_line_2" id="ship_addr_line_2" value="<?php echo $ship_addr_line_2; ?>" placeholder="Address Line 2" />
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating m-field mb-2">
                    <input type="text" class="form-control p-2" name="ship_city" id="ship_city" value="<?php echo $ship_city; ?>" placeholder="Town" required/>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating m-field mb-2">
                    <input type="text" class="form-control p-2" name="ship_pin" id="ship_pin" value="<?php echo $ship_pin; ?>" placeholder="Postcode" required/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating m-field mb-0">
                    <input type="text" class="form-control p-2" name="ship_state" id="ship_state" value="<?php echo $ship_state; ?>" placeholder="County" required/>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating m-field mb-0">
                    <input type="text" class="form-control p-2" name="ship_country" id="ship_country" value="<?php echo $ship_country; ?>" placeholder="Country" value="United Kingdom" required/>
                  </div>
                </div>
              </div>
            </div>
      <?php }


    $dt_html .= ob_get_contents();
    ob_end_clean();

    $out['type']  = 'success';
    $out['message']  = 'success fetch data.';
    $out['html'] = $dt_html;
    echo json_encode($out);
  }

  public function getBillingAddress(Request $request)
  {
    $cus_id = '';
    $dt_html = '';
    $billing_addr = null;
    $checked = $request->checked;
    $method = $request->method;

    if (Auth::check()) {
      $cus_id = Auth::user()->id;
    }
    //$cus_id = 1;

    if ($cus_id != null) {
      $billing_addr = Address::getAddressByType($cus_id, $type = 'billing');
    }

    ob_start();
    if ($checked == "false") {  ?>
  
      <div class="col-12 col-md-6 col-lg-4 mx-auto">
        <div class="card shadow-sm product mb-3">
          <div class="card-header p-2">
            <div class="row">
              <div class="col align-self-center">
                <h5 class="mb-0">Billing Address<br></h5>
              </div>
              <div class="col-auto align-self-center">
                <?php if ($cus_id != null && $method != 'add' && $billing_addr->count() > 0) { ?>
                  <a class="btn btn-link text-color-theme py-0" Onclick="getBillingAddress(checked='false',method='add')">
                    <i class="bi bi-plus "></i> ADD
                  </a>
                <?php } ?>
              </div>
            </div>
          </div>

          <?php if ($cus_id != null && $billing_addr->count() > 0) {
            if(isset($method) && $method == 'add'){ ?>
            <div class="card-body">              
                <input type="hidden" name="type" value="billing">
                <input type="hidden" id="bill_csrf" name="_token">
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" id="name" name="name" placeholder="Name" required/>
                </div>
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" id="addr_line_1" name="addr_line_1" placeholder="Address Line 1" required/>
                </div>
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" id="addr_line_2" name="addr_line_2" placeholder="Address Line 2" />
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-floating m-field mb-2">
                      <input type="text" class="form-control p-2" id="city" name="city" placeholder="Town" required/>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-floating m-field mb-2">
                      <input type="text" class="form-control p-2" id="pin" name="pin" placeholder="Postcode" required/>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-6">
                    <div class="form-floating m-field mb-0">
                      <input type="text" class="form-control p-2" id="state" name="state" placeholder="County" required/>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-floating m-field mb-0">
                      <input type="text" class="form-control p-2" id="country" name="country" placeholder="Country" value="United Kingdom" required/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col align-self-center d-grid">
                      <a class="btn btn-secondary shadow-sm" Onclick="getBillingAddress(checked='false')">Cancel</a>
                  </div>
                  <div class="col align-self-center d-grid">
                      <button type="button" id="add_new_billing_btn" class="btn btn-default btn-theme shadow-sm">Save</button>
                  </div>                  
                </div> 
            </div>
         <?php }else{
            if ($billing_addr != null && $billing_addr->count() > 0) { ?>
              <div class="card-body">
                <div class="card shadow-sm product mb-1">

                  <?php $i = 0; foreach ($billing_addr as $b_addr) { ?>
                    <div class="card-header p-2">
                      <div class="row">
                        <div class="col-auto align-self-center">
                          <div class="form-check">
                            <input class="form-check-input bill_address_id" type="radio" name="bill_address_id" value="<?php echo $b_addr->id ?>" <?php echo $i==0 ? 'checked': ''?> >
                            <label class="form-check-label" for="address1"></label>
                          </div>
                        </div>
                        <div class="col align-self-center ps-0">
                          <p class="mb-1 fw-bold"><?php echo $b_addr->name ?></p>
                          <p class="text-opac">
                            <?php echo  $b_addr->address_line_1 . ',' ?>
                            <?php echo  $b_addr->address_line_2 . ',' ?>
                            <?php echo  $b_addr->city . ',' ?>
                            <?php echo  $b_addr->state . ',' ?>
                            <?php echo  $b_addr->country ?>
                          </p>
                        </div>
                        <!-- <div class="col-auto align-self-center"><a href="#" class="btn btn-link text-color-theme"><i class="bi bi-pencil "></i></a></div> -->
                      </div>
                    </div>
                  <?php $i++; } ?>

                </div>
              </div>
            <?php } 
            }
          } else { 

            $bill_name = '';
            $bill_addr_line_1 = '';
            $bill_addr_line_2 = '';
            $bill_city = '';
            $bill_pin = '';
            $bill_state = '';
            $bill_country = '';
            if(Session::has('AddressData')){
              $session_data = Session::get('AddressData');
              if(isset($session_data[0]) && count($session_data[0]) > 0){
                $session_data = $session_data[0];               
                $bill_name = $session_data['bill_name'];
                $bill_addr_line_1 = $session_data['bill_addr_line_1'];
                $bill_addr_line_2 = $session_data['bill_addr_line_2'];
                $bill_city = $session_data['bill_city'];
                $bill_pin = $session_data['bill_pin'];
                $bill_state = $session_data['bill_state'];
                $bill_country = $session_data['bill_country'];  
              }
            }
            
            
            ?>
            <div class="card-body">
              <div class="form-floating m-field mb-2">
                <input type="text" class="form-control p-2" name="bill_name" id="bill_name" placeholder="Name" value="<?php echo $bill_name; ?>" required/>
              </div>
              <div class="form-floating m-field mb-2">
                <input type="text" class="form-control p-2" name="bill_addr_line_1" id="bill_addr_line_1" value="<?php echo $bill_addr_line_1; ?>" placeholder="Address Line 1" required/>
              </div>
              <div class="form-floating m-field mb-2">
                <input type="text" class="form-control p-2" name="bill_addr_line_2" id="bill_addr_line_2" value="<?php echo $bill_addr_line_2; ?>" placeholder="Address Line 2" />
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating m-field mb-2">
                    <input type="text" class="form-control p-2" name="bill_city" id="bill_city" value="<?php echo $bill_city; ?>" placeholder="Town" required/>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating m-field mb-2">
                    <input type="text" class="form-control p-2" name="bill_pin" id="bill_pin" value="<?php echo $bill_pin; ?>" placeholder="Postcode" required/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-floating m-field mb-0">
                    <input type="text" class="form-control p-2" name="bill_state" id="bill_state" value="<?php echo $bill_state; ?>" placeholder="County" required/>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-floating m-field mb-0">
                    <input type="text" class="form-control p-2" name="bill_country" id="bill_country" value="<?php echo $bill_country; ?>" value="United Kingdom" placeholder="Country" required/>
                  </div>
                </div>
              </div>
            </div>
      <?php }
    }

    $dt_html .= ob_get_contents();
    ob_end_clean();

    $out['type']  = 'success';
    $out['message']  = 'success fetch data.';
    $out['html'] = $dt_html;
    echo json_encode($out);
  }

  public function addNewAddress(Request $request){

    if (!$request->name) {
      $out['type']      = 'error';
      $out['msg']      = 'please fill name field';
      echo json_encode($out);
      die;
    }
    if (!$request->addr_line_1) {
      $out['type']      = 'error';
      $out['msg']      = 'please fill address line 1 field';
      echo json_encode($out);
      die;
    }
    if (!$request->city) {
      $out['type']      = 'error';
      $out['msg']      = 'please fill city field';
      echo json_encode($out);
      die;
    }
    if (!$request->pin) {
      $out['type']      = 'error';
      $out['msg']      = 'please fill postcode field';
      echo json_encode($out);
      die;
    }
    if (!$request->state) {
      $out['type']      = 'error';
      $out['msg']      = 'please fill state field';
      echo json_encode($out);
      die;
    }
    if (!$request->country) {
      $out['type']      = 'error';
      $out['msg']      = 'please fill country field';
      echo json_encode($out);
      die;
    }

    if (Auth::check()) {

      $cus_id = Auth::user()->id;      
      $type = $request->type;
      $match = [
        'cus_id' => $cus_id,
        'name' => $request->name,
        'address_line_1' => $request->addr_line_1,
        'address_line_2' => $request->addr_line_2,
        'city' => $request->city,
        'postal_code' => $request->pin,
        'state' => $request->state,
        'country' => $request->country,
        'type' => $request->type,
      ];

      if ($address = Address::where($match)->first()) {
        $out['type']      = 'error';
        $out['msg']      = 'This address already exists';
        echo json_encode($out);
        die;
      }

      $arr = array();
      $arr['name'] = $request->name;
      $arr['address_line_1'] = $request->addr_line_1;
      $arr['address_line_2'] = $request->addr_line_2;
      $arr['city'] = $request->city;
      $arr['country'] = $request->country;
      $arr['state'] = $request->state;
      $arr['postal_code'] = $request->pin;

      $id = Address::InsertAddress($cus_id,$arr,$type);
      if($id == null){        
        $out['type']      = 'error';
        $out['msg']      = 'Error in Address Save';
        echo json_encode($out);
        die;
      }else{
        $out['type']      = 'success';
        $out['msg']      = 'Address Add successfully';
        echo json_encode($out);
        die;
      }       
    }else{
      $out['type']      = 'error';
      $out['msg']      = 'Error in Address Save';
      echo json_encode($out);
      die;
    }
  }

  /*public function addNewAddress(Request $request){
    $request->validate([
      'name' => 'required',
      'addr_line_1' => 'required',
      'addr_line_2' => 'required',
      'city' => 'required',
      'pin' => 'required',
      'state' => 'required',
      'country' => 'required',
      'type' => 'required',
    ]); 

    if (Auth::check()) {

      $cus_id = Auth::user()->id;      
      $type = $request->type;
      $match = [
        'cus_id' => $cus_id,
        'name' => $request->name,
        'address_line_1' => $request->addr_line_1,
        'address_line_2' => $request->addr_line_2,
        'city' => $request->city,
        'postal_code' => $request->pin,
        'state' => $request->state,
        'country' => $request->country,
        'type' => $request->type,
      ];

      if ($address = Address::where($match)->first()) {
        return redirect()->back()->withErrors(['error' => 'This Adress Already Exists']);
      }

      $arr = array();
      $arr['name'] = $request->name;
      $arr['address_line_1'] = $request->addr_line_1;
      $arr['address_line_2'] = $request->addr_line_2;
      $arr['city'] = $request->city;
      $arr['country'] = $request->country;
      $arr['state'] = $request->state;
      $arr['postal_code'] = $request->pin;

      $id = Address::InsertAddress($cus_id,$arr,$type);
      if($id == null){
        return redirect()->back()->withErrors(['error' => 'Error in Address Save']);
      }else{
        return redirect()->route('payment')->withSuccess('Address Add successfully');
      }       
    }else{
      return redirect()->route('dashboard')->withErrors(['error' => 'Error in Address Save']);
    }
  } */

    /*public function edit_address(Request $request)
    {
      $id = $request->id;
      if ($id != null) {
        $cus = CustomerAddress::find($id);
        $out['type']      = 'success';
        $out['data']      = $cus;
        echo json_encode($out);
        die;
      } else {
        $out['type']      = 'error';
        echo json_encode($out);
        die;
      }
    }

    public function update_address(Request $request)
    {


      if (!$request->address_line_1) {
        $out['type']      = 'error';
        $out['msg']      = 'please fill addressline1 field';
        echo json_encode($out);
        die;
      }
      if (!$request->city) {
        $out['type']      = 'error';
        $out['msg']      = 'please fill city field';
        echo json_encode($out);
        die;
      }
      if (!$request->state) {
        $out['type']      = 'error';
        $out['msg']      = 'please fill state field';
        echo json_encode($out);
        die;
      }
      if (!$request->country) {
        $out['type']      = 'error';
        $out['msg']      = 'please fill country field';
        echo json_encode($out);
        die;
      }
      if (!$request->postalcode) {
        $out['type']      = 'error';
        $out['msg']      = 'please fill postal code field';
        echo json_encode($out);
        die;
      }

      if (Auth::check()) {
        $cus_id = Auth::user()->id;
        $match = [
          'cus_id' => $cus_id,
          'address_line_1' => $request->address_line_1,
          'city' => $request->city,
          'state' => $request->state,
          'country' => $request->country,
          'postal_code' => $request->postalcode,
          'type' => $request->type,
        ];

        if ($address = CustomerAddress::where($match)->where('id', '<>', $request->id)->first()) {
          $out['type']      = 'error';
          $out['msg']      = 'This address already exists';
          echo json_encode($out);
          die;
        }

        $customer_address =  CustomerAddress::find($request->id);
        $customer_address->cus_id = $cus_id;
        $customer_address->address_line_1 = (isset($request->address_line_1)) ? $request->address_line_1 : null;
        $customer_address->address_line_2 = (isset($request->address_line_2)) ? $request->address_line_2 : null;
        $customer_address->city = (isset($request->city)) ? $request->city : null;
        $customer_address->state = (isset($request->state)) ? $request->state : null;
        $customer_address->country = (isset($request->country)) ? $request->country : null;
        $customer_address->type = $request->type;
        $customer_address->postal_code =  (isset($request->postalcode)) ? $request->postalcode : null;
        $customer_address->save();

        $out['type']      = 'success';
        echo json_encode($out);
        die;
      } else {
        $out['type']      = 'error';
        echo json_encode($out);
        die;
      }
    }

    public function delete_address(Request $request)
    {
      $id = $request->id;
      $type = $request->type;
      if (isset($id) && isset($type)) {
        $data = CustomerAddress::delete_address($id, $type);
        if ($data == 1) {

          $out['type']      = 'success';
          $out['msg']      = 'Address delete successfully';
          echo json_encode($out);
          die;
        }
      }
      $out['type']      = 'error';
      $out['msg']      = 'Something went wrong';
      echo json_encode($out);
      die;
    } */

  }
