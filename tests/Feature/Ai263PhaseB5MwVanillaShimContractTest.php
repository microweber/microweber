<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-185 / AI-263 Phase B5 (2026-05-11) — `mw.$` vanilla
 * shim + drop `mw_require_jquery()` from Bootstrap master.
 *
 * THIS IS THE CYCLE WHERE 806KB ACTUALLY DROPS OFF every
 * Bootstrap-template page render.
 *
 * Phase B5 delivers:
 *
 *   1. `packages/frontend-assets/resources/assets/core/@core.js`
 *      now defines `mw.$` as a hybrid: passes through to the
 *      real jQuery when it's loaded (admin / legacy admin),
 *      returns an `MwDomCollection` vanilla DOM wrapper when
 *      jQuery is absent (public Bootstrap pages).
 *
 *   2. `MwDomCollection` implements jQuery's chainable API
 *      subset: addClass / removeClass / hasClass / toggleClass
 *      / attr / removeAttr / on / off / bind / unbind / trigger
 *      / html / text / val / css / remove / find / first / last
 *      / eq / parent / children / closest / is / append /
 *      prepend / show / hide / data / scrollTop / scroll /
 *      width / height / outerWidth / outerHeight / clone /
 *      each / ready / next / prev / replaceWith / empty /
 *      serialize / focus / blur / click / toggle / get /
 *      index / Symbol.iterator.
 *
 *   3. `window.$` + `window.jQuery` get aliased to `mw.$`
 *      when real jQuery isn't loaded — so legacy unguarded
 *      `$('.foo').addClass('bar')` calls in core/_.js /
 *      core/options.js / core/ajax.js / module skins
 *      automatically land on the vanilla wrapper. The `mw.$`
 *      function has a `window.jQuery !== mw.$` self-check to
 *      prevent infinite recursion on the alias.
 *
 *   4. Static-method jQuery compat on `$.each / $.extend /
 *      $.ajax / $.get / $.post / $.getScript / $.Deferred /
 *      $.isArray / $.isPlainObject / $.isFunction / $.trim /
 *      $.makeArray / $.inArray / $.parseJSON / $.noop /
 *      $.ajaxSetup`. The `$.ajax` shim translates jQuery
 *      ajax options to `fetch()` with same-origin credentials.
 *
 *   5. `events.js` line 5 — `$.each(eventName.split(' '), ...)`
 *      refactored to `.forEach(...)` so this file doesn't
 *      need jQuery's `$.each` to be present at module-init
 *      time.
 *
 *   6. Bootstrap `master.blade.php` — `mw_require_jquery()`
 *      call REMOVED. Public Bootstrap pages no longer eagerly
 *      load jquery.js (285KB) + jquery-ui.js (521KB) =
 *      **806KB saved per render**.
 *
 * Admin paths (/admin/*, /api/*) STILL load jQuery eagerly
 * per cycle-181's `isAdminPath()` check in ApijsScriptTag.
 * The cycle-181 conditional-emission infrastructure ALSO
 * still injects jQuery LATE (before </body>) on any public
 * page that renders a marker-bearing module skin
 * (slick-slider, masonry, datetimepicker, chosen,
 * data-mw-needs-jquery). Cycle-182/183 adapters attach to
 * jQuery if/when it loads via that path.
 */
class Ai263PhaseB5MwVanillaShimContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_185_anchor(): void
    {
        $core = $this->read('packages/frontend-assets/resources/assets/core/@core.js');
        $events = $this->read('packages/frontend-assets/resources/assets/core/events.js');
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');

        $this->assertMatchesRegularExpression('/[Cc]ycle-185/', $core,
            '@core.js MUST carry the cycle-185 anchor.');
        $this->assertStringContainsString('AI-263 Phase B5', $core,
            '@core.js MUST carry the AI-263 Phase B5 anchor.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-185/', $events,
            'events.js MUST carry the cycle-185 anchor (the $.each → '
            . 'forEach refactor on line 5).');
        $this->assertMatchesRegularExpression('/[Cc]ycle-185/', $master,
            'Bootstrap master.blade.php MUST carry the cycle-185 '
            . 'anchor documenting the mw_require_jquery() drop.');
    }

    #[Test]
    public function mw_dollar_is_hybrid_jquery_or_vanilla(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/@core.js');

        // The function body MUST check for real jQuery presence
        // with a self-alias guard (window.jQuery !== mw.$) so it
        // never infinite-recurses on the alias.
        $this->assertMatchesRegularExpression(
            '/window\.jQuery\s*!==\s*mw\.\$/m',
            $src,
            'mw.$ MUST check `window.jQuery !== mw.$` so when we '
            . 'alias window.jQuery to mw.$ on public pages the '
            . 'shim does NOT infinite-recurse.'
        );
        // Verifies it actually checks for jQuery's `.fn.jquery`
        // identifier (real jQuery only).
        $this->assertMatchesRegularExpression(
            '/typeof\s+window\.jQuery\.fn\.jquery\s*!==\s*[\'"]undefined[\'"]/m',
            $src,
            'mw.$ MUST sniff for window.jQuery.fn.jquery (the '
            . 'version-string fingerprint that only real jQuery '
            . 'exposes — our MwDomCollection vanilla wrapper does '
            . 'NOT set this).'
        );
        // Vanilla fallback returns an MwDomCollection.
        $this->assertStringContainsString('return new MwDomCollection(__mwDomToArray(selector, context))', $src,
            'mw.$ vanilla fallback MUST return a new MwDomCollection.');
    }

    #[Test]
    public function mw_dom_collection_has_all_pm_specified_methods(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/@core.js');

        $required = [
            'addClass', 'removeClass', 'hasClass',
            'attr',
            'on', 'off',
            'html', 'text',
            'css',
            'remove',
            'find',
            'first', 'last', 'eq',
            'parent', 'children',
            'each',
        ];
        foreach ($required as $method) {
            $this->assertMatchesRegularExpression(
                '/MwDomCollection\.prototype\.' . preg_quote($method, '/') . '\s*=\s*function/m',
                $src,
                "MwDomCollection MUST define the `{$method}` chainable "
                . 'method (PM-specified API surface).'
            );
        }
    }

    #[Test]
    public function mw_dom_collection_has_extended_methods(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/@core.js');

        // Beyond the PM list — methods Microweber legacy code uses.
        $extras = [
            'trigger', 'bind', 'unbind',
            'val',
            'append', 'prepend',
            'show', 'hide',
            'data',
            'scrollTop', 'scroll',
            'width', 'height',
            'outerWidth', 'outerHeight',
            'clone',
            'ready',
            'next', 'prev',
            'replaceWith', 'empty',
            'serialize',
            'closest', 'is',
            'toggleClass',
            'click', 'toggle',
            'index', 'get',
            'focus', 'blur',
        ];
        foreach ($extras as $method) {
            $this->assertStringContainsString('MwDomCollection.prototype.' . $method, $src,
                "MwDomCollection MUST define the `{$method}` method "
                . '(legacy Microweber code uses it).');
        }
    }

    #[Test]
    public function global_dollar_aliased_when_jquery_absent(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/@core.js');

        // window.$ + window.jQuery alias block — guarded by
        // typeof window.jQuery === 'undefined' so admin paths
        // (which load real jQuery first) keep the real jQuery.
        $this->assertMatchesRegularExpression(
            '/if\s*\(typeof\s+window\s*!==\s*[\'"]undefined[\'"]\s*&&\s*typeof\s+window\.jQuery\s*===\s*[\'"]undefined[\'"]\)\s*\{[\s\S]{0,200}window\.\$\s*=\s*mw\.\$/m',
            $src,
            '@core.js MUST guard window.$ = mw.$ alias behind '
            . '`typeof window.jQuery === "undefined"` so admin pages '
            . 'that load real jQuery first keep the real jQuery.'
        );
        $this->assertStringContainsString('window.jQuery = window.jQuery || mw.$', $src,
            '@core.js MUST alias window.jQuery to mw.$ ONLY if not '
            . 'already set, so admin paths keep real jQuery.');
    }

    #[Test]
    public function static_jquery_compat_methods_provided(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/@core.js');

        $statics = [
            'each',
            'extend',
            'isArray',
            'isPlainObject',
            'isFunction',
            'trim',
            'makeArray',
            'inArray',
            'parseJSON',
            'noop',
            'Deferred',
            'ajax',
            'get',
            'post',
            'getScript',
            'ajaxSetup',
        ];
        foreach ($statics as $name) {
            $this->assertStringContainsString('__jq.' . $name . ' =', $src,
                "@core.js MUST define `__jq.{$name}` static method "
                . 'so unguarded $.${name} calls work without jQuery.');
        }
    }

    #[Test]
    public function ajax_shim_uses_fetch_with_same_origin_credentials(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/@core.js');

        // $.ajax compatibility — uses fetch.
        $this->assertMatchesRegularExpression(
            '/__jq\.ajax\s*=\s*function[\s\S]{0,4000}fetch\(url,\s*fetchOpts\)/m',
            $src,
            '$.ajax shim MUST call fetch(url, fetchOpts) — translates '
            . 'jQuery options to fetch.'
        );
        // Same-origin credentials.
        $this->assertStringContainsString("credentials: 'same-origin'", $src,
            '$.ajax fetch options MUST include credentials: same-origin '
            . 'so cookies / session work.');
    }

    #[Test]
    public function events_js_replaces_dollar_each_with_foreach(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/events.js');

        // Old `$.each(eventName.split(' '), function(){...})` GONE
        // from executable code.
        $stripped = preg_replace('!/\*.*?\*/!s', '', $src);
        $stripped = preg_replace('!//[^\n]*!', '', $stripped);
        $this->assertStringNotContainsString("\$.each(eventName.split(' ')", $stripped,
            'events.js MUST NOT use $.each at module-init time in '
            . 'executable code — that throws when jQuery is missing.');

        // New native forEach.
        $this->assertStringContainsString("eventName.split(' ').forEach", $src,
            'events.js MUST use native eventName.split(\' \').forEach '
            . 'so this code runs without jQuery being loaded.');
    }

    #[Test]
    public function bootstrap_master_drops_mw_require_jquery(): void
    {
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');

        // The `mw_require_jquery()` CALL must be gone from the
        // template (still defined in helpers — admin paths use it
        // via the cycle-181 isAdminPath check). Strip blade comment
        // blocks before checking so the comment that documents the
        // removal doesn't trigger a false positive.
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $master);
        $stripped = preg_replace('/\{\{[\s\S]*?\}\}/', '', $stripped);
        $stripped = preg_replace('/@php([\s\S]*?)@endphp/', '', $stripped);
        $this->assertStringNotContainsString('mw_require_jquery()', $stripped,
            'Bootstrap master.blade.php MUST NOT call mw_require_jquery() '
            . 'in EXECUTABLE PHP — Phase B5 dropped the opt-in flag. '
            . 'The comment may still mention it.');

        // The comment documents the 5-phase journey.
        $this->assertStringContainsString('Phase B5', $master,
            'Bootstrap master.blade.php MUST document the Phase B5 '
            . 'milestone in the comment.');
        $this->assertStringContainsString('806KB', $master,
            'Bootstrap master.blade.php MUST document the 806KB '
            . 'savings outcome.');
    }

    #[Test]
    public function builtin_frontend_js_carries_mw_dom_collection(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/frontend.js';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped('Built frontend.js missing.');
        }
        $built = file_get_contents($path);

        // Distinctive identifiers — confirm the vanilla shim shipped
        // to the compiled bundle.
        $this->assertStringContainsString('MwDomCollection', $built,
            'Built frontend.js MUST contain the MwDomCollection '
            . 'class identifier — confirms the vanilla shim shipped.');
        $this->assertStringContainsString('__mwDomToArray', $built,
            'Built frontend.js MUST contain the __mwDomToArray '
            . 'helper.');
    }
}
