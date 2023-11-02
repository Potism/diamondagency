<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDegreeManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('degree_manages', function (Blueprint $table) {
            $table->id();
            $table->string('level')->nullable()->unique();
            $table->string('type')->nullable()->unique();
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
        Schema::dropIfExists('degree_manages');
    }
}
