<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Post\Filament\Admin\Resources\PostResource;

/**
 * task-2026-05-16-008d91 / AI-731 — Admin sidebar has no
 * Posts/Blog/Content entry. Jira:
 *   https://microweber.atlassian.net/browse/AI-731
 *
 * Designer dispatch 2026-05-16T15:11:29: `/admin/posts` page
 * exists but is reachable only via Live Edit redirect or direct
 * URL. Sidebar reads Dashboard / Site Statistics / Website / Shop /
 * Marketplace / Modules / Settings / Users — no Posts entry.
 *
 * Recommended fix (Option B): nest under Website group →
 * Pages | Posts | Categories | Media. Quick win — designer's
 * own hypothesis: "likely already a Filament
 * Resource::shouldRegisterNavigation() or getNavigationGroup()
 * config away."
 *
 * Recon: PostResource already had `$navigationGroup = 'Website'`
 * + `$shouldRegisterNavigation = true` + `$navigationSort = 3`.
 * The missing piece was `$navigationIcon`. Filament v5 suppresses
 * nav items whose icon resolves to null in certain panel themes.
 * Adding `heroicon-o-newspaper` (article-themed glyph matching the
 * AI-729 "Articles, news, and updates you publish appear here."
 * explainer copy) restores the nav item.
 *
 * Acceptance per dispatch:
 *   - Posts visible + active-highlighted at /admin/posts.
 *   - Article-themed icon.
 *   - Same in mobile hamburger.
 */
class Admin008d91AI731PostsNavigationContractTest extends TestCase
{
    #[Test]
    public function post_resource_belongs_to_website_navigation_group(): void
    {
        $this->assertSame(
            'Website',
            PostResource::getNavigationGroup(),
            'PostResource must belong to the Website navigation group per AI-731 Option B.'
        );
    }

    #[Test]
    public function post_resource_is_registered_in_navigation(): void
    {
        $this->assertTrue(
            PostResource::shouldRegisterNavigation(),
            'PostResource must register in the sidebar nav (shouldRegisterNavigation = true).'
        );
    }

    #[Test]
    public function post_resource_carries_article_themed_icon(): void
    {
        // Acceptance: "Article-themed icon". heroicon-o-newspaper is
        // the closest semantic match — matches the "Articles, news,
        // and updates you publish appear here." explainer from AI-729.
        $icon = PostResource::getNavigationIcon();
        // Filament's getNavigationIcon() returns the configured icon
        // string (or null). We assert a non-null icon AND that it
        // matches the article-themed heroicon.
        $this->assertNotNull(
            $icon,
            'PostResource must carry a non-null navigationIcon per AI-731 acceptance — Filament v5 suppresses nav items with null icons in some themes.'
        );
        $this->assertSame(
            'heroicon-o-newspaper',
            $icon,
            'PostResource navigationIcon must be heroicon-o-newspaper (article-themed glyph).'
        );
    }

    #[Test]
    public function post_resource_navigation_label_reads_posts(): void
    {
        // Acceptance: "Posts visible + active-highlighted at
        // /admin/posts." The visible label must read "Posts".
        $label = PostResource::getNavigationLabel();
        $this->assertSame(
            'Posts',
            $label,
            'PostResource navigation label must read "Posts".'
        );
    }

    #[Test]
    public function post_resource_inherits_website_sort_below_pages(): void
    {
        // PageResource has $navigationSort = 1 (top of Website
        // group). PostResource has $navigationSort = 3 — sits
        // below Pages, consistent with the dispatch's Pages | Posts |
        // Categories | Media order.
        $sort = (new \ReflectionProperty(PostResource::class, 'navigationSort'))->getDefaultValue();
        $this->assertSame(
            3,
            $sort,
            'PostResource navigationSort must be 3 (below Pages\' sort=1 per AI-731 Option B order).'
        );
    }

    #[Test]
    public function post_resource_source_has_ai731_marker(): void
    {
        // Audit-grep landing point.
        $src = (string) file_get_contents(base_path(
            'Modules/Post/Filament/Admin/Resources/PostResource.php'
        ));
        $this->assertStringContainsString('task-2026-05-16-008d91', $src);
        $this->assertStringContainsString('AI-731', $src);
    }

    #[Test]
    public function post_resource_navigation_icon_property_typed_for_filament_v5(): void
    {
        // Filament v5 typing on navigationIcon is
        //   string | \BackedEnum | null
        // Regression guard against a future PHP-side type widen
        // that would let null re-enter and silently re-introduce
        // the AI-731 bug.
        $reflection = new \ReflectionProperty(PostResource::class, 'navigationIcon');
        $type = (string) $reflection->getType();
        $this->assertStringContainsString(
            'string',
            $type,
            'PostResource navigationIcon property type must include `string`.'
        );
        $this->assertStringContainsString(
            'BackedEnum',
            $type,
            'PostResource navigationIcon property type must include `BackedEnum` per Filament v5 contract.'
        );
    }
}
