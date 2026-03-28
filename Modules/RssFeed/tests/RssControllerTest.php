<?php

namespace Modules\RssFeed\Tests;

use PHPUnit\Framework\Attributes\Test;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Modules\RssFeed\Http\Controllers\RssController;
use Illuminate\Http\Request;

class RssControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected RssController $rssController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rssController = new RssController();
    }

    #[Test]

    public function it_index_returns_xml_response(): void {

        $request = Request::create('/rss', 'GET', ['format' => 'atom']);
        $response = $this->rssController->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/xml', $response->headers->get('Content-Type'));



    }

    #[Test]

    public function it_posts_returns_xml_response(): void {
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

    #[Test]

    public function it_products_returns_xml_response(): void {

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
