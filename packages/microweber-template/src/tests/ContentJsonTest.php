<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;
use MicroweberPackages\Template\ContentJson\SectionRenderer;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;
use MicroweberPackages\Template\ContentJson\ContentJsonExporter;

/**
 * Covers the flexible content.json pipeline: the section renderer, the installer
 * (pages/posts/products/menus, html vs sections bodies, home + parent + price),
 * and the exporter round-trip.
 */
class ContentJsonTest extends TestCase
{
    private string $tpl = 'CjTestTpl';

    protected function tearDown(): void
    {
        // Remove everything this test created (template-scoped) so the shared DB stays clean.
        Content::where('active_site_template', $this->tpl)->forceDelete();
        foreach (['cjtest_header', 'cjtest_footer'] as $menuTitle) {
            $menu = Menu::where('title', $menuTitle)->where('item_type', 'menu')->first();
            if ($menu) {
                Menu::where('parent_id', $menu->id)->delete();
                $menu->delete();
            }
        }
        parent::tearDown();
    }

    #[Test]
    public function section_renderer_renders_a_section_list_to_html(): void
    {
        $sections = [
            ['type' => 'navbar', 'brand' => 'cj', 'wordmark' => 'CJ', 'links' => [['label' => 'Home', 'href' => '#']]],
            ['type' => 'hero-person', 'title' => 'Hello Content Json', 'lede' => 'A rendered hero.'],
            ['type' => 'footer', 'wordmark' => 'CJ'],
        ];

        $html = (new SectionRenderer())->render($sections, ['mw_asset_base' => '/templates/cjtesttpl']);

        $this->assertStringContainsString('Hello Content Json', $html);
        $this->assertStringContainsString('<main', $html);
        // navbar + footer render outside <main>
        $this->assertStringContainsString('</main>', $html);
    }

    #[Test]
    public function installer_creates_pages_posts_products_and_menus(): void
    {
        $payload = [
            'template' => $this->tpl,
            'pages' => [
                ['url' => 'cjtest-home', 'title' => 'CJ Home', 'is_home' => true, 'active_site_template' => $this->tpl,
                 'sections' => [['type' => 'hero-person', 'title' => 'CJ Hero']], 'menu' => ['cjtest_header']],
                ['url' => 'cjtest-about', 'title' => 'CJ About', 'active_site_template' => $this->tpl,
                 'html' => '<section class="cj-about">About</section>', 'menu' => ['cjtest_header']],
                ['url' => 'cjtest-blog', 'title' => 'CJ Blog', 'active_site_template' => $this->tpl],
            ],
            'posts' => [
                ['url' => 'cjtest-post-1', 'title' => 'CJ Post', 'parent' => 'cjtest-blog',
                 'active_site_template' => $this->tpl, 'content' => '<p>Body</p>'],
            ],
            'products' => [
                ['url' => 'cjtest-prod-1', 'title' => 'CJ Product', 'price' => 12.5,
                 'active_site_template' => $this->tpl, 'content' => '<p>Buy</p>'],
            ],
            'menus' => [
                'cjtest_footer' => [['page' => 'cjtest-home', 'title' => 'Home'], ['url' => 'https://example.com', 'title' => 'Ext']],
            ],
        ];

        $result = (new ContentJsonInstaller())->setData($payload)->setTemplate($this->tpl)->setImportType('full')->install();
        $this->assertTrue($result['done']);

        $home = Content::where('url', 'cjtest-home')->first();
        $about = Content::where('url', 'cjtest-about')->first();
        $blog = Content::where('url', 'cjtest-blog')->first();
        $post = Content::where('url', 'cjtest-post-1')->first();
        $prod = Content::where('url', 'cjtest-prod-1')->first();

        $this->assertNotNull($home);
        $this->assertEquals(1, (int) $home->is_home);
        $this->assertStringContainsString('<main', (string) $home->content, 'sections body should render to html');
        $this->assertStringContainsString('CJ Hero', (string) $home->content);

        $this->assertNotNull($about);
        $this->assertStringContainsString('class="cj-about"', (string) $about->content, 'raw html body is kept verbatim');

        $this->assertNotNull($post);
        $this->assertEquals('post', $post->content_type);
        $this->assertEquals((int) $blog->id, (int) $post->parent, 'post parent resolves from url reference');

        $this->assertNotNull($prod);
        $this->assertEquals('product', $prod->content_type);

        $productClass = '\\Modules\\Product\\Models\\Product';
        if (class_exists($productClass)) {
            $p = $productClass::where('url', 'cjtest-prod-1')->first();
            $this->assertEquals(12.5, (float) $p->price);
        }

        // Menus: header from per-page "menu" membership, footer from explicit map (+ external url).
        $header = Menu::where('title', 'cjtest_header')->where('item_type', 'menu')->first();
        $footer = Menu::where('title', 'cjtest_footer')->where('item_type', 'menu')->first();
        $this->assertNotNull($header);
        $this->assertNotNull($footer);
        $this->assertEquals(2, Menu::where('parent_id', $header->id)->where('item_type', 'menu_item')->count());
        $this->assertEquals(2, Menu::where('parent_id', $footer->id)->where('item_type', 'menu_item')->count());
    }

