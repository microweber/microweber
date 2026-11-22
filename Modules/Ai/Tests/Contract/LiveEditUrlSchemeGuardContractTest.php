<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guard for the Live-Edit AI URL-scheme defence.
 *
 * The agent can be steered by page text it reads (get_dom), so a link it sets
 * must never carry a script-executing or markup-bearing scheme. Two surfaces
 * write author-controlled URLs into the canvas and both must run every URL
 * through the shared helper: the set_link tool (an anchor href) and
 * sanitizeSectionHtml (hrefs + srcs inside add_section HTML). This pins the
 * helper and both call sites so neither silently regresses.
 *
 * Source-based (pre-boot). Every assertion targets an exact CODE expression
 * that does not occur in prose, so no comment-stripping is needed (and a naive
 * stripper would corrupt the regex literals this guard relies on).
 */
final class LiveEditUrlSchemeGuardContractTest extends TestCase
{
    private string $code = '';

    protected function setUp(): void
    {
        $root = dirname(__DIR__, 4);
        $path = $root . '/Modules/Ai/resources/assets/js/mw-ai.js';
        $this->assertFileExists($path, 'mw-ai.js is missing.');
        $this->code = (string) file_get_contents($path);
    }

    #[Test]
    public function is_unsafe_url_helper_blocks_script_and_markup_schemes(): void
    {
        $this->assertStringContainsString('isUnsafeUrl(val, isSrc)', $this->code,
            'A shared isUnsafeUrl(val, isSrc) helper must exist.');
        $this->assertStringContainsString('/^(javascript|vbscript):/', $this->code,
            'isUnsafeUrl must block the javascript: and vbscript: schemes.');
        $this->assertStringContainsString('/^data:/', $this->code,
            'isUnsafeUrl must block the data: scheme.');
        $this->assertStringContainsString('/^data:image\//', $this->code,
            'isUnsafeUrl must still allow an inline data:image/* on a src.');
        $this->assertStringContainsString('[\u0000- ]', $this->code,
            'isUnsafeUrl must strip control/whitespace before testing the scheme (java&#9;script: bypass).');
    }

    #[Test]
    public function set_link_runs_the_href_through_the_guard(): void
    {
        $this->assertStringContainsString('isUnsafeUrl(href, false)', $this->code,
            'set_link must reject an unsafe href via isUnsafeUrl before setting it.');
    }

    #[Test]
    public function sanitize_section_html_runs_urls_through_the_guard(): void
    {
        $this->assertStringContainsString("isUnsafeUrl(val, name === 'src')", $this->code,
            'sanitizeSectionHtml must drop href/src attributes whose scheme is unsafe.');
    }
}
