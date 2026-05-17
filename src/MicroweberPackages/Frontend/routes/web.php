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
    // returns a clean 404.
    //
    // task-2026-05-17-3e91f4 / AI-837 — added `search` to the
    // excluded-prefix regex. AI-735b follow-up closure. Pre-fix,
    // /search?q=X fell through to FrontendController which detected
    // "search" as an installed module name and rendered Templates/
    // Bootstrap clean.blade.php with hardcoded "My title / My text
    // content" placeholder at 200 OK (no noindex). The Search module
    // now ships a real `Route::get('search', SearchController@index)`
    // via Modules/Search/routes/web.php (loaded via
    // SearchServiceProvider::register() since 2026-05-17). The
    // exclusion-regex addition is belt-and-braces: if Search module
    // is ever disabled, /search 404s cleanly via Route::fallback()
    // instead of regressing back to the FrontendController stub.
    // Surface 1 (front-end 404 returning 200) closed by AI-795.
    //
    // task-2026-05-17-66a21a / AI-849 — added `shop` to the excluded-
    // prefix regex. 4th instance in the silent-stub family (sibling
    // lineage AI-755 → AI-795 → AI-837 → this), FIRST on the primary
    // commerce surface. Pre-fix, /shop + /shop/products + /shop/category
    // + /shop/featured + /shop/categories all fell through to
    // FrontendController which detected "shop" as an installed module
    // name and rendered the same clean.blade.php placeholder. The Shop
    // module now ships a real `Route::get('shop/{path?}', ShopController
    // @index)` via Modules/Shop/routes/web.php (loaded via
    // ShopServiceProvider::register() since 2026-05-17). The exclusion-
    // regex addition is belt-and-braces: if Shop module is ever disabled,
    // /shop subroutes 404 cleanly via Route::fallback() instead of
    // regressing back to the FrontendController stub renderer.
    //
    // task-2026-05-17-28d21d / AI-850 — added `customer` to the excluded-
    // prefix regex. Different defect-family from the silent-stub family
    // (AI-849 etc.) — the Customer module is BACKEND-ONLY (admin Filament
    // resource + API endpoints; no frontend templates exist). Pre-fix,
    // /customer fell through to FrontendController which detected
    // "customer" as an installed module (lines 781-797: `is_installed`
    // check), set `$page['content'] = '<module type="customer" />'` +
    // `$page['layout_file'] = 'clean.php'` + `$show_404_to_non_admin =
    // false`. Because Customer module has no template, the `<module
    // type="customer" />` rendered to nothing — body became just the
    // ApijsScriptTag meta-tag chrome (CSRF fetch wrapper) + empty
    // <title>. Designer-reported body sample showed the CSRF JS at the
    // top of the response. Decision per designer's Slice A hypothesis 3:
    // `/customer` shouldn't exist as a public surface (admin-side use
    // `/admin/customers`, end-customer use `/profile`). Adding to the
    // exclusion regex routes /customer to Route::fallback which now
    // renders the AI-795 chrome-404 view (extended in this ship — see
    // Route::fallback below).
    // task-2026-05-17-35e2d6 / AI-856 — added `blog|faq|testimonials` to
    // the excluded-prefix regex. 7th confirmed silent-stub Stage-3
    // sibling-renderer family member (AI-755 / AI-795 / AI-837 / AI-849 /
    // AI-850 / AI-856 lineage). Pre-fix, /blog + /faq + /testimonials all
    // returned HTTP 200 with "Describe your company" / "Your Story Should
    // Evolve" fixture content because FrontendController's
    // `is_installed('blog')` / `is_installed('faq')` / `is_installed
    // ('testimonials')` checks all returned true (these modules ship with
    // the standard Microweber install but only as content-surface modules
    // — they have NO public route + NO standalone listing page, only
    // <module type="..."> embeddable widgets). Excluding them from the
    // catch-all routes the URL to Route::fallback() which renders the
    // AI-795 chrome-wrapped 404 — proper "no such page" surface vs.
    // silent-stub fixture leak. AI-856b follow-up candidate (separate
    // dispatch): build actual listing-page controllers if standalone
    // /blog + /faq + /testimonials listing pages are desired (would
    // mirror AI-837 SearchController shape).
    Route::any('{slug}', array('as' => 'slug', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
        ->where('slug', '^(?!vendor|packages|template|modules|css|storage|userfiles|js|admin|search|shop|customer|blog|faq|testimonials).*')
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

        // task-2026-05-17-28d21d / AI-850 — non-admin Route::fallback now
        // renders the AI-795 chrome-404 view (instead of plain text/plain)
        // so excluded-prefix URLs (/customer per AI-850; /search if
        // Search module is disabled per AI-837; /shop if Shop module is
        // disabled per AI-849) land on a styled 404 page matching the
        // active template's chrome with noindex meta. AI-795 view was
        // built for the FrontendController $show_404_to_non_admin
        // branch — same view is now reused here for catch-all-excluded
        // URLs that reach Route::fallback. Resolves the AI-795
        // active-template-master with Bootstrap fallback inline (AI-757
        // pattern) so this fallback doesn't depend on FrontendController.
        $activeTemplate = (string) (get_option('current_template', 'template') ?? '');
        $extendsView = 'templates.bootstrap::layouts.master';
        if ($activeTemplate !== '') {
            $candidate = 'templates.' . strtolower($activeTemplate) . '::layouts.master';
            if (view()->exists($candidate)) {
                $extendsView = $candidate;
            }
        }

        if (view()->exists('frontend.errors.404')) {
            return response()
                ->view('frontend.errors.404', [
                    'extendsView' => $extendsView,
                    'requestedUrl' => '/' . ltrim((string) $url, '/'),
                ], 404)
                ->withHeaders([
                    'X-Robots-Tag' => 'noindex, nofollow',
                    'X-Fallback-Message' => 'frontend-404',
                    'X-Powered-By' => 'Microweber',
                ]);
        }

        // Final fallback: if the AI-795 view is missing (fresh install
        // mid-migration / template-removal edge case), return the
        // original text/plain shape to preserve audit-trail behaviour.
        return response('Page not found at url: ' . $url, 404)->withHeaders([
            'Content-Type' => 'text/plain',
            'X-Fallback-Message' => 'true',
            'X-Powered-By' => 'Microweber'
        ]);
    });
});







