<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
          $table->string('tax_rate')->nullable()->after('working_hours');
          $table->decimal('tax_amt', 28,8)->after('tax_rate');
          $table->decimal('invoice_amt_with_tax', 28,8)->after('tax_amt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('tax_rate');
            $table->dropColumn('tax_amt');
            $table->dropColumn('invoice_amt_with_tax');
        });
    }
}
