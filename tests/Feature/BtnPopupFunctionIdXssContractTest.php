<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-63 / AI-56 / TICKET-CW — Btn module popupFunctionId admin-XSS
 * regression coverage.
 *
 * Pins the contract that `popupFunctionId` is force-sanitised to a
 * strict JavaScript identifier shape at every layer where it is
 * emitted into JS context:
 *
 *   1. Modules/Btn/Microweber/BtnModule.php — source layer
 *   2. Modules/Btn/resources/views/components/popup.blade.php — view layer
 *   3. Modules/Btn/resources/views/templates/bootstrap.blade.php — view layer
 *
 * Defence-in-depth: even if a future refactor breaks the source
 * sanitiser, both view files re-apply preg_replace at render time so
 * the safety property cannot drift.
 *
 * Style after the cycle-52..62 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class BtnPopupFunctionIdXssContractTest extends TestCase
{
    private string $btnModuleSrc;
    private string $popupBlade;
    private string $bootstrapBlade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->btnModuleSrc = file_get_contents(base_path(
            'Modules/Btn/Microweber/BtnModule.php'
        ));
        $this->popupBlade = file_get_contents(base_path(
            'Modules/Btn/resources/views/components/popup.blade.php'
        ));
        $this->bootstrapBlade = file_get_contents(base_path(
            'Modules/Btn/resources/views/templates/bootstrap.blade.php'
        ));
    }

    #[Test]
    public function btn_module_sanitises_popup_function_id_at_source(): void
    {
        // The source-layer sanitiser is the primary defence.
        $this->assertMatchesRegularExpression(
            "/preg_replace\\(\\s*['\"]\\/\\[\\^A-Za-z0-9_\\]\\/['\"]/",
            $this->btnModuleSrc,
            'BtnModule: popupFunctionId must pass through preg_replace(/[^A-Za-z0-9_]/) sanitiser at source'
        );
        $this->assertStringContainsString(
            "\$viewData['popupFunctionId']",
            $this->btnModuleSrc,
            'BtnModule: popupFunctionId assignment must remain'
        );
    }

    #[Test]
    public function popup_blade_resanitises_popup_function_id_at_render(): void
    {
        // Defence-in-depth: the view file is reachable via direct include
        // and cached compiled views, so the sanitiser must re-apply here.
        $this->assertMatchesRegularExpression(
            "/\\\$popupFunctionId\\s*=\\s*preg_replace\\(\\s*['\"]\\/\\[\\^A-Za-z0-9_\\]\\/['\"]/",
            $this->popupBlade,
            'popup.blade.php: must re-sanitise popupFunctionId via preg_replace at render time'
        );
        // btnId is also interpolated into HTML ids and JS strings; pin
        // its sanitiser too (allows hyphen since "link-{id}" shape is
        // used).
        $this->assertMatchesRegularExpression(
            "/\\\$btnId\\s*=\\s*preg_replace\\(\\s*['\"]\\/\\[\\^A-Za-z0-9_\\-\\]\\/['\"]/",
            $this->popupBlade,
            'popup.blade.php: must re-sanitise btnId via preg_replace at render time'
        );
    }

    #[Test]
    public function bootstrap_blade_resanitises_popup_function_id_before_javascript_uri(): void
    {
        // The `href="javascript:{{ $popupFunctionId }}()"` URI is the
        // direct XSS surface — Blade {{ }} HTML-escapes but does NOT
        // JS-escape. The view must re-sanitise before that interpolation.
        $this->assertMatchesRegularExpression(
            "/\\\$popupFunctionId\\s*=\\s*preg_replace\\(\\s*['\"]\\/\\[\\^A-Za-z0-9_\\]\\/['\"]/",
            $this->bootstrapBlade,
            'bootstrap.blade.php: must re-sanitise popupFunctionId via preg_replace before the javascript: URI'
        );

        // The href shape must come AFTER the @php sanitiser block.
        $sanitiserPos = strpos(
            $this->bootstrapBlade,
            "\$popupFunctionId = preg_replace"
        );
        $hrefPos = strpos(
            $this->bootstrapBlade,
            'href="javascript:{{ $popupFunctionId }}()"'
        );
        $this->assertNotFalse($sanitiserPos, 'bootstrap.blade.php: sanitiser block must exist');
        $this->assertNotFalse($hrefPos, 'bootstrap.blade.php: href="javascript:...()" usage must exist');
        $this->assertLessThan(
            $hrefPos,
            $sanitiserPos,
            'bootstrap.blade.php: sanitiser must run BEFORE the href="javascript:..." interpolation'
        );
    }

    #[Test]
    public function popup_function_id_sanitiser_strips_xss_payloads_in_practice(): void
    {
        // Pin the actual behaviour of the sanitiser regex with a few
        // representative XSS payloads. Mirrors the regex used in
        // BtnModule.php / popup.blade.php / bootstrap.blade.php.
        $sanitise = fn (string $input): string => preg_replace(
            '/[^A-Za-z0-9_]/',
            '',
            $input
        );

        // Classic JS-context break-out attempts. The regex strips
        // every non-`[A-Za-z0-9_]` character; the result is always a
        // single legal JS identifier, never a callable sequence with
        // parens, semicolons, comment markers, or angle brackets.
        $this->assertSame('alert1', $sanitise('alert(1)'));
        $this->assertSame('fooalert1', $sanitise('foo();alert(1);//'));
        $this->assertSame('scriptalert1script', $sanitise('"><script>alert(1)</script>'));
        $this->assertSame('mwPopupBtnabc123', $sanitise('mwPopupBtnabc123'));
        $this->assertSame('', $sanitise('()/'));

        // Cross-check: the sanitised output never contains any of the
        // characters needed to break out of a `<a href="javascript:X()">`
        // URI or a `function X() {}` declaration. This is the actual
        // safety property — single legal identifier, no metacharacters.
        $payloads = [
            'alert(1)',
            'foo();alert(1);//',
            '"><script>alert(1)</script>',
            'a()/*',
            "a';alert(1);//",
        ];
        foreach ($payloads as $payload) {
            $clean = $sanitise($payload);
            $this->assertMatchesRegularExpression(
                '/^[A-Za-z0-9_]*$/',
                $clean,
                "sanitiser output for `{$payload}` must contain only identifier chars; got `{$clean}`"
            );
        }

        // Real md5 output — the only legitimate shape — passes through unchanged.
        $hex = md5('module-instance-id-1234');
        $this->assertSame($hex, $sanitise($hex));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{32}$/', $hex);
    }
}
