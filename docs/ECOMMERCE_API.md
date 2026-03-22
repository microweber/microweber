# E-commerce API Documentation

This document describes the RESTful e-commerce API endpoints for products, cart, and checkout operations.

## Overview

The e-commerce API provides comprehensive endpoints for:
- **Products**: Browse and search products
- **Cart**: Manage shopping cart items
- **Checkout**: Process orders and payments

All endpoints return JSON responses with a consistent structure:

```json
{
  "success": true|false,
  "message": "Human-readable message",
  "data": { ... },
  "error": "Error message (if applicable)"
}
```

## Authentication

Most e-commerce endpoints are **public** and do not require authentication:
- Product browsing (list, show, search)
- Cart operations
- Checkout process

Protected endpoints (require `auth:sanctum`):
- Order history (`/api/ecommerce/orders`)

## Product Endpoints

### List Products
```http
GET /api/products
```

Query Parameters:
- `search` - Search by title/description
- `category` - Filter by category
- `min_price` - Minimum price
- `max_price` - Maximum price
- `sort_by` - Sort field (title, created_at, updated_at, position)
- `sort_order` - Sort direction (asc, desc)
- `limit` - Items per page (max 100)
- `page` - Page number

Response:
```json
{
  "success": true,
  "data": {
    "data": [...],
    "current_page": 1,
    "last_page": 5,
    "per_page": 30,
    "total": 150
  }
}
```

### Get Product by ID
```http
GET /api/products/{id}
```

Response:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Product Name",
    "slug": "product-name",
    "price": 99.99,
    "currency": "USD",
    "description": "...",
    "images": [...],
    "categories": [...],
    "custom_fields": [...]
  }
}
```

### Get Product by Slug
```http
GET /api/products/slug/{slug}
```

### Get Featured Products
```http
GET /api/products/featured?limit=10
```

### Get Products by Category
```http
GET /api/products/category/{category-slug}
```

## Cart Endpoints

### Get Cart
```http
GET /api/cart
```

Response:
```json
{
  "success": true,
  "data": {
    "items": [...],
    "totals": {
      "subtotal": 199.98,
      "tax": 0,
      "discount": 0,
      "shipping": 10,
      "total": 209.98
    }
  }
}
```

### Add Item to Cart
```http
POST /api/cart
Content-Type: application/json

{
  "content_id": 123,
  "qty": 2
}
```

### Update Cart Item Quantity
```http
PUT /api/cart/{cart_item_id}
Content-Type: application/json

{
  "qty": 3
}
```

### Remove Item from Cart
```http
DELETE /api/cart/{cart_item_id}
```

### Empty Cart
```http
DELETE /api/cart/empty
```

### Get Cart Totals
```http
GET /api/cart/totals
```

### Apply Coupon
```http
POST /api/cart/coupon
Content-Type: application/json

{
  "coupon_code": "DISCOUNT10"
}
```

### Remove Coupon
```http
DELETE /api/cart/coupon
```

## Checkout Endpoints

### Get Checkout Data
```http
GET /api/checkout
```

Response includes:
- Current cart items
- Customer information
- Available shipping methods
- Available payment methods

### Process Checkout
```http
POST /api/checkout
Content-Type: application/json

{
  "email": "customer@example.com",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+1234567890",
  "address": "123 Main St",
  "address2": "Apt 4B",
  "city": "New York",
  "state": "NY",
  "zip": "10001",
  "country": "US",
  "shipping_provider_id": 1,
  "payment_provider_id": 1,
  "terms": true
}
```

Response:
```json
{
  "success": true,
  "message": "Order placed successfully",
  "data": {
    "order_id": 456,
    "order_reference_id": "ORD-ABC123",
    "redirect": "https://...",
    "order_completed": true,
    "is_paid": false,
    "transaction_id": null
  }
}
```

### Update Checkout Session
```http
PUT /api/checkout
Content-Type: application/json

{
  "email": "new@example.com",
  "first_name": "Jane"
}
```

### Validate Checkout Data
```http
POST /api/checkout/validate
Content-Type: application/json

{
  "email": "test@example.com",
  "first_name": "John"
}
```

### Get Shipping Methods
```http
GET /api/checkout/shipping-methods
```

### Get Payment Methods
```http
GET /api/checkout/payment-methods
```

### Calculate Shipping
```http
POST /api/checkout/calculate-shipping
Content-Type: application/json

{
  "shipping_provider_id": 1,
  "country": "US",
  "city": "New York",
  "zip": "10001"
}
```

### Get Order Status
```http
GET /api/checkout/order/{order_reference_id}
```

Response:
```json
{
  "success": true,
  "data": {
    "order_id": 456,
    "order_reference_id": "ORD-ABC123",
    "status": "completed",
    "is_paid": true,
    "payment_status": "completed",
    "amount": 209.98,
    "currency": "USD",
    "created_at": "2026-03-22T12:00:00Z",
    "updated_at": "2026-03-22T12:05:00Z"
  }
}
```

## Protected Endpoints (Authenticated)

### Get Order History
```http
GET /api/ecommerce/orders
Authorization: Bearer {token}
```

Response:
```json
{
  "success": true,
  "data": {
    "data": [...],
    "current_page": 1,
    "last_page": 3,
    "total": 50
  }
}
```

### Get Specific Order
```http
GET /api/ecommerce/orders/{order_id}
Authorization: Bearer {token}
```

## Error Handling

All endpoints return appropriate HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

Error responses include:

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

## Rate Limiting

Authenticated endpoints are rate-limited to 60 requests per minute per user.

## Testing

Run the e-commerce API test suite:

```bash
php artisan test tests/Feature/Api/EcommerceApiTest.php
```

## Implementation Details

### Controllers

- `Modules\Product\Http\Controllers\Api\ProductPublicApiController`
- `Modules\Cart\Http\Controllers\Api\CartApiController`
- `Modules\Checkout\Http\Controllers\Api\CheckoutApiController`

### Resources

- `Modules\Product\Http\Resources\ProductResource`

### Routes File

- `routes/ecommerce-api.php`

### Test File

- `tests/Feature/Api/EcommerceApiTest.php`

## Notes

- Cart data is session-based and persists across page reloads
- The checkout process integrates with the existing payment gateway system
- All prices are returned in the configured currency
- Products include full media and category relationships when queried by ID or slug
