<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('order', function (Blueprint $table) {
        $table->id();
        $table->String('order_no');
        $table->String('customer_id')->default(null)->nullable();
        $table->String('contact')->default(null)->nullable();
        $table->String('total_qty');
        $table->String('subtotal_amount');
        $table->String('total_amount');
        $table->String('discount')->default(null)->nullable();
        $table->String('delivery_charge')->default(null)->nullable();
        $table->String('payment_mode')->default(null)->nullable();
        $table->String('status')->default('pending')->nullable();
        $table->String('billing_address_id')->default(null)->nullable();
        $table->String('billing_address')->default(null)->nullable();
        $table->String('shipping_address_id')->default(null)->nullable();
        $table->String('shipping_address')->default(null)->nullable();
        $table->text('pay_data')->default(null)->nullable();
        $table->text('pass_code')->default(null)->nullable();
        $table->text('session_id')->default(null)->nullable();
        $table->text('pay_response')->default(null)->nullable();
        $table->integer('is_both_same')->default(0);
        $table->integer('is_active')->default(1);
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
      Schema::dropIfExists('order');
    }
}
