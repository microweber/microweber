<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['xss'])->group(function () {
    Route::post('api/pingstats', function (Request $request) {
        // Check for Do Not Track header
        if ($request->header('DNT') == 1) {
            return response()->json(['message' => 'Do Not Track enabled'], 403);
        }

        $to_track = false;
        $referer = $request->headers->get('referer');
        if ($referer) {
            $ref_page = $referer;
            if (starts_with($ref_page, site_url())) {
                if ($request->ajax()) {
                    $to_track = true;
                }
            }
        }
        if (!$to_track) {
            return response()->json(['message' => 'Not tracking'], 403);
        }

        $tracker = new \Modules\SiteStats\Support\Tracker();

        if (get_option('stats_is_buffered', 'site_stats') == 1) {
            $tracker->track_buffered();
        } else {
            $tracker->track();
        }

        // Decode referrer and save UTM in cookie
        $referer = request()->headers->get('referer');
        $refererParse = parse_url($referer);
        if (!empty($refererParse)) {
            $refererQuery = [];
            if (isset($refererParse['query']) && !empty($refererParse['query'])) {
                parse_str($refererParse['query'], $refererQuery);
            }

            if (!empty($refererQuery)) {
                foreach ($refererQuery as $refererQueryKey => $refererQueryValue) {
                    if (in_array($refererQueryKey, ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'])) {
                        set_cookie($refererQueryKey, $refererQueryValue);
                    }
                }
            }
        }

        if (!isset($_COOKIE['_mw_stats_visitor_data'])) {
            \Modules\SiteStats\Support\UtmVisitorData::setVisitorData([
                'utm_visitor_id' => md5(rand(100000000, 999999999).time()),
            ]);
        }

        event(new \Modules\SiteStats\Events\PingStatsEvent([
            'referer'=>$referer,
        ]));

        $pingStatsViewResponse = "var mwpingstats={}; \n";

        $overwriteResponse = mw()->event_manager->trigger('mw.pingstats.response');
        if (!empty($overwriteResponse)) {
            foreach ($overwriteResponse as $response) {
                $pingStatsViewResponse .= $response . "\n";
            }
        }

        $response = response($pingStatsViewResponse);

        $response->header('Pragma', 'no-cache');
        $response->header('Content-Type', 'text/javascript');
        $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        $response->header('Cache-Control', 'no-cache, must-revalidate, no-store, max-age=0, private');

        return $response;

     });
});
