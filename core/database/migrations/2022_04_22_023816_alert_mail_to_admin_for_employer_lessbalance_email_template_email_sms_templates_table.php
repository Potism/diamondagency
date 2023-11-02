<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertMailToAdminForEmployerLessbalanceEmailTemplateEmailSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         DB::table('email_sms_templates')->insert([
                 [
                     'act' => 'CANDIDATE_ALERT_MAIL',
                     'name' =>'',
                     'subj' => '',
                     'email_body' => '',
                     'sms_body' => '',
                     'shortcodes' => '',
                     'email_status' => 0,
                     'sms_status' => 0,
                 ],
                 [
                     'act' => 'ADMIN_ALERT_LOWBALANCE',
                     'name' =>'Employer balance is below the threshold amount',
                     'subj' => 'Employer balance is below the threshold amount',
                     'email_body' => "<div>Hello {{admin_name}},</div><div>Employer( {{company_name}} ) has balance of {{balance}} which is lower than the threshold amount and will not be able to post any job listing.</div>",
                     'sms_body' => "<div>Hello {{admin_name}},</div><div>Employer( {{company_name}} ) has balance of {{balance}} which is lower than the threshold amount and will not be able to post any job listing.</div>",
                     'shortcodes' => '{"company_name":"Company Name","admin_name":"Admin Name","balance":"Balance"}',
                     'email_status' => 1,
                     'sms_status' => 1,
                 ]
             ]);
     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
