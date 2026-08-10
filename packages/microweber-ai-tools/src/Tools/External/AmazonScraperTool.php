<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tools\External;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\AiTools\Services\AmazonScraperService;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Amazon Scraper Tool
 *
 * Scrapes product information from Amazon including prices, images,
 * reviews, and product details.
 */
class AmazonScraperTool extends BaseTool
{
    protected AmazonScraperService $amazonScraperService;
    protected string $domain = 'content';
    protected array $requiredPermissions = ['view content'];

    /**
     * @param array<string, mixed> $dependencies
     */
    public function __construct(array $dependencies = [])
    {
        parent::__construct(
            'amazon_scraper',
            'Search and scrape product information from Amazon including prices, images, reviews, and product details. Can search for products or get detailed information about specific products.',
            $dependencies
        );
        $this->amazonScraperService = new AmazonScraperService();
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'action',
                type: PropertyType::STRING,
                description: 'Action to perform: search for products, get detailed product info by ASIN, or get available marketplaces',
                required: true,
            ),
            new ToolProperty(
                name: 'query',
                type: PropertyType::STRING,
                description: 'Search query for products (required for search action)',
                required: false,
            ),
            new ToolProperty(
                name: 'asin',
                type: PropertyType::STRING,
                description: 'Amazon ASIN product identifier (required for get_product_details action)',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Number of products to return (for search action, 1-20, default: 10)',
                required: false,
            ),
            new ToolProperty(
                name: 'country',
                type: PropertyType::STRING,
                description: 'Amazon marketplace country (US, UK, DE, FR, CA, default: US)',
                required: false,
            ),
            new ToolProperty(
                name: 'include_reviews',
                type: PropertyType::BOOLEAN,
                description: 'Whether to include customer reviews in search results (may slow down the search)',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to access Amazon scraper.');
        }

        // Extract parameters - args could be passed as an associative array
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $action = $params['action'] ?? '';
        $query = $params['query'] ?? '';
        $asin = $params['asin'] ?? '';
        $limit = max(1, min(20, (int)($params['limit'] ?? 10)));
        $country = $params['country'] ?? 'US';
        $includeReviews = $params['include_reviews'] ?? false;

