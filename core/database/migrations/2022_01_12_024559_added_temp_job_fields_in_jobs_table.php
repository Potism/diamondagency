<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedTempJobFieldsInJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
          $table->string('primary_contact',100)->nullable()->after('status');
          $table->string('parking',100)->nullable()->after('primary_contact');
          $table->integer('radiography_id')->after('parking');
          $table->string('ultrasonic',255)->nullable()->after('radiography_id');
          $table->string('avg_recall')->nullable()->after('ultrasonic');
          $table->integer('charting_id')->after('avg_recall');
          $table->integer('software_id')->after('charting_id');
          $table->string('lunch_break',255)->nullable()->after('software_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['primary_contact', 'parking', 'radiography_id', 'ultrasonic','avg_recall','charting_id','software_id','lunch_break']);
        });
    }
}
