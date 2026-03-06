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
}
