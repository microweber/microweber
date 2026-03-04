<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;

class AmazonScraperService
{
    protected $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ];

    protected $marketplaceConfig = [
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
     * Search for products on Amazon
     */
    public function searchProducts($searchQuery, $options = [])
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
    public function getProductDetails($asin, $country = 'US')
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
    protected function buildSearchUrl($searchQuery, $config)
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
    protected function fetchPage($url, $config)
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
    protected function parseSearchResults($html, $config)
    {
        $products = [];

        // Handle empty or invalid HTML
        if (empty($html) || !is_string($html)) {
            return $products;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Find product containers
        $productNodes = $xpath->query('//div[@data-component-type="s-search-result"]');

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
    protected function extractProductFromNode($node, $xpath, $config)
    {
        // Get ASIN
        $asin = $node->getAttribute('data-asin');
        if (empty($asin)) {
            return null;
        }

        // Get title
        $titleNode = $xpath->query('.//h2[@class="a-size-mini"]//span', $node)->item(0);
        $title = $titleNode ? trim($titleNode->textContent) : '';

        // Get price
        $price = $this->extractPrice($node, $xpath);

        // Get image
        $imageNode = $xpath->query('.//img[@class="s-image"]', $node)->item(0);
        $image = $imageNode ? $imageNode->getAttribute('src') : '';
        $image = $image ?: '';
        $image = html_entity_decode($image);

        // Get rating
        $ratingNode = $xpath->query('.//span[@class="a-icon-alt"]', $node)->item(0);
        $rating = $ratingNode ? $this->extractRating($ratingNode->textContent) : null;

        // Get review count
        $reviewNode = $xpath->query('.//a[@class="a-link-normal"]//span[@class="a-size-base"]', $node)->item(0);
        $reviewCount = $reviewNode ? $this->extractNumber($reviewNode->textContent) : 0;

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
     * Parse detailed product information
     */
    protected function parseProductDetails($html, $asin, $config)
    {
        // Handle empty or invalid HTML
        if (empty($html) || !is_string($html)) {
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

        // Get title
        $titleNode = $xpath->query('//span[@id="productTitle"]')->item(0);
        $details['title'] = $titleNode ? trim($titleNode->textContent) : '';

        // Get price
        $details['price'] = $this->extractDetailedPrice($xpath);

        // Get images
        $details['images'] = $this->extractImages($xpath);

        // Get description
        $details['description'] = $this->extractDescription($xpath);

        // Get brand
        $brandNode = $xpath->query('//tr[@class="a-spacing-small po-brand"]//span[@class="a-size-base"]')->item(0);
        $details['brand'] = $brandNode ? trim($brandNode->textContent) : '';

        // Get availability
        $availNode = $xpath->query('//div[@id="availability"]//span')->item(0);
        $details['availability'] = $availNode ? trim($availNode->textContent) : '';

        // Get rating and reviews
        $ratingNode = $xpath->query('//span[@class="a-icon-alt"]')->item(0);
        $details['rating'] = $ratingNode ? $this->extractRating($ratingNode->textContent) : null;

        $reviewNode = $xpath->query('//span[@id="acrCustomerReviewText"]')->item(0);
        $details['review_count'] = $reviewNode ? $this->extractNumber($reviewNode->textContent) : 0;

        // Get reviews if available
        $details['reviews'] = $this->extractReviews($xpath);

        return $details;
    }

    /**
     * Extract price from search results
     */
    protected function extractPrice($node, $xpath)
    {
        // Try different price selectors
        $priceSelectors = [
            './/span[@class="a-price-whole"]',
            './/span[@class="a-offscreen"]',
            './/span[@class="a-price"]//span[@class="a-offscreen"]'
        ];

        foreach ($priceSelectors as $selector) {
            $priceNode = $xpath->query($selector, $node)->item(0);
            if ($priceNode) {
                $priceText = $priceNode->textContent;
                return $this->cleanPrice($priceText);
            }
        }

        return null;
    }

    /**
     * Extract detailed price information
     */
    protected function extractDetailedPrice($xpath)
    {
        $priceSelectors = [
            '//span[@class="a-price a-text-price a-size-medium a-color-base"]//span[@class="a-offscreen"]',
            '//span[@id="priceblock_dealprice"]',
            '//span[@id="priceblock_ourprice"]',
            '//span[@class="a-price-whole"]'
        ];

        foreach ($priceSelectors as $selector) {
            $priceNode = $xpath->query($selector)->item(0);
            if ($priceNode) {
                return $this->cleanPrice($priceNode->textContent);
            }
        }

        return null;
    }

    /**
     * Extract product images
     */
    protected function extractImages($xpath)
    {
        $images = [];

        // Main product image
        $mainImageNode = $xpath->query('//img[@id="landingImage"]')->item(0);
        if ($mainImageNode) {
            $images[] = $mainImageNode->getAttribute('src');
        }

        // Additional images
        $imageNodes = $xpath->query('//div[@id="altImages"]//img');
        foreach ($imageNodes as $imageNode) {
            $src = $imageNode->getAttribute('src');
            if ($src && !in_array($src, $images)) {
                $images[] = $src;
            }
        }

        return $images;
    }

    /**
     * Extract product description
     */
    protected function extractDescription($xpath)
    {
        $descSelectors = [
            '//div[@id="feature-bullets"]//span[@class="a-list-item"]',
            '//div[@id="productDescription"]//p',
            '//div[@class="a-section a-spacing-medium"]//span'
        ];

        foreach ($descSelectors as $selector) {
            $descNodes = $xpath->query($selector);
            if ($descNodes->length > 0) {
                $description = '';
                foreach ($descNodes as $node) {
                    $text = trim($node->textContent);
                    if (!empty($text) && !str_contains($text, 'Important information')) {
                        $description .= $text . "\n";
                    }
                }
                return trim($description);
            }
        }

        return '';
    }

    /**
     * Extract customer reviews
     */
    protected function extractReviews($xpath)
    {
        $reviews = [];

        $reviewNodes = $xpath->query('//div[@data-hook="review"]');
        foreach ($reviewNodes as $reviewNode) {
            $author = $xpath->query('.//span[@class="a-profile-name"]', $reviewNode)->item(0);
            $rating = $xpath->query('.//span[@class="a-icon-alt"]', $reviewNode)->item(0);
            $text = $xpath->query('.//span[@data-hook="review-body"]', $reviewNode)->item(0);
            $date = $xpath->query('.//span[@data-hook="review-date"]', $reviewNode)->item(0);

            if ($author && $text) {
                $reviews[] = [
                    'author' => trim($author->textContent),
                    'rating' => $rating ? $this->extractRating($rating->textContent) : null,
                    'text' => trim($text->textContent),
                    'date' => $date ? trim($date->textContent) : ''
                ];
            }
        }

        return array_slice($reviews, 0, 5); // Limit to 5 reviews
    }

    /**
     * Clean and parse price text
     */
    protected function cleanPrice($priceText)
    {
        if (empty($priceText)) {
            return null;
        }

        // Remove currency symbols and extract numeric value
        $cleaned = preg_replace('/[^\d.,]/', '', $priceText);
        $cleaned = str_replace(',', '', $cleaned);

        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    /**
     * Extract rating from text
     */
    protected function extractRating($text)
    {
        if (preg_match('/(\d+\.?\d*)\s*out\s*of\s*5/i', $text, $matches)) {
            return (float) $matches[1];
        }
        return null;
    }

    /**
     * Extract number from text
     */
    protected function extractNumber($text)
    {
        $cleaned = preg_replace('/[^\d]/', '', $text);
        return is_numeric($cleaned) ? (int) $cleaned : 0;
    }

    /**
     * Get random user agent
     */
    protected function getRandomUserAgent()
    {
        return $this->userAgents[array_rand($this->userAgents)];
    }

    /**
     * Get available marketplaces
     */
    public function getAvailableMarketplaces()
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
