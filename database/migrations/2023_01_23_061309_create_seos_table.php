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
      Schema::create('seos', function (Blueprint $table) {
        $table->id();      
        $table->string('type'); 
        $table->string('slug');
        $table->string('title');
        $table->string('keyword')->default(null)->nullable();
        $table->string('discription')->default(null)->nullable();
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
        Schema::dropIfExists('seos');
    }
};
