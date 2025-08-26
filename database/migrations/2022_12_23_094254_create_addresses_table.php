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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('cus_id')->default(null)->nullable();
            $table->string('name')->default(null)->nullable();
            $table->string('address_line_1')->default(null)->nullable();
            $table->string('address_line_2')->default(null)->nullable();
            $table->string('city')->default(null)->nullable();
            $table->string('state')->default(null)->nullable();
            $table->string('country')->default(null)->nullable();
            $table->string('type')->default(null)->nullable();
            $table->string('postal_code')->default(null)->nullable();
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
        Schema::dropIfExists('addresses');
    }
};
