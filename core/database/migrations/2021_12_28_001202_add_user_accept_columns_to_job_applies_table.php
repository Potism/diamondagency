<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAcceptColumnsToJobAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_applies', function (Blueprint $table) {
          $table->tinyInteger('accept_by_user')->default(0)->comment('pending: 0, Accepted : 1, Rejected : 2')->after('status');
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
            $table->dropColumn('accept_by_user');
        });
    }
}
