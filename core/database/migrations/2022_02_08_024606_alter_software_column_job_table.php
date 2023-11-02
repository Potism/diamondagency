<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSoftwareColumnJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement('ALTER TABLE `jobs` CHANGE `software_id` `software_id` TEXT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('jobs', function (Blueprint $table) {
        DB::statement('ALTER TABLE `jobs` CHANGE `software_id` `software_id` int(11) NULL'); 
      });
    }
}
