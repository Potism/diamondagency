<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('primary_contact',100)->nullable();
            $table->string('parking',100)->nullable();
            $table->integer('radiography_id');
            $table->string('ultrasonic',255)->nullable();
            $table->string('avg_recall')->nullable();
            $table->integer('charting_id');
            $table->integer('software_id');
            $table->string('lunch_break',255)->nullable();
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
        Schema::dropIfExists('temp_jobs');
    }
}
