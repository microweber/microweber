<?php

namespace Modules\Sitemap\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class SitemapRoutesTest extends TestCase
{
    #[Test]
    public function it_sitemap_index_route(): void {
        $response = $this->get('sitemap.xml');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    #[Test]

    public function it_sitemap_categories_route(): void {
        $response = $this->get('sitemap.xml/categories');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    #[Test]

    public function it_sitemap_tags_route(): void {
        $response = $this->get('sitemap.xml/tags');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    #[Test]

    public function it_sitemap_products_route(): void {
        $response = $this->get('sitemap.xml/products');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    #[Test]

    public function it_sitemap_posts_route(): void {
        $response = $this->get('sitemap.xml/posts');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }

    #[Test]

    public function it_sitemap_pages_route(): void {
        $response = $this->get('sitemap.xml/pages');
        $response->assertStatus(200);

        $actual = $response->headers->get('Content-Type');
        $actual = str_replace('charset=UTF-8', 'charset=utf-8', $actual);
        $this->assertEquals('text/xml; charset=utf-8', $actual);
    }
}
