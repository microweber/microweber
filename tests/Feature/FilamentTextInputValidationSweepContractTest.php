<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-96 / AI-85 / TICKET-AW — Filament TextInput validation sweep.
 *
 * Pins:
 *   - Every Filament `TextInput::make('phone')` carries `->tel()` so
 *     mobile keyboards promote the dial-pad and the field declares
 *     its semantic intent to AT.
 *   - Every Filament `TextInput::make('website')` carries `->url()`
 *     so save-time rejects "javascript:..." or "not-a-url" before
 *     they hit the database.
 *   - Every Filament `TextInput::make('email')` carries `->email()`
 *     (already established baseline; pinning the absence of drift).
 *
 * Out of scope (intentional false-positives the audit script flags
 * but the fields aren't actually typed):
 *   - `url` field on Content + Category SEO tabs is a path slug
 *     (e.g. "/my-page"), not a full URL.
 *   - `url` field on Monitoring/ErrorTrackingResource is a
 *     `->disabled()` display field.
 *   - `price` field on Billing/SubscriptionPlan is a free-text
 *     `longText` "Displayed Price" label like "Starting at $99".
 *
 * Style after the cycle-52..95 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class FilamentTextInputValidationSweepContractTest extends TestCase
{
    /**
     * Test fixtures: each entry is [path, regex matching
     * `TextInput::make('field')`, required validation method].
     * The regex captures the chain that follows the make() call up
     * to the next sibling TextInput::make() OR the next ; — the
     * required method must appear inside that window.
     */
    private const TYPED_FIELDS = [
        // Customer Resource: 2x phone + 1x website
        [
            'Modules/Customer/Filament/CustomerResource.php',
            "/TextInput::make\\('phone'\\)\\s*\\n\\s*(?:\\/\\/[^\\n]*\\n\\s*)*->tel\\(\\)/",
            'Customer phone fields must declare ->tel()',
        ],
        [
            'Modules/Customer/Filament/CustomerResource.php',
            "/TextInput::make\\('website'\\)\\s*\\n\\s*(?:\\/\\/[^\\n]*\\n\\s*)*->url\\(\\)/",
            'Customer website field must declare ->url()',
        ],

        // OrderResource: 2x phone
        [
            'Modules/Order/Filament/Admin/Resources/OrderResource.php',
            "/TextInput::make\\('phone'\\)->tel\\(\\)/",
            'OrderResource shipping/billing phone field must declare ->tel()',
        ],
        [
            'Modules/Order/Filament/Admin/Resources/OrderResource.php',
            "/TextInput::make\\('phone'\\)\\s*\\n\\s*(?:\\/\\/[^\\n]*\\n\\s*)*->tel\\(\\)/",
            'OrderResource customer-create phone field must declare ->tel()',
        ],
    ];

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function customer_phone_field_uses_tel_validation(): void
    {
        $src = $this->read('Modules/Customer/Filament/CustomerResource.php');

        // Both phone TextInput::make('phone') sites must have ->tel()
        // somewhere within the chain. Strip PHP // comments first so
        // the audit-trail comment doesn't confuse the count.
        $stripped = preg_replace('!//.*$!m', '', $src);
        $count = preg_match_all(
            "/TextInput::make\\('phone'\\)\\s*->tel\\(\\)/",
            $stripped
        );
        $this->assertGreaterThanOrEqual(
            2,
            $count,
            'CustomerResource: both `phone` TextInput sites (top-level + company-create modal) must declare ->tel()'
        );
    }

    #[Test]
    public function customer_website_field_uses_url_validation(): void
    {
        $src = $this->read('Modules/Customer/Filament/CustomerResource.php');
        $stripped = preg_replace('!//.*$!m', '', $src);

        $this->assertMatchesRegularExpression(
            "/TextInput::make\\('website'\\)\\s*->url\\(\\)/",
            $stripped,
            'CustomerResource: company `website` field must declare ->url() so save-time rejects javascript:/data: schemes'
        );
    }

    #[Test]
    public function order_phone_fields_use_tel_validation(): void
    {
        $src = $this->read('Modules/Order/Filament/Admin/Resources/OrderResource.php');
        $stripped = preg_replace('!//.*$!m', '', $src);

        // Both phone sites: shipping/billing block + customer-create modal.
        $count = preg_match_all(
            "/TextInput::make\\('phone'\\)(?:\\s*->tel\\(\\)|->tel\\(\\))/",
            $stripped
        );
        $this->assertGreaterThanOrEqual(
            2,
            $count,
            'OrderResource: both `phone` TextInput sites must declare ->tel()'
        );
    }

    #[Test]
    public function email_fields_keep_email_validation_chain(): void
    {
        // Baseline-pin: existing email-typed fields had ->email()
        // before this cycle; pin so a future refactor can't silently
        // drop them.
        foreach ([
            'Modules/Customer/Filament/CustomerResource.php',
            'Modules/Profile/Filament/Pages/EditProfile.php',
            'Modules/Profile/Filament/Pages/Register.php',
            'Modules/Newsletter/Filament/Admin/Resources/SubscribersResource.php',
        ] as $rel) {
            $src = $this->read($rel);
            $this->assertMatchesRegularExpression(
                "/TextInput::make\\('email'\\)[\\s\\S]{0,200}->email\\(\\)/",
                $src,
                "{$rel}: email TextInput must keep ->email() validation"
            );
        }
    }

    #[Test]
    public function audit_script_finds_no_remaining_typed_fields_without_validation(): void
    {
        // Inline replica of the audit script: scan every Filament
        // *.php under Modules/ + src/ for TextInput::make('phone'/
        // 'email'/'website'/'website_url') without the matching
        // chain method in the next 30 lines.
        $typed = [
            'email'       => '->email()',
            'phone'       => '->tel()',
            'website'     => '->url()',
            'website_url' => '->url()',
        ];

        $globs = [
            'Modules/*/Filament/**/*.php',
            'Modules/*/Filament/*.php',
            'src/MicroweberPackages/*/Filament/**/*.php',
            'src/MicroweberPackages/*/Filament/*.php',
        ];

        $files = [];
        foreach ($globs as $g) {
            foreach (glob(base_path($g), GLOB_BRACE) ?: [] as $f) {
                $files[$f] = true;
            }
        }
        // Recurse manually for `**` since glob() doesn't support it.
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path('Modules')));
        foreach ($rii as $file) {
            if ($file->isFile() && str_ends_with($file->getPathname(), '.php') && str_contains($file->getPathname(), '/Filament/')) {
                $files[$file->getPathname()] = true;
            }
        }
        $rii2 = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path('src/MicroweberPackages')));
        foreach ($rii2 as $file) {
            if ($file->isFile() && str_ends_with($file->getPathname(), '.php') && str_contains($file->getPathname(), '/Filament/')) {
                $files[$file->getPathname()] = true;
            }
        }

        $missing = [];
        foreach (array_keys($files) as $fp) {
            $lines = file($fp, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $i => $line) {
                if (preg_match("/TextInput::make\\(['\"]([^'\"]+)['\"]\\)/", $line, $m)) {
                    $field = $m[1];
                    if (!isset($typed[$field])) {
                        continue;
                    }
                    $wanted = $typed[$field];
                    $chunk  = implode("\n", array_slice($lines, $i, 30));
                    // Trim to next sibling TextInput::make() if any.
                    $firstMake  = strpos($chunk, 'TextInput::make(');
                    $secondMake = $firstMake !== false ? strpos($chunk, 'TextInput::make(', $firstMake + 1) : false;
                    if ($secondMake !== false) {
                        $chunk = substr($chunk, 0, $secondMake);
                    }
                    if (!str_contains($chunk, $wanted)) {
                        $missing[] = sprintf('%s:%d  field=%s missing %s', $fp, $i + 1, $field, $wanted);
                    }
                }
            }
        }

        $this->assertEmpty(
            $missing,
            "Filament TextInput validation gaps still present:\n" . implode("\n", $missing)
        );
    }
}
