<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;

/**
 * Amazon Product Scraper Service
 *
 * Scrapes product information from Amazon marketplaces including prices,
 * images, reviews, and product details.
 */
class AmazonScraperService
{
    /** @var list<string> */
    protected array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ];

    /** @var array<string, array{host: string, country: string, currency: string, language: string}> */
    protected array $marketplaceConfig = [
        'US' => [
            'host' => 'amazon.com',
            'country' => 'United States',
            'currency' => 'USD',
            'language' => 'en-US'
        ],
        'UK' => [
            'host' => 'amazon.co.uk',
            'country' => 'United Kingdom',
            'currency' => 'GBP',
            'language' => 'en-GB'
        ],
        'DE' => [
            'host' => 'amazon.de',
            'country' => 'Germany',
            'currency' => 'EUR',
            'language' => 'de-DE'
        ],
        'FR' => [
            'host' => 'amazon.fr',
            'country' => 'France',
            'currency' => 'EUR',
            'language' => 'fr-FR'
        ],
        'CA' => [
            'host' => 'amazon.ca',
            'country' => 'Canada',
            'currency' => 'CAD',
            'language' => 'en-CA'
        ]
    ];


    /**
     * @return list<\DOMNode>
     */
    private function queryNodes(\DOMXPath $xpath, string $expression, ?\DOMNode $context = null): array
    {
        $list = $context === null
            ? $xpath->query($expression)
            : $xpath->query($expression, $context);

        if (!$list instanceof \DOMNodeList) {
            return [];
        }

        $nodes = [];
        foreach ($list as $node) {
            if ($node instanceof \DOMNode) {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    private function queryFirst(\DOMXPath $xpath, string $expression, ?\DOMNode $context = null): ?\DOMNode
    {
        $nodes = $this->queryNodes($xpath, $expression, $context);

        return $nodes[0] ?? null;
    }

    private function nodeText(?\DOMNode $node): string
    {
        return $node !== null ? trim((string) $node->textContent) : '';
    }

    private function nodeAttr(?\DOMNode $node, string $attribute): string
    {
        if ($node instanceof \DOMElement) {
            return (string) $node->getAttribute($attribute);
        }

        return '';
    }

    /**
     * Search for products on Amazon
     */
    /**
     * @param array<string, mixed> $options
     * @return list<array<string, mixed>>
     */
    public function searchProducts(string $searchQuery, array $options = []): array
    {
        $defaults = [
            'limit' => 10,
            'country' => 'US',
            'include_reviews' => false
        ];

        $options = array_merge($defaults, $options);
        $config = $this->marketplaceConfig[$options['country']] ?? $this->marketplaceConfig['US'];

        try {
            $url = $this->buildSearchUrl($searchQuery, $config);
            $html = $this->fetchPage($url, $config);
            $products = $this->parseSearchResults($html, $config);

            // Limit results
            $products = array_slice($products, 0, $options['limit']);

            // Get detailed info including reviews if requested
            if ($options['include_reviews']) {
                foreach ($products as &$product) {
                    if (!empty($product['asin'])) {
                        $details = $this->getProductDetails($product['asin'], $options['country']);
                        $product = array_merge($product, $details);
                    }
                }
            }

            return $products;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get detailed product information by ASIN
     */
    /**
     * @return array<string, mixed>
     */
    public function getProductDetails(string $asin, string $country = 'US'): array
    {
        $config = $this->marketplaceConfig[$country] ?? $this->marketplaceConfig['US'];

        try {
            $url = "https://www.{$config['host']}/dp/{$asin}";
            $html = $this->fetchPage($url, $config);

            return $this->parseProductDetails($html, $asin, $config);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Build search URL
     */
    /**
     * @param array<string, string> $config
     */
    protected function buildSearchUrl(string $searchQuery, array $config): string
    {
        $params = [
            'k' => $searchQuery,
            'ref' => 'sr_pg_1'
        ];

        return "https://www.{$config['host']}/s?" . http_build_query($params);
    }

    /**
     * Fetch page content
     */
    /**
     * @param array<string, string> $config
     */
    protected function fetchPage(string $url, array $config): string
    {
        $response = Http::withHeaders([
            'User-Agent' => $this->getRandomUserAgent(),
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => $config['language'] . ',en;q=0.5',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
            'Upgrade-Insecure-Requests' => '1'
        ])
        ->timeout(30)
        ->get($url);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch page: HTTP ' . $response->status());
        }

        $body = $response->body();

        if (empty($body)) {
            throw new \Exception('Failed to fetch page: Empty response body');
        }

        return $body;
    }

    /**
     * Parse search results from HTML
     */
    /**
     * @param array<string, string> $config
     * @return list<array<string, mixed>>
     */
    protected function parseSearchResults(string $html, array $config): array
    {
        $products = [];

        // Handle empty or invalid HTML
        if (empty($html)) {
            return $products;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Find product containers
        $productNodes = $this->queryNodes($xpath, '//div[@data-component-type="s-search-result"]');

        foreach ($productNodes as $node) {
            try {
                $product = $this->extractProductFromNode($node, $xpath, $config);
                if ($product) {
                    $products[] = $product;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $products;
    }

    /**
     * Extract product data from search result node
     */
    /**
     * @param array<string, string> $config
     * @return array<string, mixed>|null
     */
    protected function extractProductFromNode(\DOMNode $node, DOMXPath $xpath, array $config): ?array
    {
        // Get ASIN
        $asin = $this->nodeAttr($node, 'data-asin');
        if ($asin === '') {
            return null;
        }

        // Get title
        $title = $this->nodeText($this->queryFirst($xpath, './/h2[@class="a-size-mini"]//span', $node));

        // Get price
        $price = $this->extractPrice($node, $xpath);

        // Get image
        $imageNode = $this->queryFirst($xpath, './/img[@class="s-image"]', $node);
        $image = html_entity_decode($this->nodeAttr($imageNode, 'src'));

        // Get rating
        $ratingNode = $this->queryFirst($xpath, './/span[@class="a-icon-alt"]', $node);
        $rating = $ratingNode ? $this->extractRating($this->nodeText($ratingNode)) : null;

        // Get review count
        $reviewNode = $this->queryFirst($xpath, './/a[@class="a-link-normal"]//span[@class="a-size-base"]', $node);
        $reviewCount = $reviewNode ? $this->extractNumber($this->nodeText($reviewNode)) : 0;

        // Build product URL
        $url = "https://www.{$config['host']}/dp/{$asin}";

        return [
            'asin' => $asin,
            'title' => $title,
            'price' => $price,
            'currency' => $config['currency'],
            'image' => $image,
            'rating' => $rating,
            'review_count' => $reviewCount,
            'url' => $url,
            'marketplace' => $config['country']
        ];
    }

    /**
     * Parse detailed product information.
     *
     * @param array<string, string> $config
     * @return array<string, mixed>
     */
    protected function parseProductDetails(string $html, string $asin, array $config): array
    {
        // Handle empty or invalid HTML
        if ($html === '') {
            return [
                'title' => '',
                'price' => null,
                'images' => [],
                'description' => '',
                'brand' => '',
                'availability' => '',
                'rating' => null,
                'review_count' => 0,
                'reviews' => [],
            ];
        }

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $details = [];

        $details['title'] = $this->nodeText($this->queryFirst($xpath, '//span[@id="productTitle"]'));
        $details['price'] = $this->extractDetailedPrice($xpath);
        $details['images'] = $this->extractImages($xpath);
        $details['description'] = $this->extractDescription($xpath);
        $details['brand'] = $this->nodeText(
            $this->queryFirst($xpath, '//tr[@class="a-spacing-small po-brand"]//span[@class="a-size-base"]')
        );
        $details['availability'] = $this->nodeText($this->queryFirst($xpath, '//div[@id="availability"]//span'));

        $ratingNode = $this->queryFirst($xpath, '//span[@class="a-icon-alt"]');
        $details['rating'] = $ratingNode !== null ? $this->extractRating($this->nodeText($ratingNode)) : null;

        $reviewNode = $this->queryFirst($xpath, '//span[@id="acrCustomerReviewText"]');
        $details['review_count'] = $reviewNode !== null ? $this->extractNumber($this->nodeText($reviewNode)) : 0;

        $details['reviews'] = $this->extractReviews($xpath);

        return $details;
    }

    /**
     * Extract price from search results
     */
    /**
     * @return float|null
     */
    protected function extractPrice(\DOMNode $node, DOMXPath $xpath): ?float
    {
        // Try different price selectors
        $priceSelectors = [
            './/span[@class="a-price-whole"]',
            './/span[@class="a-offscreen"]',
            './/span[@class="a-price"]//span[@class="a-offscreen"]'
        ];

        foreach ($priceSelectors as $selector) {
            $priceNode = $this->queryFirst($xpath, $selector, $node);
            if ($priceNode !== null) {
                return $this->cleanPrice($this->nodeText($priceNode));
            }
        }

        return null;
    }

    /**
     * Extract detailed price information
     */
    protected function extractDetailedPrice(DOMXPath $xpath): ?float
    {
        $priceSelectors = [
            '//span[@class="a-price a-text-price a-size-medium a-color-base"]//span[@class="a-offscreen"]',
            '//span[@id="priceblock_dealprice"]',
            '//span[@id="priceblock_ourprice"]',
            '//span[@class="a-price-whole"]',
        ];

        foreach ($priceSelectors as $selector) {
            $priceNode = $this->queryFirst($xpath, $selector);
            if ($priceNode !== null) {
                return $this->cleanPrice($this->nodeText($priceNode));
            }
        }

        return null;
    }

    /**
     * Extract product images.
     *
     * @return list<string>
     */
    protected function extractImages(DOMXPath $xpath): array
    {
        $images = [];

        $mainSrc = $this->nodeAttr($this->queryFirst($xpath, '//img[@id="landingImage"]'), 'src');
        if ($mainSrc !== '') {
            $images[] = $mainSrc;
        }

        foreach ($this->queryNodes($xpath, '//div[@id="altImages"]//img') as $imageNode) {
            $src = $this->nodeAttr($imageNode, 'src');
            if ($src !== '' && !in_array($src, $images, true)) {
                $images[] = $src;
            }
        }

        return $images;
    }

    /**
     * Extract product description.
     */
    protected function extractDescription(DOMXPath $xpath): string
    {
        $descSelectors = [
            '//div[@id="feature-bullets"]//span[@class="a-list-item"]',
            '//div[@id="productDescription"]//p',
            '//div[@class="a-section a-spacing-medium"]//span',
        ];

        foreach ($descSelectors as $selector) {
            $descNodes = $this->queryNodes($xpath, $selector);
            if ($descNodes === []) {
                continue;
            }

            $description = '';
            foreach ($descNodes as $node) {
                $text = $this->nodeText($node);
                if ($text !== '' && !str_contains($text, 'Important information')) {
                    $description .= $text . "\n";
                }
            }

            $description = trim($description);
            if ($description !== '') {
                return $description;
            }
        }

        return '';
    }

    /**
     * Extract customer reviews.
     *
     * @return list<array{author: string, rating: float|null, text: string, date: string}>
     */
    protected function extractReviews(DOMXPath $xpath): array
    {
        $reviews = [];

        foreach ($this->queryNodes($xpath, '//div[@data-hook="review"]') as $reviewNode) {
            $author = $this->queryFirst($xpath, './/span[@class="a-profile-name"]', $reviewNode);
            $rating = $this->queryFirst($xpath, './/span[@class="a-icon-alt"]', $reviewNode);
            $text = $this->queryFirst($xpath, './/span[@data-hook="review-body"]', $reviewNode);
            $date = $this->queryFirst($xpath, './/span[@data-hook="review-date"]', $reviewNode);

            if ($author !== null && $text !== null) {
                $reviews[] = [
                    'author' => $this->nodeText($author),
                    'rating' => $rating !== null ? $this->extractRating($this->nodeText($rating)) : null,
                    'text' => $this->nodeText($text),
                    'date' => $this->nodeText($date),
                ];
            }
        }

        return array_slice($reviews, 0, 5);
    }

    /**
     * Clean and parse price text
     */
    protected function cleanPrice(?string $priceText): ?float
    {
        if (empty($priceText)) {
            return null;
        }

        // Remove currency symbols and extract numeric value
        $cleaned = (string) preg_replace('/[^\d.,]/', '', $priceText);
        $cleaned = str_replace(',', '', $cleaned);

        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    /**
     * Extract rating from text
     */
    protected function extractRating(string $text): ?float
    {
        if (preg_match('/(\d+\.?\d*)\s*out\s*of\s*5/i', $text, $matches)) {
            return (float) $matches[1];
        }
        return null;
    }

    /**
     * Extract number from text
     */
    protected function extractNumber(string $text): int
    {
        $cleaned = (string) preg_replace('/[^\d]/', '', $text);
        return is_numeric($cleaned) ? (int) $cleaned : 0;
    }

    /**
     * Get random user agent
     */
    protected function getRandomUserAgent(): string
    {
        return $this->userAgents[array_rand($this->userAgents)];
    }

    /**
     * Get available marketplaces
     */
    /**
     * @return list<array{code: string, name: string, currency: string}>
     */
    public function getAvailableMarketplaces(): array
    {
        $marketplaces = [];
        foreach ($this->marketplaceConfig as $code => $config) {
            $marketplaces[] = [
                'code' => $code,
                'name' => $config['country'],
                'currency' => $config['currency']
            ];
        }
        return $marketplaces;
    }
}
