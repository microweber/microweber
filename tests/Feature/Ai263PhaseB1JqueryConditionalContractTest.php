<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\MetaTags\Entities\ApijsScriptTag;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-181 / AI-263 Phase B1 (2026-05-11) — conditional jQuery
 * emission + vanilla CSRF fetch interceptor on public pages.
 *
 * Phase A audit (cycle-180) identified
 * `src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php`
 * as the SINGLE unconditional jQuery + jQuery UI script-tag
 * emission point on public pages (806KB combined: 285KB
 * jquery.js + 521KB jquery-ui.js). Phase B1 implementation:
 *
 *   1. ApijsScriptTag now only eagerly emits jQuery on admin
 *      paths (`/admin*`, `/api/*`). Public pages get vanilla
 *      `fetch` CSRF interceptor + frontend.js only.
 *
 *   2. The legacy `$.ajaxSetup` inline CSRF script is replaced
 *      with a guarded version that only runs IF jQuery is loaded
 *      later in the request (lazy-loaded by the conditional
 *      footer injection).
 *
 *   3. `TemplateManager::injectConditionalJqueryFooter()` scans
 *      the rendered layout for jQuery-plugin markers (slick,
 *      masonry, datetimepicker, chosen, `data-mw-needs-jquery`).
 *      If any found, injects jquery.js + jquery-ui.js + the
 *      legacy `$.ajaxSetup` shim BEFORE `</body>`. If none
 *      found, skips entirely — public pages save 806KB of
 *      blocking JS.
 *
 *   4. `frontend.js` jQuery extensions (`$.fn.commuter`,
 *      `jQuery.fn.reload_module`) are now guarded behind
 *      `typeof window.jQuery !== 'undefined'` checks. If jQuery
 *      isn't loaded at frontend.js execution time, the
 *      extension is queued in
 *      `window.__mwRegisterJqueryExtensions[]` and registered
 *      later by the conditional footer script.
 *
 * Admin paths (legacy + Filament) are entirely unchanged.
 */
