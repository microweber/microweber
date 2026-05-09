<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-120 / AI-122 / TICKET-CA + CC-EVAL + CD + CF — docs batch
 * regression coverage.
 *
 * Pins:
 *   - CONTRIBUTING.md documents the CHANGELOG cadence (release-tag
 *     vs per-cycle vs skip-changelog) per TICKET-CA.
 *   - TROUBLESHOOTING.md backfills the 5 recurring developer
 *     gotchas per TICKET-CC-EVAL (backtick-template-literal,
 *     wire:click on <option>, hardcoded ids, str_contains arg
 *     order, AEAD-ciphertext column widening).
 *   - docs/openapi.yaml exists with at least the Cart, Newsletter,
 *     Captcha, Search, and Admin auth path stubs per TICKET-CD.
 *   - README.md carries the AI-122 / TICKET-CF audit-trail stamp
 *     and lists Node 22.
 *
 * Style after the cycle-52..119 contract tests (file-system reads only,
 * no DB touch).
 */
class DocsBatchContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function ticket_ca_contributing_documents_changelog_cadence(): void
    {
        $src = $this->read('CONTRIBUTING.md');

        $this->assertStringContainsString(
            'AI-122 / TICKET-CA',
            $src,
            'CONTRIBUTING.md: must carry the AI-122 audit-trail stamp'
        );

        foreach ([
            '## CHANGELOG cadence',
            'Release-tag-driven',
            'Per-cycle',
            'What does NOT belong in CHANGELOG.md',
            '[skip changelog]',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "CONTRIBUTING.md: must contain `{$needle}` in the CHANGELOG cadence section"
            );
        }
    }

    #[Test]
    public function ticket_cc_eval_troubleshooting_backfills_five_gotchas(): void
    {
        $src = $this->read('TROUBLESHOOTING.md');

        $this->assertStringContainsString(
            'AI-122 / TICKET-CC-EVAL',
            $src,
            'TROUBLESHOOTING.md: must carry the AI-122 / TICKET-CC-EVAL stamp'
        );

        // The 5 brief-required gotchas.
        foreach ([
            'Backtick template literal collision',
            'wire:click` on a `<select>` `<option>',
            'Hardcoded element IDs in module skins',
            '`str_contains()` argument order',
            'AEAD-ciphertext column too narrow',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "TROUBLESHOOTING.md: must document the gotcha `{$needle}`"
            );
        }
    }

    #[Test]
    public function ticket_cd_openapi_yaml_stub_exists_with_core_paths(): void
    {
        $src = $this->read('docs/openapi.yaml');

        $this->assertStringContainsString(
            'AI-122 / TICKET-CD',
            $src,
            'docs/openapi.yaml: must carry the AI-122 / TICKET-CD audit-trail comment'
        );

        // OpenAPI version + info header.
        $this->assertMatchesRegularExpression(
            '/^openapi:\\s*3\\.[01]/m',
            $src,
            'docs/openapi.yaml: must declare an OpenAPI 3.x version'
        );

        // High-traffic endpoints from the brief's expected coverage.
        foreach ([
            '/cart:',
            '/cart/coupon/apply:',
            '/newsletter/subscribe:',
            '/captcha:',
            '/search:',
            '/admin/api/auth/login:',
        ] as $path) {
            $this->assertStringContainsString(
                $path,
                $src,
                "docs/openapi.yaml: must declare path `{$path}`"
            );
        }

        // Shared security schemes.
        $this->assertStringContainsString(
            'SanctumToken:',
            $src,
            'docs/openapi.yaml: must declare the SanctumToken security scheme'
        );
    }

    #[Test]
    public function ticket_cf_readme_audit_stamp_and_node_22(): void
    {
        $src = $this->read('README.md');

        $this->assertStringContainsString(
            'AI-122 / TICKET-CF',
            $src,
            'README.md: must carry the AI-122 / TICKET-CF audit-trail stamp'
        );

        $this->assertMatchesRegularExpression(
            '/Node\\s+22\\b/',
            $src,
            'README.md: must list Node 22 in System Requirements'
        );

        // PHP version stays accurate.
        $this->assertMatchesRegularExpression(
            '/PHP\\s*>=\\s*8\\.3\\b/',
            $src,
            'README.md: must list PHP >= 8.3'
        );
    }
}
