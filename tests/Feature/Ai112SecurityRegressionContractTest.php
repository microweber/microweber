<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-132 / AI-112 / TICKET-BY — Security regression contract.
 *
 * Pins three previously-shipped security fixes so they cannot silently
 * regress under an unrelated refactor:
 *
 *   - cycle-43 / TICKET-AL : NewsletterSenderAccount encrypts every secret
 *                            credential at rest via the Eloquent `encrypted`
 *                            cast — smtp_password, mailchimp_secret,
 *                            mailgun_secret, mandrill_secret, sparkpost_secret,
 *                            amazon_ses_key.
 *   - cycle-25 / Post XSS : the "icon_html" template helper no longer accepts
 *                           bare `<…>` strings — every `<i>`, `<svg>`, `<img>`,
 *                           `<span>` pass-through is routed through
 *                           mw()->format->clean_xss (the project-blessed
 *                           XSSSecurity + svg-sanitize pipeline).
 *   - cycle-33 / icon_html allowlist : the SAME helper drops the bare-`<`
 *                                     catch-all that used to return ANY string
 *                                     starting with `<` verbatim — only the
 *                                     four allowlisted opening sequences are
 *                                     accepted, plus the four prefix-style
 *                                     conventions (mdi-, mw-, icon-, fa-,
 *                                     glyphicon-).
 *
 * Source-grep style after Sec05SsrfAndStoredXssContractTest.
 */
class Ai112SecurityRegressionContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    // -------------------------------------------------------------------
    // cycle-43 / TICKET-AL — NewsletterSenderAccount encrypted-at-rest
    // -------------------------------------------------------------------

    #[Test]
    public function newsletter_sender_account_encrypts_every_secret_field(): void
    {
        $src = $this->read('Modules/Newsletter/Models/NewsletterSenderAccount.php');

        // The cast map MUST include every credential field so a SELECT *
        // returns ciphertext, not plaintext. Removing any of these silently
        // re-exposes the secret.
        $required = [
            'smtp_password',
            'mailchimp_secret',
            'mailgun_secret',
            'mandrill_secret',
            'sparkpost_secret',
            'amazon_ses_key',
        ];
        foreach ($required as $field) {
            $this->assertMatchesRegularExpression(
                '/[\'"]' . preg_quote($field, '/') . '[\'"]\s*=>\s*[\'"]encrypted[\'"]/',
                $src,
                "NewsletterSenderAccount::\$casts MUST contain '{$field}' => "
                . "'encrypted' so the credential never sits at rest in plaintext."
            );
        }
    }

    // -------------------------------------------------------------------
    // cycle-25 + cycle-33 — icon_html XSS allowlist + clean_xss routing
    // -------------------------------------------------------------------

    #[Test]
    public function icon_html_rejects_non_string_or_empty_input(): void
    {
        $src = $this->read('src/MicroweberPackages/Template/helpers/templates.php');

        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*!\s*is_string\(\$icon\)\s*\|\|\s*empty\(\$icon\)\s*\)/',
            $src,
            'icon_html MUST guard against non-string and empty input '
            . 'as the very first branch.'
        );
    }

    #[Test]
    public function icon_html_routes_every_html_passthrough_through_clean_xss(): void
    {
        $src = $this->read('src/MicroweberPackages/Template/helpers/templates.php');

        // Each of the four allowlisted opening sequences MUST route through
        // mw()->format->clean_xss to strip event handlers, javascript: URLs,
        // and (for SVG) enforce the svg-sanitize tag/attribute allowlist.
        $allowlisted = [
            '<i class="',
            '<svg',
            '<img',
            '<span class="',
        ];
        foreach ($allowlisted as $opening) {
            $quoted = preg_quote($opening, '/');
            $this->assertMatchesRegularExpression(
                '/str_starts_with\(\$icon,\s*[\'"]' . $quoted . '[\'"]\)\s*\)\s*\{\s*return\s+mw\(\)->format->clean_xss\(\$icon\)/s',
                $src,
                "icon_html MUST route the '{$opening}' allowlist branch through "
                . 'mw()->format->clean_xss (post-cycle-33 follow-up #2 hardening).'
            );
        }
    }

    #[Test]
    public function icon_html_does_not_have_bare_lt_catch_all(): void
    {
        $src = $this->read('src/MicroweberPackages/Template/helpers/templates.php');

        // Cycle-33 deleted the bare `<` catch-all. Ensure no `str_starts_with($icon, '<')`
        // returning $icon unmodified has been re-introduced (XSS escape hatch).
        $this->assertDoesNotMatchRegularExpression(
            '/str_starts_with\(\$icon,\s*[\'"]<[\'"]\)\s*\)\s*\{\s*return\s+\$icon\s*;/',
            $src,
            'icon_html MUST NOT have a bare `<` catch-all that returns the '
            . 'string unmodified — this was the universal XSS escape hatch '
            . 'removed in cycle-33.'
        );
    }

    #[Test]
    public function icon_html_keeps_safe_prefix_conventions(): void
    {
        $src = $this->read('src/MicroweberPackages/Template/helpers/templates.php');

        // The non-HTML prefix conventions ARE safe (the helper builds the
        // tag itself with a fixed class), so they should remain — pinning
        // them so a refactor does not accidentally drop them.
        foreach (['mdi-', 'mw-', 'icon-', 'fa-', 'glyphicon-'] as $prefix) {
            $this->assertMatchesRegularExpression(
                '/str_starts_with\(\$icon,\s*[\'"]' . preg_quote($prefix, '/') . '[\'"]\)/',
                $src,
                "icon_html MUST keep the '{$prefix}' prefix branch so existing "
                . "icon classnames render correctly."
            );
        }
    }
}
