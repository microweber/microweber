<?php

namespace Modules\Sitemap\Tests\Unit;

use Tests\TestCase;

class SitemapRoutesTest extends TestCase
{
    public function testSitemapIndexRoute()
    {
        $response = $this->get('sitemap.xml');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    public function testSitemapCategoriesRoute()
    {
        $response = $this->get('sitemap.xml/categories');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    public function testSitemapTagsRoute()
    {
        $response = $this->get('sitemap.xml/tags');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    public function testSitemapProductsRoute()
    {
        $response = $this->get('sitemap.xml/products');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    public function testSitemapPostsRoute()
    {
        $response = $this->get('sitemap.xml/posts');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    public function testSitemapPagesRoute()
    {
        $response = $this->get('sitemap.xml/pages');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }
}
