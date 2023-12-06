<?php

Route::name('admin.newsletter.')
    ->prefix(ADMIN_PREFIX . '/modules/newsletter')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/', 'AdminController@index')->name('index');
        Route::get('/preview-email-template-iframe', function() {

            $templateFilename = request()->get('filename');
            if (!$templateFilename) {
                return;
            }

            return view('microweber-module-newsletter::email-templates.'.$templateFilename, [
                'content' => 'This is a test content',
                'title' => 'This is a test title',
                'name' => 'Jhon Doe',
                'unsubscribe_url' => '',
                'email' => 'jhon@doe.com'
            ]);

        })->name('preview-email-template-iframe');

        Route::post('sender-accounts/save', 'NewsletterSenderAccountController@save')->name('sender-accounts.save');

    });
