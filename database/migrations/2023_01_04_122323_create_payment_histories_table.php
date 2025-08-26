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
      Schema::create('payment_histories', function (Blueprint $table) {
        $table->id();
        $table->string('cus_id')->default(null)->nullable();
        $table->string('ord_id')->default(null)->nullable();
        $table->string('type')->default(null)->nullable();
        $table->text('data')->default(null)->nullable();
        $table->string('session_id')->default(null)->nullable();            
        $table->string('status')->default(null)->nullable();            
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
        Schema::dropIfExists('payment_histories');
    }
};
