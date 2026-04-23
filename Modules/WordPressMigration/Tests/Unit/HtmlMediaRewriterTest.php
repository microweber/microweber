<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\Services\Media\HtmlMediaRewriter;
use Modules\WordPressMigration\Services\Media\MediaRehoster;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HtmlMediaRewriterTest extends TestCase
{
    #[Test]
    public function img_src_is_rewritten_when_rehoster_returns_a_new_url(): void
    {
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/image.jpg' => '/userfiles/media/external/wp-example/image.jpg',
        ]);

        $html = '<p>Before <img src="https://wp.example/image.jpg" alt="x"> After</p>';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertSame(
            '<p>Before <img src="/userfiles/media/external/wp-example/image.jpg" alt="x"> After</p>',
            $out
        );
        $this->assertSame(['https://wp.example/image.jpg'], $rehoster->calls);
    }

    #[Test]
    public function a_href_is_rewritten_with_the_same_rehoster_rules(): void
    {
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/doc.pdf' => '/userfiles/media/external/wp-example/doc.pdf',
        ]);

        $html = '<p><a href="https://wp.example/doc.pdf">PDF</a></p>';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertSame(
            '<p><a href="/userfiles/media/external/wp-example/doc.pdf">PDF</a></p>',
            $out
        );
    }

    #[Test]
    public function null_return_leaves_the_original_url_untouched(): void
    {
        $rehoster = new FakeMediaRehoster([]); // null for everything

        $html = '<p><a href="mailto:you@example.com">mail</a> <img src="https://off-site.example/x.png"></p>';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertSame($html, $out);
    }

    #[Test]
    public function same_url_referenced_twice_is_rehosted_once(): void
    {
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/hero.jpg' => '/userfiles/media/hero.jpg',
        ]);

        $html = '<a href="https://wp.example/hero.jpg"><img src="https://wp.example/hero.jpg"></a>';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertStringContainsString('href="/userfiles/media/hero.jpg"', $out);
        $this->assertStringContainsString('src="/userfiles/media/hero.jpg"', $out);
        $this->assertSame(['https://wp.example/hero.jpg'], $rehoster->calls,
            'URL must be rehosted once, reused on the second reference'
        );
    }

    #[Test]
    public function single_quoted_attributes_are_supported_and_preserve_quote_style(): void
    {
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/x.png' => '/local/x.png',
        ]);

        $html = "<img src='https://wp.example/x.png' alt='x'>";
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertSame("<img src='/local/x.png' alt='x'>", $out);
    }

    #[Test]
    public function other_attributes_survive_the_rewrite(): void
    {
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/i.jpg' => '/local/i.jpg',
        ]);

        $html = '<img class="hero" data-id="17" src="https://wp.example/i.jpg" loading="lazy" width="800" height="600">';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertStringContainsString('class="hero"', $out);
        $this->assertStringContainsString('data-id="17"', $out);
        $this->assertStringContainsString('loading="lazy"', $out);
        $this->assertStringContainsString('width="800"', $out);
        $this->assertStringContainsString('src="/local/i.jpg"', $out);
    }

    #[Test]
    public function ampersand_and_quote_in_rehosted_url_are_escaped(): void
    {
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/file.jpg' => '/local/file.jpg?v=1&foo="bar"',
        ]);

        $html = '<img src="https://wp.example/file.jpg">';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertSame('<img src="/local/file.jpg?v=1&amp;foo=&quot;bar&quot;">', $out);
    }

    #[Test]
    public function empty_or_non_markup_input_is_returned_untouched(): void
    {
        $rewriter = new HtmlMediaRewriter();
        $rehoster = new FakeMediaRehoster(['any' => 'x']);

        $this->assertSame('', $rewriter->rewrite('', $rehoster));
        $this->assertSame('plain text, no tags', $rewriter->rewrite('plain text, no tags', $rehoster));
        $this->assertSame([], $rehoster->calls);
    }

    #[Test]
    public function non_matching_tags_are_ignored(): void
    {
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/v.mp4' => '/local/v.mp4',
        ]);

        // <video> and <source> are NOT in scope — task is img+a.
        $html = '<video><source src="https://wp.example/v.mp4"></video>';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster);

        $this->assertSame($html, $out);
        $this->assertSame([], $rehoster->calls);
    }

    #[Test]
    public function context_is_forwarded_to_the_rehoster(): void
    {
        $rehoster = new class implements MediaRehoster {
            public array $lastContext = [];
            public function rehost(string $url, array $context = []): ?string
            {
                $this->lastContext = $context;
                return '/rehosted';
            }
        };

        (new HtmlMediaRewriter())->rewrite(
            '<img src="https://wp.example/x.jpg">',
            $rehoster,
            ['rel_type' => 'Content', 'rel_id' => 42]
        );

        $this->assertSame(['rel_type' => 'Content', 'rel_id' => 42], $rehoster->lastContext);
    }

    #[Test]
    public function off_site_url_is_left_alone_when_origin_host_is_set(): void
    {
        // Host filter is active because context['host'] is present.
        // wp.example is not the origin, so the rehoster must never
        // be asked about it — the URL stays byte-identical.
        $rehoster = new FakeMediaRehoster([
            'https://wp.example/hotlink.jpg' => '/should-never-be-used',
        ]);

        $html = '<p><img src="https://wp.example/hotlink.jpg"></p>';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster, ['host' => 'example.com']);

        $this->assertSame($html, $out);
        $this->assertSame([], $rehoster->calls,
            'Off-site URL must be filtered before the rehoster is consulted'
        );
    }

    #[Test]
    public function same_host_url_is_rewritten_even_without_wp_content_uploads(): void
    {
        // Themes and plugins serve assets from /wp-content/themes/ or
        // /wp-includes/ — same origin, still worth rehosting.
        $rehoster = new FakeMediaRehoster([
            'https://example.com/wp-content/themes/foo/bg.jpg' => '/userfiles/media/bg.jpg',
        ]);

        $html = '<img src="https://example.com/wp-content/themes/foo/bg.jpg">';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster, ['host' => 'example.com']);

        $this->assertStringContainsString('src="/userfiles/media/bg.jpg"', $out);
    }

    #[Test]
    public function wp_content_uploads_on_any_host_is_rewritten(): void
    {
        // Jetpack's Photon CDN rewrites URLs to i0.wp.com/<origin>/...
        // — the upload path survives, the host does not. Must still
        // rehost so images keep working after migration.
        $rehoster = new FakeMediaRehoster([
            'https://i0.wp.com/example.com/wp-content/uploads/2024/hero.jpg' => '/userfiles/media/hero.jpg',
        ]);

        $html = '<img src="https://i0.wp.com/example.com/wp-content/uploads/2024/hero.jpg">';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster, ['host' => 'example.com']);

        $this->assertStringContainsString('src="/userfiles/media/hero.jpg"', $out);
    }

    #[Test]
    public function subdomain_of_origin_host_is_treated_as_in_scope(): void
    {
        // Many WP installs use cdn.example.com or static.example.com
        // for assets. Those should still be rewritten — same company.
        $rehoster = new FakeMediaRehoster([
            'https://cdn.example.com/media/pic.jpg' => '/userfiles/media/pic.jpg',
        ]);

        $html = '<img src="https://cdn.example.com/media/pic.jpg">';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster, ['host' => 'example.com']);

        $this->assertStringContainsString('src="/userfiles/media/pic.jpg"', $out);
    }

    #[Test]
    public function suffix_match_does_not_cross_into_unrelated_host(): void
    {
        // evil-example.com ends with "example.com" as a substring but
        // is NOT a subdomain of example.com. Must not be treated as
        // in-scope, even though string-contains would match.
        $rehoster = new FakeMediaRehoster([
            'https://evil-example.com/x.jpg' => '/should-never-be-used',
        ]);

        $html = '<img src="https://evil-example.com/x.jpg">';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster, ['host' => 'example.com']);

        $this->assertSame($html, $out);
        $this->assertSame([], $rehoster->calls);
    }

    #[Test]
    public function off_site_anchor_href_to_external_page_is_left_alone(): void
    {
        // Users often link out to Twitter, GitHub, etc. Those anchors
        // must survive the migration verbatim.
        $rehoster = new FakeMediaRehoster([]);

        $html = '<p>See <a href="https://github.com/acme/repo">this repo</a> and '
            . '<a href="https://twitter.com/acme">the team</a>.</p>';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster, ['host' => 'example.com']);

        $this->assertSame($html, $out);
        $this->assertSame([], $rehoster->calls);
    }

    #[Test]
    public function without_origin_host_legacy_permissive_mode_delegates_everything(): void
    {
        // Backwards compat: callers that don't supply host (older
        // importers, direct rewriter usage) get the pre-scoping
        // behavior — every URL goes to the rehoster and its null
        // return controls the outcome.
        $rehoster = new FakeMediaRehoster([
            'https://anywhere.example/x.jpg' => '/userfiles/media/x.jpg',
        ]);

        $html = '<img src="https://anywhere.example/x.jpg">';
        $out = (new HtmlMediaRewriter())->rewrite($html, $rehoster); // no context

        $this->assertStringContainsString('src="/userfiles/media/x.jpg"', $out);
        $this->assertSame(['https://anywhere.example/x.jpg'], $rehoster->calls);
    }
}

/**
 * Table-driven fake. Keys are the URLs the rewriter will hand us;
 * values are what to return. Any URL not in the table returns null
 * (leave the HTML alone).
 */
final class FakeMediaRehoster implements MediaRehoster
{
    /** @var list<string> */
    public array $calls = [];

    /**
     * @param array<string, string|null> $table
     */
    public function __construct(private array $table) {}

    public function rehost(string $url, array $context = []): ?string
    {
        $this->calls[] = $url;
        return $this->table[$url] ?? null;
    }
}
