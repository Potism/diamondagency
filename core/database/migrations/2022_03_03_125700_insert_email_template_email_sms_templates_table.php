<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertEmailTemplateEmailSmsTemplatesTable extends Migration
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
                    'act' => 'IN_TIME_INFO_EMPLOYER',
                    'name' =>'In Time Info Employer',
                    'subj' => 'In time information of candidate',
                    'email_body' => '<div>Hello {{company_name}},</div><div>Candidate( {{candidate_name}} ) have just started your job ( {{job_title}} )</div>',
                    'sms_body' => '<div>Hello {{company_name}},</div><div>Candidate( {{candidate_name}} ) have just started your job ( {{job_title}} )</div>',
                    'shortcodes' => '{"company_name":"Company Name","candidate_name":"Candidate Name","job_title":"Job Title"}',
                    'email_status' => 1,
                    'sms_status' => 1,
                ],
                [
                    'act' => 'IN_TIME_INFO_ADMIN',
                    'name' =>'In Time Info Admin',
                    'subj' => 'In time information of candidate',
                    'email_body' => "<div>Hello {{admin_name}},</div><div>Candidate( {{candidate_name}} ) have just started employer's ({{company_name}}) job ( {{job_title}} )</div>",
                    'sms_body' => "<div>Hello {{admin_name}},</div><div>Candidate( {{candidate_name}} ) have just started employer's ({{company_name}}) job ( {{job_title}} )</div>",
                    'shortcodes' => '{"company_name":"Company Name","candidate_name":"Candidate Name","job_title":"Job Title","admin_name":"Admin Name"}',
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