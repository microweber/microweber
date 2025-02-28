<?php

use Illuminate\Support\Facades\Route;

Route::name('web.newsletter.')
    ->prefix('/web/modules/newsletter')
    ->middleware(['web'])
    ->group(function () {

        Route::get('/click-link', function() {

            $campaignId = request()->get('campaign_id');
            $requestEmail = request()->get('email');
            $requestIp = request()->ip();
            $userAgent = request()->userAgent();
            $redirectTo = request()->get('redirect_to');
            $redirectTo = urldecode($redirectTo);

            if ($campaignId) {
                $findCampaign = \Modules\Newsletter\Models\NewsletterCampaign::where('id', $campaignId)->first();
                if ($findCampaign) {
                    $newsletterCampaignClickedLink = new \Modules\Newsletter\Models\NewsletterCampaignClickedLink();
                    $newsletterCampaignClickedLink->campaign_id = $campaignId;
                    $newsletterCampaignClickedLink->email = $requestEmail;
                    $newsletterCampaignClickedLink->ip = $requestIp;
                    $newsletterCampaignClickedLink->user_agent = $userAgent;
                    $newsletterCampaignClickedLink->link = $redirectTo;
                    $newsletterCampaignClickedLink->save();
                }
            }

            return redirect($redirectTo);
        })->name('click-link');

        Route::get('/pixel', function() {

            $campaignId = request()->get('campaign_id');
            if ($campaignId) {
                $findCampaign = \Modules\Newsletter\Models\NewsletterCampaign::where('id', $campaignId)->first();
                if ($findCampaign) {
                    $newsletterCampaignPixel = new \Modules\Newsletter\Models\NewsletterCampaignPixel();
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
