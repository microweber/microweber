<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-66 / AI-59 / TICKET-VV — image picker URL tab scheme
 * validation regression coverage.
 *
 * Pins:
 *   - PHP helper `mw_is_safe_remote_url()` exists, lives in
 *     src/MicroweberPackages/Helper/functions/url.php, accepts
 *     http(s) + protocol-relative `//host/path`, rejects
 *     javascript:/data:/file:/vbscript: and malformed/empty input.
 *   - JS helper `AdminTools.isAllowedFileUrl()` exists in
 *     admin-tools.service.js with the same surface (regex + URL
 *     parser fallback so the two sides agree byte-for-byte).
 *   - filepicker.js URL tab calls the JS helper before persisting
 *     a typed value, AND surfaces an inline error region when the
 *     URL is rejected.
 *
 * Style after the cycle-52..65 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ImagePickerUrlSchemeContractTest extends TestCase
{
    private string $urlHelperSrc;
    private string $adminToolsSrc;
    private string $filepickerSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlHelperSrc = file_get_contents(base_path(
            'src/MicroweberPackages/Helper/functions/url.php'
        ));
        $this->adminToolsSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/js/admin-tools.service.js'
        ));
        $this->filepickerSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
    }

    #[Test]
    public function php_helper_is_globally_registered(): void
    {
        // The function_exists() guard pattern matches the rest of the
        // url.php helpers — pin both the declaration AND the runtime
        // availability.
        $this->assertStringContainsString(
            "function_exists('mw_is_safe_remote_url')",
            $this->urlHelperSrc,
            'url.php: must declare mw_is_safe_remote_url() inside a function_exists() guard'
        );
        $this->assertStringContainsString(
            'function mw_is_safe_remote_url($url): bool',
            $this->urlHelperSrc,
            'url.php: mw_is_safe_remote_url must have the canonical signature'
        );
        $this->assertTrue(
            function_exists('mw_is_safe_remote_url'),
            'mw_is_safe_remote_url() must be globally available via the autoloaded helpers file'
        );
    }

    #[Test]
    public function php_helper_accepts_http_and_https_remote_urls(): void
    {
        $this->assertTrue(mw_is_safe_remote_url('http://example.com/foo.jpg'));
        $this->assertTrue(mw_is_safe_remote_url('https://example.com/foo.jpg'));
        $this->assertTrue(mw_is_safe_remote_url('HTTPS://Example.COM/foo.jpg'));
        // Protocol-relative URLs resolve to the page scheme — always
        // http(s) in any page-load context — so they are safe.
        $this->assertTrue(mw_is_safe_remote_url('//cdn.example.com/foo.jpg'));
    }

    #[Test]
    public function php_helper_rejects_dangerous_schemes(): void
    {
        // The full set of XSS-vector schemes that have historically
        // shipped in browsers.
        $payloads = [
            'javascript:alert(1)',
            'JaVaScRiPt:alert(1)',
            'data:text/html,<script>alert(1)</script>',
            'file:///etc/passwd',
            'vbscript:msgbox(1)',
            'chrome://settings',
            // ftp/about/blob — not http(s), so reject as remote-image URL.
            'ftp://example.com/foo.jpg',
            'about:blank',
            'blob:http://example.com/abc-123',
        ];
        foreach ($payloads as $payload) {
            $this->assertFalse(
                mw_is_safe_remote_url($payload),
                "mw_is_safe_remote_url: must reject `{$payload}`"
            );
        }
    }

    #[Test]
    public function php_helper_rejects_empty_and_malformed_input(): void
    {
        $this->assertFalse(mw_is_safe_remote_url(''));
        $this->assertFalse(mw_is_safe_remote_url('   '));
        // parse_url accepts bare strings as paths, with no host. Pin
        // that we reject those (the picker's URL tab is for REMOTE
        // URLs; relative paths belong on the local-asset tab).
        $this->assertFalse(mw_is_safe_remote_url('not-a-url'));
        $this->assertFalse(mw_is_safe_remote_url('/relative/path.jpg'));
        // Wrong types must not blow up.
        $this->assertFalse(mw_is_safe_remote_url(null));
        $this->assertFalse(mw_is_safe_remote_url(123));
        $this->assertFalse(mw_is_safe_remote_url([]));
    }

    #[Test]
    public function js_admin_tools_declares_is_allowed_file_url_method(): void
    {
        $this->assertStringContainsString(
            'isAllowedFileUrl(value)',
            $this->adminToolsSrc,
            'admin-tools.service.js: AdminTools must expose isAllowedFileUrl(value) method'
        );
        // The implementation must use the URL constructor (not just a
        // regex) so the parsing matches the PHP parse_url path.
        $this->assertStringContainsString(
            'new URL(trimmed,',
            $this->adminToolsSrc,
            'admin-tools.service.js: must use the URL constructor for parsing (matches PHP parse_url)'
        );
        // Both http AND https accepted.
        $this->assertMatchesRegularExpression(
            '/scheme\\s*!==\\s*"http"\\s*&&\\s*scheme\\s*!==\\s*"https"/',
            $this->adminToolsSrc,
            'admin-tools.service.js: must accept http AND https schemes only'
        );
    }

    #[Test]
    public function filepicker_url_tab_calls_validator_before_persisting(): void
    {
        // The URL tab handler must invoke the AdminTools validator
        // BEFORE setSectionValue commits the value. The contract pins
        // the call shape so a future refactor that swaps the handler
        // for a bare `setSectionValue(val)` would fail loudly.
        $this->assertStringContainsString(
            'mw.tools.isAllowedFileUrl(val)',
            $this->filepickerSrc,
            'filepicker.js: URL tab must call mw.tools.isAllowedFileUrl(val) before committing'
        );
        // Inline error surface for UX feedback.
        $this->assertStringContainsString(
            'data-mw-filepicker-url-error',
            $this->filepickerSrc,
            'filepicker.js: URL tab must render an inline error region (data-mw-filepicker-url-error)'
        );
        // Negative path: when allowed === false, setSectionValue
        // receives null (not the malicious string).
        $this->assertMatchesRegularExpression(
            '/if\\s*\\(\\s*!\\s*allowed\\s*\\)\\s*\\{[^}]*scope\\.setSectionValue\\s*\\(\\s*null/',
            $this->filepickerSrc,
            'filepicker.js: rejected URLs must call setSectionValue(null), not the malicious value'
        );
    }
}
