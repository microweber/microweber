<?php

namespace Modules\Sitemap\Tests\Unit;

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

    public function testIndexReturnsXmlResponse()
    {
        $response = $this->sitemapController->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    public function testCategoriesReturnsXmlResponse()
    {
        $response = $this->sitemapController->categories();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    public function testTagsReturnsXmlResponse()
    {
        $response = $this->sitemapController->tags();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    public function testProductsReturnsXmlResponse()
    {
        $response = $this->sitemapController->products();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    public function testPostsReturnsXmlResponse()
    {
        $response = $this->sitemapController->posts();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    public function testPagesReturnsXmlResponse()
    {
        $response = $this->sitemapController->pages();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }
}
