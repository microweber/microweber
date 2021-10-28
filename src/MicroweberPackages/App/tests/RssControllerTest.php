<?php
namespace MicroweberPackages\App\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;


class RssControllerTest extends TestCase
{
    public function testIndex()
    {
        $tag = new Post();
        $tag->slug = 'slug-'.str_random();
        $tag->title = 'title-'.str_random();
        $tag->content = 'content-'.str_random();
        $tag->save();

        $response = $this->call('GET', route('rss.index'),[]);
        $this->assertEquals(200, $response->status());

        $rssXmlContent = $response->getOriginalContent();

        $rssXml = simplexml_load_string($rssXmlContent);
        $this->assertIsObject($rssXml);
    }

    public function testProducts()
    {
        $tag = new Product();
        $tag->slug = 'slug-'.str_random();
        $tag->title = 'title-'.str_random();
        $tag->content = 'content-'.str_random();
        $tag->save();

        $response = $this->call('GET', route('rss.products'),[]);
        $this->assertEquals(200, $response->status());

        $rssXmlContent = $response->getOriginalContent();

        $rssXml = simplexml_load_string($rssXmlContent);
        $this->assertIsObject($rssXml);

    }
}
