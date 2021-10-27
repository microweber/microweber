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

        $sitemapXmlContent = $response->getOriginalContent();

        $sitemapXml = simplexml_load_string($sitemapXmlContent);
        $this->assertIsObject($sitemapXml);

        $locations = [];
        foreach ($sitemapXml as $item) {
            $loc = $item->loc[0];
            $locExp = explode('sitemap.xml/', $loc);
            $locations[] = $locExp[1];
        }

        $this->assertTrue(in_array('categories', $locations));
        $this->assertTrue(in_array('products', $locations));
        $this->assertTrue(in_array('posts', $locations));
        $this->assertTrue(in_array('tags', $locations));
        $this->assertTrue(in_array('pages', $locations));

    }
}
