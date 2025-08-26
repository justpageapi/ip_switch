<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmail;
use App\Models\EmailSendData;
use Mail;
use Exception;


class SendEmailJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
    $data = $this->data;
    if ($data['customer_email'] != null) {
      $email = new SendEmail($data);
      $email_send_data = EmailSendData::find($data['id']);
      try {
        Mail::to($data['customer_email'])->send($email);
        $email_send_data->is_send = 1;
      } catch (\Exception $e) {
        // Get error here
        $email_send_data->is_send = 2;
      }
      $email_send_data->save();
    }
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // $data = $this->data;
    // if ($data['customer_email'] != null) {
    //   $email = new SendEmail($data);
    //   $email_send_data = EmailSendData::find($data['id']);
    //   try {
    //     Mail::to($data['customer_email'])->send($email);
    //     $email_send_data->is_send = 1;
    //   } catch (\Exception $e) {
    //     // Get error here
    //     $email_send_data->is_send = 2;
    //   }
    //   $email_send_data->save();
    // }
  }
}
