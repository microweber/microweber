<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\Services\Importers\RssFeedImporter;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Fixture-driven coverage for the RSS/Atom parser. The companion
 * {@see RssFeedImporterTest} covers parser branches with inline
 * XML so a failure localizes to a named case; this suite pins the
 * behavior against realistic recorded feeds on disk so a regression
 * in parsing a wild-shaped feed surfaces immediately.
 *
 * Fixtures live at `tests/fixtures/wp/feed-*.xml` and are reused by
 * the live Dusk probe (see `tests/fixtures/wp/router.php`), so the
 * same bytes that hit the Dusk-hosted PHP-built-in server are what
 * the unit parser is exercised against here.
 */
class RssFeedImporterFixtureTest extends TestCase
{
    private const FIXTURE_DIR = __DIR__ . '/../../../../tests/fixtures/wp';

    private static function fixture(string $name): string
    {
        $path = self::FIXTURE_DIR . '/' . $name;
        $body = @file_get_contents($path);
        if ($body === false) {
            throw new \RuntimeException("Fixture missing: {$path}");
        }
        return $body;
    }

    #[Test]
    public function rss_2_fixture_yields_two_dtos_with_expected_identity_and_fields(): void
    {
        $items = (new RssFeedImporter())->parseRss(
            self::fixture('feed-rss-2.xml'),
            'example.com'
        );

        $this->assertCount(2, $items);

        // First item — url-shaped <guid> is the identity because
        // there's no wp:post_id on a non-WordPress feed.
        $this->assertSame('https://example.com/?p=1', $items[0]->guid);
        $this->assertSame('First post', $items[0]->title);
        $this->assertSame('Short teaser for the first post.', $items[0]->html);
        $this->assertNull($items[0]->excerpt, 'Plain RSS with only <description> has no separate excerpt');
        $this->assertSame('Alice', $items[0]->author);
        $this->assertSame(['News'], $items[0]->categories);
        $this->assertSame(['announcement'], $items[0]->tags);
        $this->assertSame('https://example.com/first', $items[0]->canonicalUrl);
        $this->assertSame('2026-04-15T10:30:00+00:00', $items[0]->publishedAt?->format(DATE_ATOM));
        $this->assertSame('example.com', $items[0]->sourceHost);
        $this->assertSame('rss', $items[0]->source);

        // Second item — urn: guid, no author, no categories.
        $this->assertSame('urn:uuid:7f1a', $items[1]->guid);
        $this->assertSame('Second post', $items[1]->title);
        $this->assertSame('Body of the second post.', $items[1]->html);
        $this->assertNull($items[1]->author);
        $this->assertSame([], $items[1]->categories);
        $this->assertSame([], $items[1]->tags);
    }

    #[Test]
    public function content_encoded_fixture_prefers_full_body_and_keeps_description_as_excerpt(): void
    {
        $items = (new RssFeedImporter())->parseRss(
            self::fixture('feed-rss-content-encoded.xml'),
            'wp.example'
        );

        $this->assertCount(2, $items);

        // wp:post_id is the stable identity — this is the whole
        // point of the WordPress variant.
        $this->assertSame('wp:42', $items[0]->guid);
        $this->assertSame('Full article', $items[0]->title);
        $this->assertStringContainsString('<em>full</em>', $items[0]->html);
        $this->assertStringContainsString('<img src="https://wp.example/img/a.png"', $items[0]->html);
        $this->assertStringContainsString('<a href="https://wp.example/related">', $items[0]->html);
        $this->assertSame(
            'This is the short excerpt that appears in feed readers.',
            $items[0]->excerpt,
            'description becomes excerpt when content:encoded is present'
        );
        $this->assertSame(['Essays'], $items[0]->categories);
        $this->assertSame(['longform', 'featured'], $items[0]->tags);
        $this->assertSame('Carol', $items[0]->author);

        $this->assertSame('wp:43', $items[1]->guid);
        $this->assertSame('<p>Body.</p>', $items[1]->html);
        $this->assertSame('Teaser.', $items[1]->excerpt);
        $this->assertSame(['News'], $items[1]->categories);
        $this->assertSame([], $items[1]->tags);
    }

    #[Test]
    public function atom_fixture_uses_alternate_link_and_summary_becomes_excerpt(): void
    {
        $items = (new RssFeedImporter())->parseAtom(
            self::fixture('feed-atom.xml'),
            'example.com'
        );

        $this->assertCount(2, $items);

        // First entry — summary + content:html both present → content wins
        // as html, summary survives as excerpt.
        $this->assertSame('tag:example.com,2026:post-7', $items[0]->guid);
        $this->assertSame('Atom post', $items[0]->title);
        $this->assertSame('<p>Atom body <strong>content</strong>.</p>', $items[0]->html);
        $this->assertSame('Short Atom summary.', $items[0]->excerpt);
        $this->assertSame('Bob', $items[0]->author);
        $this->assertSame(['Essays', 'Writing'], $items[0]->categories);
        $this->assertSame([], $items[0]->tags, 'Atom has no tag/category split');
        $this->assertSame(
            'https://example.com/atom-post',
            $items[0]->canonicalUrl,
            'rel="alternate" must win over rel="self"'
        );
        $this->assertSame('2026-03-05T08:15:00+00:00', $items[0]->publishedAt?->format(DATE_ATOM));

        // Second entry — plain-text content element, no summary → no excerpt.
        $this->assertSame('tag:example.com,2026:post-8', $items[1]->guid);
        $this->assertSame('Plain body without CDATA.', $items[1]->html);
        $this->assertNull($items[1]->excerpt);
    }

    #[Test]
    public function broken_fixture_yields_empty_list_without_throwing(): void
    {
        $items = (new RssFeedImporter())->parseRss(self::fixture('feed-broken.xml'));

        $this->assertSame([], $items);
    }

    #[Test]
    public function end_to_end_import_rss_fixture_via_http_stub(): void
    {
        // The importer's public entrypoint hits `/feed/` first; a
        // stubbed fetcher returns the recorded RSS 2.0 fixture.
        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/feed/' => [
                'body' => self::fixture('feed-rss-2.xml'),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $items = (new RssFeedImporter($fetcher))->import('https://example.com');

        $this->assertCount(2, $items);
        $this->assertSame(['https://example.com/feed/'], $fetcher->fetched);
        $this->assertSame('First post', $items[0]->title);
        $this->assertSame('Second post', $items[1]->title);
    }

    #[Test]
    public function end_to_end_import_falls_back_to_atom_fixture_when_rss_is_broken(): void
    {
        // RSS endpoint responds with the truncated fixture (which
        // does open with <rss> so `looksLikeRss` matches, but the
        // parse itself fails and yields an empty item list). The
        // importer MUST then try `/feed/atom/`.
        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/feed/' => [
                'body' => self::fixture('feed-broken.xml'),
                'http_code' => 200,
                'error' => '',
            ],
            'https://example.com/feed/atom/' => [
                'body' => self::fixture('feed-atom.xml'),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $items = (new RssFeedImporter($fetcher))->import('https://example.com');

        $this->assertCount(2, $items);
        $this->assertSame('tag:example.com,2026:post-7', $items[0]->guid);
        $this->assertSame(
            ['https://example.com/feed/', 'https://example.com/feed/atom/'],
            $fetcher->fetched,
            'RSS is always tried first; Atom only when RSS parse yields nothing'
        );
    }
}
