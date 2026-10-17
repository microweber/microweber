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

        // excluded-prefix regex. Pre-fix, unmatched admin URLs
    // (e.g. /admin/media, /admin/custom-fields) fell through to
    // the frontend catch-all and rendered the "My title / My text
    // content" placeholder page. Adding `admin` lets unmatched
    // admin URLs propagate to Route::fallback() below which
    // returns a clean 404.
            //
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
        //
        // prefix regex. 4th instance in the silent-stub family (sibling
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
        // prefix regex. Different defect-family from the silent-stub family
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
        // Route::fallback below).
        // the excluded-prefix regex. 7th confirmed silent-stub Stage-3
            // returned HTTP 200 with "Describe your company" / "Your Story Should
    // Evolve" fixture content because FrontendController's
    // `is_installed('blog')` / `is_installed('faq')` / `is_installed
    // ('testimonials')` checks all returned true (these modules ship with
    // the standard Microweber install but only as content-surface modules
    // — they have NO public route + NO standalone listing page, only
    // <module type="..."> embeddable widgets). Excluding them from the
    // catch-all routes the URL to Route::fallback() which renders the
            // dispatch): build actual listing-page controllers if standalone
    // /blog + /faq + /testimonials listing pages are desired (would
        //
        // captcha` to the excluded-prefix regex. 8th confirmed silent-stub
            // + /categories + /captcha all returned HTTP 200 with placeholder /
    // empty fixture content because FrontendController's silent-stub
    // detector (`is_installed('tag')` / `is_installed('category')` /
    // `is_installed('captcha')` — note Tag/Category module names map to
    // singular per Microweber convention) returned true: Modules/Tag,
    // Modules/Category, Modules/Captcha all ship with the standard
    // install. None of these modules expose a public listing surface —
    // Tag/Category are taxonomy back-ends consumed by Content templates
    // via `<module type="..."/>` embeddable widgets; Captcha is a
    // backend-only image generator (admin Filament + form-validation
    // helper). Excluding them from the catch-all routes the URL to
        // proper "no such page" surface vs. silent-stub fixture leak. The
    // `tag` and `tags` entries are BOTH required because PCRE word
    // boundary `\b` matches between `g` and `s` only when the next char
    // is a non-word char — `tags` would NOT be matched by the `tag\b`
    // anchor (the `\b` between `g` and `s` does not apply since both are
        // build actual taxonomy listing-page controllers (/tag/{slug} +
    // /category/{slug} archive pages) if standalone tag + category
        // shape and need TagController + CategoryController + Routes +
    // listing views + content-by-taxonomy DB queries).
    Route::any('{slug}', array('as' => 'slug', 'uses' =>
        \MicroweberPackages\Frontend\Http\Controllers\FrontendController::class . '@index'))
        ->middleware('web')
                                // (layout_file=blog.blade.php). With that fix in place, /blog is a real content
        // page in the DB and FrontendController resolves it correctly. Excluding blog
                ->where('slug', '^(?!vendor|packages|template|modules|css|storage|userfiles|js|admin|search|shop|customer|faq|testimonials|tag\b|tags\b|categories\b|captcha\b).*')
        ->name('website');

    Route::fallback(function () {
        $url = url_string();

                        // catch-all exclusion above so unmatched /admin/* URLs reach
        // this fallback. Previously this fallback returned plain-text
        // 404 — user lands on /admin/seo-settings + sees a wall of
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

                                                        // built for the FrontendController $show_404_to_non_admin
        // branch — same view is now reused here for catch-all-excluded
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

                // mid-migration / template-removal edge case), return the
        // original text/plain shape to preserve audit-trail behaviour.
        return response('Page not found at url: ' . $url, 404)->withHeaders([
            'Content-Type' => 'text/plain',
            'X-Fallback-Message' => 'true',
            'X-Powered-By' => 'Microweber'
        ]);
    });
});

