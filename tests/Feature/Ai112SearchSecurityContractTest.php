<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-132 / AI-112 / TICKET-BX — Search input-handling security contract.
 *
 * Pins the security guards inside Modules/Search/Livewire/SearchComponent
 * (already shipped in cycle-123 under SEC-05) so the brief's required
 * scenarios cannot regress:
 *
 *   - "reflected-XSS" : every keyword is run through strip_tags so a
 *                       payload like `<svg onload=alert(1)>` is sanitised
 *                       before it is echoed back to the search-results
 *                       view.
 *   - "long-query"    : keyword is mb_substr-capped at 200 chars so an
 *                       attacker cannot force a 10MB LIKE pattern.
 *   - "empty-query"   : updatedSearchQuery() requires strlen > 2 before
 *                       triggering the search; below threshold it just
 *                       clears searchResults.
 *   - "pagination"    : the get_content() call is parametrised with a
 *                       fixed limit:10 so the user cannot blow up the
 *                       result set via the keyword field.
 *
 * Source-grep style after Sec05SsrfAndStoredXssContractTest.
 */
class Ai112SearchSecurityContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function search_strips_html_tags_from_keyword(): void
    {
        $src = $this->read('Modules/Search/Livewire/SearchComponent.php');

        $this->assertMatchesRegularExpression(
            '/\$keyword\s*=\s*strip_tags\(\s*\(string\)\s*\$this->searchQuery\s*\)/',
            $src,
            'SearchComponent::search MUST strip_tags() the keyword so '
            . 'reflected XSS payloads (`<svg onload=...>`, `<img onerror=...>`) '
            . 'are stripped before the value reaches the results view.'
        );
    }

    #[Test]
    public function search_caps_keyword_length_at_200_chars(): void
    {
        $src = $this->read('Modules/Search/Livewire/SearchComponent.php');

        $this->assertMatchesRegularExpression(
            '/\$keyword\s*=\s*mb_substr\(\s*\$keyword\s*,\s*0\s*,\s*200\s*\)/',
            $src,
            'SearchComponent::search MUST mb_substr-cap keyword to 200 chars '
            . 'so an attacker cannot force a multi-megabyte LIKE pattern.'
        );
    }

    #[Test]
    public function search_short_query_clears_results_without_query(): void
    {
        $src = $this->read('Modules/Search/Livewire/SearchComponent.php');

        $this->assertMatchesRegularExpression(
            '/strlen\(\$this->searchQuery\)\s*>\s*2/',
            $src,
            'updatedSearchQuery MUST require strlen > 2 before triggering '
            . 'the underlying search call (empty-query DoS guard + UX).'
        );

        $this->assertMatchesRegularExpression(
            '/\$this->searchResults\s*=\s*\[\]\s*;/',
            $src,
            'updatedSearchQuery MUST clear searchResults when below threshold.'
        );
    }

    #[Test]
    public function search_uses_fixed_result_limit(): void
    {
        $src = $this->read('Modules/Search/Livewire/SearchComponent.php');

        $this->assertMatchesRegularExpression(
            '/[\'"]limit[\'"]\s*=>\s*10/',
            $src,
            'SearchComponent::search MUST pin a fixed limit:10 in the '
            . 'get_content params so the result set cannot be blown up '
            . 'via the keyword field.'
        );
    }

    #[Test]
    public function search_decodes_keyword_query_param_safely(): void
    {
        $src = $this->read('Modules/Search/Livewire/SearchComponent.php');

        $this->assertMatchesRegularExpression(
            '/request\(\)->get\(\s*[\'"]keyword[\'"]\s*\)/',
            $src,
            'mount MUST read keyword from request() so deep-links to a '
            . 'pre-populated search work consistently.'
        );

        $this->assertMatchesRegularExpression(
            '/urldecode\(\$hash\)/',
            $src,
            'mount MUST urldecode the keyword param so URL-encoded characters '
            . 'reach strip_tags / mb_substr in their decoded form (otherwise '
            . 'a percent-encoded `<svg>` payload would skip the strip_tags '
            . 'pass).'
        );
    }

    #[Test]
    public function search_constrains_to_content_field_set(): void
    {
        $src = $this->read('Modules/Search/Livewire/SearchComponent.php');

        // search_in_fields is hard-coded so the user cannot force a search
        // against arbitrary columns (no column injection via query params).
        $this->assertMatchesRegularExpression(
            "/'search_in_fields'\s*=>\s*'title,content,description'/",
            $src,
            'search_in_fields MUST be hard-coded to title,content,description '
            . 'so the search cannot be redirected to other (potentially '
            . 'sensitive) columns via user input.'
        );
    }
}
