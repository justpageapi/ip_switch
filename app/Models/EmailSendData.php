<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\SendEmailJob;

class EmailSendData extends Model
{
  use HasFactory;
  protected $table = 'email_send_data';
  protected $primaryKey = 'id';

  public function customer_data()
  {
    return $this->belongsTo(Customer::class, 'cus_id', 'id');
  }

  public function order_data()
  {
    return $this->belongsTo(Order::class, 'order_id', 'id');
  }

  public function activationlink()
  {
    return $this->belongsTo(ActivationLink::class, 'cus_id', 'cus_id');
  }

  public static function insert_data($arr, $for_admin = '')
  {

    $cus_id = $arr['cus_id'];
    $cus_name = $arr['cus_name'];
    $cus_email = $arr['cus_email'];
    $subject = $arr['subject'];
    $ord_id = $arr['ord_id'];
    $type = $arr['type'];
    $data = $arr['data'];

    $email_data = new EmailSendData;
    $email_data->cus_id = $cus_id;
    $email_data->cus_name = $cus_name;
    $email_data->cus_email = $cus_email;
    $email_data->order_id = $ord_id;
    $email_data->subject = $subject;
    $email_data->type = $type;
    $email_data->data = $data;
    $email_data->save();

    return $email_data->id;
  }
  public static function sendmail($id)
  {
    if (isset($id)) {
      $email_data = EmailSendData::with('customer_data', 'order_data', 'activationlink')->where('is_send', 0)->find($id);
      if ($email_data->type == 'new_user_register') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $link = $email_data->activationlink;
        $data = array();
        $data['mail_header_title'] = 'Thankyou for Register';
        $data['id'] = $id;
        $data['type'] = 'new_user_register';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['customer_password'] = $email_data->data;
        $data['activation_link'] = env('APP_URL') . '/activate/' . $link->token;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        $data['not_include_items'] = [];
      } elseif ($email_data->type == 'extra_discount') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $link = $email_data->activationlink;
        $data = array();
        $data['mail_header_title'] = 'Congratulations!';
        $data['id'] = $id;
        $data['type'] = 'extra_discount';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        if ($order_data->count() > 0) {
          $data['order_date'] = $order_data->formatdate;
          $data['order_no'] = $order_data->order_no;
        }
        $data['not_include_items'] = [];
      } elseif ($email_data->type == 'extra_discount_reminder') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $link = $email_data->activationlink;
        $data = array();
        $data['mail_header_title'] = 'Congratulations!';
        $data['id'] = $id;
        $data['type'] = 'extra_discount_reminder';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        $data['not_include_items'] = [];
      }
      //order place
      elseif ($email_data->type == 'new_order_placed') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $data = array();
        $data['id'] = $id;
        $data['mail_header_title'] = 'Thankyou for Ordering';
        $data['type'] = 'new_order_placed';
        $data['mail_header_title'] = 'Thankyou for Ordering';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        if ($order_data->count() > 0) {
          $data['order_date'] = $order_data->formatdate;
          $data['order_no'] = $order_data->order_no;
          $data['pass_code'] = $order_data->pass_code;
          foreach ($order_data->orderitem as $item) {
            $tmp = [];
            $tmp['name'] = $item->product->name;
            $tmp['qty'] = $item->qty;
            $tmp['price'] = env('symbol') . $item->price;
            $tmp['total'] = env('symbol') . $item->qty * $item->price;
            $tmp['image'] = $item->product->imagepath;
            $data['items'][] = $tmp;
          }
        }
      }
      //calcel order
      elseif ($email_data->type == 'cancel_order') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $data = array();
        $data['id'] = $id;
        $data['mail_header_title'] = 'Order Cancel';
        $data['type'] = 'cancel_order';
        $data['mail_header_title'] = 'Order Cancel';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        if ($order_data->count() > 0) {
          $data['order_date'] = $order_data->formatdate;
          $data['order_no'] = $order_data->order_no;
          $data['pass_code'] = $order_data->pass_code;
          foreach ($order_data->orderitem as $item) {
            $tmp = [];
            $tmp['name'] = $item->product->name;
            $tmp['qty'] = $item->qty;
            $tmp['price'] = env('symbol') . $item->price;
            $tmp['total'] = env('symbol') . $item->qty * $item->price;
            $tmp['image'] = $item->product->imagepath;
            $data['items'][] = $tmp;
          }
        }
      } elseif ($email_data->type == 'passcode_mail') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $data = array();
        $data['id'] = $id;
        $data['mail_header_title'] = 'Thankyou for Ordering';
        $data['type'] = 'passcode_mail';
        $data['mail_header_title'] = 'Thankyou for Ordering';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        if ($order_data->count() > 0) {
          $data['order_date'] = $order_data->formatdate;
          $data['order_no'] = $order_data->order_no;
          $data['pass_code'] = $order_data->pass_code;
        }
      } elseif ($email_data->type == 'forget_password') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $data = array();
        $data['id'] = $id;
        $data['mail_header_title'] = 'Forget Password';
        $data['type'] = 'forget_password';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['customer_password'] = $email_data->data;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
      }
      //order update
      elseif ($email_data->type == 'order_update') {
        $customer_id = $email_data->cus_id;
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $data = array();
        $data['id'] = $id;
        $data['mail_header_title'] = 'Order Update';
        $data['type'] = 'order_update';
        $data['mail_header_title'] = 'Order Update';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        $data['not_include_items'] = [];
        if ($order_data->count() > 0) {
          $data['order_date'] = $order_data->formatdate;
          $data['order_no'] = $order_data->order_no;
          foreach ($order_data->orderitem as $item) {
            if ($item->remaining_order == null) {
              if ($item->send_qty > 0) {
                $tmp = [];
                $tmp['name'] = $item->product->name;
                $tmp['qty'] = $item->send_qty;
                $tmp['price'] = env('symbol') . $item->price;
                $tmp['total'] = env('symbol') . $item->send_qty  * $item->price;
                $tmp['image'] = $item->product->imagepath[0];
                $data['items'][] = $tmp;
              }
            } else {
              $tmp = [];
              $tmp['name'] = $item->product->name;
              $tmp['qty'] =  $item->remaining_item->remaining_qty;
              $tmp['price'] = env('symbol') . $item->price;
              $tmp['total'] = env('symbol') . $item->remaining_item->remaining_qty  * $item->price;
              $tmp['image'] = $item->product->imagepath[0];
              $data['items'][] = $tmp;
            }
          }
          //for remaining order
          /* if($order_data->remaining_item->count() > 0){
            foreach($order_data->remaining_item as $item){
              $price = $item->variant->get_price($customer_id,$item->variant->id);
              $tmp=[];
              $tmp['name'] = $item->variant->name;
              $tmp['qty'] = $item->remaining_qty;
              $tmp['price'] = env('symbol').$price;
              $tmp['total'] = env('symbol'). $item->remaining_qty  * $price;
              $tmp['image'] = $item->variant->imagepath[0];
              $data['not_include_items'][] = $tmp;
            }
          } */
        }
      }
      //invoice generate
      elseif ($email_data->type == 'invoice_generate') {
        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        $data = array();
        $data['id'] = $id;
        $data['mail_header_title'] = 'Invoice For Your Order';
        $data['type'] = 'invoice_generate';
        $data['mail_header_title'] = 'Invoice For Your Order';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        $data['not_include_items'] = [];
        if ($order_data->count() > 0) {
          $data['order_date'] = $order_data->formatdate;
          $data['order_no'] = $order_data->order_no;
          foreach ($order_data->orderitem as $item) {
            if ($item->remaining_order == null) {
              if ($item->send_qty > 0) {
                $tmp = [];
                $tmp['name'] = $item->product->name;
                $tmp['qty'] = $item->send_qty;
                $tmp['price'] = env('symbol') . $item->price;
                $tmp['total'] = env('symbol') . $item->send_qty  * $item->price;
                $tmp['image'] = $item->product->imagepath;
                $data['items'][] = $tmp;
              }
            } else {
              $tmp = [];
              $tmp['name'] = $item->product->name;
              $tmp['qty'] =  $item->remaining_item->remaining_qty;
              $tmp['price'] = env('symbol') . $item->price;
              $tmp['total'] = env('symbol') . $item->remaining_item->remaining_qty  * $item->price;
              $tmp['image'] = $item->product->imagepath;
              $data['items'][] = $tmp;
            }
          }
        }
      }

      //track order
      elseif ($email_data->type == 'track_order') {

        $cus_data = $email_data->customer_data;
        $order_data = $email_data->order_data;
        //Order::with('customer','orderitem','remaining_item')->find($order_id);

        $data = array();
        $data['mail_header_title'] = 'Track Your Order';
        $data['id'] = $id;
        $data['type'] = 'new_user_register';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        $data['not_include_items'] = [];
        $data['tracking_url'] = $order_data->tracking_url;

        //return view('email_templates.register',compact('data'));
      } elseif ($email_data->type == 'backend_order_status') {
        $data = array();
        $data['mail_header_title'] = 'Site order status';
        $data['id'] = $id;
        $data['type'] = 'backend_order_status';
        $data['subject'] = $email_data->subject;
        $data['customer_name'] = $email_data->cus_name;
        $data['customer_email'] = $email_data->cus_email;
        $data['order_date'] = '';
        $data['order_no'] = '';
        $data['items'] = [];
        $data['data'] = json_decode($email_data->data);
      }

      dispatch(new SendEmailJob($data));
    }
  }
}
