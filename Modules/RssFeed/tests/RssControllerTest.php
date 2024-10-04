<?php

namespace Modules\RssFeed\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Modules\RssFeed\Http\Controllers\RssController;
use Illuminate\Http\Request;

class RssControllerTest extends TestCase
{
    use RefreshDatabase;

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
        $title  = 'Rss Feed Content' . uniqid();
        $content = [];
        $content['title'] = $title;
        $content['content'] = $title;
        $content['content_type'] = 'post';
        $content['is_active'] = 1;
        save_content($content);



        $request = Request::create('/rss/posts', 'GET');
        $response = $this->rssController->posts($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));

        $xml = simplexml_load_string($response->getContent());
        $this->assertStringContainsString($title, $xml->asXML());
    }

    public function testProductsReturnsXmlResponse()
    {

        $title  = 'Rss Feed Content' . uniqid();
        $content = [];
        $content['title'] = $title;
        $content['content'] = $title;
        $content['content_type'] = 'product';
        $content['is_active'] = 1;
        save_content($content);


        $request = Request::create('/rss/products', 'GET');
        $response = $this->rssController->products($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));

        $xml = simplexml_load_string($response->getContent());
        $this->assertStringContainsString($title, $xml->asXML());
    }
}
