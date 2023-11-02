<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('company_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->date('start_date');
            $table->tinyInteger('currently_work')->default(0)->comment('yes : 1');
            $table->text('responsibilities')->nullable();
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
        Schema::dropIfExists('employment_histories');
    }
}
