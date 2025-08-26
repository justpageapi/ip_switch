<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;


class MailSettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //$name=['default','home','cart','my-account','my-order','login','checkout','message'];
    $data = [];
    $data[0]=['key'=>'address_line_1','value'=>'203 Fake St. Mountain View,','type'=>'common_mail'];
    $data[1]=['key'=>'address_line_2','value'=>'San Francisco, California, USA,','type'=>'common_mail'];
    $data[2]=['key'=>'link','value'=>env('APP_URL'),'type'=>'common_mail'];
    $data[3]=['key'=>'link_text','value'=>'Go To Our website','type'=>'common_mail'];
    
    //for invoice mail
    $data[4]=['key'=>'invoice_mail_text_1','value'=>'Your invoice for','type'=>'invoice_mail'];
    $data[5]=['key'=>'invoice_mail_text_2','value'=>'is generated successfully. You can download your invoice.','type'=>'invoice_mail'];
    $data[6]=['key'=>'invoice_mail_order_title','value'=>"Here's what we shipped:",'type'=>'invoice_mail'];
    
      //for order-place mail
    $data[7]=['key'=>'order_place_mail_text_1','value'=>'Thank you for shopping at '.env('APP_URL'),'type'=>'order_place_mail'];
    $data[8]=['key'=>'order_place_mail_text_2','value'=>'You can also check the details and status of your order any time in your account.','type'=>'order_place_mail'];
    $data[9]=['key'=>'order_place_mail_order_title','value'=>"Here's what we shipped:",'type'=>'order_place_mail'];
    
    //for order-update mail
    $data[10]=['key'=>'order_update_mail_text_1','value'=>'Thank you for shopping at '.env('APP_URL'),'type'=>'order_update_mail'];
    $data[11]=['key'=>'order_update_mail_text_2','value'=>'is Updated By Administrator.','type'=>'order_update_mail'];
    $data[12]=['key'=>'order_update_mail_text_3','value'=>'You can also check the details and status of your order any time in your account.','type'=>'order_update_mail'];
    $data[13]=['key'=>'order_update_mail_order_title','value'=>"Here's what we shipped:",'type'=>'order_update_mail'];
    $data[14]=['key'=>'order_update_mail_order_not_include_title','value'=>"Item not include in this order:",'type'=>'order_update_mail'];
    $data[15]=['key'=>'order_update_mail_order_note','value'=>"Remaining Amount is credit into your account soon.",'type'=>'order_update_mail'];
    
    //register
    $data[16]=['key'=>'register_mail_text_1','value'=>'Thanks for joining '.env('APP_URL'),'type'=>'register_mail'];
    $data[17]=['key'=>'register_link_text','value'=>'Go For Shopping','type'=>'register_mail'];
      
    //track
    $data[18]=['key'=>'track_mail_text_1','value'=>'Thank you for shopping at '.env('APP_URL'),'type'=>'track_mail'];
    $data[19]=['key'=>'track_link_text','value'=>'Go For Shopping','type'=>'track_mail'];
      
    //stocknotification
    $data[20]=['key'=>'stocknotification_mail_text_1','value'=>'Products Available In Stock','type'=>'stocknotification_mail'];
    $data[21]=['key'=>'stocknotification_mail_text_2','value'=>'Products Available In Stock','type'=>'stocknotification_mail'];
      
    //passcode
    $data[22]=['key'=>'passcode_mail_text_1','value'=>'Thank you for shopping at '.env('APP_URL'),'type'=>'passcode_mail'];
      

    
    for($i=0;$i<count($data);$i++){
      $mail_setting = Setting::where(array('key'=>$data[$i]['key'],'type'=>$data[$i]['type']))->first();
      
      $arr = [
        'key' => $data[$i]['key'],
        'value' => $data[$i]['value'],
        'type' => $data[$i]['type'],
      ];
      
      if($mail_setting){
        $mail_setting->update($arr);
      }
      else{
        $mail_setting = Setting::create($arr);
      }
    }      
  }
}
