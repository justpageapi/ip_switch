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
        Schema::create('activation_links', function (Blueprint $table) {
          $table->id();
          $table->string('cus_id');
          $table->string('cus_email');
          $table->string('token')->default(null)->nullable();         
          $table->string('status')->default('pending')->nullable();         
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
        Schema::dropIfExists('activation_links');
    }
};
