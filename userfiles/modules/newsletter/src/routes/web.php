<?php

Route::name('web.newsletter.')
    ->prefix('/web/modules/newsletter')
    ->middleware(['web'])
    ->group(function () {

        Route::get('/unsubscribe', function() {

            $email = request()->get('email');
            if (empty($email)) {
                return redirect('/');
            }
            $findSubscriber = \MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber::where('email', $email)->first();
            if (!$findSubscriber) {
                return redirect('/');
            }

            $findSubscriber->is_subscribed = 0;
            $findSubscriber->save();

        })->name('unsubscribe');

    });
