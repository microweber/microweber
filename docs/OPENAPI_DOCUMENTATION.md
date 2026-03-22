# OpenAPI/Swagger API Documentation

## Overview

This document describes the comprehensive OpenAPI/Swagger documentation generated for the Microweber CMS REST API. The API documentation covers all RESTful endpoints including Content Management, E-commerce, Health Checks, and Authentication.

## Quick Access

- **Swagger UI**: Access the interactive API documentation at `/api-documentation.html`
- **OpenAPI JSON**: `/storage/api-docs/api-docs.json`
- **OpenAPI YAML**: `/storage/api-docs/openapi.yaml`

## API Coverage

### 1. Health Check Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/health` | GET | General health status |
| `/api/health/database` | GET | Database connectivity check |
| `/api/health/cache` | GET | Cache system check |
| `/api/health/storage` | GET | Storage system check |

**Features:**
- Returns detailed health status for all system components
- Includes response times and error messages
- Returns HTTP 503 when any component is unhealthy

### 2. Content Management Endpoints

#### Content API

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/content` | GET | No | List all content (paginated) |
| `/api/content` | POST | Yes | Create new content |
| `/api/content/{id}` | GET | No | Get content by ID |
| `/api/content/{id}` | PUT | Yes | Update content |
| `/api/content/{id}` | PATCH | Yes | Partial update |
| `/api/content/{id}` | DELETE | Yes | Delete content |

**Query Parameters:**
- `limit` - Items per page (max 100, default 30)
- `content_type` - Filter by type (page, post, product)
- `is_active` - Filter by active status
- `search` - Search in title and content
- `page` - Page number

**Response Fields:**
- Full SEO metadata (meta_title, meta_description, og_*, twitter_*)
- Sitemap configuration
- Navigation links
- Relationships (categories, tags, media, custom_fields)

#### Page API

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/pages` | GET | No | List all pages |
| `/api/pages` | POST | Yes | Create new page |
| `/api/pages/{id}` | GET | No | Get page by ID |
| `/api/pages/{id}` | PUT | Yes | Update page |
| `/api/pages/{id}` | DELETE | Yes | Delete page |

**Page-Specific Fields:**
- `is_home` - Home page flag
- `layout_file` - Template layout
- `active_site_template` - Site template

#### Post API

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/posts` | GET | No | List all posts |
| `/api/posts` | POST | Yes | Create new post |
| `/api/posts/{id}` | GET | No | Get post by ID |
| `/api/posts/{id}` | PUT | Yes | Update post |
| `/api/posts/{id}` | DELETE | Yes | Delete post |

### 3. E-commerce Endpoints

#### Product API

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/products` | GET | No | List products |
| `/api/products/featured` | GET | No | Get featured products |
| `/api/products/category/{category}` | GET | No | Get products by category |
| `/api/products/slug/{slug}` | GET | No | Get product by slug |
| `/api/products/{id}` | GET | No | Get product by ID |

**Query Parameters:**
- `search` - Search by title/description
- `category` - Filter by category
- `min_price` / `max_price` - Price range
- `sort_by` - Sort field (title, created_at, updated_at, position)
- `sort_order` - Sort direction (asc, desc)
- `limit` - Items per page (max 100)

**Product Fields:**
- Complete pricing information
- Inventory tracking
- Shipping dimensions and weight
- Media gallery
- Categories and custom fields

#### Cart API

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/cart` | GET | No | Get cart contents |
| `/api/cart` | POST | No | Add item to cart |
| `/api/cart/totals` | GET | No | Get cart totals |
| `/api/cart/empty` | DELETE | No | Empty cart |
| `/api/cart/coupon` | POST | No | Apply coupon |
| `/api/cart/coupon` | DELETE | No | Remove coupon |
| `/api/cart/{id}` | PUT | No | Update cart item |
| `/api/cart/{id}` | DELETE | No | Remove cart item |

**Cart Totals Include:**
- Subtotal
- Tax
- Discount
- Shipping
- Total
- Currency
- Coupon information

#### Checkout API

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/checkout` | GET | No | Get checkout data |
| `/api/checkout` | POST | No | Process checkout |
| `/api/checkout` | PUT | No | Update checkout session |
| `/api/checkout/validate` | POST | No | Validate checkout data |
| `/api/checkout/shipping-methods` | GET | No | Get shipping methods |
| `/api/checkout/payment-methods` | GET | No | Get payment methods |
| `/api/checkout/calculate-shipping` | POST | No | Calculate shipping cost |
| `/api/checkout/order/{orderReferenceId}` | GET | No | Get order status |

**Checkout Response Includes:**
- Order ID and reference ID
- Payment redirect URL
- Order completion status
- Payment status

#### Protected Order Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/ecommerce/orders` | GET | Yes | Get order history |
| `/api/ecommerce/orders/{order}` | GET | Yes | Get specific order |

**Authentication:**
- Requires valid Bearer token
- Returns only authenticated user's orders

## Authentication

The API uses Laravel Sanctum for authentication:

```
Authorization: Bearer <your-token>
```

### Obtaining a Token

1. Login via `/api/login` or create a token via the admin panel
2. Include the token in the Authorization header
3. Token expires based on Sanctum configuration

## Rate Limiting

- Authenticated endpoints: 60 requests per minute
- Public endpoints: Generally unlimited
- Rate limit headers included in responses

## Response Format

All endpoints return a consistent JSON structure:

```json
{
  "success": true|false,
  "message": "Human-readable message",
  "data": { ... },
  "error": "Error message (if applicable)"
}
```

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Server Error |
| 503 | Service Unhealthy |

