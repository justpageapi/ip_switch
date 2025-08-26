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
        Schema::table('onlineorders', function (Blueprint $table) {
            $table->longText('cart_dt')->nullable()->default(null);
            $table->String('ref_code')->nullable()->default(null);
            $table->String('shop_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('onlineorders', function (Blueprint $table) {
            //
        });
    }
};
