<?php


use  \Illuminate\Support\Facades\Route;


Route::group(
    [

    ], function () {

    Route::any('/', \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index')
        ->middleware('web')
        ->name('home');

/*
    Route::any('{any}', array('as' => 'all', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('all', '.*');
*/


/*    Route::any('{slug}', array('as' => 'slug', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('slug', '.*')->name('website');
    */

    // task-2026-05-16-256d49 / AI-735 — added `admin` to the
    // excluded-prefix regex. Pre-fix, unmatched admin URLs
    // (e.g. /admin/media, /admin/custom-fields) fell through to
    // the frontend catch-all and rendered the "My title / My text
    // content" placeholder page. Adding `admin` lets unmatched
    // admin URLs propagate to Route::fallback() below which
    // returns a clean 404. Surfaces 1 (front-end 404 returning
    // 200) and 2 (search results template missing) remain — they
    // sit deeper in FrontendController + Search-module routing
    // and are tracked as AI-735a / AI-735b follow-ups.
    Route::any('{slug}', array('as' => 'slug', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('slug', '^(?!vendor|packages|template|modules|css|storage|userfiles|js|admin).*')
        ->name('website');

    Route::fallback(function () {
        $url = url_string();

        // task-2026-05-17-a596d2 / AI-793 — admin-styled 404 propagation.
        // AI-735 slice 1 (task-2026-05-16-256d49) added `admin` to the
        // catch-all exclusion above so unmatched /admin/* URLs reach
        // this fallback. Previously this fallback returned plain-text
        // 404 — user lands on /admin/seo-settings + sees a wall of
        // unstyled text. AI-793 detects admin-prefix URLs and renders
        // the admin-styled 404 view at
        // resources/views/admin/errors/404.blade.php. Front-end URLs
        // keep the existing text/plain behaviour (front-end users get
        // their template's stock 404 via app/Exceptions/Handler if
        // configured; the text/plain shape is an explicit Microweber
        // signal that the URL didn't match ANY layer).
        $adminPrefix = trim((string) (mw_admin_prefix_url() ?: 'admin'), '/');
        $normalised = ltrim((string) $url, '/');
        if ($adminPrefix !== '' && (
            $normalised === $adminPrefix ||
            str_starts_with($normalised, $adminPrefix . '/')
        )) {
            return response()
                ->view('admin.errors.404', ['requestedUrl' => '/' . $normalised], 404)
                ->withHeaders([
                    'X-Fallback-Message' => 'admin-404',
                    'X-Powered-By' => 'Microweber',
                ]);
        }

        return response('Page not found at url: ' . $url, 404)->withHeaders([
            'Content-Type' => 'text/plain',
            'X-Fallback-Message' => 'true',
            'X-Powered-By' => 'Microweber'
        ]);
    });
});







