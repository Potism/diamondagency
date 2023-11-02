<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->nullable()->unique();
            $table->decimal('amount', 28,8)->default(0);
            $table->tinyInteger('duration_type')->default(0)->comment('weekly : 1, monthly : 2, yearly : 3');
            $table->integer('duration');
            $table->tinyInteger('status')->default(0)->comment('Enable : 1, Disable : 2');
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
        Schema::dropIfExists('plans');
    }
}
