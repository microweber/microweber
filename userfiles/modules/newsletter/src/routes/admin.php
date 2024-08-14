<?php

use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\Senders\NewsletterMailSender;

Route::name('admin.newsletter.')
    ->prefix(mw_admin_prefix_url() . '/modules/newsletter')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin')
    ->group(function () {

        Route::post('/upload-subscribers-list', 'NewsletterUploadSubscribersListController@upload')->name('upload-subscribers-list');

        Route::get('/', 'AdminController@index')->name('index');
        Route::get('/subscribers', 'AdminController@subscribers')->name('subscribers');
        Route::get('/lists', 'AdminController@lists')->name('lists');
        Route::get('/templates', 'AdminController@templates')->name('templates');
        Route::get('/sender-accounts', 'AdminController@senderAccounts')->name('sender-accounts');
        Route::get('/settings', 'AdminController@settings')->name('settings');

        Route::post('/templates/edit/{id}', 'AdminController@templatesEdit')->name('templates.edit');

        Route::get('/templates/preview/{id}', function ($id) {

            $template = \MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate::find($id);
            if (!$template) {
                return;
            }

            echo $template->text;

        })->name('templates.preview');

        Route::get('/preview-campaign-email', function() {

            $newsletterCampaignId = request()->get('id');
            $campaign = \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::where('id', $newsletterCampaignId)->first();
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

        Route::get('/preview-email-template-saved', function() {

            $templateId = request()->get('id');
            $emailTemplate = \MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate::where('id', $templateId)->first();
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

        Route::get('/preview-email-template', function() {

            $templateFilename = request()->get('filename');
            if (!$templateFilename) {
                return;
            }
            $templateJson = file_get_contents(modules_path() . 'newsletter/src/resources/views/email-templates/' . $templateFilename. '.json');
            $templateJson = json_decode($templateJson, true);

//            $templateHtml = file_get_contents(modules_path() . 'newsletter/src/resources/views/email-templates/' . $templateFilename. '.html');
//            if (!$templateHtml) {
//                return;
//            }

            $render = new \MicroweberPackages\Modules\Newsletter\EmailTemplateRendering\Render();
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

        Route::post('sender-accounts/save', 'NewsletterSenderAccountController@save')->name('sender-accounts.save');

    });
