<?php

namespace Modules\RssFeed\Tests;


use Tests\TestCase;
use Modules\RssFeed\Http\Controllers\RssController;
use Illuminate\Http\Request;

class RssControllerTest extends TestCase
{

    protected RssController $rssController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rssController = new RssController();
    }

    public function testIndexReturnsXmlResponse()
    {
        $request = Request::create('/rss', 'GET', ['format' => 'atom']);
        $response = $this->rssController->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    public function testPostsReturnsXmlResponse()
    {
        $request = Request::create('/rss/posts', 'GET');
        $response = $this->rssController->posts($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }

    public function testProductsReturnsXmlResponse()
    {
        $request = Request::create('/rss/products', 'GET');
        $response = $this->rssController->products($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));
    }
}
