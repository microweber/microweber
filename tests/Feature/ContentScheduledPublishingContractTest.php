<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-schedpublish — scheduled publishing was not enforced.
 *
 * The admin content form lets an author set a future `posted_at` and renders
 * "Scheduled — publishes in X", but nothing hid the row until that date and no
 * scheduler ever flipped a draft on: a post with is_active=1 + a future
 * posted_at was live on the public site the instant it was saved
 * (browser-reproduced: a post dated 3 days out appeared on /blog and its URL
 * returned HTTP 200).
 *
 * Fix: a HasScheduledPublishingScope trait registers a global scope that hides
 * future-dated content from public (non-admin) requests. Admins / Live Edit
 * bypass it. When posted_at <= now() the row re-enters every query — no cron.
 *
 * Delivered as a trait (not Content::booted()) because Post, Page and Product
 * each already override booted() without calling parent; a trait boot method is
 * invoked additively for the base class AND every subclass.
 */
class ContentScheduledPublishingContractTest extends TestCase
{
    private string $traitSrc;
    private string $contentSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->traitSrc = (string) file_get_contents(base_path(
            'Modules/Content/Models/Concerns/HasScheduledPublishingScope.php'
        ));
        $this->contentSrc = (string) file_get_contents(base_path(
            'Modules/Content/Models/Content.php'
        ));
    }

    #[Test]
    public function trait_registers_a_named_global_scope(): void
    {
        $this->assertMatchesRegularExpression(
            '/public static function bootHasScheduledPublishingScope\(\)/',
            $this->traitSrc,
            'The trait must expose a bootHasScheduledPublishingScope() boot method.'
        );
        $this->assertStringContainsString("addGlobalScope('mwScheduledPublish'", $this->traitSrc,
            'The trait must register a named global scope mwScheduledPublish.');
    }

    #[Test]
    public function admins_bypass_the_scope(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*function_exists\(\'is_admin\'\)\s*&&\s*is_admin\(\)\s*\)\s*\{\s*return;/',
            $this->traitSrc,
            'Admins / Live Edit must bypass the scheduling scope so they can preview scheduled content.'
        );
    }

    #[Test]
    public function scope_hides_future_posted_at_and_keeps_null_visible(): void
    {
        // whereNull(posted_at) OR posted_at <= now() — NULL is always published,
        // future dates are hidden.
        $this->assertMatchesRegularExpression(
            "/whereNull\(\\\$table\s*\.\s*'\.posted_at'\)/",
            $this->traitSrc,
            'Content with a NULL posted_at must remain visible (treated as published).'
        );
        $this->assertMatchesRegularExpression(
            "/orWhere\(\\\$table\s*\.\s*'\.posted_at',\s*'<=',\s*now\(\)\)/",
            $this->traitSrc,
            'Only content whose posted_at is <= now() may be publicly visible.'
        );
    }

    #[Test]
    public function content_model_uses_the_trait(): void
    {
        $this->assertMatchesRegularExpression(
            '/use\s+\\\\?Modules\\\\Content\\\\Models\\\\Concerns\\\\HasScheduledPublishingScope;/',
            $this->contentSrc,
            'The Content model must use HasScheduledPublishingScope.'
        );
    }
}
