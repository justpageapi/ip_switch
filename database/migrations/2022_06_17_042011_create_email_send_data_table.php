<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSendDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_send_data', function (Blueprint $table) {
            $table->id();            
            $table->integer('cus_id');
            $table->String('cus_name');
            $table->String('cus_email');
            $table->String('cus_password')->default(null)->nullable();
            $table->Text('subject')->default(null)->nullable();
            $table->integer('order_id')->default(null)->nullable();
            $table->String('type');
            $table->Text('data')->default(null)->nullable();
            $table->integer('is_send')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_send_data');
    }
}
