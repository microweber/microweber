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
        header('Content-Type: application/rss+xml; charset=UTF-8');
        echo <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Fixture WP Site</title>
    <link>http://127.0.0.1/</link>
    <description>Dusk probe fixture</description>
    <item><title>One</title><link>http://127.0.0.1/one</link><guid>http://127.0.0.1/?p=1</guid></item>
    <item><title>Two</title><link>http://127.0.0.1/two</link><guid>http://127.0.0.1/?p=2</guid></item>
    <item><title>Three</title><link>http://127.0.0.1/three</link><guid>http://127.0.0.1/?p=3</guid></item>
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
