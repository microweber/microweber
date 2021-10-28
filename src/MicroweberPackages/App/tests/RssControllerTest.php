<?php
namespace MicroweberPackages\App\tests;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use MicroweberPackages\Blog\Http\Controllers\BlogController;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Shop\Http\Controllers\ShopController;
use MicroweberPackages\Tag\Model\Tag;

class RssControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->call('GET', route('rss.index'),[]);
        $this->assertEquals(200, $response->status());

        $rssXmlContent = $response->getOriginalContent();

        $rssXml = simplexml_load_string($rssXmlContent);
        $this->assertIsObject($rssXml);
    }

    public function testProducts()
    {
        $response = $this->call('GET', route('rss.products'),[]);
        $this->assertEquals(200, $response->status());

        $rssXmlContent = $response->getOriginalContent();

        $rssXml = simplexml_load_string($rssXmlContent);
        $this->assertIsObject($rssXml);

    }
}
