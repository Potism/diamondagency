<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobStatusColumnToJobAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_applies', function (Blueprint $table) {
            $table->tinyInteger('job_status')->default(0)->comment('pending: 0, Working : 1, Completed : 2, onHold : 3')->after('accept_by_user');
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
            $table->dropColumn('job_status');
        });
    }
}
