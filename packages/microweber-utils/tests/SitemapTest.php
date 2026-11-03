<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Tests;

use MicroweberPackages\Utils\Misc\Sitemap;
use PHPUnit\Framework\Attributes\Test;

class SitemapTest extends TestCase
{
    #[Test]
    public function xml_header_and_footer_wrap_a_urlset_document(): void
    {
        $sitemap = new Sitemap();

        $this->assertStringContainsString('<?xml version="1.0"', $sitemap->xmlHeader());
        $this->assertStringContainsString('<urlset', $sitemap->xmlHeader());
        $this->assertStringContainsString('</urlset>', $sitemap->xmlFooter());
    }

    #[Test]
    public function added_pages_are_rendered_as_url_entries(): void
    {
        $sitemap = new Sitemap();
        $sitemap->addPage('https://example.com/', 'daily', 0.8);
        $sitemap->addPage('https://example.com/about', 'weekly', 0.5);

        $pages = $sitemap->getPages();

        $this->assertStringContainsString('<loc>https://example.com/</loc>', $pages);
        $this->assertStringContainsString('<loc>https://example.com/about</loc>', $pages);
        $this->assertStringContainsString('<changefreq>daily</changefreq>', $pages);
        $this->assertStringContainsString('<priority>0.8</priority>', $pages);
        $this->assertSame(2, substr_count($pages, '<url>'));
    }
}