    #[Test]
    public function installer_is_idempotent_on_reapply(): void
    {
        $payload = [
            'template' => $this->tpl,
            'pages' => [['url' => 'cjtest-home', 'title' => 'CJ Home', 'active_site_template' => $this->tpl,
                'html' => '<p>v1</p>', 'menu' => ['cjtest_header']]],
        ];
        (new ContentJsonInstaller())->setData($payload)->setTemplate($this->tpl)->install();

        // Re-apply with an updated body; url is the identity key → update, not duplicate.
        $payload['pages'][0]['html'] = '<p>v2</p>';
        (new ContentJsonInstaller())->setData($payload)->setTemplate($this->tpl)->install();

        $this->assertEquals(1, Content::where('url', 'cjtest-home')->count(), 'no duplicate page on re-apply');
        $this->assertStringContainsString('v2', (string) Content::where('url', 'cjtest-home')->first()->content);

        $header = Menu::where('title', 'cjtest_header')->where('item_type', 'menu')->first();
        $this->assertEquals(1, Menu::where('parent_id', $header->id)->where('item_type', 'menu_item')->count(),
            'menu item not duplicated on re-apply');
    }

    #[Test]
    public function exporter_round_trips_the_installed_content(): void
    {
        $payload = [
            'template' => $this->tpl,
            'pages' => [
                ['url' => 'cjtest-home', 'title' => 'CJ Home', 'is_home' => true, 'active_site_template' => $this->tpl, 'html' => '<p>Home</p>'],
                ['url' => 'cjtest-blog', 'title' => 'CJ Blog', 'active_site_template' => $this->tpl, 'html' => '<p>Blog</p>'],
            ],
            'posts' => [
                ['url' => 'cjtest-post-1', 'title' => 'CJ Post', 'parent' => 'cjtest-blog', 'active_site_template' => $this->tpl, 'content' => '<p>Body</p>'],
            ],
        ];
        (new ContentJsonInstaller())->setData($payload)->setTemplate($this->tpl)->install();

        $export = (new ContentJsonExporter())->setTemplate($this->tpl)->export();

        $pageUrls = array_column($export['pages'] ?? [], 'url');
        $this->assertContains('cjtest-home', $pageUrls);
        $this->assertContains('cjtest-blog', $pageUrls);

        $home = null;
        foreach ($export['pages'] as $p) {
            if ($p['url'] === 'cjtest-home') { $home = $p; }
        }
        $this->assertTrue(($home['is_home'] ?? false) === true, 'home flag exported');
        $this->assertArrayHasKey('html', $home, 'body exported as portable html');

        $post = null;
        foreach ($export['posts'] ?? [] as $p) {
            if ($p['url'] === 'cjtest-post-1') { $post = $p; }
        }
        $this->assertNotNull($post);
        $this->assertEquals('cjtest-blog', $post['parent'] ?? null, 'parent exported as a portable url');
    }
}
