<?php

namespace Modules\WordPressMigration\Services\Extractors;

use DateTimeImmutable;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Modules\WordPressMigration\DTOs\ExtractedPageDTO;

/**
 * DOM-based readability extractor for the Phase 4 sitemap pipeline.
 *
 * The sitemap importer only hands us a URL list; turning each URL
 * into a Microweber content row needs the page's title, body, hero
 * image, and publish time. Since the origin site may be any
 * WordPress theme ever shipped, we can't rely on a single selector
 * — we lean on three layers of signals, in priority order:
 *
 *   1. OpenGraph / Twitter / meta tags for the easy fields (title,
 *      description, image, publish time, author). Virtually every
 *      WP theme emits these via Yoast / RankMath / AIOSEO SEO
 *      plugins, so the happy path is a few XPath lookups.
 *
 *   2. Semantic HTML5 (`<article>`, `<main>`, `[itemprop="articleBody"]`,
 *      `[role="main"]`). Modern themes wrap post bodies in these,
 *      which cleanly separates content from chrome.
 *
 *   3. Paragraph-density heuristic fallback: pick the block element
 *      with the highest sum-of-`<p>`-text-lengths. Slow-path for
 *      legacy themes without semantic markup.
 *
 * We do NOT aim to be Mozilla Readability — a full port would drag
 * in `fivefilters/readability.php` plus its DOM dependencies and
 * give us many edge cases we don't need against WP sources (which
 * are overwhelmingly in the happy-path bucket). The three-layer
 * approach above covers every wild-WP theme the import pipeline
 * has to migrate without adding a composer dep.
 *
 * Safety contract
 * ---------------
 *   - Parser errors never throw; they populate `$warnings` on the
 *     returned DTO. Callers that care (the importer) treat
 *     `!$dto->isUsable()` as "skip this URL" rather than "abort the
 *     crawl".
 *   - DOMDocument is run with libxml error suppression so malformed
 *     or partial HTML doesn't emit warnings to the Laravel log.
 *   - Chrome tags are removed from the picked content root
 *     (not from the whole document) so meta extraction still sees
 *     header content like `<link rel="canonical">`.
 */
class SitemapPageExtractor
{
    /**
     * Tags stripped from the picked content root before serializing
     * to HTML. These are the "chrome" that wraps every WordPress
     * theme: global nav, header, footer, sidebars, share widgets,
     * comment sections, analytics scripts.
     */
    private const CHROME_TAGS = [
        'nav', 'header', 'footer', 'aside', 'script', 'style',
        'form', 'iframe', 'noscript', 'svg',
    ];

    /**
     * Class / id tokens we treat as chrome. Matched case-insensitive
     * against each token in the element's class and id attributes.
     * Kept narrow to avoid nuking content that happens to share a
     * word with a chrome widget (e.g. an article titled "Related").
     */
    private const CHROME_CLASS_TOKENS = [
        'comments', 'sidebar', 'site-header', 'site-footer', 'social',
        'share', 'related', 'breadcrumbs', 'breadcrumb', 'advertisement',
        'widget-area', 'widget_', 'cookie', 'subscribe', 'newsletter-signup',
    ];

    /**
     * Minimum character count of the picked content root. Below
     * this we consider the match too thin to be a real article body
     * and leave the body empty — the importer then skips the URL.
     * Calibrated to accept a short news blurb (one tweet-sized
     * paragraph) while rejecting "Hi." / a stray nav link.
     */
    private const MIN_BODY_LENGTH = 80;

    public function extract(string $html, string $canonicalUrl = ''): ExtractedPageDTO
    {
        $warnings = [];

        if (trim($html) === '') {
            return new ExtractedPageDTO('', '', canonicalUrl: $canonicalUrl, warnings: ['empty_html']);
        }

        $doc = $this->loadHtml($html);
        if ($doc === null) {
            return new ExtractedPageDTO('', '', canonicalUrl: $canonicalUrl, warnings: ['unparseable_html']);
        }

        $xpath = new DOMXPath($doc);

        $title = $this->extractTitle($xpath);
        $excerpt = $this->extractExcerpt($xpath);
        $author = $this->extractAuthor($xpath);
        $publishedAt = $this->extractPublishedAt($xpath);

        $bodyRoot = $this->findContentRoot($xpath, $doc, $warnings);
        $ogImage = $this->extractOgImage($xpath, $bodyRoot);

        $bodyHtml = $bodyRoot !== null ? $this->serializeContent($doc, $bodyRoot) : '';
        if ($bodyHtml !== '' && mb_strlen(strip_tags($bodyHtml)) < self::MIN_BODY_LENGTH) {
            // A picked root that serializes to near-nothing is not
            // trustworthy — drop it and let the importer skip.
            $warnings[] = 'body_below_min_length';
            $bodyHtml = '';
        }

        return new ExtractedPageDTO(
            title: $title,
            html: $bodyHtml,
            excerpt: $excerpt,
            author: $author,
            ogImage: $ogImage,
            publishedAt: $publishedAt,
            canonicalUrl: $canonicalUrl,
            warnings: $warnings,
        );
    }

