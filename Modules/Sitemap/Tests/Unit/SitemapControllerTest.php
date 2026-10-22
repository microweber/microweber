<?php

namespace Modules\Sitemap\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Sitemap\Http\Controllers\SitemapController;
use Illuminate\Http\Request;

class SitemapControllerTest extends TestCase
{
    protected SitemapController $sitemapController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sitemapController = new SitemapController();
    }

    #[Test]

    public function it_index_returns_xml_response(): void {
        $response = $this->sitemapController->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    #[Test]

    public function it_categories_returns_xml_response(): void {
        $response = $this->sitemapController->categories();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    #[Test]

    public function it_tags_returns_xml_response(): void {
        $response = $this->sitemapController->tags();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    #[Test]

    public function it_products_returns_xml_response(): void {
        $response = $this->sitemapController->products();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    #[Test]

    public function it_posts_returns_xml_response(): void {
        $response = $this->sitemapController->posts();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    #[Test]

    public function it_pages_returns_xml_response(): void {
        $response = $this->sitemapController->pages();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    #[Test]
    public function it_stylesheet_returns_xsl_with_sitemaps_namespace(): void {
        $response = $this->sitemapController->stylesheet();
        $body = $response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('text/xsl', (string) $response->headers->get('Content-Type'));
        // The stylesheet MUST declare the sitemaps.org namespace and match on the
        // prefixed nodes, or the browser renders a blank page.
        $this->assertStringContainsString('www.sitemaps.org/schemas/sitemap/0.9', $body);
        $this->assertStringContainsString('xsl:stylesheet', $body);
    }

    #[Test]
    public function it_index_embeds_the_stylesheet_processing_instruction(): void {
        $body = $this->sitemapController->index()->getContent();

        $this->assertStringContainsString('<?xml-stylesheet', $body);
        $this->assertStringContainsString('sitemap.xsl', $body);
    }

    #[Test]
    public function it_child_sitemap_embeds_the_stylesheet_processing_instruction(): void {
        $body = $this->sitemapController->pages()->getContent();

        $this->assertStringContainsString('<?xml-stylesheet', $body);
    }

    #[Test]
    public function it_products_declares_image_and_extension_namespaces(): void {
        // The products sitemap is enriched with the OFFICIAL image extension and
        // a custom mw: namespace (price/categories/tags) — both must be declared
        // on <urlset> so the XML stays well-formed and the styled view can read
        // them. Declared unconditionally by the shared items view.
        $body = $this->sitemapController->products()->getContent();

        $this->assertStringContainsString('schemas/sitemap-image', $body);
        $this->assertStringContainsString('microweber.com/schemas/sitemap-ext', $body);
    }
}
