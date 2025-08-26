<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onlineorders', function (Blueprint $table) {
            $table->id();
            $table->String('cus_id');
            $table->String('order_no')->nullable()->default(null);
            $table->String('shipping_addr_id')->nullable()->default(null);
            $table->String('billing_addr_id')->nullable()->default(null);
            $table->String('phone')->nullable()->default(null);
            $table->String('type')->nullable()->default(null);
            $table->String('checkbox')->nullable()->default(null);
            $table->String('offer_id')->nullable()->default(null);
            $table->String('payment_status')->nullable()->default(null);
            $table->String('transaction_id')->nullable()->default(null);
            $table->Text('data')->nullable()->default(null);
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
        Schema::dropIfExists('onlineorders');
    }
};