    private function loadHtml(string $html): ?DOMDocument
    {
        $doc = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        try {
            // Explicit UTF-8 declaration so mb_* in the rest of the
            // class doesn't get a latin1 surprise. `loadHTML` honors
            // an explicit meta charset in the head if present.
            $prefix = '<?xml encoding="UTF-8">';
            $ok = $doc->loadHTML($prefix . $html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
            return $ok ? $doc : null;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    private function extractTitle(DOMXPath $xpath): string
    {
        foreach ([
            '//meta[@property="og:title"]/@content',
            '//meta[@name="twitter:title"]/@content',
        ] as $selector) {
            $value = $this->firstText($xpath, $selector);
            if ($value !== '') {
                return $value;
            }
        }

        $titleNode = $xpath->query('//head/title');
        if ($titleNode && $titleNode->length > 0) {
            $t = trim((string)$titleNode->item(0)->textContent);
            if ($t !== '') {
                return $this->stripSiteNameSuffix($t);
            }
        }

        $h1 = $xpath->query('//h1');
        if ($h1 && $h1->length > 0) {
            return trim((string)$h1->item(0)->textContent);
        }

        return '';
    }

    private function extractExcerpt(DOMXPath $xpath): ?string
    {
        foreach ([
            '//meta[@property="og:description"]/@content',
            '//meta[@name="description"]/@content',
            '//meta[@name="twitter:description"]/@content',
        ] as $selector) {
            $value = $this->firstText($xpath, $selector);
            if ($value !== '') {
                return $value;
            }
        }
        return null;
    }

    private function extractAuthor(DOMXPath $xpath): ?string
    {
        foreach ([
            '//meta[@name="author"]/@content',
            '//meta[@property="article:author"]/@content',
        ] as $selector) {
            $value = $this->firstText($xpath, $selector);
            if ($value !== '') {
                return $value;
            }
        }

        $jsonLd = $this->readJsonLdAuthor($xpath);
        if ($jsonLd !== null) {
            return $jsonLd;
        }

        return null;
    }

    private function extractPublishedAt(DOMXPath $xpath): ?DateTimeImmutable
    {
        foreach ([
            '//meta[@property="article:published_time"]/@content',
            '//meta[@name="pubdate"]/@content',
            '//meta[@name="publish_date"]/@content',
            '//meta[@itemprop="datePublished"]/@content',
            '//time[@datetime]/@datetime',
        ] as $selector) {
            $value = $this->firstText($xpath, $selector);
            if ($value !== '') {
                $parsed = $this->parseDate($value);
                if ($parsed !== null) {
                    return $parsed;
                }
            }
        }

        $jsonLd = $this->readJsonLdPublishedDate($xpath);
        if ($jsonLd !== null) {
            return $jsonLd;
        }

        return null;
    }

    private function extractOgImage(DOMXPath $xpath, ?DOMElement $bodyRoot): ?string
    {
        foreach ([
            '//meta[@property="og:image"]/@content',
            '//meta[@property="og:image:url"]/@content',
            '//meta[@name="twitter:image"]/@content',
            '//meta[@itemprop="image"]/@content',
            '//link[@rel="image_src"]/@href',
        ] as $selector) {
            $value = $this->firstText($xpath, $selector);
            if ($value !== '') {
                return $value;
            }
        }

        if ($bodyRoot !== null) {
            $firstImg = $xpath->query('.//img[@src]', $bodyRoot);
            if ($firstImg && $firstImg->length > 0) {
                $src = trim((string)$firstImg->item(0)->getAttribute('src'));
                if ($src !== '') {
                    return $src;
                }
            }
        }

        return null;
    }

    /**
     * Pick the element that most plausibly contains the article
     * body. Semantic HTML5 wins, paragraph-density is the fallback.
     */
    private function findContentRoot(DOMXPath $xpath, DOMDocument $doc, array &$warnings): ?DOMElement
    {
        foreach ([
            '//article[not(ancestor::article)]',
            '//*[@itemprop="articleBody"]',
            '//main',
            '//*[@role="main"]',
            '//div[@id="content"]',
            '//div[contains(concat(" ", normalize-space(@class), " "), " entry-content ")]',
            '//div[contains(concat(" ", normalize-space(@class), " "), " post-content ")]',
        ] as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes && $nodes->length > 0) {
                $candidate = $nodes->item(0);
                if ($candidate instanceof DOMElement) {
                    return $candidate;
                }
            }
        }

        $warnings[] = 'semantic_root_not_found';
        return $this->densestParagraphBlock($xpath, $doc);
    }

    private function densestParagraphBlock(DOMXPath $xpath, DOMDocument $doc): ?DOMElement
    {
        $candidates = $xpath->query('//div[p] | //section[p]');
        if (!$candidates || $candidates->length === 0) {
            $body = $doc->getElementsByTagName('body');
            return $body->length > 0 && $body->item(0) instanceof DOMElement
                ? $body->item(0)
                : null;
        }

        $best = null;
        $bestScore = 0;
        foreach ($candidates as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }
            $score = 0;
            $paras = $node->getElementsByTagName('p');
            foreach ($paras as $p) {
                $score += mb_strlen(trim((string)$p->textContent));
            }
            if ($score > $bestScore) {
                $best = $node;
                $bestScore = $score;
            }
        }
        return $best;
    }

    /**
     * Walk $root, remove chrome nodes in-place, and emit the inner
     * HTML of the resulting tree. We clone the subtree so later
     * passes (e.g. extracting og:image from the body) can still see
     * the original structure if needed.
     */
    private function serializeContent(DOMDocument $doc, DOMElement $root): string
    {
        $clone = $root->cloneNode(true);
        if (!$clone instanceof DOMElement) {
            return '';
        }

        $this->stripChrome($clone);

        $html = '';
        foreach ($clone->childNodes as $child) {
            $html .= $doc->saveHTML($child);
        }
        return trim($html);
    }

    private function stripChrome(DOMElement $root): void
    {
        $queue = [$root];
        $toRemove = [];

        while ($queue !== []) {
            $node = array_pop($queue);
            foreach ($node->childNodes as $child) {
                if ($child instanceof DOMElement) {
                    if ($this->isChrome($child)) {
                        $toRemove[] = $child;
                    } else {
                        $queue[] = $child;
                    }
                }
            }
        }

        foreach ($toRemove as $node) {
            if ($node->parentNode !== null) {
                $node->parentNode->removeChild($node);
            }
        }
    }

    private function isChrome(DOMElement $el): bool
    {
        $tag = strtolower($el->nodeName);
        if (in_array($tag, self::CHROME_TAGS, true)) {
            return true;
        }

        $classes = strtolower((string)$el->getAttribute('class'));
        $id = strtolower((string)$el->getAttribute('id'));
        $haystack = trim($classes . ' ' . $id);
        if ($haystack === '') {
            return false;
        }

        foreach (self::CHROME_CLASS_TOKENS as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

    private function firstText(DOMXPath $xpath, string $selector): string
    {
        $nodes = $xpath->query($selector);
        if (!$nodes || $nodes->length === 0) {
            return '';
        }
        $value = (string)$nodes->item(0)->textContent;
        return trim($value);
    }

    private function parseDate(string $raw): ?DateTimeImmutable
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        try {
            return new DateTimeImmutable($raw);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Some themes suffix the site name to `<title>` with ` | Site`
     * or ` — Site`. Stripping the suffix gives the cleaner article
     * title the mapper actually wants.
     */
    private function stripSiteNameSuffix(string $title): string
    {
        // Only split on separator chars that a real article title
        // wouldn't contain (em/en dash, pipe). We deliberately don't
        // split on " - " because article titles commonly use hyphens
        // as real punctuation.
        foreach ([' | ', ' — ', ' – ', ' « '] as $sep) {
            $pos = strpos($title, $sep);
            if ($pos !== false && $pos > 0) {
                return trim(substr($title, 0, $pos));
            }
        }
        return $title;
    }

    private function readJsonLdAuthor(DOMXPath $xpath): ?string
    {
        foreach ($this->jsonLdBlocks($xpath) as $data) {
            $author = $data['author'] ?? null;
            if (is_string($author) && trim($author) !== '') {
                return trim($author);
            }
            if (is_array($author)) {
                $name = $author['name'] ?? ($author[0]['name'] ?? null);
                if (is_string($name) && trim($name) !== '') {
                    return trim($name);
                }
            }
        }
        return null;
    }

    private function readJsonLdPublishedDate(DOMXPath $xpath): ?DateTimeImmutable
    {
        foreach ($this->jsonLdBlocks($xpath) as $data) {
            $date = $data['datePublished'] ?? null;
            if (is_string($date) && trim($date) !== '') {
                $parsed = $this->parseDate($date);
                if ($parsed !== null) {
                    return $parsed;
                }
            }
        }
        return null;
    }

    /**
     * @return iterable<array<string, mixed>>
     */
    private function jsonLdBlocks(DOMXPath $xpath): iterable
    {
        $scripts = $xpath->query('//script[@type="application/ld+json"]');
        if (!$scripts) {
            return;
        }
        foreach ($scripts as $script) {
            $raw = trim((string)$script->textContent);
            if ($raw === '') {
                continue;
            }
            $decoded = json_decode($raw, true);
            if (!is_array($decoded)) {
                continue;
            }
            if (isset($decoded['@graph']) && is_array($decoded['@graph'])) {
                foreach ($decoded['@graph'] as $node) {
                    if (is_array($node)) {
                        yield $node;
                    }
                }
                continue;
            }
            if (array_is_list($decoded)) {
                foreach ($decoded as $node) {
                    if (is_array($node)) {
                        yield $node;
                    }
                }
                continue;
            }
            yield $decoded;
        }
    }
}
