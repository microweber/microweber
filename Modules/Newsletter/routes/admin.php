<?php

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Senders\NewsletterMailSender;
use Modules\Newsletter\Http\Controllers\Admin\NewsletterSubscriberExportController;
use Modules\Newsletter\Http\Controllers\Admin\NewsletterListExportController;
use Modules\Newsletter\Http\Controllers\Admin\NewsletterCampaignExportController;


Route::name('filament.admin-newsletter.')
    ->prefix(mw_admin_prefix_url() . '/modules/newsletter')
    ->middleware(['admin'])
    ->group(function () {

        // Export Routes
        Route::get('/export/subscribers', [NewsletterSubscriberExportController::class, 'export'])->name('export.subscribers');
        Route::get('/export/lists', [NewsletterListExportController::class, 'export'])->name('export.lists');
        Route::get('/export/campaigns', [NewsletterCampaignExportController::class, 'export'])->name('export.campaigns');
    });

Route::name('admin.newsletter.')
    ->prefix(mw_admin_prefix_url() . '/modules/newsletter')
    ->middleware(['admin'])
    ->group(function () {

        Route::post('/upload-subscribers-list',
            \Modules\Newsletter\Http\Controllers\Admin\NewsletterUploadSubscribersListController::class . '@upload')->name('upload-subscribers-list');

        Route::get('/', \Modules\Newsletter\Http\Controllers\Admin\AdminController::class . '@index')->name('index');
        Route::get('/subscribers', \Modules\Newsletter\Http\Controllers\Admin\AdminController::class . '@subscribers')->name('subscribers');
        Route::get('/lists', \Modules\Newsletter\Http\Controllers\Admin\AdminController::class . '@lists')->name('lists');
        Route::get('/templates', \Modules\Newsletter\Http\Controllers\Admin\AdminController::class . '@templates')->name('templates');
        Route::get('/sender-accounts', \Modules\Newsletter\Http\Controllers\Admin\AdminController::class . '@senderAccounts')->name('sender-accounts');
        Route::get('/settings', \Modules\Newsletter\Http\Controllers\Admin\AdminController::class . '@settings')->name('settings');

        Route::post('/templates/edit/{id}', \Modules\Newsletter\Http\Controllers\Admin\AdminController::class . '@templatesEdit')->name('templates.edit');

        Route::get('/templates/preview/{id}', function ($id) {

            $template = \Modules\Newsletter\Models\NewsletterTemplate::find($id);
            if (!$template) {
                return;
            }

            echo $template->text;

        })->name('templates.preview');

        Route::get('/preview-campaign-email', function () {

            $newsletterCampaignId = request()->get('id');
            $campaign = \Modules\Newsletter\Models\NewsletterCampaign::where('id', $newsletterCampaignId)->first();
            if (!$campaign) {
                return;
            }

            $templateArray = [];
            if ($campaign->email_content_type == 'design') {
                $template = NewsletterTemplate::where('id', $campaign->email_template_id)->first();
                $templateArray = $template->toArray();
            } else {
                $templateArray['text'] = $campaign->email_content_html;
            }

            $newsletterMailSender = new NewsletterMailSender();
            $newsletterMailSender->setCampaign($campaign->toArray());
            $newsletterMailSender->setSubscriber([
                'name' => 'Jhon Doe',
                'first_name' => 'Jhon',
                'last_name' => 'Doe',
                'email' => 'jhon.doe@microweber.com',
            ]);
            $newsletterMailSender->setTemplate($templateArray);

            echo $newsletterMailSender->getParsedTemplate();

        });

        Route::get('/preview-email-template-saved', function () {

            $templateId = request()->get('id');
            $emailTemplate = \Modules\Newsletter\Models\NewsletterTemplate::where('id', $templateId)->first();
            if (!$emailTemplate) {
                return;
            }

            return view('microweber-module-newsletter::email-templates.preview', [
                'content' => 'This is a test content',
                'title' => 'This is a test title',
                'name' => 'Jhon Doe',
                'unsubscribe_url' => '',
                'email' => 'jhon@doe.com',
                'html' => $emailTemplate->text,
            ]);


        });

        Route::get('/preview-email-template', function () {

            $templateFilename = request()->get('filename');
            if (!$templateFilename) {
                return;
            }
            $templateJson = file_get_contents(module_path('newsletter') . '/resources/views/email-templates/' . $templateFilename . '.json');
            $templateJson = json_decode($templateJson, true);

//            $templateHtml = file_get_contents(module_path('newsletter'). '/resources/views/email-templates/' . $templateFilename. '.html');
//            if (!$templateHtml) {
//                return;
//            }

            $render = new \Modules\Newsletter\EmailTemplateRendering\Render();
            $templateHtml = $render->html($templateJson);

            return view('microweber-module-newsletter::email-templates.preview', [
                'content' => 'This is a test content',
                'title' => 'This is a test title',
                'name' => 'Jhon Doe',
                'unsubscribe_url' => '',
                'email' => 'jhon@doe.com',
                'html' => $templateHtml,
            ]);

        })->name('preview-email-template');

        Route::post('sender-accounts/save', \Modules\Newsletter\Http\Controllers\Admin\NewsletterSenderAccountController::class . '@save')->name('sender-accounts.save');


    });
