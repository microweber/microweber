<?php

declare(strict_types=1);

namespace Modules\Product\Tools;

use Modules\Content\Tools\AbstractContentTool;

use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ProductListTool extends AbstractContentTool
{
    protected string $domain = 'shop';
    protected string $contentType = 'product';
    protected array $requiredPermissions = ['view products'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'product_list',
            'List and filter products in Microweber CMS with advanced filtering by price, stock, custom fields, categories, and attributes.'
        );
    }

    protected function properties(): array
    {
        $baseProperties = $this->getBaseProperties();
        
        // Add product-specific properties
        $productProperties = [
            new ToolProperty(
                name: 'price_min',
                type: PropertyType::STRING,
                description: 'Minimum price filter for products.',
                required: false,
            ),
            new ToolProperty(
                name: 'price_max',
                type: PropertyType::STRING,
                description: 'Maximum price filter for products.',
                required: false,
            ),
            new ToolProperty(
                name: 'in_stock',
                type: PropertyType::STRING,
                description: 'Filter by stock status. Options: "1" for in stock, "0" for out of stock, or "all" for both.',
                required: false,
            ),
        ];
        
        return array_merge($productProperties, $baseProperties);
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $price_min = $args['price_min'] ?? '';
        $price_max = $args['price_max'] ?? '';
        $in_stock = $args['in_stock'] ?? 'all';
        $search_term = $args['search_term'] ?? '';
        $is_active = $args['is_active'] ?? 'all';
        $parent_id = $args['parent_id'] ?? null;
        $category_id = $args['category_id'] ?? null;
        $custom_fields = $args['custom_fields'] ?? '';
        $limit = $args['limit'] ?? 20;
        $sort_by = $args['sort_by'] ?? 'position';

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to list products.');
        }

        // Validate limit
        $limit = max(1, min(100, $limit));

        try {
            $query = $this->buildContentQuery();

            // Apply filters
            $params = [
                'search_term' => $search_term,
                'is_active' => $is_active,
                'parent_id' => $parent_id,
                'category_id' => $category_id,
                'custom_fields' => $custom_fields,
                'sort_by' => $sort_by,
            ];

            $query = $this->applyFilters($query, $params);

            // Apply product-specific filters using the ProductFilter
            $filterParams = [];
            if (!empty($price_min)) {
                $filterParams['priceFrom'] = $price_min;
            }
            if (!empty($price_max)) {
                $filterParams['priceTo'] = $price_max;
            }
            if ($in_stock !== 'all') {
                $filterParams['inStock'] = (int)$in_stock;
            }

            if (!empty($filterParams)) {
                $query->filter($filterParams);
            }

            $products = $query->limit($limit)->get();

            if ($products->isEmpty()) {
                $statusInfo = $is_active !== 'all' ? " with status '" . ($is_active ? 'published' : 'unpublished') . "'" : '';
                $searchInfo = !empty($search_term) ? " matching '{$search_term}'" : '';
                $priceInfo = '';
                if (!empty($price_min) || !empty($price_max)) {
                    $priceInfo = " in price range" . 
                        (!empty($price_min) ? " from €{$price_min}" : '') .
                        (!empty($price_max) ? " to €{$price_max}" : '');
                }
                
                return $this->formatAsHtmlTable(
                    [],
                    ['title' => 'Product', 'price' => 'Price', 'status' => 'Status'],
                    "No products found{$statusInfo}{$searchInfo}{$priceInfo}.",
                    'product-list-empty'
                );
            }

            return $this->formatProductsAsHtml($products, $params, $limit);

        } catch (\Exception $e) {
            return $this->handleError('Error listing products: ' . $e->getMessage());
        }
    }

    protected function formatProductsAsHtml($products, array $params, int $limit): string
    {
        $totalFound = $products->count();
        
        $searchInfo = !empty($params['search_term']) ? "Search: \"{$params['search_term']}\" " : '';
        $statusInfo = isset($params['is_active']) && $params['is_active'] !== 'all' ? 
            "Status: " . ($params['is_active'] ? 'Published' : 'Unpublished') . " " : '';
        
        $header = "
        <div class='product-list-header mb-3'>
            <h4><i class='fas fa-shopping-bag text-primary me-2'></i>Product List</h4>
            <p class='mb-2'>
                {$searchInfo}{$statusInfo}
                <strong>Found:</strong> {$totalFound} product(s)" . 
                ($totalFound >= $limit ? " (showing first {$limit})" : '') . "
            </p>
        </div>";

        $cards = "<div class='row'>";
        
        foreach ($products as $product) {
            $statusBadge = $this->getContentStatusBadge($product->is_active ?? 0);
            
            $title = $product->title ?: 'Untitled Product';
            $excerpt = $product->description ?: 'No description available';
            
            // Get price
            $price = 'Price not set';
            try {
                if (method_exists($product, 'getPrice')) {
                    $priceValue = $product->getPrice();
                    if ($priceValue) {
                        $price = $priceValue;
                    }
                } elseif (isset($product->price) && $product->price > 0) {
                    $price = $product->price;
                }
            } catch (\Exception $e) {
                // Ignore price errors
            }

            // Get SKU
            $sku = '';
            try {
                if (method_exists($product, 'getSku')) {
                    $skuValue = $product->getSku();
                    if ($skuValue) {
                        $sku = "<small class='text-muted'>SKU: {$skuValue}</small><br>";
                    }
                }
            } catch (\Exception $e) {
                // Ignore SKU errors
            }

            // Get stock status
            $stockBadge = '';
            try {
                if (method_exists($product, 'getQty')) {
                    $qty = $product->getQty();
                    if ($qty > 0) {
                        $stockBadge = "<span class='badge bg-success'>In Stock ({$qty})</span>";
                    } else {
                        $stockBadge = "<span class='badge bg-danger'>Out of Stock</span>";
                    }
                } elseif (isset($product->qty)) {
                    $qty = $product->qty ?? 0;
                    if ($qty > 0) {
                        $stockBadge = "<span class='badge bg-success'>In Stock ({$qty})</span>";
                    } else {
                        $stockBadge = "<span class='badge bg-danger'>Out of Stock</span>";
                    }
                } else {
                    $stockBadge = "<span class='badge bg-info'>Available</span>";
                }
            } catch (\Exception $e) {
                $stockBadge = "<span class='badge bg-secondary'>Stock Unknown</span>";
            }

            $categories = $product->categories->pluck('title')->implode(', ');
            $categoryInfo = $categories ? 
                "<small class='text-muted'>Categories: {$categories}</small><br>" : '';

            $createdAt = $product->created_at ? 
                $product->created_at->format('M j, Y') : 
                'Unknown';

            $cards .= "
            <div class='col-md-6 col-lg-4 mb-3'>
                <div class='card h-100 product-card'>
                    <div class='card-body'>
                        <div class='d-flex justify-content-between align-items-start mb-2'>
                            <h6 class='card-title mb-0'>{$title}</h6>
                            {$statusBadge}
                        </div>
                        <p class='card-text small'>" . \Str::limit($excerpt, 100) . "</p>
                        <div class='product-price mb-2'>
                            <strong class='text-primary'>{$price}</strong>
                        </div>
                        <div class='mb-2'>
                            {$stockBadge}
                        </div>
                    </div>
                    <div class='card-footer bg-transparent'>
                        {$sku}
                        {$categoryInfo}
                        <small class='text-muted'><i class='fas fa-calendar'></i> {$createdAt}</small><br>
                        <small class='text-muted'>ID: #{$product->id}</small>
                    </div>
                </div>
            </div>";
        }
        
        $cards .= "</div>";

        return $header . $cards;
    }
}