class Ai263PhaseB1JqueryConditionalContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_181_anchor(): void
    {
        $apijs = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');
        $tm = $this->read('src/MicroweberPackages/Template/TemplateManager.php');
        $frontend = $this->read('packages/frontend-assets/resources/assets/js/frontend.js');

        $this->assertStringContainsString('AI-263 Phase B1', $apijs,
            'ApijsScriptTag.php MUST carry the AI-263 Phase B1 anchor.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-181/', $apijs,
            'ApijsScriptTag.php MUST carry the cycle-181 anchor.');
        $this->assertStringContainsString('AI-263 Phase B1', $tm,
            'TemplateManager.php MUST carry the AI-263 Phase B1 anchor.');
        $this->assertStringContainsString('AI-263 Phase B1', $frontend,
            'frontend.js MUST carry the AI-263 Phase B1 anchor.');
    }

    #[Test]
    public function apijs_script_tag_drops_unconditional_jquery_emission(): void
    {
        $apijs = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');
        // The jQuery + UI emission MUST be wrapped in the
        // needsJqueryEager branch (admin OR opt-in flag) —
        // never unconditional.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$needsJqueryEager\s*\)[\s\S]{0,800}mw-jquery-js-libs-scripts/m',
            $apijs,
            'ApijsScriptTag::toHtml MUST gate the jquery.js + '
            . 'jquery-ui.js script tags behind the $needsJqueryEager '
            . 'check so non-opt-in public pages skip them entirely.'
        );
        // $needsJqueryEager combines admin path + opt-in flag.
        $this->assertMatchesRegularExpression(
            '/\$needsJqueryEager\s*=\s*\$isAdminPath\s*\|\|\s*\$this->requestRequiresJquery\(\)/m',
            $apijs,
            'ApijsScriptTag MUST compute $needsJqueryEager as the OR '
            . 'of isAdminPath (legacy admin) and requestRequiresJquery '
            . '(template opt-in flag).'
        );
        // isAdminPath helper exists.
        $this->assertMatchesRegularExpression(
            '/protected\s+function\s+isAdminPath\(\)\s*:\s*bool/m',
            $apijs,
            'ApijsScriptTag MUST define a protected isAdminPath() '
            . 'method that returns true on /admin* and /api/* URLs.'
        );
        // requestRequiresJquery helper exists.
        $this->assertMatchesRegularExpression(
            '/protected\s+function\s+requestRequiresJquery\(\)\s*:\s*bool/m',
            $apijs,
            'ApijsScriptTag MUST define a protected '
            . 'requestRequiresJquery() method that reads the '
            . 'mw.requires_jquery container binding (set by '
            . 'mw_require_jquery() helper).'
        );
        // Uses request()->is for path matching.
        $this->assertStringContainsString("\$request->is('admin')", $apijs,
            'isAdminPath() MUST use request()->is("admin") for path '
            . 'detection (covers /admin exact match).');
        $this->assertStringContainsString("\$request->is('admin/*')", $apijs,
            'isAdminPath() MUST also cover request()->is("admin/*") '
            . '(all admin sub-pages).');
    }

    #[Test]
    public function mw_require_jquery_helper_exists(): void
    {
        $src = $this->read('src/MicroweberPackages/MetaTags/helpers/meta_tags_functions.php');
        $this->assertMatchesRegularExpression(
            '/if\s*\(!function_exists\(\'mw_require_jquery\'\)\)\s*\{\s*function\s+mw_require_jquery\(\)\s*:\s*void/m',
            $src,
            'meta_tags_functions.php MUST define a global '
            . 'mw_require_jquery(): void helper templates call from '
            . 'their master.blade.php to opt in to eager jQuery.'
        );
        $this->assertStringContainsString("app()->instance('mw.requires_jquery', true)", $src,
            'mw_require_jquery() MUST set the mw.requires_jquery '
            . 'container instance to true (request-scoped flag).');

        // Bootstrap template MUST call the helper at top of master.
        $bootstrap = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');
        $this->assertStringContainsString('mw_require_jquery()', $bootstrap,
            'Templates/Bootstrap/resources/views/layouts/master.blade.php '
            . 'MUST call mw_require_jquery() — its dist/build/app.js '
            . 'uses jQuery at module init, so eager loading is '
            . 'required until that JS is refactored.');
    }

    #[Test]
    public function opt_in_path_emits_eager_jquery(): void
    {
        // Set up a public request context + opt-in flag.
        $request = \Illuminate\Http\Request::create('/', 'GET');
        $this->app->instance('request', $request);
        $this->app->instance('mw.requires_jquery', true);

        $tag = new ApijsScriptTag();
        $html = $tag->toHtml();

        $this->assertStringContainsString('mw-jquery-js-libs-scripts', $html,
            'When mw.requires_jquery flag is true, ApijsScriptTag '
            . 'MUST emit the eager jquery.js <script> tag even on '
            . 'public paths — proves the template opt-in path works.');

        // Cleanup so other tests aren't polluted.
        $this->app->forgetInstance('mw.requires_jquery');
    }

    #[Test]
    public function apijs_script_tag_emits_vanilla_csrf_fetch_interceptor(): void
    {
        $apijs = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');
        // The vanilla CSRF wrapper exists.
        $this->assertStringContainsString('mw-js-csrf-vanilla', $apijs,
            'ApijsScriptTag MUST emit a <script id="mw-js-csrf-vanilla"> '
            . 'tag that contains the vanilla-fetch CSRF interceptor.');
        // Wraps window.fetch.
        $this->assertMatchesRegularExpression(
            '/window\.fetch\s*=\s*function\s*\(\s*input,\s*init\s*\)/m',
            $apijs,
            'CSRF interceptor MUST wrap window.fetch with a function '
            . '(input, init) signature.'
        );
        // Uses __mwCsrfFetchWrapped guard to prevent double-wrap.
        $this->assertStringContainsString('__mwCsrfFetchWrapped', $apijs,
            'CSRF interceptor MUST guard with window.__mwCsrfFetchWrapped '
            . 'so multiple ApijsScriptTag renders never double-wrap fetch.');
        // Same-origin check + token from meta[name=csrf-token].
        $this->assertStringContainsString('meta[name="csrf-token"]', $apijs,
            'CSRF interceptor MUST read token from '
            . '<meta name="csrf-token"> content attribute.');
        $this->assertStringContainsString('X-CSRF-TOKEN', $apijs,
            'CSRF interceptor MUST set the X-CSRF-TOKEN request header.');
        $this->assertStringContainsString('sameOrigin', $apijs,
            'CSRF interceptor MUST guard token injection behind a '
            . 'same-origin check so the token is NOT sent to '
            . 'cross-origin URLs (security).');
    }

    #[Test]
    public function apijs_script_tag_emits_guarded_jquery_csrf_shim(): void
    {
        $apijs = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');
        // Guarded $.ajaxSetup shim — only runs if jQuery exists.
        $this->assertStringContainsString('mw-js-csrf-jquery-guarded', $apijs,
            'ApijsScriptTag MUST emit a guarded jQuery CSRF shim.');
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*typeof\s+window\.jQuery\s*===\s*[\'"]undefined[\'"]\s*\)\s*return/m',
            $apijs,
            'guarded shim MUST short-circuit when jQuery is undefined '
            . '(so public pages without jQuery do NOT throw).'
        );
    }

    #[Test]
    public function template_manager_injects_conditional_footer(): void
    {
        $tm = $this->read('src/MicroweberPackages/Template/TemplateManager.php');
        // injectConditionalJqueryFooter method exists.
        $this->assertMatchesRegularExpression(
            '/public\s+function\s+injectConditionalJqueryFooter\(string\s+\$layout\)\s*:\s*string/m',
            $tm,
            'TemplateManager MUST define '
            . 'injectConditionalJqueryFooter(string $layout): string '
            . 'as a public method.'
        );
        // layoutNeedsJquery helper exists with marker scan.
        $this->assertMatchesRegularExpression(
            '/protected\s+function\s+layoutNeedsJquery\(string\s+\$layout\)\s*:\s*bool/m',
            $tm,
            'TemplateManager MUST define '
            . 'layoutNeedsJquery(string $layout): bool helper.'
        );
        // Called from frontend_append_meta_tags only on public path.
        $this->assertMatchesRegularExpression(
            '/if\s*\(!\$is_laravel_template\)\s*\{[\s\S]{0,1000}\$this->injectConditionalJqueryFooter\(\$layout\)/m',
            $tm,
            'frontend_append_meta_tags MUST call '
            . 'injectConditionalJqueryFooter() ONLY when '
            . '$is_laravel_template is false (= public-page render).'
        );
    }

    #[Test]
    public function template_manager_marker_set_covers_known_plugins(): void
    {
        $tm = $this->read('src/MicroweberPackages/Template/TemplateManager.php');
        // Markers from the Phase A audit must all be in the scan list.
        $markers = [
            'slick-slider',
            'slick-track',
            'data-slick',
            'masonry',
            'datetimepicker',
            'chosen-select',
            'chosen-container',
            'data-mw-needs-jquery',
        ];
        foreach ($markers as $marker) {
            $this->assertStringContainsString("'{$marker}'", $tm,
                "layoutNeedsJquery() MUST scan for the '{$marker}' "
                . "marker (jQuery-plugin / opt-in indicator).");
        }
    }

    #[Test]
    public function frontend_js_extensions_guarded_behind_typeof_check(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/js/frontend.js');
        // commuter registration is now in a function guarded by
        // typeof window.$.
        $this->assertMatchesRegularExpression(
            '/__mwRegisterCommuter[\s\S]{0,500}typeof\s+window\.\$\s*===\s*[\'"]undefined[\'"]/m',
            $src,
            'frontend.js __mwRegisterCommuter MUST guard $.fn '
            . 'extension behind typeof window.$ === "undefined" '
            . 'check so frontend.js can load before jQuery.'
        );
        // reload_module similarly guarded.
        $this->assertMatchesRegularExpression(
            '/__mwRegisterReloadModule[\s\S]{0,500}typeof\s+window\.jQuery\s*===\s*[\'"]undefined[\'"]/m',
            $src,
            'frontend.js __mwRegisterReloadModule MUST guard '
            . 'jQuery.fn extension behind typeof window.jQuery '
            . 'check.'
        );
        // Lazy-register queue.
        $this->assertStringContainsString('__mwRegisterJqueryExtensions', $src,
            'frontend.js MUST push deferred registrations into '
            . 'window.__mwRegisterJqueryExtensions[] so the conditional '
            . 'footer script can call them after jQuery loads.');
    }

    #[Test]
    public function conditional_footer_runner_registers_extensions_after_jquery_loads(): void
    {
        $tm = $this->read('src/MicroweberPackages/Template/TemplateManager.php');
        // The injected footer script MUST call the queued extensions.
        $this->assertMatchesRegularExpression(
            '/mw-jquery-late-bootstrap[\s\S]{0,1500}__mwRegisterJqueryExtensions[\s\S]{0,400}ext\[i\]\(\)/m',
            $tm,
            'The injected footer bootstrap script MUST iterate '
            . 'window.__mwRegisterJqueryExtensions[] and invoke each '
            . 'so frontend.js extensions are registered after jQuery '
            . 'loads.'
        );
        // Bootstraps $.ajaxSetup with token.
        $this->assertMatchesRegularExpression(
            '/mw-jquery-late-bootstrap[\s\S]{0,800}window\.jQuery\.ajaxSetup\(/m',
            $tm,
            'The injected footer bootstrap script MUST call '
            . 'window.jQuery.ajaxSetup() with the CSRF token so the '
            . 'legacy jQuery-AJAX CSRF flow still works on pages '
            . 'that load jQuery via this path.'
        );
    }

    #[Test]
    public function admin_path_emits_legacy_eager_jquery(): void
    {
        // Set up an admin request context and assert ApijsScriptTag
        // still emits the legacy eager-load behavior.
        $request = \Illuminate\Http\Request::create('/admin/login', 'GET');
        $this->app->instance('request', $request);

        $tag = new ApijsScriptTag();
        $html = $tag->toHtml();

        $this->assertStringContainsString('mw-jquery-js-libs-scripts', $html,
            'On admin path, ApijsScriptTag MUST still emit the '
            . 'jquery.js <script> tag (legacy behavior preserved).');
        $this->assertStringContainsString('mw-jquery-ui-js-libs-scripts', $html,
            'On admin path, ApijsScriptTag MUST still emit the '
            . 'jquery-ui.js <script> tag.');
        $this->assertStringContainsString('mw-jquery-ui-js-libs-styles', $html,
            'On admin path, ApijsScriptTag MUST still emit the '
            . 'jquery-ui.css <link> tag.');
    }

    #[Test]
    public function public_path_does_not_emit_eager_jquery(): void
    {
        // Set up a public request context and assert ApijsScriptTag
        // does NOT emit jquery.js eagerly. Forget the opt-in flag
        // in case a previous test set it.
        $request = \Illuminate\Http\Request::create('/', 'GET');
        $this->app->instance('request', $request);
        $this->app->forgetInstance('mw.requires_jquery');

        $tag = new ApijsScriptTag();
        $html = $tag->toHtml();

        $this->assertStringNotContainsString('mw-jquery-js-libs-scripts', $html,
            'On public path, ApijsScriptTag MUST NOT emit the '
            . 'eager jquery.js <script> tag — public pages save '
            . '806KB of blocking JS.');
        $this->assertStringNotContainsString('mw-jquery-ui-js-libs-scripts', $html,
            'On public path, ApijsScriptTag MUST NOT emit the '
            . 'eager jquery-ui.js <script> tag.');
        // But vanilla CSRF interceptor + guarded shim ARE emitted.
        $this->assertStringContainsString('mw-js-csrf-vanilla', $html,
            'On public path, ApijsScriptTag MUST emit the vanilla '
            . 'fetch CSRF interceptor.');
        $this->assertStringContainsString('mw-js-csrf-jquery-guarded', $html,
            'On public path, ApijsScriptTag MUST emit the guarded '
            . 'jQuery CSRF shim (no-op until jQuery loads).');
    }

    #[Test]
    public function injection_skipped_when_no_jquery_markers(): void
    {
        $tm = app()->template_manager;
        $emptyLayout = "<html><head></head><body><p>plain page</p></body></html>";
        $result = $tm->injectConditionalJqueryFooter($emptyLayout);

        $this->assertStringNotContainsString('jquery.js', $result,
            'Layout with no jQuery markers MUST NOT trigger jquery.js '
            . 'injection.');
        $this->assertStringNotContainsString('mw-jquery-late-bootstrap', $result,
            'Layout with no jQuery markers MUST NOT trigger the late-'
            . 'bootstrap script.');
        $this->assertSame($emptyLayout, $result,
            'Layout with no jQuery markers MUST be returned unchanged.');
    }

    #[Test]
    public function injection_triggered_by_each_marker_type(): void
    {
        $tm = app()->template_manager;
        $cases = [
            'slick'         => "<body><div class=\"slick-slider\"></div></body>",
            'masonry'       => "<body><div data-masonry></div></body>",
            'datetimepicker'=> "<body><input class=\"datetimepicker\"></body>",
            'chosen'        => "<body><select class=\"chosen-select\"></select></body>",
            'opt-in'        => "<body><div data-mw-needs-jquery></div></body>",
        ];

        foreach ($cases as $name => $layout) {
            $result = $tm->injectConditionalJqueryFooter($layout);
            $this->assertStringContainsString('jquery.js', $result,
                "Layout with {$name} marker MUST trigger jquery.js "
                . "injection.");
            $this->assertStringContainsString('jquery-ui.js', $result,
                "Layout with {$name} marker MUST trigger jquery-ui.js "
                . "injection.");
            $this->assertStringContainsString('mw-jquery-late-bootstrap', $result,
                "Layout with {$name} marker MUST trigger the late-"
                . "bootstrap script that re-registers frontend.js "
                . "extensions + sets up legacy \$.ajaxSetup.");
        }
    }

    #[Test]
    public function built_frontend_js_carries_guarded_extensions(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/frontend.js';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built frontend.js missing.");
        }
        $built = file_get_contents($path);
        // Distinctive identifiers from the guarded extensions.
        $this->assertStringContainsString('__mwRegisterJqueryExtensions', $built,
            'Built frontend.js MUST carry __mwRegisterJqueryExtensions '
            . 'queue identifier — confirms the lazy-register hook '
            . 'shipped to the bundle.');
        $this->assertStringContainsString('__mwRegisterCommuter', $built,
            'Built frontend.js MUST carry the guarded '
            . '__mwRegisterCommuter function.');
        $this->assertStringContainsString('__mwRegisterReloadModule', $built,
            'Built frontend.js MUST carry the guarded '
            . '__mwRegisterReloadModule function.');
    }
}
