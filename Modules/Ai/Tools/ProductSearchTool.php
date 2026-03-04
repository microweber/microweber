<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Product\Models\Product;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ProductSearchTool extends BaseTool
{
    protected string $domain = 'shop';
    protected array $requiredPermissions = ['view_products'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'product_search',
            'Search for products by title, SKU, price range, or other criteria.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::from('string'),
                description: 'Search term: product name, SKU, or description keywords.',
                required: false,
            ),
            new ToolProperty(
                name: 'min_price',
                type: PropertyType::from('number'),
                description: 'Minimum price filter (optional).',
                required: false,
            ),
            new ToolProperty(
                name: 'max_price',
                type: PropertyType::from('number'),
                description: 'Maximum price filter (optional).',
                required: false,
            ),
            new ToolProperty(
                name: 'category',
                type: PropertyType::from('string'),
                description: 'Product category name or ID (optional).',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::from('integer'),
                description: 'Maximum number of products to return (default: 10, max: 50).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string 
    {
        // Extract parameters from args array using keys
        $search_term = $args['search_term'] ?? '';
        $min_price = $args['min_price'] ?? null;
        $max_price = $args['max_price'] ?? null;
        $category = $args['category'] ?? '';
        $limit = $args['limit'] ?? 10;
        
        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to search products.');
        }

        // Validate limit
        $limit = max(1, min(50, $limit));

        try {
            $query = Product::query()->with(['categories', 'customFieldValues']);

            // Search by title, content, or SKU
            if (!empty($search_term)) {
                $query->where(function ($q) use ($search_term) {
                    $q->where('title', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('content', 'LIKE', '%' . $search_term . '%')
                      ->orWhere('description', 'LIKE', '%' . $search_term . '%');
                });
            }

            // Price range filter
            if ($min_price !== null || $max_price !== null) {
                $query->whereHas('customFieldValues', function ($q) use ($min_price, $max_price) {
                    $q->where('name_key', 'price');
                    if ($min_price !== null) {
                        $q->where('value', '>=', $min_price);
                    }
                    if ($max_price !== null) {
                        $q->where('value', '<=', $max_price);
                    }
                });
            }

            // Category filter
            if (!empty($category)) {
                $query->whereHas('categories', function ($q) use ($category) {
                    if (is_numeric($category)) {
                        $q->where('id', $category);
                    } else {
                        $q->where('title', 'LIKE', '%' . $category . '%');
                    }
                });
            }

            // Only active products
            $query->where('is_active', 1);
            
            // Order by title
            $query->orderBy('title');

            $products = $query->limit($limit)->get();

            if ($products->isEmpty()) {
                $searchCriteria = [];
                if (!empty($search_term)) $searchCriteria[] = "term: '{$search_term}'";
                if ($min_price !== null) $searchCriteria[] = "min price: {$min_price}";
                if ($max_price !== null) $searchCriteria[] = "max price: {$max_price}";
                if (!empty($category)) $searchCriteria[] = "category: '{$category}'";
                
                $criteriaText = !empty($searchCriteria) ? ' with ' . implode(', ', $searchCriteria) : '';
                return $this->handleError("No products found{$criteriaText}.");
            }

            return $this->formatProductsAsHtml($products, $search_term, $min_price, $max_price, $category);

        } catch (\Exception $e) {
            return $this->handleError('Error searching for products: ' . $e->getMessage());
        }
    }

    private function formatProductsAsHtml($products, $search_term, $min_price, $max_price, $category): string
    {
        $html = '<div class="products-results">';
        
        // Search summary
        $searchSummary = [];
        if (!empty($search_term)) $searchSummary[] = "'{$search_term}'";
        if ($min_price !== null) $searchSummary[] = "min price: " . $this->formatMoney($min_price);
        if ($max_price !== null) $searchSummary[] = "max price: " . $this->formatMoney($max_price);
        if (!empty($category)) $searchSummary[] = "category: '{$category}'";
        
        if (!empty($searchSummary)) {
            $html .= '<div class="alert alert-info mb-3">';
            $html .= '<strong>Search Results for:</strong> ' . implode(', ', $searchSummary);
            $html .= ' (' . $products->count() . ' products found)';
            $html .= '</div>';
        }
        
        $html .= '<div class="row">';
        
        foreach ($products as $product) {
            $html .= '<div class="col-md-6 col-lg-4 mb-4">';
            $html .= '<div class="card h-100">';

            // Product image placeholder
            $html .= '<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">';
            $html .= '<i class="fas fa-image fa-3x text-muted"></i>';
            $html .= '</div>';

            $html .= '<div class="card-body d-flex flex-column">';

            // Product title
            $html .= '<h5 class="card-title">' . htmlspecialchars($product->title) . '</h5>';

            // Product description
            if (!empty($product->description)) {
                $description = strip_tags($product->description);
                $html .= '<p class="card-text">' . htmlspecialchars(substr($description, 0, 100)) . (strlen($description) > 100 ? '...' : '') . '</p>';
            }

            // Product details
            $html .= '<div class="mt-auto">';

            // Price
            $price = $this->getProductPrice($product);
            if ($price > 0) {
                $html .= '<div class="mb-2">';
                $html .= '<span class="h5 text-primary">' . $this->formatMoney($price) . '</span>';
                $html .= '</div>';
            }

            // SKU
            $sku = $this->getProductSku($product);
            if (!empty($sku)) {
                $html .= '<small class="text-muted">SKU: ' . htmlspecialchars($sku) . '</small><br>';
            }

            // Stock/Quantity
            $qty = $this->getProductQty($product);
            if ($qty !== null && $qty !== 'nolimit') {
                $html .= '<small class="text-muted">Stock: ' . htmlspecialchars($qty) . '</small><br>';
            } elseif ($qty === 'nolimit') {
                $html .= '<small class="text-success">In Stock</small><br>';
            }

            // Categories
            if ($product->categories && $product->categories->count() > 0) {
                $html .= '<small class="text-muted">Categories: ';
                $categoryNames = $product->categories->pluck('title')->take(3)->toArray();
                $html .= htmlspecialchars(implode(', ', $categoryNames));
                if ($product->categories->count() > 3) {
                    $html .= '...';
                }
                $html .= '</small>';
            }

            $html .= '</div>'; // mt-auto
            $html .= '</div>'; // card-body

            $html .= '<div class="card-footer">';
            $html .= '<small class="text-muted">Product ID: ' . $product->id . '</small>';
            $html .= '</div>';

            $html .= '</div>'; // card
            $html .= '</div>'; // col
        }
        
        $html .= '</div>'; // row
        $html .= '</div>'; // products-results
        
        return $html;
    }
    
    private function getProductPrice($product): float
    {
        // Try to get price from custom fields
        if ($product->customFieldValues) {
            $priceField = $product->customFieldValues->where('name_key', 'price')->first();
            if ($priceField && is_numeric($priceField->value)) {
                return (float) $priceField->value;
            }
        }
        
        // Fallback - you might need to adjust this based on Microweber's price structure
        return 0.0;
    }
    
    private function getProductSku($product): string
    {
        // Try to get SKU from content data or custom fields
        if (isset($product->content_data['sku'])) {
            return $product->content_data['sku'];
        }
        
        if ($product->customFieldValues) {
            $skuField = $product->customFieldValues->where('name_key', 'sku')->first();
            if ($skuField) {
                return $skuField->value;
            }
        }
        
        return '';
    }
    
    private function getProductQty($product)
    {
        // Try to get quantity from content data
        if (isset($product->content_data['qty'])) {
            return $product->content_data['qty'];
        }
        
        return null;
    }
}
