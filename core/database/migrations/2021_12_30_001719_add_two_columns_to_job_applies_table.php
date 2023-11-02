<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoColumnsToJobAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_applies', function (Blueprint $table) {
          $table->string('job_type')->nullable()->after('user_id');
          $table->decimal('job_amt', 28,8)->after('job_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_applies', function (Blueprint $table) {
            $table->dropColumn('job_type');
            $table->dropColumn('job_amt');
        });
    }
}
