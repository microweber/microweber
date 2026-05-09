<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-111 / AI-114 + AI-124(CS) + AI-127(BB-EVAL) — ADR + decision
 * docs regression coverage.
 *
 * Pins:
 *   - docs/adr/ contains the 4 ADRs called out by the brief:
 *     0001 (helper-layer security), 0002 (allowlists), 0003
 *     (two-pass escape), 0004 (cart guest-merging).
 *   - TROUBLESHOOTING.md documents the AI-127 / TICKET-BB-EVAL
 *     finding (no first-party postMessage in live-edit; Origin
 *     guard is N/A; conditional implementation rule for future PRs).
 *
 * Style after the cycle-52..110 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class AdrAndDecisionDocsContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function adr_0001_helper_layer_security_exists_with_three_principles(): void
    {
        $src = $this->read('docs/adr/0001-helper-layer-security.md');

        $this->assertMatchesRegularExpression(
            '/# ADR-0001 — Helper-layer security/',
            $src,
            'ADR-0001 must have the canonical title'
        );

        // The 4 principles from the ADR.
        foreach ([
            'Principle 1 — Two-pass escape',
            'Principle 2 — URL allowlists',
            'Principle 3 — Sanitizers fail closed',
            'Principle 4 — Helpers MUST be context-aware',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "ADR-0001: must declare `{$needle}`"
            );
        }
    }

    #[Test]
    public function adr_0002_allowlists_sanitization_exists(): void
    {
        $src = $this->read('docs/adr/0002-allowlists-sanitization.md');

        $this->assertMatchesRegularExpression(
            '/# ADR-0002 — Allowlists & sanitization/',
            $src,
            'ADR-0002 must have the canonical title'
        );
        $this->assertStringContainsString(
            'Allowlists are explicit + versioned',
            $src,
            'ADR-0002: must document the explicit-allowlist principle'
        );
        $this->assertStringContainsString(
            'Sanitization runs at BOTH storage and render',
            $src,
            'ADR-0002: must document the storage+render sanitization rule'
        );
        $this->assertStringContainsString(
            'File uploads use signature checks',
            $src,
            'ADR-0002: must document the upload-signature-check rule'
        );
    }

    #[Test]
    public function adr_0003_two_pass_escape_exists(): void
    {
        $src = $this->read('docs/adr/0003-two-pass-escape.md');

        $this->assertMatchesRegularExpression(
            '/# ADR-0003 — Two-pass escape/',
            $src,
            'ADR-0003 must have the canonical title'
        );
        $this->assertStringContainsString(
            'Blade `{{ }}` is the default',
            $src,
            'ADR-0003: must document the {{ }} default'
        );
        $this->assertStringContainsString(
            '`data-*` attributes for JS hand-offs',
            $src,
            'ADR-0003: must document the data-mw-* + delegated-listener pattern'
        );
        $this->assertStringContainsString(
            'CSS interpolation: never inline',
            $src,
            'ADR-0003: must document the CSS-interpolation ban'
        );
    }

    #[Test]
    public function adr_0004_cart_guest_merging_exists(): void
    {
        $src = $this->read('docs/adr/0004-cart-guest-merging.md');

        $this->assertMatchesRegularExpression(
            '/# ADR-0004 — Cart guest-merging/',
            $src,
            'ADR-0004 must have the canonical title'
        );
        $this->assertStringContainsString(
            'session cart wins on login',
            $src,
            'ADR-0004: must document the session-wins-on-login decision'
        );
        $this->assertStringContainsString(
            'QUANTITIES are SUMMED',
            $src,
            'ADR-0004: must document the qty-sum merge rule'
        );
        $this->assertStringContainsString(
            'Cookie carts are READ-only after cycle-22',
            $src,
            'ADR-0004: must document the legacy MW_CART cookie read-only path'
        );
    }

    #[Test]
    public function troubleshooting_documents_origin_guard_evaluation(): void
    {
        $src = $this->read('TROUBLESHOOTING.md');

        $this->assertStringContainsString(
            'AI-127 / TICKET-BB-EVAL',
            $src,
            'TROUBLESHOOTING.md: must contain the AI-127 origin-guard finding'
        );

        $this->assertStringContainsString(
            'live-edit JS does NOT use `window.postMessage`',
            $src,
            'TROUBLESHOOTING.md: must document that the first-party JS does not use postMessage'
        );

        $this->assertStringContainsString(
            'event.origin === window.location.origin',
            $src,
            'TROUBLESHOOTING.md: must document the future-proof origin-check rule'
        );
    }
}
