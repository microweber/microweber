<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-4e9d1b / AI-792 — Default Blog page binds to
 * blog.blade.php layout. Jira: https://microweber.atlassian.net/browse/AI-792
 *
 * Designer's R10-5 audit caught /Blog rendering the home hero
 * instead of a blog archive page. **"A blog without an index page
 * is not a blog."** Customer-trust issue — published posts have no
 * discoverability path.
 *
 * Root cause: `Content::createDefaultBlogPage()` at
 * Modules/Content/Models/Content.php:882 creates the Blog page with
 *   title       = 'Blog'
 *   content_type = 'page'
 *   subtype      = 'dynamic'
 * but does NOT set `layout_file`. Without it, the renderer falls
 * back to the active template's DEFAULT layout (the home hero).
 * The Bootstrap template ships a `blog.blade.php` with
 *   <module type="layouts" template="blog/skin-1"/>
 * which in turn renders <module type="posts"/> — the post archive
 * the audit expected. Binding the Blog page to `blog.blade.php`
 * routes the renderer to the right layout.
 *
 * Fix scope: setter add on the NEW-Blog-page path only. Existing
 * Blog pages with `layout_file = null` would need a one-off
 * migration — DEFERRED pending PM/operator-data audit; the
 * `createDefaultBlogPage()` lookup at line 884 uses
 * `content_type=page&subtype=dynamic` so existing well-formed Blog
 * pages are preserved (the new layout_file only writes on freshly-
 * created pages).
 *
 * Templates that lack `blog.blade.php`: the resolver downgrades to
 * the next-best layout match (subtype=dynamic without a specific
 * layout-file) — no error, no broken page. The fix is strictly
 * additive.
 *
 * Out of scope (deferred to AI-791 follow-up dispatches):
 *   - Slice D — menu dedupe. Local DB shows 0 duplicates; designer's
 *     audit captured duplicates in their environment. Can't ship
 *     defensive de-dupe without knowing which layer creates the
 *     duplicates. Flag for next dispatch with designer's repro.
 *   - Slice A/B/C — body max-width / author / category / tags /
 *     reading time / comments / related / prev-next / WhatsApp
 *     share. Each is its own feature slice; bundling here would
 *     exceed dispatch scope.
 */
class Content4e9d1bAI792DefaultBlogPageLayoutContractTest extends TestCase
{
    private string $contentModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentModel = (string) file_get_contents(base_path(
            'Modules/Content/Models/Content.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — createDefaultBlogPage sets layout_file
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function create_default_blog_page_sets_layout_file_to_blog_blade(): void
    {
        // Slice the createDefaultBlogPage method body via strpos to
        // avoid selector-self-match on the docblock prose that
        // legitimately mentions `layout_file = null`.
        $start = strpos($this->contentModel, 'public static function createDefaultBlogPage()');
        $this->assertNotFalse($start);
        $end = strpos($this->contentModel, '    }', $start);
        $this->assertNotFalse($end);
        $body = substr($this->contentModel, $start, $end - $start);

        $this->assertMatchesRegularExpression(
            "/\\\$blogPage->layout_file\\s*=\\s*'blog\\.blade\\.php'/",
            $body,
            'createDefaultBlogPage() must set `$blogPage->layout_file = \'blog.blade.php\'` so the renderer routes to the blog archive layout.'
        );
    }

    #[Test]
    public function create_default_blog_page_still_sets_existing_fields(): void
    {
        // Behaviour-parity guard: the prior 3 fields (title /
        // content_type / subtype) must still be set so the
        // idempotency check at line 884 (`get_pages(...&subtype=dynamic)`)
        // continues to find this page on subsequent calls.
        $start = strpos($this->contentModel, 'public static function createDefaultBlogPage()');
        $end = strpos($this->contentModel, '    }', $start);
        $body = substr($this->contentModel, $start, $end - $start);

        $this->assertStringContainsString("\$blogPage->title = 'Blog'", $body);
        $this->assertStringContainsString("\$blogPage->content_type = 'page'", $body);
        $this->assertStringContainsString("\$blogPage->subtype = 'dynamic'", $body);
    }

    #[Test]
    public function create_default_blog_page_idempotency_check_preserved(): void
    {
        // Critical: the idempotency check at the top of the method
        // (`get_pages('content_type=page&subtype=dynamic&...')`)
        // MUST remain so re-running the installer does NOT create
        // a second Blog page.
        $start = strpos($this->contentModel, 'public static function createDefaultBlogPage()');
        $end = strpos($this->contentModel, '    }', $start);
        $body = substr($this->contentModel, $start, $end - $start);

        $this->assertStringContainsString(
            "get_pages('content_type=page&subtype=dynamic&is_shop=0&single=1')",
            $body,
            'Idempotency check `get_pages(content_type=page&subtype=dynamic&...)` must remain at the top of the method.'
        );
        $this->assertStringContainsString(
            'if (!$blogPage) {',
            $body,
            'Method must only INSERT a new Blog page when the lookup returns falsy — preserves idempotency.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — bootstrap template blog.blade.php still ships posts module
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bootstrap_blog_blade_renders_posts_module(): void
    {
        // The fix is conditional on blog.blade.php containing a posts
        // module — if upstream removes the module, our layout_file
        // binding routes to a posts-free template. Tripwire.
        $bootstrapBlog = base_path('Templates/Bootstrap/resources/views/blog.blade.php');
        $this->assertFileExists($bootstrapBlog);
        $contents = (string) file_get_contents($bootstrapBlog);
        $this->assertStringContainsString(
            '<module type="layouts" template="blog/skin-1"/>',
            $contents,
            'Bootstrap blog.blade.php must include the blog/skin-1 layout module (which renders <module type="posts"/>).'
        );
    }

    #[Test]
    public function bootstrap_blog_skin1_layout_renders_posts(): void
    {
        $skin1 = base_path('Templates/Bootstrap/resources/views/modules/layouts/templates/blog/skin-1.blade.php');
        $this->assertFileExists($skin1);
        $contents = (string) file_get_contents($skin1);
        $this->assertStringContainsString(
            '<module type="posts"',
            $contents,
            'Bootstrap blog/skin-1 layout must render <module type="posts"> — that is what makes the Blog page an archive.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai792_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-4e9d1b', $this->contentModel);
        $this->assertStringContainsString('AI-792', $this->contentModel);
    }
}
