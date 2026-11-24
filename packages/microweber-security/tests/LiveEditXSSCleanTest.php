<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\LiveEditXSSClean;
use MicroweberPackages\Security\XSSClean;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Contract for the permissive rich-content filter used on the trusted admin
 * Live-Edit / content-save path. It must KEEP rich, designed markup (a sanitized
 * <style> block and <iframe> embeds) that the strict base filter escapes, while
 * still stripping every script-execution vector — including ones hidden inside
 * the newly-allowed <style>/<iframe>.
 */
class LiveEditXSSCleanTest extends TestCase
{
    private function rich(string $html): string
    {
        return (string) (new LiveEditXSSClean())->clean($html);
    }

    // --- The strict base still escapes style/iframe (the behaviour we relax) ---

    #[Test]
    public function the_strict_base_filter_still_escapes_style_and_iframe(): void
    {
        $strict = new XSSClean();
        $this->assertStringNotContainsString('<style>', (string) $strict->clean('<style>.x{color:red}</style>'));
        $this->assertStringNotContainsString('<iframe', (string) $strict->clean('<iframe src="https://x/e"></iframe>'));
    }

    #[Test]
    public function admin_mode_option_on_the_base_filter_enables_the_rich_path(): void
    {
        $strict = new XSSClean();
        $out = (string) $strict->clean('<style>.x{color:red}</style>', ['admin_mode' => true]);
        $this->assertStringContainsString('<style>', $out);
    }

    // --- Rich markup survives ---

    /** @return array<string,array{0:string,1:string}> */
    public static function richMarkupProvider(): array
    {
        return [
            'style keyframes'   => ['<style>@keyframes f{from{opacity:0}to{opacity:1}}</style>', '@keyframes'],
            'style gradient'    => ['<style>.h{background:linear-gradient(135deg,#111,#333)}</style>', 'linear-gradient'],
            'style grid'        => ['<style>.g{display:grid;grid-template-columns:repeat(3,1fr)}</style>', 'grid-template-columns'],
            'style media query' => ['<style>@media(max-width:768px){.g{display:block}}</style>', '@media'],
            'style custom prop' => ['<style>.r{--accent:#f4a261;color:var(--accent)}</style>', '--accent'],
            'youtube iframe'    => ['<iframe src="https://www.youtube.com/embed/x" allowfullscreen></iframe>', 'youtube.com/embed'],
            'vimeo iframe'      => ['<iframe src="https://player.vimeo.com/video/1" width="640"></iframe>', 'player.vimeo.com'],
        ];
    }

    #[Test]
    #[DataProvider('richMarkupProvider')]
    public function rich_markup_survives(string $html, string $needle): void
    {
        $this->assertStringContainsString($needle, $this->rich($html));
    }

    #[Test]
    public function modern_css_properties_are_not_false_positives(): void
    {
        // scroll-behavior / overscroll-behavior contain the "behavior" substring
        // but are legitimate and must be kept.
        $out = $this->rich('<style>html{scroll-behavior:smooth}.x{overscroll-behavior:contain}</style>');
        $this->assertStringContainsString('scroll-behavior:smooth', $out);
        $this->assertStringContainsString('overscroll-behavior:contain', $out);
    }

    // --- Attacks are stripped, including inside the allowed style/iframe ---

    /** @return array<string,array{0:string,1:string}> */
    public static function attackProvider(): array
    {
        return [
            'script tag'          => ['<div>ok</div><script>alert(1)</script>', 'alert(1)'],
            'onclick handler'     => ['<button onclick="alert(1)">x</button>', 'onclick'],
            'javascript href'     => ['<a href="javascript:alert(1)">x</a>', 'javascript:alert'],
            'css expression'      => ['<style>.x{width:expression(alert(1))}</style>', 'expression('],
            'css url javascript'  => ['<style>.x{background:url(javascript:alert(1))}</style>', 'javascript:'],
            'css moz binding'     => ['<style>.x{-moz-binding:url(http://evil/x#x)}</style>', '-moz-binding'],
            'css behavior htc'    => ['<style>.x{behavior:url(evil.htc)}</style>', 'behavior:url'],
            'css remote import'   => ['<style>@import url(https://evil/x.css)</style>', '@import'],
            'css escaped expr'    => ['<style>.x{width:expr\\65 ssion(alert(1))}</style>', 'alert(1)'],
            'iframe javascript'   => ['<iframe src="javascript:alert(1)"></iframe>', 'javascript:'],
            'iframe data html'    => ['<iframe src="data:text/html,<script>alert(1)</script>"></iframe>', 'alert(1)'],
            'iframe srcdoc'       => ['<iframe srcdoc="<script>alert(1)</script>" src="https://ok/e"></iframe>', 'srcdoc'],
            'iframe onload'       => ['<iframe src="https://ok/e" onload="alert(1)"></iframe>', 'onload'],
        ];
    }

    #[Test]
    #[DataProvider('attackProvider')]
    public function attacks_are_stripped(string $html, string $mustNotContain): void
    {
        $out = $this->rich($html);
        $this->assertStringNotContainsStringIgnoringCase($mustNotContain, $out,
            "Attack vector survived the rich filter: {$mustNotContain}");
    }

    #[Test]
    public function a_dangerous_declaration_is_removed_but_the_rest_of_the_block_is_kept(): void
    {
        $out = $this->rich('<style>.ok{color:red}.evil{width:expression(alert(1))}.ok2{color:blue}</style>');
        $this->assertStringContainsString('<style>', $out);
        $this->assertStringContainsString('color:red', $out);
        $this->assertStringContainsString('color:blue', $out);
        $this->assertStringNotContainsStringIgnoringCase('expression(', $out);
    }

    #[Test]
    public function a_safe_iframe_is_kept_but_an_unsafe_one_in_the_same_input_is_dropped(): void
    {
        $out = $this->rich(
            '<iframe src="https://www.youtube.com/embed/x"></iframe>'
            . '<iframe src="javascript:alert(1)"></iframe>'
        );
        $this->assertStringContainsString('youtube.com/embed', $out);
        $this->assertStringNotContainsStringIgnoringCase('javascript:', $out);
    }
}
