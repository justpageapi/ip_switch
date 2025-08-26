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
        Schema::table('order', function (Blueprint $table) {
            $table->string('cash_amount')->default(0)->nullable()->after('payment_mode');
            $table->string('card_amount')->default(0)->nullable()->after('cash_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            
            /* $table->dropColumn('cash_amount');
            $table->dropColumn('card_amount'); */
        });
    }
};
