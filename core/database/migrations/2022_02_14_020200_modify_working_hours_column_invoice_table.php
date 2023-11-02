<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWorkingHoursColumnInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement('ALTER TABLE `invoices` CHANGE `working_hours` `working_hours` DECIMAL(4,2) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('invoices', function (Blueprint $table) {
        DB::statement('ALTER TABLE `invoices` CHANGE `working_hours` `working_hours` int(11) NOT NULL'); 
      });
    }
} 