## Data Types

### Content Types
- `page` - Static pages
- `post` - Blog posts
- `product` - E-commerce products

### SEO Fields
All content types include comprehensive SEO fields:
- Meta title and description
- Open Graph data (og_title, og_description, og_image, og_type)
- Twitter Card data (twitter_title, twitter_description, twitter_image, twitter_card)
- Canonical URL
- Robots meta directives
- Sitemap priority and change frequency

### Product Fields
- Pricing (price, special_price, currency)
- Inventory (sku, quantity, track_quantity)
- Shipping (physical_product, weight, dimensions)
- Media (image, media gallery)
- Categorization (categories, custom_fields)

## Testing

### Using Swagger UI

1. Navigate to `/api-documentation.html`
2. Browse available endpoints
3. Click "Authorize" to add your Bearer token
4. Try out endpoints directly in the browser

### Using cURL Examples

#### List Products
```bash
curl -X GET "https://your-site.com/api/products?limit=10&search=widget"
```

#### Create Content
```bash
curl -X POST "https://your-site.com/api/content" \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "New Article",
    "content_type": "post",
    "content": "Article content...",
    "is_active": true
  }'
```

#### Add to Cart
```bash
curl -X POST "https://your-site.com/api/cart" \
  -H "Content-Type: application/json" \
  -d '{
    "content_id": 123,
    "qty": 2
  }'
```

#### Process Checkout
```bash
curl -X POST "https://your-site.com/api/checkout" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "customer@example.com",
    "first_name": "John",
    "last_name": "Doe",
    "address": "123 Main St",
    "city": "New York",
    "zip": "10001",
    "country": "US"
  }'
```

## Schema Definitions

### Content Schema

```json
{
  "id": 1,
  "title": "Sample Content",
  "url": "sample-content",
  "content_type": "page",
  "description": "Description...",
  "content": "Content body...",
  "content_meta_title": "Meta Title",
  "content_meta_description": "Meta description",
  "is_home": false,
  "is_shop": false,
  "is_active": true,
  "created_at": "2026-03-22T10:00:00.000000Z",
  "updated_at": "2026-03-22T10:00:00.000000Z",
  "link": "https://example.com/sample-content",
  "edit_link": "https://example.com/admin/content/edit/1"
}
```

### Product Schema

```json
{
  "id": 1,
  "title": "Product Name",
  "slug": "product-name",
  "price": 99.99,
  "currency": "USD",
  "description": "Product description",
  "image": "https://example.com/image.jpg",
  "is_active": true,
  "is_featured": true,
  "categories": [
    {"id": 1, "title": "Electronics", "slug": "electronics"}
  ],
  "created_at": "2026-03-22T10:00:00.000000Z"
}
```

### Cart Schema

```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "content_id": 123,
        "title": "Product Name",
        "qty": 2,
        "price": 99.99,
        "currency": "USD"
      }
    ],
    "totals": {
      "subtotal": 199.98,
      "tax": 0,
      "discount": 0,
      "shipping": 10,
      "total": 209.98,
      "currency": "USD"
    }
  }
}
```

### Order Schema

```json
{
  "id": 456,
  "order_reference_id": "ORD-ABC123",
  "email": "customer@example.com",
  "first_name": "John",
  "last_name": "Doe",
  "amount": 209.98,
  "currency": "USD",
  "order_status": "completed",
  "payment_status": "completed",
  "is_paid": true,
  "created_at": "2026-03-22T12:00:00Z"
}
```

## Error Handling

### Validation Errors (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "email": ["The email must be a valid email address."]
  }
}
```

### Not Found (404)

```json
{
  "success": false,
  "message": "Content not found"
}
```

### Unauthorized (401)

```json
{
  "message": "Unauthenticated."
}
```

## Security Considerations

1. **HTTPS Required**: All API endpoints should be accessed over HTTPS
2. **Token Security**: Store tokens securely and never expose them in client-side code
3. **Rate Limiting**: Respect rate limits to avoid being blocked
4. **Input Validation**: All inputs are validated; check error responses
5. **CORS**: Configure CORS settings in `config/cors.php` for cross-origin requests

## Pagination

List endpoints support pagination with the following response structure:

```json
{
  "data": [...],
  "links": {
    "first": "https://example.com/api/content?page=1",
    "last": "https://example.com/api/content?page=5",
    "prev": null,
    "next": "https://example.com/api/content?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 30,
    "to": 30,
    "total": 150
  }
}
```

## Filtering and Searching

### Content Filtering

- `?content_type=page` - Filter by type
- `?is_active=1` - Filter by status
- `?search=keyword` - Search in title and content
- `?parent=5` - Filter by parent content

### Product Filtering

- `?category=electronics` - Filter by category
- `?min_price=10&max_price=100` - Price range
- `?sort_by=created_at&sort_order=desc` - Sorting

## Webhooks

For payment gateway integrations, webhook endpoints are available:

- Stripe: `/stripe/webhook`
- PayPal: `/paypal/webhook`

See payment integration documentation for details.

## Support

For API support:
- Documentation: https://docs.microweber.com
- Support: support@microweber.com
- GitHub Issues: https://github.com/microweber/microweber/issues

## Changelog

### Version 2.0.0 (2026-03-22)
- Complete OpenAPI 3.0.3 specification
- Added Health Check endpoints
- Added Content Management endpoints
- Added E-commerce endpoints (Products, Cart, Checkout)
- Added protected Order endpoints
- Interactive Swagger UI documentation
- Comprehensive examples and schemas
