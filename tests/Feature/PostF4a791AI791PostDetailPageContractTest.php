<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-f4a791 / AI-791 HIGH — Frontend post detail page (5 slices).
 *
 * Slice A (P2): Reading-line discipline — max-width 720px on article container.
 * Slice B (P2): Required metadata — "Published on" label prefix, category chips,
 *               tags row, reading-time estimate.
 * Slice C (P2): Discovery affordances — prev/next navigation (next_post / prev_post),
 *               comments section gated on is_module_installed('comments').
 * Slice D (P1): Header/footer dedupe — DOMDocument deduplication of top-level
 *               menu items by href in navbar.blade.php.
 * Slice E (P3): Share controls — wa.me WhatsApp URL, "Copy link" button,
 *               "Share this post" section heading.
 */
class PostF4a791AI791PostDetailPageContractTest extends TestCase
{
    private string $postTpl;
    private string $postStripped;
    private string $sharerTpl;
    private string $sharerStripped;
    private string $navbarTpl;
    private string $navbarStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $postRaw = (string) file_get_contents(
            base_path('Templates/Bootstrap/resources/views/post.blade.php')
        );
        $this->postTpl = $postRaw;
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $postRaw) ?? $postRaw;
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $stripped) ?? $stripped;
        $stripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;
        $this->postStripped = $stripped;

        $sharerRaw = (string) file_get_contents(
            base_path('Modules/Sharer/resources/views/templates/default.blade.php')
        );
        $this->sharerTpl = $sharerRaw;
        $sharerStripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $sharerRaw) ?? $sharerRaw;
        $this->sharerStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $sharerStripped) ?? $sharerStripped;

        $navbarRaw = (string) file_get_contents(
            base_path('Modules/Menu/resources/views/templates/navbar.blade.php')
        );
        $this->navbarTpl = $navbarRaw;
        $navbarStripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $navbarRaw) ?? $navbarRaw;
        $navbarStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $navbarStripped) ?? $navbarStripped;
        $this->navbarStripped = preg_replace('~//[^\n]*~', '', $navbarStripped) ?? $navbarStripped;
    }

    // ─── Slice A: Reading-line max-width ──────────────────────────────────────

    #[Test]
    public function post_article_container_has_720px_max_width(): void
    {
        $this->assertStringContainsString(
            'max-width: 720px',
            $this->postStripped,
            'post.blade.php article container must have max-width: 720px for reading-line discipline.'
        );
    }

    #[Test]
    public function post_article_container_is_horizontally_centred(): void
    {
        $pos720 = strpos($this->postStripped, 'max-width: 720px');
        $this->assertNotFalse($pos720);
        $slice = substr($this->postStripped, (int) $pos720, 80);
        $this->assertStringContainsString('auto', $slice,
            'The 720px container must use margin: auto for horizontal centring.'
        );
    }

    // ─── Slice B: Required metadata ───────────────────────────────────────────

    #[Test]
    public function post_meta_has_published_on_label(): void
    {
        $this->assertMatchesRegularExpression(
            "~Published on~s",
            $this->postStripped,
            'post.blade.php must render a visible "Published on" label prefix before the date.'
        );
    }

    #[Test]
    public function post_fetches_categories(): void
    {
        $this->assertStringContainsString('content_categories(', $this->postStripped,
            'post.blade.php must call content_categories() to retrieve category data.'
        );
    }

    #[Test]
    public function post_renders_category_chips(): void
    {
        $this->assertStringContainsString('$itemCategories', $this->postStripped,
            'post.blade.php must render category chips from $itemCategories.'
        );
        $this->assertStringContainsString('category_link(', $this->postStripped,
            'Category chips must link via category_link().'
        );
    }

    #[Test]
    public function post_renders_tags_row(): void
    {
        $this->assertStringContainsString('$itemTags', $this->postStripped,
            'post.blade.php must render a tags row from $itemTags.'
        );
    }

    #[Test]
    public function post_computes_reading_time(): void
    {
        $this->assertStringContainsString('mwAi791Words', $this->postStripped,
            'post.blade.php must compute word count for reading-time estimate.'
        );
        $this->assertStringContainsString('mwAi791ReadingMins', $this->postStripped,
            'post.blade.php must compute reading minutes and render them.'
        );
        $this->assertStringContainsString('min read', $this->postStripped,
            'post.blade.php must output the reading-time estimate with "min read" label.'
        );
    }

    // ─── Slice C: Discovery affordances ──────────────────────────────────────

    #[Test]
    public function post_has_prev_next_navigation(): void
    {
        $this->assertStringContainsString('mwAi791PrevPost', $this->postStripped,
            'post.blade.php must fetch and render the previous post link.'
        );
        $this->assertStringContainsString('mwAi791NextPost', $this->postStripped,
            'post.blade.php must fetch and render the next post link.'
        );
        $this->assertStringContainsString('prev_post(', $this->postStripped,
            'post.blade.php must call prev_post() for prev navigation.'
        );
        $this->assertStringContainsString('next_post(', $this->postStripped,
            'post.blade.php must call next_post() for next navigation.'
        );
    }

    #[Test]
    public function prev_next_nav_has_rel_attributes(): void
    {
        $this->assertStringContainsString('rel="prev"', $this->postStripped,
            'Previous post link must carry rel="prev" for SEO.'
        );
        $this->assertStringContainsString('rel="next"', $this->postStripped,
            'Next post link must carry rel="next" for SEO.'
        );
    }

    #[Test]
    public function comments_section_gated_on_module_installed(): void
    {
        $this->assertStringContainsString("is_module_installed('comments')", $this->postStripped,
            'Comments section must be gated on is_module_installed(\'comments\').'
        );
        $this->assertStringContainsString("type=\"comments\"", $this->postStripped,
            'post.blade.php must render <module type="comments"> when comments are installed.'
        );
    }

    // ─── Slice D: Header/footer menu deduplification ──────────────────────────

    #[Test]
    public function navbar_has_menu_dedup_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-22-f4a791', $this->navbarTpl,
            'navbar.blade.php must carry the AI-791 task marker for audit traceability.'
        );
    }

    #[Test]
    public function navbar_dedup_uses_domdocument(): void
    {
        $this->assertStringContainsString('DOMDocument', $this->navbarStripped,
            'navbar.blade.php must use DOMDocument for HTML-level menu deduplication.'
        );
    }

    #[Test]
    public function navbar_dedup_finds_top_level_ul(): void
    {
        $this->assertStringContainsString('nodeName !== \'ul\'', $this->navbarStripped,
            'Deduplication must target only <ul> elements (not other node types).'
        );
    }

    #[Test]
    public function navbar_dedup_checks_href_attribute(): void
    {
        $this->assertStringContainsString('getAttribute(\'href\')', $this->navbarStripped,
            'Deduplication must compare menu items by their href attribute.'
        );
    }

    #[Test]
    public function navbar_dedup_removes_duplicate_li_items(): void
    {
        $this->assertStringContainsString('removeChild(', $this->navbarStripped,
            'Deduplication must call removeChild() to delete duplicate <li> items.'
        );
    }

    // ─── Slice E: Share controls ─────────────────────────────────────────────

    #[Test]
    public function sharer_uses_wame_url_not_whatsapp_deep_link(): void
    {
        $this->assertStringContainsString('https://wa.me/?text=', $this->sharerStripped,
            'WhatsApp share URL must use https://wa.me/?text= (works on desktop + mobile).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '~whatsapp://send~',
            $this->sharerStripped,
            'Legacy whatsapp:// deep-link must be replaced by the wa.me web URL.'
        );
    }

    #[Test]
    public function sharer_has_share_this_post_heading(): void
    {
        $this->assertMatchesRegularExpression(
            '~Share this post~',
            $this->sharerStripped,
            'Sharer template must include a "Share this post" section heading.'
        );
    }

    #[Test]
    public function sharer_has_copy_link_button(): void
    {
        $this->assertStringContainsString('Copy link', $this->sharerStripped,
            'Sharer template must include a "Copy link" button.'
        );
        $this->assertStringContainsString('navigator.clipboard', $this->sharerStripped,
            '"Copy link" button must use the Clipboard API.'
        );
    }

    #[Test]
    public function sharer_copy_link_uses_data_url_not_inline_string(): void
    {
        $this->assertStringContainsString('data-url=', $this->sharerStripped,
            'Copy link button must pass the URL via data-url attribute to avoid Stage-5 attribute quoting issues.'
        );
        $this->assertStringContainsString('b.dataset.url', $this->sharerStripped,
            'Copy link onclick handler must read URL from b.dataset.url, not from an inline-encoded string.'
        );
    }

    #[Test]
    public function sharer_whatsapp_url_uses_urlencode(): void
    {
        $this->assertStringContainsString('urlencode(', $this->sharerStripped,
            'WhatsApp wa.me URL must properly urlencode the share text.'
        );
    }

    #[Test]
    public function task_marker_present_in_all_files(): void
    {
        $this->assertStringContainsString('task-2026-05-22-f4a791', $this->postTpl,
            'post.blade.php must carry the AI-791 task marker.'
        );
        $this->assertStringContainsString('task-2026-05-22-f4a791', $this->sharerTpl,
            'sharer/default.blade.php must carry the AI-791 task marker.'
        );
        $this->assertStringContainsString('task-2026-05-22-f4a791', $this->navbarTpl,
            'navbar.blade.php must carry the AI-791 task marker.'
        );
    }
}
