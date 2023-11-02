<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('plan/cron/run', 'CronController@index')->name('cron.run');

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
    Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
    Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
    Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
    Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
    Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
    Route::post('skrill', 'Skrill\ProcessController@ipn')->name('Skrill');
    Route::post('paytm', 'Paytm\ProcessController@ipn')->name('Paytm');
    Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
    Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
    Route::post('voguepay', 'Voguepay\ProcessController@ipn')->name('Voguepay');
    Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
    Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
    Route::post('instamojo', 'Instamojo\ProcessController@ipn')->name('Instamojo');
    Route::get('blockchain', 'Blockchain\ProcessController@ipn')->name('Blockchain');
    Route::get('blockio', 'Blockio\ProcessController@ipn')->name('Blockio');
    Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
    Route::post('coinpayments-fiat', 'Coinpayments_fiat\ProcessController@ipn')->name('CoinpaymentsFiat');
    Route::post('coingate', 'Coingate\ProcessController@ipn')->name('Coingate');
    Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
    Route::get('mollie', 'Mollie\ProcessController@ipn')->name('Mollie');
    Route::post('cashmaal', 'Cashmaal\ProcessController@ipn')->name('Cashmaal');
    Route::post('authorize-net', 'AuthorizeNet\ProcessController@ipn')->name('AuthorizeNet');
    Route::post('2check-out', 'TwoCheckOut\ProcessController@ipn')->name('TwoCheckOut');
    Route::post('mercado-pago', 'MercadoPago\ProcessController@ipn')->name('MercadoPago');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});

