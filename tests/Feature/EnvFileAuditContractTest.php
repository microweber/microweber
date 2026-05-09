<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-106 / AI-127 / TICKET-BP — env-file audit regression
 * coverage.
 *
 * Pins:
 *   - `.env` and `.env.testing` are explicitly listed in `.gitignore`
 *     (the brief's primary "no real secrets in tracked .env" guarantee
 *     is enforced by gitignore, not by file content).
 *   - `.env.example` exists at the repo root and carries no
 *     non-placeholder credentials.
 *   - `.env.production` exists at the repo root and the Stripe slots
 *     declare the `REPLACE_WITH_REAL_KEY` sentinel (so they're
 *     visibly placeholders, not mistaken for real keys).
 *   - SETUP.md documents the audit findings so the next maintainer
 *     can re-audit any new tracked `.env*` file against the same
 *     baseline.
 *
 * Style after the cycle-52..105 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class EnvFileAuditContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function gitignore_excludes_local_env_files(): void
    {
        $gitignore = $this->read('.gitignore');

        // `.env` (the dev-local copy) MUST be gitignored — anything
        // less and a developer's local secrets leak into the first
        // `git add .` they run.
        $this->assertMatchesRegularExpression(
            '/^\\.env\\s*$/m',
            $gitignore,
            '.gitignore: must contain a top-level `.env` line so dev-local credentials never get tracked'
        );

        // `.env.testing` carries DB credentials for the test runner;
        // also must stay local.
        $this->assertMatchesRegularExpression(
            '/^\\.env\\.testing\\s*$/m',
            $gitignore,
            '.gitignore: must contain `.env.testing` so test-runner credentials stay local'
        );
    }

    #[Test]
    public function env_example_has_no_real_credentials(): void
    {
        $env = $this->read('.env.example');

        // Strip comments + blank lines so we don't false-match on
        // documentation snippets.
        $stripped = preg_replace('/^\\s*#.*$/m', '', $env);

        // Look for any `<KEY>=<value>` where KEY hints at a secret
        // and the value is non-empty + non-placeholder.
        $offenders = [];
        foreach (preg_split('/\\R/', $stripped) as $line) {
            if (!preg_match('/^([A-Z_][A-Z0-9_]*)=(.*)$/i', $line, $m)) {
                continue;
            }
            $key = $m[1];
            $val = trim($m[2]);
            if ($val === '' || $val === 'null' || $val === '"null"') {
                continue;
            }
            // Slack/email/etc lookalike keys with non-secret-but-still-
            // sensitive defaults are fine — only flag obvious credential
            // KEYS.
            if (!preg_match('/(PASSWORD|SECRET|API_KEY|TOKEN|PRIVATE_KEY|CLIENT_SECRET)$/', $key)) {
                continue;
            }
            // Acceptable placeholder patterns:
            //   pk_test_... / sk_test_... / whsec_... / sk_live_... — Stripe-style prefix-only
            //   ${...}                                              — env interpolation
            //   "${...}"                                            — quoted env interp
            //   YOUR_*_HERE                                         — explicit placeholder
            //   REPLACE_WITH_*                                      — explicit placeholder
            //   example*                                            — explicit placeholder
            if (preg_match('/^(?:pk|sk|whsec|rk)_(?:test|live)?_?\\.{0,3}$/', $val)
                || str_contains($val, 'YOUR_')
                || str_contains($val, 'REPLACE_WITH_')
                || str_contains($val, 'example')
                || str_starts_with($val, '${')
                || str_starts_with($val, '"${')) {
                continue;
            }
            $offenders[] = "{$key}={$val}";
        }

        $this->assertEmpty(
            $offenders,
            ".env.example contains potential real credentials:\n  " . implode("\n  ", $offenders)
        );
    }

    #[Test]
    public function env_production_stripe_slots_use_explicit_placeholders(): void
    {
        $env = $this->read('.env.production');

        // The pre-fix `pk_live_` / `sk_live_` / `whsec_` bare prefixes
        // looked like real Stripe keys. The `REPLACE_WITH_REAL_KEY`
        // sentinel makes the placeholder unambiguous.
        $this->assertMatchesRegularExpression(
            '/STRIPE_PUBLISHABLE_KEY=pk_live_REPLACE_WITH_REAL_KEY/',
            $env,
            '.env.production: STRIPE_PUBLISHABLE_KEY must be the explicit `pk_live_REPLACE_WITH_REAL_KEY` placeholder'
        );
        $this->assertMatchesRegularExpression(
            '/STRIPE_SECRET_KEY=sk_live_REPLACE_WITH_REAL_KEY/',
            $env,
            '.env.production: STRIPE_SECRET_KEY must be the explicit `sk_live_REPLACE_WITH_REAL_KEY` placeholder'
        );
        $this->assertMatchesRegularExpression(
            '/STRIPE_WEBHOOK_SECRET=whsec_REPLACE_WITH_REAL_KEY/',
            $env,
            '.env.production: STRIPE_WEBHOOK_SECRET must be the explicit `whsec_REPLACE_WITH_REAL_KEY` placeholder'
        );
    }

    #[Test]
    public function setup_md_documents_env_file_audit(): void
    {
        $setup = $this->read('SETUP.md');

        $this->assertMatchesRegularExpression(
            '/##\\s+Env-file audit \\(AI-127/',
            $setup,
            'SETUP.md: must contain an "Env-file audit (AI-127 / TICKET-BP)" section'
        );

        // Every tracked .env file must be mentioned in the audit
        // section so a new audit run can follow the same baseline.
        foreach ([
            '.env.example',
            '.env.production',
            '.env.staging',
            '.env.docker',
            '.env.dusk',
        ] as $envFile) {
            $this->assertStringContainsString(
                "**`{$envFile}`**",
                $setup,
                "SETUP.md env-file audit: must list `{$envFile}` with its audit verdict"
            );
        }
    }
}
