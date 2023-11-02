<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoColumnsToTempJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_jobs', function (Blueprint $table) {
          $table->decimal('salary_from', 28,8)->after('lunch_break');
          $table->decimal('salary_to', 28,8)->after('salary_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_jobs', function (Blueprint $table) {
            $table->dropColumn('salary_from');
            $table->dropColumn('salary_to');
        });
    }
}