        try {
            switch ($action) {
                case 'search':
                    return $this->handleSearch($query, $limit, $country, $includeReviews);

                case 'get_product_details':
                    return $this->handleProductDetails($asin, $country);

                case 'get_marketplaces':
                    return $this->handleMarketplaces();

                default:
                    return $this->handleError("Unknown action: {$action}. Available actions: search, get_product_details, get_marketplaces");
            }

        } catch (\Exception $e) {
            return $this->handleError('Failed to execute Amazon scraper: ' . $e->getMessage());
        }
    }

    protected function handleSearch(string $query, int $limit, string $country, bool $includeReviews): string
    {
        if (empty($query)) {
            return $this->handleError('Query parameter is required for search action');
        }

        $options = [
            'limit' => $limit,
            'country' => $country,
            'include_reviews' => $includeReviews
        ];

        $products = $this->amazonScraperService->searchProducts($query, $options);

        return $this->formatSearchResults($products, $query, $country);
    }

    protected function handleProductDetails(string $asin, string $country): string
    {
        if (empty($asin)) {
            return $this->handleError('ASIN parameter is required for get_product_details action');
        }

        $details = $this->amazonScraperService->getProductDetails($asin, $country);

        return $this->formatProductDetails($details, $asin, $country);
    }

    protected function handleMarketplaces(): string
    {
        $marketplaces = $this->amazonScraperService->getAvailableMarketplaces();

        return $this->formatMarketplaces($marketplaces);
    }

    /**
     * @param array<int|string, mixed> $products
     */
    protected function formatSearchResults(array $products, string $query, string $country): string
    {
        if (empty($products)) {
            return $this->handleError("No products found for '{$query}' on Amazon {$country}");
        }

        $totalFound = count($products);

        $header = "
<div class='amazon-search-header mb-4'>
<h4><i class='fab fa-amazon text-warning me-2'></i>Amazon Search Results</h4>
<div class='row'>
<div class='col-md-6'>
<p class='mb-1'><strong>Query:</strong> {$query}</p>
<p class='mb-1'><strong>Marketplace:</strong> Amazon {$country}</p>
</div>
<div class='col-md-6'>
<p class='mb-1'><strong>Products Found:</strong> {$totalFound}</p>
<p class='mb-1'><strong>Updated:</strong> " . now()->format('M j, Y H:i') . "</p>
</div>
</div>
</div>";

        $cards = "<div class='row'>";

        foreach ($products as $index => $product) {
            $rank = (int) $index + 1;
            $title = htmlspecialchars($product['title'] ?? 'Unknown Product');
            $price = $product['price'] ? number_format($product['price'], 2) . ' ' . ($product['currency'] ?? 'USD') : 'Price not available';
            $rating = $product['rating'] ? number_format($product['rating'], 1) . '/5' : 'No rating';
            $reviewCount = number_format($product['review_count'] ?? 0);
            $asin = $product['asin'] ?? '';
            $image = $product['image'] ?? '';
            $url = $product['url'] ?? '';

            $imageHtml = $image ? "<img src='" . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . "' class='card-img-top product-image' style='height: 200px; object-fit: contain;' alt='Product image'>" : '';

            $cards .= "
<div class='col-md-6 col-lg-4 mb-3'>
<div class='card h-100 product-card'>
{$imageHtml}
<div class='card-header d-flex justify-content-between'>
<small class='text-muted'>#{$rank}</small>
<span class='badge bg-warning'>ASIN: {$asin}</span>
</div>
<div class='card-body'>
<h6 class='card-title'>" . substr($title, 0, 80) . (strlen($title) > 80 ? '...' : '') . "</h6>
<div class='product-stats'>
<p class='mb-1'><strong>Price:</strong> {$price}</p>
<p class='mb-1'><strong>Rating:</strong> {$rating} ({$reviewCount} reviews)</p>
" . ($url ? "<p class='mb-0'><a href='" . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . "' target='_blank' class='btn btn-sm btn-outline-primary'>View on Amazon</a></p>" : '') . "
</div>
</div>
</div>
</div>";
        }

        $cards .= "</div>";

        return $header . $cards;
    }

    /**
     * @param array<int|string, mixed> $details
     */
    protected function formatProductDetails(array $details, string $asin, string $country): string
    {
        $title = htmlspecialchars($details['title'] ?? 'Unknown Product');
        $price = $details['price'] ? number_format($details['price'], 2) . ' USD' : 'Price not available';
        $brand = htmlspecialchars($details['brand'] ?? 'Unknown Brand');
        $availability = htmlspecialchars($details['availability'] ?? 'Unknown');
        $rating = $details['rating'] ? number_format($details['rating'], 1) . '/5' : 'No rating';
        $reviewCount = number_format($details['review_count'] ?? 0);
        $description = htmlspecialchars($details['description'] ?? 'No description available');
        $images = $details['images'] ?? [];
        $reviews = $details['reviews'] ?? [];

        $header = "
<div class='amazon-product-header mb-4'>
<h4><i class='fab fa-amazon text-warning me-2'></i>Product Details</h4>
<div class='row'>
<div class='col-md-6'>
<p class='mb-1'><strong>ASIN:</strong> {$asin}</p>
<p class='mb-1'><strong>Marketplace:</strong> Amazon {$country}</p>
</div>
<div class='col-md-6'>
<p class='mb-1'><strong>Brand:</strong> {$brand}</p>
<p class='mb-1'><strong>Availability:</strong> {$availability}</p>
</div>
</div>
</div>";

        $productInfo = "
<div class='card mb-4'>
<div class='card-body'>
<h5 class='card-title'>{$title}</h5>
<div class='row'>
<div class='col-md-8'>
<p><strong>Price:</strong> {$price}</p>
<p><strong>Rating:</strong> {$rating} ({$reviewCount} reviews)</p>
<p><strong>Description:</strong></p>
<p class='text-muted'>" . substr($description, 0, 500) . (strlen($description) > 500 ? '...' : '') . "</p>
</div>
</div>
</div>
</div>";

        $imagesHtml = '';
        if (!empty($images)) {
            $imagesHtml = "
<div class='card mb-4'>
<div class='card-header'>
<h6>Product Images</h6>
</div>
<div class='card-body'>
<div class='row'>";

            foreach (array_slice($images, 0, 6) as $image) {
                $imagesHtml .= "
<div class='col-md-4 mb-2'>
<img src='{$image}' class='img-fluid rounded' style='max-height: 200px; object-fit: contain;' alt='Product image'>
</div>";
            }

            $imagesHtml .= "</div></div></div>";
        }

        $reviewsHtml = '';
        if (!empty($reviews)) {
            $reviewsHtml = "
<div class='card mb-4'>
<div class='card-header'>
<h6>Customer Reviews</h6>
</div>
<div class='card-body'>";

            foreach (array_slice($reviews, 0, 3) as $review) {
                $author = htmlspecialchars($review['author'] ?? 'Anonymous');
                $reviewRating = $review['rating'] ? number_format($review['rating'], 1) . '/5' : 'No rating';
                $reviewText = htmlspecialchars(substr($review['text'] ?? '', 0, 300));
                $reviewDate = htmlspecialchars($review['date'] ?? '');

                $reviewsHtml .= "
<div class='border-bottom pb-3 mb-3'>
<div class='d-flex justify-content-between'>
<strong>{$author}</strong>
<span class='badge bg-primary'>{$reviewRating}</span>
</div>
<small class='text-muted'>{$reviewDate}</small>
<p class='mt-2'>{$reviewText}" . (strlen($review['text'] ?? '') > 300 ? '...' : '') . "</p>
</div>";
            }

            $reviewsHtml .= "</div></div>";
        }

        return $header . $productInfo . $imagesHtml . $reviewsHtml;
    }

    /**
     * @param array<int|string, mixed> $marketplaces
     */
    protected function formatMarketplaces(array $marketplaces): string
    {
        $header = "
<div class='amazon-marketplaces-header mb-4'>
<h4><i class='fab fa-amazon text-warning me-2'></i>Available Amazon Marketplaces</h4>
<p class='text-muted'>Choose from the following Amazon marketplaces for product searches:</p>
</div>";

        $cards = "<div class='row'>";

        foreach ($marketplaces as $marketplace) {
            $code = $marketplace['code'];
            $name = htmlspecialchars($marketplace['name']);
            $currency = $marketplace['currency'];

            $cards .= "
<div class='col-md-6 col-lg-4 mb-3'>
<div class='card marketplace-card'>
<div class='card-body text-center'>
<h6 class='card-title'>{$name}</h6>
<p class='card-text'>
<strong>Code:</strong> {$code}<br>
<strong>Currency:</strong> {$currency}
</p>
</div>
</div>
</div>";
        }

        $cards .= "</div>";

        return $header . $cards;
    }
}
