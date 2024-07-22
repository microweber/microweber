<?php

Route::name('web.newsletter.')
    ->prefix('/web/modules/newsletter')
    ->middleware(['web'])
    ->group(function () {

        Route::get('/pixel', function() {

            $campaignId = request()->get('campaign_id');
            if ($campaignId) {
                $findCampaign = \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::where('id', $campaignId)->first();
                if ($findCampaign) {
                    
                }
            }

            return response()->stream(function() {
                $png = imagecreatetruecolor(1, 1);
                imagepng($png);
            }, 200, ['Content-type' => 'image/png']);

        })->name('pixel');


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

            return redirect('/');

        })->name('unsubscribe');

    });
