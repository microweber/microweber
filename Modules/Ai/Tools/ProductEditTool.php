<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class ProductEditTool extends AbstractContentTool
{
    protected string $domain = 'shop';
    protected string $contentType = 'product';
    protected array $requiredPermissions = ['edit products'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'product_edit',
            'Edit existing products in Microweber CMS including updating title, description, price, stock, and custom fields.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'id',
                type: PropertyType::INTEGER,
                description: 'ID of the product to edit.',
                required: true,
            ),
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'New title for the product.',
                required: false,
            ),
            new ToolProperty(
                name: 'content_body',
                type: PropertyType::STRING,
                description: 'New product description/content.',
                required: false,
            ),
            new ToolProperty(
                name: 'description',
                type: PropertyType::STRING,
                description: 'New short description/excerpt.',
                required: false,
            ),
            new ToolProperty(
                name: 'url',
                type: PropertyType::STRING,
                description: 'New URL slug for the product.',
                required: false,
            ),
            new ToolProperty(
                name: 'is_active',
                type: PropertyType::STRING,
                description: 'Publication status. Options: "1" for published, "0" for unpublished.',
                required: false,
            ),
            new ToolProperty(
                name: 'price',
                type: PropertyType::STRING,
                description: 'New price for the product (decimal number).',
                required: false,
            ),
            new ToolProperty(
                name: 'qty',
                type: PropertyType::STRING,
                description: 'New stock quantity for the product.',
                required: false,
            ),
            new ToolProperty(
                name: 'sku',
                type: PropertyType::STRING,
                description: 'New SKU for the product.',
                required: false,
            ),
            new ToolProperty(
                name: 'custom_fields',
                type: PropertyType::STRING,
                description: 'Custom fields to update in format "field_name:value,field_name2:value2".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $product_id = $args['id'] ?? null;
        $title = $args['title'] ?? '';
        $content_body = $args['content_body'] ?? '';
        $description = $args['description'] ?? '';
        $url = $args['url'] ?? '';
        $is_active = $args['is_active'] ?? '';
        $price = $args['price'] ?? '';
        $qty = $args['qty'] ?? '';
        $sku = $args['sku'] ?? '';
        $custom_fields = $args['custom_fields'] ?? '';

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to edit products.');
        }

        if (!$product_id) {
            return $this->handleError('Product ID is required.');
        }

        try {
            // Find the product
            $product = $this->getContentById($product_id);
            if (!$product) {
                return $this->handleError("Product with ID {$product_id} not found.");
            }

            // Prepare update data
            $updateData = [];
            if (!empty($title)) $updateData['title'] = $title;
            if (!empty($content_body)) $updateData['content_body'] = $content_body;
            if (!empty($description)) $updateData['description'] = $description;
            if (!empty($url)) $updateData['url'] = $url;
            if ($is_active !== '') $updateData['is_active'] = (int)$is_active;
            if (!empty($custom_fields)) $updateData['custom_fields'] = $custom_fields;

            // Handle product-specific fields
            if (!empty($price)) {
                $updateData['price'] = $price;
            }
            if (!empty($qty)) {
                $updateData['qty'] = $qty;
            }
            if (!empty($sku)) {
                $updateData['sku'] = $sku;
            }

            if (empty($updateData)) {
                return $this->handleError('No fields to update provided.');
            }

            // Update the product
            $success = $this->updateProduct($product, $updateData);

            if (!$success) {
                return $this->handleError('Failed to update product.');
            }

            // Audit log
            $this->auditWriteOperation('edit', 'product', $product_id, $updateData);

            // Reload to get updated data
            $product->refresh();

            return $this->formatProductUpdateResult($product, $updateData);

        } catch (\Exception $e) {
            return $this->handleError('Error editing product: ' . $e->getMessage());
        }
    }

    protected function updateProduct($product, array $data): bool
    {
        try {
            // Update basic content fields
            $basicFields = ['title', 'content_body', 'description', 'url', 'is_active'];
            foreach ($basicFields as $field) {
                if (isset($data[$field])) {
                    $product->$field = $data[$field];
                }
            }

            // Handle product-specific fields
            if (isset($data['qty'])) {
                $product->qty = (int)$data['qty'];
            }

            // Handle price as custom field
            if (isset($data['price'])) {
                $product->price = $data['price']; // This will trigger the CustomFieldPriceTrait
            }

            // Handle SKU as custom field
            if (isset($data['sku'])) {
                $product->setCustomField([
                    'name' => 'SKU',
                    'name_key' => 'sku',
                    'value' => [$data['sku']]
                ]);
            }

            // Handle other custom fields
            if (isset($data['custom_fields'])) {
                $customFieldsData = $this->parseCustomFields($data['custom_fields']);
                foreach ($customFieldsData as $fieldName => $fieldValue) {
                    $product->setCustomField([
                        'name' => $fieldName,
                        'name_key' => $fieldName,
                        'value' => [$fieldValue]
                    ]);
                }
            }

            return $product->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function formatProductUpdateResult($product, array $updateData): string
    {
        $statusBadge = $this->getContentStatusBadge($product->is_active ?? 0);
        $typeBadge = $this->getContentTypeBadge($product->content_type ?? 'product');

        $updatedFields = [];
        foreach (array_keys($updateData) as $field) {
            $updatedFields[] = "<span class='badge bg-info'>" . ucfirst(str_replace('_', ' ', $field)) . "</span>";
        }
        $fieldsUpdated = implode(' ', $updatedFields);

        // Get product-specific data
        $price = 'Not set';
        try {
            if (method_exists($product, 'getPrice')) {
                $priceValue = $product->getPrice();
                if ($priceValue) {
                    $price = '€' . number_format($priceValue, 2);
                }
            }
        } catch (\Exception $e) {
            // Ignore price errors
        }

        $sku = '';
        try {
            if (method_exists($product, 'getSku')) {
                $sku = $product->getSku() ?: 'Not set';
            }
        } catch (\Exception $e) {
            $sku = 'Not set';
        }

        $qty = $product->qty ?? 0;
        $stockStatus = $qty > 0 ?
            "<span class='badge bg-success'>In Stock ({$qty})</span>" :
            "<span class='badge bg-danger'>Out of Stock</span>";

        $customFieldsInfo = $this->formatCustomFields($product);

        return "
        <div class='product-edit-result'>
            <div class='alert alert-success'>
                <h5><i class='fas fa-check-circle me-2'></i>Product Updated Successfully</h5>
                <p class='mb-2'>Updated fields: {$fieldsUpdated}</p>
            </div>

            <div class='card'>
                <div class='card-header d-flex justify-content-between align-items-center'>
                    <h6 class='mb-0'><i class='fas fa-shopping-bag me-2'></i>Product Details</h6>
                    <div>
                        {$typeBadge}
                        {$statusBadge}
                    </div>
                </div>
                <div class='card-body'>
                    <table class='table table-sm'>
                        <tr>
                            <th width='150'>ID:</th>
                            <td>#{$product->id}</td>
                        </tr>
                        <tr>
                            <th>Title:</th>
                            <td>{$product->title}</td>
                        </tr>
                        <tr>
                            <th>URL:</th>
                            <td>" . ($product->url ?: '<em>No URL</em>') . "</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td><strong class='text-primary'>{$price}</strong></td>
                        </tr>
                        <tr>
                            <th>SKU:</th>
                            <td>{$sku}</td>
                        </tr>
                        <tr>
                            <th>Stock:</th>
                            <td>{$stockStatus}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>" . ($product->description ?: '<em>No description</em>') . "</td>
                        </tr>
                        <tr>
                            <th>Content:</th>
                            <td>" . ($product->content_body ? \Str::limit(strip_tags($product->content_body), 200) : '<em>No content</em>') . "</td>
                        </tr>
                        <tr>
                            <th>Custom Fields:</th>
                            <td>{$customFieldsInfo}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>" . ($product->updated_at ? $product->updated_at->format('M j, Y H:i:s') : 'Unknown') . "</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>";
    }
}
