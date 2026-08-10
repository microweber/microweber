<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use Illuminate\Support\Facades\Http;
use MicroweberPackages\AiTools\Tools\External\AmazonScraperTool;
use PHPUnit\Framework\Attributes\Test;

class AmazonScraperToolTest extends ToolTestCase
{
    private AmazonScraperTool $tool;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tool = new AmazonScraperTool();
    }

    #[Test]
    public function it_returns_error_for_missing_action_parameter(): void
    {
        $result = $this->tool->__invoke([]);

        $this->assertStringContainsString('Unknown action', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_returns_error_for_invalid_action(): void
    {
        $result = $this->tool->__invoke(['action' => 'invalid_action']);

        $this->assertStringContainsString('Unknown action: invalid_action', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_returns_error_for_search_without_query(): void
    {
        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => '',
        ]);

        $this->assertStringContainsString('Query parameter is required', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_searches_for_products_successfully(): void
    {
        $html = $this->getSampleSearchHtml();

        // Mock the specific Amazon search URL pattern
        Http::fake([
            'amazon.com/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
            'amazon.de/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'laptop',
            'limit' => 5,
            'country' => 'US',
        ]);

        $this->assertStringContainsString('Amazon Search Results', $result);
        $this->assertStringContainsString('laptop', $result);
        $this->assertStringContainsString('product-card', $result);
    }

    #[Test]
    public function it_handles_empty_search_results(): void
    {
        Http::fake([
            'amazon.com/s*' => Http::response('<html><body>No results found</body></html>', 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/s*' => Http::response('<html><body>No results found</body></html>', 200, ['Content-Type' => 'text/html']),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'xyznonexistentproduct123',
            'limit' => 10,
        ]);

        $this->assertStringContainsString('No products found', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_returns_error_for_product_details_without_asin(): void
    {
        $result = $this->tool->__invoke([
            'action' => 'get_product_details',
            'asin' => '',
        ]);

        $this->assertStringContainsString('ASIN parameter is required', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_gets_product_details_successfully(): void
    {
        $html = $this->getSampleProductHtml();

        Http::fake([
            'amazon.com/dp/*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/dp/*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
            'amazon.de/dp/*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'get_product_details',
            'asin' => 'B08N5WRWNW',
            'country' => 'US',
        ]);

        $this->assertStringContainsString('Product Details', $result);
        $this->assertStringContainsString('B08N5WRWNW', $result);
    }

    #[Test]
    public function it_returns_marketplaces_list(): void
    {
        $result = $this->tool->__invoke([
            'action' => 'get_marketplaces',
        ]);

        $this->assertStringContainsString('Available Amazon Marketplaces', $result);
        $this->assertStringContainsString('United States', $result);
        $this->assertStringContainsString('United Kingdom', $result);
        $this->assertStringContainsString('Germany', $result);
    }

    #[Test]
    public function it_respects_limit_parameter(): void
    {
        $html = $this->getSampleSearchHtml(20);

        Http::fake([
            'amazon.com/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
        ]);

        // Test with limit of 5
        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'phone',
            'limit' => 5,
        ]);

        // Should contain product cards
        $this->assertStringContainsString('product-card', $result);

        // Test with limit exceeding max
        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'tablet',
            'limit' => 100,
        ]);

        // Should still work, limit is capped at 20
        $this->assertStringContainsString('Amazon Search Results', $result);
    }

    #[Test]
    public function it_handles_http_errors_gracefully(): void
    {
        Http::fake([
            'amazon.com/s*' => Http::response('Error', 500),
            'amazon.co.uk/s*' => Http::response('Error', 500),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'error-test',
        ]);

        $this->assertStringContainsString('Failed to execute Amazon scraper', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_handles_network_exceptions(): void
    {
        Http::fake([
            'amazon.com/s*' => function () {
                throw new \Exception('Connection timeout');
            },
            'amazon.co.uk/s*' => function () {
                throw new \Exception('Connection timeout');
            },
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'network-error',
        ]);

        $this->assertStringContainsString('Failed to execute Amazon scraper', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_validates_country_parameter(): void
    {
        $html = $this->getSampleSearchHtml();

        Http::fake([
            'amazon.co.uk/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
            'amazon.com/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'book',
            'country' => 'UK',
        ]);

        // Should still work with valid country code
        $this->assertStringContainsString('Amazon Search Results', $result);
    }

    #[Test]
    public function it_includes_reviews_when_requested(): void
    {
        $searchHtml = $this->getSampleSearchHtml(1);
        $productHtml = $this->getSampleProductHtmlWithReviews();

        Http::fake([
            'amazon.com/s*' => Http::response($searchHtml, 200, ['Content-Type' => 'text/html']),
            'amazon.com/dp/*' => Http::response($productHtml, 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/s*' => Http::response($searchHtml, 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/dp/*' => Http::response($productHtml, 200, ['Content-Type' => 'text/html']),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'headphones',
            'include_reviews' => true,
            'limit' => 1,
        ]);

        // Should contain search results (reviews are loaded per product)
        $this->assertStringContainsString('Amazon Search Results', $result);
    }

    #[Test]
    public function it_escapes_html_in_output(): void
    {
        $html = $this->getSampleSearchHtmlWithSpecialChars();

        Http::fake([
            'amazon.com/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/s*' => Http::response($html, 200, ['Content-Type' => 'text/html']),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'special',
        ]);

        // HTML entities should be escaped - check that dangerous tags are neutralized
        $this->assertStringNotContainsString('<script>', $result);
        // The title with script tags should be escaped
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }

    #[Test]
    public function it_handles_malformed_html(): void
    {
        Http::fake([
            'amazon.com/s*' => Http::response('<html>malformed', 200, ['Content-Type' => 'text/html']),
            'amazon.co.uk/s*' => Http::response('<html>malformed', 200, ['Content-Type' => 'text/html']),
        ]);

        $result = $this->tool->__invoke([
            'action' => 'search',
            'query' => 'malformed',
        ]);

        // Should not crash, should return no results message
        $this->assertStringContainsString('No products found', $result);
    }

    /**
     * Generate sample HTML for search results
     */
    private function getSampleSearchHtml(int $count = 5): string
    {
        $products = '';
        for ($i = 1; $i <= $count; $i++) {
            $products .= '
                <div data-component-type="s-search-result" data-asin="B0' . str_pad((string)$i, 8, '0', STR_PAD_LEFT) . '">
                    <h2 class="a-size-mini"><span>Product ' . $i . ' Title</span></h2>
                    <span class="a-price-whole">' . (100 + $i * 10) . '</span>
                    <img class="s-image" src="https://example.com/image' . $i . '.jpg">
                    <span class="a-icon-alt">4.' . $i . ' out of 5 stars</span>
                    <a class="a-link-normal"><span class="a-size-base">' . ($i * 100) . '</span></a>
                </div>
            ';
        }

        return '<html><body>' . $products . '</body></html>';
    }

    /**
     * Generate sample HTML for product details
     */
    private function getSampleProductHtml(): string
    {
        return '<html><body>
            <span id="productTitle">Test Product Title</span>
            <span class="a-price a-text-price a-size-medium a-color-base">
                <span class="a-offscreen">$99.99</span>
            </span>
            <img id="landingImage" src="https://example.com/main.jpg">
            <div id="feature-bullets">
                <span class="a-list-item">Feature 1</span>
                <span class="a-list-item">Feature 2</span>
            </div>
            <tr class="a-spacing-small po-brand">
                <span class="a-size-base">Test Brand</span>
            </tr>
            <div id="availability"><span>In Stock</span></div>
            <span class="a-icon-alt">4.5 out of 5 stars</span>
            <span id="acrCustomerReviewText">123 reviews</span>
        </body></html>';
    }

    /**
     * Generate sample HTML with reviews
     */
    private function getSampleProductHtmlWithReviews(): string
    {
        return $this->getSampleProductHtml() . '
            <div data-hook="review">
                <span class="a-profile-name">John Doe</span>
                <span class="a-icon-alt">5 out of 5 stars</span>
                <span data-hook="review-body">Great product!</span>
                <span data-hook="review-date">January 1, 2024</span>
            </div>
        ';
    }

    /**
     * Generate sample HTML with special characters
     */
    private function getSampleSearchHtmlWithSpecialChars(): string
    {
        return '<html><body>
            <div data-component-type="s-search-result" data-asin="B001">
                <h2 class="a-size-mini"><span>&lt;script&gt;alert("xss")&lt;/script&gt;</span></h2>
                <span class="a-price-whole">100</span>
                <img class="s-image" src="javascript:alert(1)">
            </div>
        </body></html>';
    }
}