/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/
Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications','AdminController@notifications')->name('notifications');
        Route::get('notification/read/{id}','AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all','AdminController@readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report','AdminController@requestReport')->name('request.report');
        Route::post('request-report','AdminController@reportSubmit');
        Route::get('system-info','AdminController@systemInfo')->name('system.info');

        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::get('user/cv/download/{id}', 'ManageUsersController@cvDownload')->name('users.cv.download');
        Route::get('user/padfile/download/{name}', 'ManageUsersController@downloadPadFile')->name('users.padfile.download');
        Route::get('user/credentials/download/{id}', 'ManageUsersController@credentialsDownload')->name('users.credentials.download');

        Route::get('user/education/list/{id}', 'ManageUsersController@educations')->name('users.education.list');
        Route::get('user/employment/list/{id}', 'ManageUsersController@employment')->name('users.employment.list');
        Route::get('user/job/application/list/{id}', 'ManageUsersController@jobApplication')->name('users.job.application');
        Route::get('user/support/ticket/{id}', 'ManageUsersController@supportTicket')->name('users.support.ticket');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');

        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');
        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');

        //Employer
        Route::get('employers', 'ManageEmployerController@allUsers')->name('employers.all');
        Route::get('employers/active', 'ManageEmployerController@activeUsers')->name('employers.active');
        Route::get('employers/banned', 'ManageEmployerController@bannedUsers')->name('employers.banned');
        Route::get('employers/email-verified', 'ManageEmployerController@emailUnverifiedUsers')->name('employers.email.unverified');
        Route::get('employers/email-unverified', 'ManageEmployerController@emailVerifiedUsers')->name('employers.email.verified');
        Route::get('employers/sms-unverified', 'ManageEmployerController@smsUnverifiedUsers')->name('employers.sms.unverified');
        Route::get('employers/sms-verified', 'ManageEmployerController@smsVerifiedUsers')->name('employers.sms.verified');
        Route::get('employers/{scope}/search', 'ManageEmployerController@search')->name('employers.search');
        Route::get('employers/detail/{id}', 'ManageEmployerController@detail')->name('employers.detail');
        Route::get('employers/job/list/{id}', 'ManageEmployerController@employerJob')->name('employers.job.list');
        Route::get('employers/plan/subscriber/list/{id}', 'ManageEmployerController@employerPlanSubscribe')->name('employers.plan.subscriber');
        Route::post('employers/update/{id}', 'ManageEmployerController@update')->name('employers.update');
        Route::post('user/add-sub-balance/{id}', 'ManageEmployerController@addSubBalance')->name('employers.add.sub.balance');
        Route::get('employers/transactions/{id}', 'ManageEmployerController@transactions')->name('employers.transactions');
        Route::get('employers/deposits/{id}', 'ManageEmployerController@deposits')->name('employers.deposits');

        Route::get('employers/send-email/{id}', 'ManageEmployerController@showEmailSingleForm')->name('employers.email.single');
        Route::post('employers/send-email/{id}', 'ManageEmployerController@sendEmailSingle')->name('employers.email.single');
        Route::get('employers/login/{id}', 'ManageEmployerController@login')->name('employers.login');

        //Login History
        Route::get('employers/login/history/{id}', 'ManageEmployerController@userLoginHistory')->name('employers.login.history.single');
        Route::get('employers/send-email', 'ManageEmployerController@showEmailAllForm')->name('employers.email.all');
        Route::post('employers/send-email', 'ManageEmployerController@sendEmailAll')->name('employers.email.send');
        Route::get('employers/email-log/{id}', 'ManageEmployerController@emailLog')->name('employers.email.log');
        Route::get('employers/email-details/{id}', 'ManageEmployerController@emailDetails')->name('employers.email.details');
        //City
        Route::get('cities', 'CityController@index')->name('location.city.index');
        Route::post('city/store', 'CityController@store')->name('location.city.store');
        Route::post('city/update', 'CityController@update')->name('location.city.update');
        // Location
        Route::get('location', 'LocationController@index')->name('location.index');
        Route::post('location/store', 'LocationController@store')->name('location.store');
        Route::post('location/update', 'LocationController@update')->name('location.update');
        // Invoice
        Route::get('invoice', 'InvoiceController@index')->name('invoice.index');
        Route::get('invoice/lowbalance', 'InvoiceController@lowbalance')->name('invoice.lowbalance');
        Route::get('invoice/create', 'InvoiceController@create')->name('invoice.create');
        Route::post('invoice/editstore', 'InvoiceController@editstore')->name('invoice.editstore');
        Route::get('invoice/update/{id}', 'InvoiceController@update')->name('invoice.update');
        Route::post('invoice/get_amount', 'InvoiceController@get_amount')->name('invoice.get_amount');
        //Ratings
        Route::get('ratings', 'RatingsController@index')->name('ratings.index');
        //Job Type
        Route::get('job/type', 'JobTypeController@index')->name('job.setting.type.index');
        Route::post('job/type/store', 'JobTypeController@store')->name('job.setting.type.store');
        Route::post('job/type/update', 'JobTypeController@update')->name('job.setting.type.update');
        // Jobshift
        Route::get('job/shift', 'JobShiftController@index')->name('job.setting.shift.index');
        Route::post('job/shift/store', 'JobShiftController@store')->name('job.setting.shift.store');
        Route::post('job/shift/update', 'JobShiftController@update')->name('job.setting.shift.update');
        // Job Skill
        Route::get('job/skill', 'JobSkillController@index')->name('job.setting.skill.index');
        Route::post('job/skill/store', 'JobSkillController@store')->name('job.setting.skill.store');
        Route::post('job/skill/update', 'JobSkillController@update')->name('job.setting.skill.update');
        // Job Experience
        Route::get('job/experience', 'JobExperienceController@index')->name('job.setting.experience.index');
        Route::post('job/experience/store', 'JobExperienceController@store')->name('job.setting.experience.store');
        Route::post('job/experience/update', 'JobExperienceController@update')->name('job.setting.experience.update');
        //Salary Period
        Route::get('salary/period', 'SalaryPeriodController@index')->name('job.setting.salary.period.index');
        Route::post('salary/period/store', 'SalaryPeriodController@store')->name('job.setting.salary.period.store');
        Route::post('salary/period/update', 'SalaryPeriodController@update')->name('job.setting.salary.period.update');
        // Job Radiography
        Route::get('job/radiography', 'JobRadiographyController@index')->name('job.setting.radiography.index');
        Route::post('job/radiography/store', 'JobRadiographyController@store')->name('job.setting.radiography.store');
        Route::post('job/radiography/update', 'JobRadiographyController@update')->name('job.setting.radiography.update');
        // Job Software
        Route::get('job/software', 'JobSoftwareController@index')->name('job.setting.software.index');
        Route::post('job/software/store', 'JobSoftwareController@store')->name('job.setting.software.store');
        Route::post('job/software/update', 'JobSoftwareController@update')->name('job.setting.software.update');
        // Job Charting
        Route::get('job/charting', 'JobChartingController@index')->name('job.setting.charting.index');
        Route::post('job/charting/store', 'JobChartingController@store')->name('job.setting.charting.store');
        Route::post('job/charting/update', 'JobChartingController@update')->name('job.setting.charting.update');
        // Job Experience
        Route::get('degree', 'DegreeController@index')->name('education.degree.index');
        Route::post('degree/store', 'DegreeController@store')->name('education.degree.store');
        Route::post('degree/update', 'DegreeController@update')->name('education.degree.update');
        // Level Of Educatioin
        Route::get('level-of-education', 'LevelOfEducationController@index')->name('education.level.index');
        Route::post('level-of-education/store', 'LevelOfEducationController@store')->name('education.level.store');
        Route::post('level-of-education/update', 'LevelOfEducationController@update')->name('education.level.update');
        //Industry
        Route::get('industry/index', 'IndustryController@index')->name('industry.index');
        Route::post('industry/store', 'IndustryController@store')->name('industry.store');
        Route::post('industry/update', 'IndustryController@update')->name('industry.update');
        //NumberOfEmployees
        Route::get('number/of/employees', 'NumberOfEmployeesController@index')->name('number.employees.index');
        Route::post('number/of/employees/store', 'NumberOfEmployeesController@store')->name('number.employees.store');
        Route::post('number/of/employees/update', 'NumberOfEmployeesController@update')->name('number.employees.update');
        //Manage Job
        Route::get('job/index', 'JobController@index')->name('manage.job.index');
        Route::get('job/detail/{id}', 'JobController@detail')->name('manage.job.detail');
        Route::get('job/pending', 'JobController@pending')->name('manage.job.pending');
        Route::get('job/approved', 'JobController@approved')->name('manage.job.approved');
        Route::get('job/cancel', 'JobController@cancel')->name('manage.job.cancel');
        Route::get('job/expired', 'JobController@expired')->name('manage.job.expired');
        Route::post('job/approve', 'JobController@approvBy')->name('manage.job.approve');
        Route::post('job/cancel-by', 'JobController@cancelBy')->name('manage.job.cancelBy');
        Route::post('job/featured/list/Include', 'JobController@featuredInclude')->name('manage.job.featured.include');
        Route::post('job/featured/list/remove', 'JobController@featuredNotInclude')->name('manage.job.featured.remove');
        Route::get('job/applied/list/{id}', 'JobController@jobApplication')->name('manage.job.applied');

        //Category
        Route::get('category', 'CategoryController@index')->name('category.index');
        Route::post('category/store', 'CategoryController@store')->name('category.store');
        Route::post('category/update', 'CategoryController@update')->name('category.update');
        //Manage Plan
        Route::get('plan','PlanController@index')->name('plan.index');
        Route::get('plan/create','PlanController@create')->name('plan.create');
        Route::get('plan/edit/{id}','PlanController@edit')->name('plan.edit');
        Route::post('plan/update/{id}','PlanController@update')->name('plan.update');
        Route::post('plan/store','PlanController@store')->name('plan.store');
        Route::get('plan/subscribes','PlanController@planSubscribe')->name('plan.subscriber');
        Route::get('plan/subscribes/search','PlanController@planSubscribeSearch')->name('plan.subscriber.search');

        //Service
        Route::get('service', 'ServiceController@index')->name('plan.service.index');
        Route::post('service/store', 'ServiceController@store')->name('plan.service.store');
        Route::post('service/update', 'ServiceController@update')->name('plan.service.update');

        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function(){
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });
        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function(){
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depositViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');
        });
        // Report
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');
        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/email/history', 'ReportController@emailHistory')->name('report.email.history');

        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');

        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');

        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');

        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css','GeneralSettingController@customCssSubmit');

        //Cookie
        Route::get('cookie','GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie','GeneralSettingController@cookieSubmit');

        //tax & platform fee markup rate
        Route::get('tax-markup', 'GeneralSettingController@taxMarkup')->name('setting.tax.markup');
        Route::post('tax-markup', 'GeneralSettingController@taxMarkupUpdate')->name('setting.tax.markup');

        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');

        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.test.mail');

        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsTemplate')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsTemplateUpdate')->name('sms.template.global');
        Route::get('sms-template/setting','SmsTemplateController@smsSetting')->name('sms.templates.setting');
        Route::post('sms-template/setting', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.setting');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.test.sms');
        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');
        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {
            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');

            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');
            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });

        // Badge
        Route::get('badge', 'BadgeController@index')->name('badge.index');
        Route::post('badge/store', 'BadgeController@store')->name('badge.store');
        Route::post('badge/update', 'BadgeController@update')->name('badge.update');
    });
});

