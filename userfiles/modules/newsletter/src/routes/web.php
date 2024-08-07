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
                    $newsletterCampaignPixel = new \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignPixel();
                    $newsletterCampaignPixel->campaign_id = $campaignId;
                    $newsletterCampaignPixel->email = request()->get('email');
                    $newsletterCampaignPixel->ip = request()->ip();
                    $newsletterCampaignPixel->user_agent = request()->userAgent();
                    $newsletterCampaignPixel->save();
                }
            }

            return response()->stream(function() {
                $png = imagecreatetruecolor(1, 1);
                imagepng($png);
            }, 200, ['Content-type' => 'image/png']);

        })->name('pixel');


        Route::get('/unsubscribe', function() {
            return view('microweber-module-newsletter::unsubscribe');

        })->name('unsubscribe');

    });
