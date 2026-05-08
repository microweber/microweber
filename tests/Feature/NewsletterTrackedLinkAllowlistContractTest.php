<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Newsletter\Models\NewsletterTrackedLinkAllowlist;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-65 / AI-58 / TICKET-RR — newsletter tracked-link allowlist
 * regression coverage.
 *
 * Pins:
 *   - migration creates `newsletter_tracked_link_allowlist` table with
 *     the canonical schema (host_pattern, note, is_active, created_by,
 *     updated_by, timestamps)
 *   - NewsletterTrackedLinkAllowlist model exists with the expected
 *     fillable + casts
 *   - hostMatchesPattern() handles exact + wildcard (`*.example.com`)
 *     match semantics correctly, including the wildcard-doesn't-shadow
 *     the bare suffix invariant
 *   - urlIsAllowed() rejects non-http(s) schemes
 *   - the /click-link route consults
 *     NewsletterTrackedLinkAllowlist::urlIsAllowed AFTER the same-host
 *     fallback (so site-local URLs are accepted without a DB hit)
 *
 * Style after the cycle-52..64 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class NewsletterTrackedLinkAllowlistContractTest extends TestCase
{
    private string $migrationSrc;
    private string $modelSrc;
    private string $routeSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->migrationSrc = file_get_contents(base_path(
            'Modules/Newsletter/database/migrations/2026_05_08_200000_create_newsletter_tracked_link_allowlist_table.php'
        ));
        $this->modelSrc = file_get_contents(base_path(
            'Modules/Newsletter/Models/NewsletterTrackedLinkAllowlist.php'
        ));
        $this->routeSrc = file_get_contents(base_path(
            'Modules/Newsletter/routes/web.php'
        ));
    }

    #[Test]
    public function migration_creates_table_with_canonical_schema(): void
    {
        $this->assertStringContainsString(
            "Schema::create('newsletter_tracked_link_allowlist'",
            $this->migrationSrc,
            'migration: must Schema::create the canonical table name'
        );
        // Required columns.
        $required = [
            "\$table->string('host_pattern')->index()",
            "\$table->string('note')->nullable()",
            "\$table->boolean('is_active')->default(true)->index()",
            "\$table->unsignedBigInteger('created_by')->nullable()",
            "\$table->unsignedBigInteger('updated_by')->nullable()",
            '$table->timestamps()',
        ];
        foreach ($required as $col) {
            $this->assertStringContainsString(
                $col,
                $this->migrationSrc,
                "migration: column declaration `{$col}` must be present"
            );
        }
        // Idempotent up()
        $this->assertStringContainsString(
            "Schema::hasTable('newsletter_tracked_link_allowlist')",
            $this->migrationSrc,
            'migration: up() must guard against re-running with hasTable check'
        );
        // Reversible down()
        $this->assertStringContainsString(
            "Schema::dropIfExists('newsletter_tracked_link_allowlist')",
            $this->migrationSrc,
            'migration: down() must dropIfExists the table'
        );
    }

    #[Test]
    public function model_declares_canonical_fillable_and_casts(): void
    {
        $this->assertStringContainsString(
            "\$table = 'newsletter_tracked_link_allowlist'",
            $this->modelSrc,
            'model: $table must point at newsletter_tracked_link_allowlist'
        );
        $required = ['host_pattern', 'note', 'is_active', 'created_by', 'updated_by'];
        foreach ($required as $col) {
            $this->assertStringContainsString(
                "'{$col}'",
                $this->modelSrc,
                "model: \$fillable must include '{$col}'"
            );
        }
        // Casts
        $this->assertStringContainsString(
            "'is_active' => 'bool'",
            $this->modelSrc,
            'model: is_active must be cast to bool'
        );
    }

    #[Test]
    public function host_matches_pattern_handles_exact_match(): void
    {
        $this->assertTrue(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'example.com',
            'example.com'
        ));
        $this->assertTrue(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'EXAMPLE.com',
            'example.com'
        ), 'exact match must be case-insensitive');

        $this->assertFalse(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'attacker.com',
            'example.com'
        ));
        $this->assertFalse(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'foo.example.com',
            'example.com'
        ), 'exact match must NOT cover subdomains');
    }

    #[Test]
    public function host_matches_pattern_handles_wildcard(): void
    {
        // `*.example.com` matches subdomains.
        $this->assertTrue(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'foo.example.com',
            '*.example.com'
        ));
        $this->assertTrue(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'a.b.example.com',
            '*.example.com'
        ));

        // The wildcard must NOT match the bare suffix — that would
        // shadow more-specific exact rows.
        $this->assertFalse(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'example.com',
            '*.example.com'
        ), 'wildcard must NOT match the bare suffix');

        // Cross-host attacker tries:
        $this->assertFalse(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'fooexample.com',
            '*.example.com'
        ), 'wildcard must require a literal `.` separator (not just any suffix-of-string)');
        $this->assertFalse(NewsletterTrackedLinkAllowlist::hostMatchesPattern(
            'example.com.attacker.com',
            '*.example.com'
        ));
    }

    #[Test]
    public function url_is_allowed_rejects_non_http_schemes(): void
    {
        // No DB seed; the URL never reaches the table query.
        $this->assertFalse(NewsletterTrackedLinkAllowlist::urlIsAllowed(
            'javascript:alert(1)'
        ));
        $this->assertFalse(NewsletterTrackedLinkAllowlist::urlIsAllowed(
            'data:text/html,<script>alert(1)</script>'
        ));
        $this->assertFalse(NewsletterTrackedLinkAllowlist::urlIsAllowed(
            'file:///etc/passwd'
        ));
        $this->assertFalse(NewsletterTrackedLinkAllowlist::urlIsAllowed(
            ''
        ));
        $this->assertFalse(NewsletterTrackedLinkAllowlist::urlIsAllowed(
            'not-a-url'
        ));
    }

    #[Test]
    public function route_consults_allowlist_after_same_host_fallback(): void
    {
        // The allowlist tier should be the THIRD branch (after sig and
        // same-host) so site-local URLs don't take a DB query hit.
        $sigPos = strpos(
            $this->routeSrc,
            'if ($sigIsValid) {'
        );
        $sameHostPos = strpos(
            $this->routeSrc,
            'strcasecmp($parts[\'host\'], $siteHost) === 0'
        );
        $allowlistPos = strpos(
            $this->routeSrc,
            'NewsletterTrackedLinkAllowlist::urlIsAllowed'
        );

        $this->assertNotFalse($sigPos, '/click-link route: sig branch must exist');
        $this->assertNotFalse($sameHostPos, '/click-link route: same-host branch must exist');
        $this->assertNotFalse($allowlistPos, '/click-link route: allowlist branch must exist');

        $this->assertLessThan(
            $sameHostPos,
            $sigPos,
            '/click-link route: sig branch must precede same-host'
        );
        $this->assertLessThan(
            $allowlistPos,
            $sameHostPos,
            '/click-link route: same-host branch must precede the allowlist DB-hit branch'
        );

        // Allowlist call must use the FQCN form so it works whether or
        // not the routes file imports the model.
        $this->assertStringContainsString(
            '\\Modules\\Newsletter\\Models\\NewsletterTrackedLinkAllowlist::urlIsAllowed',
            $this->routeSrc,
            '/click-link route: must reference the model via FQCN'
        );
    }
}
