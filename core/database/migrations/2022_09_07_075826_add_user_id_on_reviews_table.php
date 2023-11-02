<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdOnReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('reviews', function (Blueprint $table) {
        $table->integer('user_id')->nullable()->after('id');
        $table->integer('employer_id')->nullable()->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('reviews', function (Blueprint $table) {
        $table->dropColumn('user_id');
      });
    }
}
