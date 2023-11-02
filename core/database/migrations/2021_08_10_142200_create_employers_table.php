<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname',40)->nullable();
            $table->string('lastname',40)->nullable();
            $table->string('username',40)->nullable();
            $table->string('email', 40)->nullable();
            $table->string('country_code', 40)->nullable();
            $table->string('mobile',40)->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('status')->default(1)->comment('banned : 0, active : 1');
            $table->tinyInteger('ev')->default(0)->comment('Email Unverified : 0, Email Verified : 1');
            $table->tinyInteger('sv')->default(0)->comment('SMS Unverified : 0, SMS Verified : 1');
            $table->string('ver_code', 40)->comment('Stores verification Code');
            $table->datetime('ver_code_send_at')->comment('Verification send time');
            $table->tinyInteger('ts')->default(0)->comment('0: 2fa off, 1: 2fa on');
            $table->tinyInteger('tv')->default(1)->comment('2fa unverified : 0 , 2fa verified : 1');
            $table->tinyInteger('tsc')->nullable();
            $table->string('remember_token')->nullable();
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
        Schema::dropIfExists('employers');
    }
}
