<?php

namespace Modules\Sitemap\Tests\Unit;

use Tests\TestCase;

class SitemapRoutesTest extends TestCase
{
    public function testSitemapIndexRoute()
    {
        $response = $this->get('sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function testSitemapCategoriesRoute()
    {
        $response = $this->get('sitemap.xml/categories');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function testSitemapTagsRoute()
    {
        $response = $this->get('sitemap.xml/tags');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function testSitemapProductsRoute()
    {
        $response = $this->get('sitemap.xml/products');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function testSitemapPostsRoute()
    {
        $response = $this->get('sitemap.xml/posts');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function testSitemapPagesRoute()
    {
        $response = $this->get('sitemap.xml/pages');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }
}