Route::namespace('Employer')->prefix('employer')->name('employer.')->group(function () {
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('register', 'Auth\RegisterController@register')->name('register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
    Route::get('/download_invoice', 'Auth\LoginController@downloadInvoice')->name('download.invoice');
});

Route::namespace('Employer')->name('employer.')->prefix('employer')->group(function () {
    Route::middleware('auth.employer')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatusEmployer'])->group(function () {
            Route::get('dashboard', 'HomeController@dashboard')->name('home');
            Route::get('profile', 'HomeController@profile')->name('profile');
            Route::get('change-password', 'HomeController@changePassword')->name('change.password');
            Route::post('change-password', 'HomeController@submitPassword');
            Route::post('profile/submit', 'HomeController@submitProfile')->name('profile.submit');

            //Plan
            Route::get('plan/payment', 'HomeController@payment')->name('payment');
            Route::post('plan/subscribe', 'HomeController@planSubscribe')->name('plan.subscribe');
            Route::post('plan/insert', 'HomeController@paymentInsert')->name('payment.insert');

            //Deposit
            Route::get('deposit/history', 'HomeController@depositHistory')->name('deposit.history');
            Route::get('transactions', 'HomeController@transaction')->name('transaction.history');

             //2FA
            Route::get('twofactor', 'HomeController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'HomeController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'HomeController@disable2fa')->name('twofactor.disable');

            //Job
            Route::get('job/index', 'JobController@index')->name('job.index');
            Route::get('job/create', 'JobController@create')->name('job.create');
            Route::get('job/edit/{id}', 'JobController@edit')->name('job.edit');
            Route::post('job/store', 'JobController@store')->name('job.store');
            Route::post('job/update/{id}', 'JobController@update')->name('job.update');
            Route::get('applied/job/{id}', 'JobController@appliedJob')->name('applied.job');
            Route::get('cv/download/{id}', 'JobController@cvDownload')->name('cv.download');
            Route::post('job/approved/', 'JobController@approved')->name('job.approved');
            Route::post('job/cancel/', 'JobController@cancel')->name('job.cancel');
            Route::get('/temp_applied/job/{id}', 'JobController@temp_appliedJob')->name('temp_applied.job');
            Route::post('job/temp_approved/', 'JobController@temp_approved')->name('job.temp_approved');
            Route::post('job/temp_cancel/', 'JobController@temp_cancel')->name('job.temp_cancel');
            Route::get('/generate_pdf', 'Auth\LoginController@generatePdf')->name('generate.pdf');
            Route::get('/invoices/{type}/{job_id}/{id}', 'JobController@UserInovices')->name('user.invoices');
            Route::post('job/catmarkup', 'JobController@catmarkup')->name('job.catmarkup');
            Route::post('job/jobreview', 'JobController@jobreview')->name('job.jobreview');
        });
    });
});


