<?php
namespace MicroweberPackages\App\tests;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use MicroweberPackages\Blog\Http\Controllers\BlogController;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Shop\Http\Controllers\ShopController;
use MicroweberPackages\Tag\Model\Tag;

class SitemapControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->call('GET', route('sitemap.index'),[]);
        $this->assertEquals(200, $response->status());

        $sitemapXmlContent = $response->getContent();

        $sitemapXml = simplexml_load_string($sitemapXmlContent);
        $this->assertTrue(is_object($sitemapXml));

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

    public function testCategories()
    {
        $response = $this->call('GET', route('sitemap.categories'),[]);
        $this->assertEquals(200, $response->status());

        $sitemapXmlContent = $response->getContent();

        $sitemapXml = simplexml_load_string($sitemapXmlContent);
        $this->assertTrue(is_object($sitemapXml));

    }

    public function testTags()
    {
        $tag = new \Conner\Tagging\Model\Tag();
        $tag->slug = 'slug-'.str_random();
        $tag->name = 'name-'.str_random();
        $tag->save();

        $response = $this->call('GET', route('sitemap.tags'),[]);
        $this->assertEquals(200, $response->status());

    }

    public function testProducts()
    {
        $response = $this->call('GET', route('sitemap.products'),[]);
        $this->assertEquals(200, $response->status());

        $sitemapXmlContent = $response->getContent();

        $sitemapXml = simplexml_load_string($sitemapXmlContent);
        $this->assertTrue(is_object($sitemapXml));

    }

    public function testPosts()
    {
        $response = $this->call('GET', route('sitemap.posts'),[]);
        $this->assertEquals(200, $response->status());

        $sitemapXmlContent = $response->getContent();

        $sitemapXml = simplexml_load_string($sitemapXmlContent);
        $this->assertTrue(is_object($sitemapXml));

    }

    public function testPages()
    {
        $response = $this->call('GET', route('sitemap.pages'),[]);
        $this->assertEquals(200, $response->status());

        $sitemapXmlContent = $response->getContent();

        $sitemapXml = simplexml_load_string($sitemapXmlContent);
        $this->assertTrue(is_object($sitemapXml));

    }
}
