<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Setting;


class SendEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $common = Setting::getSettingByType('common_mail');
    $data = $this->data;
    if ($data['type'] == 'new_user_register') {
      $settings = Setting::getSettingByType('register_mail');
      return $this->view('email_templates.register', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'extra_discount') {
      $settings = Setting::getSettingByType('register_mail');
      return $this->view('email_templates.extra_discount', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'extra_discount_reminder') {
      $settings = Setting::getSettingByType('register_mail');
      return $this->view('email_templates.extra_discount_reminder', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'new_order_placed') {
      $settings = Setting::getSettingByType('order_place_mail');
      return $this->view('email_templates.order-placed', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'cancel_order') {
      $settings = Setting::getSettingByType('cancel_order');
      return $this->view('email_templates.order-cancel', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'passcode_mail') {
      $settings = Setting::getSettingByType('passcode_mail');
      return $this->view('email_templates.pass-code', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'forget_password') {
      return $this->view('email_templates.forget_password', array('data' => $data, 'common' => $common))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'order_update') {
      $settings = Setting::getSettingByType('order_update_mail');
      return $this->view('email_templates.order-update', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    } elseif ($data['type'] == 'invoice_generate') {
      $settings = Setting::getSettingByType('invoice_mail');
      return $this->view('email_templates.invoice_mail', array('data' => $data, 'common' => $common, 'settings' => $settings))
        //->from($address = 'noreply@domain.com', $name = 'Sender name')
        ->subject($data['subject'])
        //->attach(Storage::path('public\invoice\invoice_'.$data['order_no'].'.pdf'));
        ->attach(storage_path('app/public/invoice/invoice_' . $data['order_no'] . '.pdf'));
    } elseif ($data['type'] == 'track_order') {
      $settings = Setting::getSettingByType('track_mail');
      return $this->view('email_templates.tracking', array('data' => $data, 'common' => $common, 'settings' => $settings))
        //->from($address = 'noreply@domain.com', $name = 'Sender name')
        ->subject($data['subject']);
    } elseif ($data['type'] == 'backend_order_status') {
      return $this->view('email_templates.order-status', ['data' => $data])
        ->subject($data['subject']);
    } elseif ($data['type'] == 'update_order_notification') {
      $settings = Setting::getSettingByType('stocknotification_mail');
      return $this->view('email_templates.stocknotification_mail', array('data' => $data, 'common' => $common, 'settings' => $settings))
        ->subject($data['subject']);
    }
  }
}