Route::name('employer.')->prefix('employer')->group(function () {
    Route::middleware('auth.employer')->group(function () {
        Route::middleware(['checkStatusEmployer'])->group(function () {
            // Deposit
            Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
        });
    });
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

Route::name('user.')->group(function () {
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('register', 'Auth\RegisterController@register')->name('register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');
            Route::get('profile-setting', 'UserController@profile')->name('profile.setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::post('upload/cv', 'UserController@uploadCv')->name('upload.cv');
            Route::post('upload/license', 'UserController@uploadLicense')->name('upload.license');
            Route::post('upload/certificate', 'UserController@uploadCertificate')->name('upload.certificate');
            Route::post('upload/drivinglicense', 'UserController@uploadDrivingLicense')->name('upload.drivinglicense');
            Route::post('upload/covid19id', 'UserController@covid19id')->name('upload.covid19id');
            //Education History
            Route::get('education/index', 'UserController@educationIndex')->name('education.index');
            Route::post('education/store', 'UserController@educationStore')->name('education.store');
            Route::post('education/update', 'UserController@educationUpdate')->name('education.update');
            Route::post('education/delete', 'UserController@educationDelete')->name('education.delete');
            //Employment History
            Route::get('employment/history', 'UserController@employmentIndex')->name('employment.index');
            Route::post('employment/store', 'UserController@employmentStore')->name('employment.store');
            Route::post('employment/update', 'UserController@employmentUpdate')->name('employment.update');
            Route::post('employment/delete', 'UserController@employmentDelete')->name('employment.delete');
            Route::get('change-password', 'UserController@changePassword')->name('change.password');
            Route::post('change-password', 'UserController@submitPassword');
            Route::get('view/resume', 'UserController@pdfViewer')->name('pdf.view');


            //Favorite Job List
            Route::get('favorite/item/{id}', 'UserController@favoriteItem')->name('favorite.item');
            Route::get('favorite/job/list', 'UserController@favoriteJob')->name('favorite.job.list');
            Route::post('favorite/job/delete', 'UserController@favoriteJobdelete')->name('favorite.job.delete');
            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');

            //Job Apply
            Route::post('apply/job', 'UserController@applyJob')->name('job.apply');
            Route::get('job/application/list', 'UserController@jobApplication')->name('job.application.list');
            Route::get('job/currentjob', 'UserController@currentjob')->name('job.currentjob');
            Route::post('job/approved/', 'UserController@approved')->name('job.approved');
            Route::post('job/lowbalance/', 'UserController@lowbalance')->name('job.lowbalance');
            Route::post('job/checktime/', 'UserController@checktime')->name('job.checktime');
            Route::post('job/cancel/', 'UserController@cancel')->name('job.cancel');
            Route::get('job/current_job_report/{id}', 'UserController@currjobreport')->name('job.currjobreport');
            Route::post('job/current_job_report/{id}', 'UserController@submitjobreport');
            Route::get('statistic', 'UserController@statistics')->name('statistics');
            Route::post('job/currjobintime/', 'UserController@currjobintime')->name('job.currjobintime');
            Route::post('job/currjobouttime/', 'UserController@currjobouttime')->name('job.currjobouttime');
            Route::post('job/currjobreportingtime/', 'UserController@currjobreportingtime')->name('job.currjobreportingtime');
            Route::get('invoices/', 'UserController@invoices')->name('invoices');

        });
    });
});
Route::post('submit_employee_rating/', 'UserController@submit_employee_rating')->name('submit_employee_rating');
Route::get('/clients', 'SiteController@companyList')->name('company.list');
Route::get('/companies/search', 'SiteController@companySearch')->name('company.search');
Route::get('/candidate/profile/{slug}/{id}/{job_id}', 'SiteController@candidateProfile')->name('candidate.profile');
Route::get('/employers/profile/{slug}/{id}', 'SiteController@companyProfile')->name('profile');
Route::get('/job', 'SiteController@job')->name('job');
Route::get('/job/filter', 'SiteController@jobFilter')->name('job.filter');
Route::get('/job/detail/{id}', 'SiteController@jobDetails')->name('job.detail');
Route::get('/job/category/{id}', 'SiteController@jobCategory')->name('job.category');
Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');
Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');
Route::get('blog', 'SiteController@blog')->name('blog');
Route::get('blog/{id}/{slug}', 'SiteController@blogDetails')->name('blog.details');
Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');
Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/', 'SiteController@index')->name('home');
Route::get('/menu/{slug}/{id}', 'SiteController@footerMenu')->name('footer.menu');
Route::get('/candidate/cv/download/{id}', 'SiteController@candidateCvDownload')->name('candidate.cv.download');
Route::post('/contact/with/company', 'SiteController@contactWithCompany')->name('contact.with.company');
Route::post('/contact/with/employer', 'SiteController@contactWithEmployer')->name('contact.with.employer');
Route::get('/download_invoice/{id}', 'SiteController@downloadInvoice')->name('download.invoice');
Route::get('/preview_invoice/{id}', 'SiteController@previewInvoice')->name('preview.invoice');
Route::get('/print_invoice/{id}', 'SiteController@printInvoice')->name('print.invoice');
Route::get('/paddownload/{name}', 'SiteController@paddownload')->name('paddownload');
// Route::get('/invoices/{job_id}/{id}', 'SiteController@UserInovices')->name('user.invoices');
