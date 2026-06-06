<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-AI731 — Posts must be discoverable in the admin left-nav.
 *
 * Already fixed (task-2026-05-16-008d91) and runtime-verified: "Posts" renders
 * in the Website nav group (Pages / Categories / Posts / Products). This guard
 * pins the nav-registration config so it can't silently regress to the
 * URL-only-reachable state the ticket described — Filament v5 suppresses nav
 * items whose icon resolves to null, so the icon is part of the contract.
 */
class AdminAI731PostsNavEntryContractTest extends TestCase
{
    #[Test]
    public function post_resource_registers_in_the_website_nav_group_with_an_icon(): void
    {
        $src = (string) file_get_contents(base_path(
            'Modules/Post/Filament/Admin/Resources/PostResource.php'
        ));

        $this->assertMatchesRegularExpression(
            '/\$shouldRegisterNavigation\s*=\s*true/',
            $src,
            'PostResource must register a navigation entry.'
        );
        $this->assertMatchesRegularExpression(
            "/\\\$navigationGroup\s*=\s*'Website'/",
            $src,
            'Posts must live under the Website nav group.'
        );
        $this->assertMatchesRegularExpression(
            "/\\\$navigationIcon\s*=\s*'heroicon-[^']+'/",
            $src,
            'PostResource must carry a non-null navigation icon (null icon = suppressed nav item in Filament v5).'
        );
    }
}
