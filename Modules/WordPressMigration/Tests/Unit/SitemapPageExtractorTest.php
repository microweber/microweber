<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\DTOs\ExtractedPageDTO;
use Modules\WordPressMigration\Services\Extractors\SitemapPageExtractor;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for the DOM-based readability extractor used by the
 * Phase 4 sitemap → content pipeline.
 *
 * Every test builds a minimal HTML fragment that exercises exactly
 * one code path — the happy OG/meta path, the semantic HTML5 body
 * root, the density-fallback path, and the chrome-stripper. A real
 * WordPress page has all of these at once; the unit tests keep them
 * isolated so a regression shows up with a single failing assertion.
 */
class SitemapPageExtractorTest extends TestCase
{
    #[Test]
    public function prefers_og_title_over_document_title(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head>
    <title>Fallback — My Blog</title>
    <meta property="og:title" content="Canonical article title">
  </head>
  <body>
    <article>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
    </article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html, 'https://example.com/a');

        $this->assertSame('Canonical article title', $dto->title);
    }

    #[Test]
    public function falls_back_to_document_title_and_strips_site_name_suffix(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>My great post | Example Blog</title></head>
  <body>
    <article>
      <p>Body paragraph long enough to clear the minimum body length that the extractor enforces before accepting a candidate content root. Lorem ipsum.</p>
    </article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertSame('My great post', $dto->title);
    }

    #[Test]
    public function extracts_og_image_and_description(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head>
    <title>Post</title>
    <meta property="og:image" content="https://cdn.example.com/hero.jpg">
    <meta property="og:description" content="A short summary of the post.">
  </head>
  <body>
    <article>
      <p>Body long enough for the extractor to accept the article as the body root. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertSame('https://cdn.example.com/hero.jpg', $dto->ogImage);
        $this->assertSame('A short summary of the post.', $dto->excerpt);
    }

    #[Test]
    public function falls_back_to_first_body_image_when_og_image_missing(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>Post</title></head>
  <body>
    <article>
      <p>Enough body text to accept the article root; lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
      <img src="https://cdn.example.com/body-lead.png">
    </article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertSame('https://cdn.example.com/body-lead.png', $dto->ogImage);
    }

    #[Test]
    public function extracts_published_time_from_article_meta_tag(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head>
    <title>Dated post</title>
    <meta property="article:published_time" content="2026-04-10T12:34:56+00:00">
  </head>
  <body>
    <article><p>Body paragraph content long enough for the extractor to accept the article as the body root; lorem ipsum dolor sit amet.</p></article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertNotNull($dto->publishedAt);
        $this->assertSame('2026-04-10T12:34:56+00:00', $dto->publishedAt->format(DATE_ATOM));
    }

    #[Test]
    public function extracts_published_time_from_time_element_when_meta_absent(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>Post</title></head>
  <body>
    <article>
      <time datetime="2026-03-01T09:00:00+00:00">March 1</time>
      <p>Body long enough for the extractor to accept this article as the content root; lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertNotNull($dto->publishedAt);
        $this->assertSame('2026-03-01T09:00:00+00:00', $dto->publishedAt->format(DATE_ATOM));
    }

    #[Test]
    public function extracts_published_time_and_author_from_json_ld(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head>
    <title>JSON-LD post</title>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "datePublished": "2026-02-15T08:00:00+00:00",
      "author": {"@type": "Person", "name": "Alice Writer"}
    }
    </script>
  </head>
  <body>
    <article><p>Body text long enough for the extractor to pick this article as the content root; lorem ipsum dolor sit amet, consectetur.</p></article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertSame('Alice Writer', $dto->author);
        $this->assertNotNull($dto->publishedAt);
        $this->assertSame('2026-02-15T08:00:00+00:00', $dto->publishedAt->format(DATE_ATOM));
    }

    #[Test]
    public function uses_article_tag_as_body_root_and_keeps_inline_links(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>Post</title></head>
  <body>
    <header><nav><a href="/">Home</a></nav></header>
    <article>
      <p>Lorem ipsum dolor sit amet, with <a href="https://example.com/linked">an inline link</a> that must survive.</p>
      <p>Second paragraph, additional text to push past the min body length threshold enforced by the extractor.</p>
    </article>
    <footer>© 2026</footer>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertStringContainsString('<a href="https://example.com/linked">', $dto->html);
        $this->assertStringNotContainsString('Home', $dto->html, 'Header nav must be absent');
        $this->assertStringNotContainsString('2026', $dto->html, 'Footer must be absent');
    }

    #[Test]
    public function strips_chrome_classes_and_ids_inside_article(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>Post</title></head>
  <body>
    <article>
      <p>Real body content long enough to clear the minimum body length gate the extractor enforces; lorem ipsum dolor sit amet.</p>
      <div class="related"><h3>Related posts</h3><p>promotional link</p></div>
      <div id="comments"><p>Reader comment</p></div>
      <aside class="sidebar"><p>Widget blob</p></aside>
    </article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertStringNotContainsString('Related posts', $dto->html);
        $this->assertStringNotContainsString('Reader comment', $dto->html);
        $this->assertStringNotContainsString('Widget blob', $dto->html);
        $this->assertStringContainsString('Real body content', $dto->html);
    }

    #[Test]
    public function uses_main_when_no_article_is_present(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>Post</title></head>
  <body>
    <header>Chrome</header>
    <main>
      <p>Main-body content long enough to clear the minimum body length threshold required by the extractor before a candidate root is accepted.</p>
    </main>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertStringContainsString('Main-body content', $dto->html);
        $this->assertStringNotContainsString('Chrome', $dto->html);
    }

    #[Test]
    public function density_fallback_picks_paragraph_heavy_div_when_semantic_tags_absent(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>Post</title></head>
  <body>
    <div class="top-chrome">
      <p>menu</p>
    </div>
    <div class="body-wrap">
      <p>Long body paragraph number one with enough text to outweigh the chrome div. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      <p>Long body paragraph number two, also with enough text to dominate the density score used by the extractor's fallback path.</p>
    </div>
    <div class="footer-chrome"><p>fine print</p></div>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertStringContainsString('Long body paragraph number one', $dto->html);
        $this->assertStringNotContainsString('menu', $dto->html);
        $this->assertStringNotContainsString('fine print', $dto->html);
        $this->assertContains('semantic_root_not_found', $dto->warnings);
    }

    #[Test]
    public function returns_empty_body_and_warning_when_content_too_thin(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><title>Thin post</title></head>
  <body>
    <article><p>Hi.</p></article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertSame('', $dto->html);
        $this->assertContains('body_below_min_length', $dto->warnings);
        $this->assertFalse($dto->isUsable(), 'Thin posts are not usable for import');
    }

    #[Test]
    public function returns_empty_dto_for_empty_or_whitespace_input(): void
    {
        $dto = (new SitemapPageExtractor())->extract('   ');
        $this->assertSame('', $dto->title);
        $this->assertSame('', $dto->html);
        $this->assertContains('empty_html', $dto->warnings);
    }

    #[Test]
    public function is_usable_requires_both_title_and_html(): void
    {
        $withBoth = new ExtractedPageDTO('Title', '<p>body</p>');
        $noTitle = new ExtractedPageDTO('', '<p>body</p>');
        $noBody = new ExtractedPageDTO('Title', '');

        $this->assertTrue($withBoth->isUsable());
        $this->assertFalse($noTitle->isUsable());
        $this->assertFalse($noBody->isUsable());
    }

    #[Test]
    public function carries_canonical_url_onto_dto(): void
    {
        $html = '<html><head><title>t</title></head><body><article><p>Enough body text to clear the min-length gate the extractor enforces; lorem ipsum dolor sit amet.</p></article></body></html>';

        $dto = (new SitemapPageExtractor())->extract($html, 'https://example.com/post-1');

        $this->assertSame('https://example.com/post-1', $dto->canonicalUrl);
    }

    #[Test]
    public function prefers_author_meta_over_json_ld(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head>
    <title>Post</title>
    <meta name="author" content="Meta Author">
    <script type="application/ld+json">{"@type":"Article","author":{"name":"JSON Author"}}</script>
  </head>
  <body>
    <article><p>Body text long enough to clear the minimum body length gate enforced by the extractor before accepting an article as the body root.</p></article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertSame('Meta Author', $dto->author);
    }

    #[Test]
    public function handles_utf8_content_without_mojibake(): void
    {
        $html = <<<'HTML'
<!doctype html>
<html>
  <head><meta charset="utf-8"><title>Пост</title></head>
  <body>
    <article><p>Съешь ещё этих мягких французских булок, да выпей же чаю — пример русского текста достаточной длины для экстрактора.</p></article>
  </body>
</html>
HTML;

        $dto = (new SitemapPageExtractor())->extract($html);

        $this->assertSame('Пост', $dto->title);
        $this->assertStringContainsString('Съешь ещё этих', $dto->html);
    }
}
