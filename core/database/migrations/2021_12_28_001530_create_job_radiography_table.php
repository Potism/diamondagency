<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobRadiographyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_radiographies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->nullable()->unique();
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
        Schema::dropIfExists('job_radiographies');
    }
}
