<?php

/**
 * PHP built-in server router for the WordPress fixture site used by
 * LiveAdminWordPressMigrationProbeTest. Maps the probe endpoints the
 * real Microweber-WP-Migration prober hits at:
 *
 *   GET /wp-json                       → sniff REST root
 *   GET /wp-json/wp/v2/posts?per_page=1 → X-WP-Total header (42)
 *   GET /wp-json/wp/v2/pages?per_page=1 → X-WP-Total header (5)
 *   GET /feed                          → RSS 2.0 with 3 items
 *   GET /sitemap.xml                   → urlset
 *   GET /sitemap_index.xml             → sitemapindex
 *   GET /robots.txt                    → User-agent: * Disallow: /wp-admin/
 *
 * Keep the payloads tiny — the point is to exercise the probe's
 * classification logic, not to model a real WordPress site.
 *
 * Boot with:
 *   php -S 127.0.0.1:<port> tests/fixtures/wp/router.php
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

switch ($path) {
    case '/wp-json':
        header('Content-Type: application/json');
        echo json_encode([
            'name' => 'Fixture WP Site',
            'description' => 'Dusk probe fixture',
            'url' => 'http://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1'),
            'namespaces' => ['oembed/1.0', 'wp/v2'],
            'routes' => new stdClass(),
        ]);
        return true;

    case '/wp-json/wp/v2/posts':
        header('Content-Type: application/json');
        header('X-WP-Total: 42');
        header('X-WP-TotalPages: 42');
        echo '[{"id":1,"title":{"rendered":"Hello"}}]';
        return true;

    case '/wp-json/wp/v2/pages':
        header('Content-Type: application/json');
        header('X-WP-Total: 5');
        header('X-WP-TotalPages: 5');
        echo '[{"id":2,"title":{"rendered":"About"}}]';
        return true;

    case '/feed':
        // Rich WordPress-style RSS 2.0 so the LiveAdmin RSS import
        // test has meaningful <content:encoded> bodies + wp:post_id
        // stable identities to import. The probe test only cares
        // that <rss> shows up (to count the RSS capability), so it
        // doesn't matter to the probe whether items are rich or bare.
        header('Content-Type: application/rss+xml; charset=UTF-8');
        echo <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:wp="http://wordpress.org/export/1.2/">
  <channel>
    <title>Fixture WP Site</title>
    <link>http://127.0.0.1/</link>
    <description>Dusk probe fixture</description>
    <item>
      <title>Dusk fixture hello</title>
      <link>http://127.0.0.1/dusk-fixture-hello</link>
      <guid>http://127.0.0.1/?p=1001</guid>
      <wp:post_id>1001</wp:post_id>
      <pubDate>Thu, 23 Apr 2026 09:00:00 +0000</pubDate>
      <dc:creator>Fixture Author</dc:creator>
      <description>Short teaser for the hello post.</description>
      <content:encoded><![CDATA[<p>Dusk <strong>hello</strong> body with a <a href="http://127.0.0.1/related">related link</a>.</p>]]></content:encoded>
      <category>News</category>
      <category domain="post_tag">imported</category>
    </item>
    <item>
      <title>Dusk fixture world</title>
      <link>http://127.0.0.1/dusk-fixture-world</link>
      <guid>http://127.0.0.1/?p=1002</guid>
      <wp:post_id>1002</wp:post_id>
      <pubDate>Thu, 23 Apr 2026 09:15:00 +0000</pubDate>
      <dc:creator>Fixture Author</dc:creator>
      <description>Second teaser.</description>
      <content:encoded><![CDATA[<p>World body with <em>emphasis</em>.</p>]]></content:encoded>
      <category>Essays</category>
    </item>
  </channel>
</rss>
XML;
        return true;

    case '/sitemap.xml':
        header('Content-Type: application/xml; charset=UTF-8');
        echo <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>http://127.0.0.1/one</loc></url>
  <url><loc>http://127.0.0.1/two</loc></url>
</urlset>
XML;
        return true;

    case '/sitemap_index.xml':
        header('Content-Type: application/xml; charset=UTF-8');
        echo <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>http://127.0.0.1/sitemap.xml</loc></sitemap>
</sitemapindex>
XML;
        return true;

    case '/robots.txt':
        header('Content-Type: text/plain');
        echo "User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\n";
        return true;

    case '/':
        header('Content-Type: text/html');
        echo '<!doctype html><title>Fixture WP Site</title><body>ok</body>';
        return true;
}

http_response_code(404);
header('Content-Type: text/plain');
echo "404 fixture";
return true;
