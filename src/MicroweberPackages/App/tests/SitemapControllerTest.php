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

class SitemapControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->call('GET', route('sitemap.index'),[]);
        $this->assertEquals(200, $response->status());

        $sitemapXmlContent = $response->getContent();
        $sitemapXml = simplexml_load_string($sitemapXmlContent);

        $this->assertIsObject($sitemapXml);

        $this->asserTrue(str_contains($sitemapXmlContent, '/sitemap.xml/categories'));
        $this->asserTrue(str_contains($sitemapXmlContent, '/sitemap.xml/products'));
        $this->asserTrue(str_contains($sitemapXmlContent, '/sitemap.xml/posts'));
        $this->asserTrue(str_contains($sitemapXmlContent, '/sitemap.xml/tags'));
        $this->asserTrue(str_contains($sitemapXmlContent, '/sitemap.xml/pages'));

    }
}
