<?php

use Illuminate\Support\Facades\Route;

Route::name('modules.newsletter.')
    // audit-test 2026-05-07 Email-Module Audit finding #1 (SECURITY):
    // /click-link and /pixel were `web` only — no rate limiting, no
    // redirect-target validation. That made the newsletter system an
    // open-redirect helper for phishing (any attacker URL hosted on
    // `https://your-site/click-link?…`), and a stats-poisoning surface
    // (sequential campaign IDs + no auth). Adding `throttle:60,1` caps
    // the abuse surface at 60 requests per minute per IP — high enough
    // to never affect a legitimate subscriber clicking 60 emails in a
    // minute, low enough that a single attacker can't write millions
    // of rows.
    ->middleware(['web', 'throttle:60,1'])
    ->group(function () {

        Route::get('/click-link', function () {

            $campaignId = request()->get('campaign_id');
            $requestEmail = request()->get('email');
            $requestIp = request()->ip();
            $userAgent = request()->userAgent();
            $redirectTo = request()->get('redirect_to');
            $redirectTo = urldecode((string) $redirectTo);

            // audit-test 2026-05-07 finding #1 (SECURITY): the
            // open-redirect leg. Anyone could craft
            // /click-link?redirect_to=https://attacker.example.com to
            // make Microweber 302 to an attacker URL — perfect phishing
            // vehicle since the link looks like the legitimate site.
            //
            // Smallest fix that closes the vector: refuse any
            // redirect_to whose host is NOT the same as the site's
            // configured host. A signed-token approach (HMAC over
            // campaign_id|email|redirect_to) would be stronger but is
            // a larger change — tracked as TICKET-QQ. Same-host
            // validation handles 99% of real-world abuse today.
            $safeRedirect = null;
            if ($redirectTo) {
                $parts = parse_url($redirectTo);
                if ($parts !== false && ! empty($parts['host'])) {
                    $scheme = strtolower($parts['scheme'] ?? '');
                    $siteHost = parse_url(url('/'), PHP_URL_HOST);
                    if (in_array($scheme, ['http', 'https'], true)
                        && $siteHost
                        && strcasecmp($parts['host'], $siteHost) === 0) {
                        $safeRedirect = $redirectTo;
                    }
                }
            }

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

            // Always 302 — but to the validated target if any, else home.
            return redirect($safeRedirect ?: url('/'));
        })->name('click-link');

        Route::get('/pixel', function () {

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

            return response()->stream(function () {
                $png = imagecreatetruecolor(1, 1);
                imagepng($png);
            }, 200, ['Content-type' => 'image/png']);

        })->name('pixel');


        Route::get('/unsubscribe', function () {
            return view('microweber-module-newsletter::unsubscribe');

        })->name('unsubscribe');
        Route::post('subscribe', \Modules\Newsletter\Http\NewsletterController::class . '@subscribe')->name('subscribe');

    });
