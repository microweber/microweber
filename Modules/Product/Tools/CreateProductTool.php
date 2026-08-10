<?php

namespace Modules\Product\Tools;

use Modules\Content\Tools\CreateContentTool;

use NeuronAI\Tools\ToolProperty;
use NeuronAI\Tools\PropertyType;
use Modules\Product\Models\Product;

class CreateProductTool extends CreateContentTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct($dependencies);
        $this->name = 'create_product';
        $this->description = 'Create new product for e-commerce';
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'The product name/title',
                required: true
            ),
            new ToolProperty(
                name: 'description',
                type: PropertyType::STRING,
                description: 'Product description',
                required: true
            ),
            new ToolProperty(
                name: 'content_body',
                type: PropertyType::STRING,
                description: 'New content/body text.',
                required: false,
            ),
            new ToolProperty(
                name: 'price',
                type: PropertyType::NUMBER,
                description: 'Product price',
                required: false
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'Product URL slug for the product page (if not provided, it will be generated from the title)',
                required: false
            ),
            new ToolProperty(
                name: 'original_url',
                type: PropertyType::STRING,
                description: 'Product original URL',
                required: false
            ),
            new ToolProperty(
                name: 'media_urls',
                type: PropertyType::STRING,
                description: 'Comma-separated list of media URLs to attach to the product',
                required: false
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $title = $args['title'] ?? null;
        $description = $args['description'] ?? null;
        $content_body = $args['content_body'] ?? null;
        $price = $args['price'] ?? null;
        $url = $args['url'] ?? null;
        $media_urls = $args['media_urls'] ?? '';

        // Convert comma-separated string to array
        $media_urls_array = [];
        if (!empty($media_urls)) {
            $media_urls_array = array_map('trim', explode(',', $media_urls));
            $media_urls_array = array_filter($media_urls_array, function($url) {
                return !empty($url) && filter_var($url, FILTER_VALIDATE_URL);
            });
        }


        // Validate required parameters
        if (empty($title)) {
            return $this->handleError('Title is required for product creation.');
        }
        if (empty($description)) {
            return $this->handleError('Description is required for product creation.');
        }

        // Generate URL if not provided
        if (empty($url) && !empty($title)) {
            $url = $this->generateSlug($title);
        }

        // Create the product data
        $productData = [
            'title' => $title,
            'content_body' => $content_body,
            'description' => $description,
            'url' => $url,
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
            // task-2026-06-06-mcpparent: root-level products must persist
            // parent=0, not NULL, so they appear in admin product pickers
            // and the Live Edit page tree (which filter on parent=0).
            'parent' => 0,

            'created_by' => \user_id()
        ];

        $product = Product::create($productData);

        // Audit log
        $this->auditWriteOperation('create', 'product', $product->id, $productData);

//        // Handle price as custom field if provided
        if ($price !== null && function_exists('save_custom_field')) {
            save_custom_field([
                'field' => 'price',
                'value' => $price,
                'rel_type' => 'content',
                'rel_id' => $product->id,
                'type' => 'price'
            ]);
        }

        // Handle media URLs if provided
        if (!empty($media_urls_array)) {
            $this->attachMediaUrls($product->id, $media_urls_array);
        }



        return $this->handleSuccess("Product created successfully with ID: {$product->id}") .
               $this->formatProductDetails($product, $price);
    }

    private function formatProductDetails($product, $price): string
    {
        return '
        <div class="card mt-3">
            <div class="card-header">
                <h5>Product Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> ' . $product->id . '</p>
                        <p><strong>Title:</strong> ' . htmlspecialchars($product->title) . '</p>
                        <p><strong>URL:</strong> ' . htmlspecialchars($product->url) . '</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Price:</strong> ' . ($price ? '€' . number_format($price, 2) : 'N/A') . '</p>
                        <p><strong>Status:</strong> ' . ($product->is_active ? 'Published' : 'Draft') . '</p>
                        <p><strong>Created:</strong> ' . $product->created_at->format('Y-m-d H:i:s') . '</p>
                    </div>
                </div>
            </div>
        </div>';
    }
}
