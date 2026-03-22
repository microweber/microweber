# RESTful Content API Documentation

## Overview

The Microweber Content API provides RESTful endpoints for managing content, pages, and posts. The API supports both public (read-only) and authenticated (full CRUD) access patterns.

## Authentication

The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header:

```
Authorization: Bearer <your-token>
```

### Obtaining a Token

```bash
curl -X POST https://your-site.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password"}'
```

## Base URL

```
https://your-site.com/api
```

## Rate Limiting

Protected endpoints are rate-limited using Laravel's throttle middleware. The default limit is 60 requests per minute for authenticated users.

## Endpoints

### Content API

#### List All Content

```http
GET /api/content
```

Query Parameters:
- `limit` (integer): Number of items per page (default: 30)
- `content_type` (string): Filter by content type (e.g., 'page', 'post')
- `is_active` (boolean): Filter by active status
- `search` (string): Search in title and content

Response:
```json
{
  "data": [
    {
      "id": 1,
      "title": "Sample Content",
      "url": "sample-content",
      "content_type": "page",
      "is_active": true,
      "created_at": "2026-03-22T10:00:00.000000Z",
      "updated_at": "2026-03-22T10:00:00.000000Z"
    }
  ],
  "links": {
    "first": "https://your-site.com/api/content?page=1",
    "last": "https://your-site.com/api/content?page=5",
    "prev": null,
    "next": "https://your-site.com/api/content?page=2"
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

#### Get Single Content

```http
GET /api/content/{id}
```

Response:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Sample Content",
    "url": "sample-content",
    "content_type": "page",
    "content": "Content body...",
    "description": "Description...",
    "is_active": true,
    "is_home": false,
    "is_shop": false,
    "content_meta_title": "Meta Title",
    "content_meta_keywords": "keywords",
    "content_meta_description": "Meta description",
    "og_title": "OG Title",
    "og_description": "OG Description",
    "og_image": "https://...",
    "twitter_title": "Twitter Title",
    "twitter_description": "Twitter Description",
    "canonical_url": "https://...",
    "robots_meta": "index,follow",
    "sitemap_priority": 0.8,
    "sitemap_changefreq": "weekly",
    "exclude_from_sitemap": false,
    "link": "https://your-site.com/sample-content",
    "image": "https://your-site.com/image.jpg",
    "edit_link": "https://your-site.com/admin/content/edit/1",
    "live_edit_link": "https://your-site.com/sample-content?editmode=y",
    "created_at": "2026-03-22T10:00:00.000000Z",
    "updated_at": "2026-03-22T10:00:00.000000Z"
  }
}
```

#### Create Content

```http
POST /api/content
Authorization: Bearer <token>
Content-Type: application/json
```

Request Body:
```json
{
  "title": "New Content",
  "content_type": "page",
  "url": "new-content",
  "content": "Content body...",
  "description": "Description...",
  "is_active": true
}
```

Response (201 Created):
```json
{
  "success": true,
  "message": "Content created successfully",
  "data": {
    "id": 1,
    "title": "New Content",
    "content_type": "page",
    "url": "new-content",
    "is_active": true,
    "created_at": "2026-03-22T10:00:00.000000Z",
    "updated_at": "2026-03-22T10:00:00.000000Z"
  }
}
```

#### Update Content

```http
PUT /api/content/{id}
Authorization: Bearer <token>
Content-Type: application/json
```

Request Body:
```json
{
  "title": "Updated Title",
  "content": "Updated content...",
  "is_active": false
}
```

Response:
```json
{
  "success": true,
  "message": "Content updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Title",
    "is_active": false,
    "updated_at": "2026-03-22T11:00:00.000000Z"
  }
}
```

#### Partial Update (PATCH)

```http
PATCH /api/content/{id}
Authorization: Bearer <token>
Content-Type: application/json
```

Request Body:
```json
{
  "title": "Patched Title"
}
```

#### Delete Content

```http
DELETE /api/content/{id}
Authorization: Bearer <token>
```

Response:
```json
{
  "success": true,
  "message": "Content deleted successfully",
  "data": {
    "id": 1
  }
}
```

### Page API

The Page API provides the same endpoints as the Content API but specifically for pages.

#### List All Pages

```http
GET /api/pages
```

#### Get Single Page

```http
GET /api/pages/{id}
```

#### Create Page

```http
POST /api/pages
Authorization: Bearer <token>
Content-Type: application/json
```

Request Body:
```json
{
  "title": "New Page",
  "url": "new-page",
  "content": "Page content...",
  "is_home": false,
  "is_active": true
}
```

#### Update Page

```http
PUT /api/pages/{id}
Authorization: Bearer <token>
Content-Type: application/json
```

#### Delete Page

```http
DELETE /api/pages/{id}
Authorization: Bearer <token>
```

### Post API

The Post API provides the same endpoints as the Content API but specifically for posts.

#### List All Posts

```http
GET /api/posts
```

#### Get Single Post

```http
GET /api/posts/{id}
```

#### Create Post

```http
POST /api/posts
Authorization: Bearer <token>
Content-Type: application/json
```

Request Body:
```json
{
  "title": "New Post",
  "url": "new-post",
  "content": "Post content...",
  "is_active": true
}
```

#### Update Post

```http
PUT /api/posts/{id}
Authorization: Bearer <token>
Content-Type: application/json
```

#### Delete Post

```http
DELETE /api/posts/{id}
Authorization: Bearer <token>
```

## Validation Rules

### Create/Update Requests

| Field | Rules | Description |
|-------|-------|-------------|
| title | required, string, max:500 | Content title |
| url | nullable, string, max:500, unique | URL slug |
| content_type | required (create), string, max:255 | Type of content |
| content | nullable, string | Content body |
| description | nullable, string | Meta description |
| is_active | nullable, boolean | Active status |
| is_home | nullable, boolean | Home page flag |
| parent | nullable, integer | Parent content ID |
| layout_file | nullable, string, max:500 | Layout template |
| active_site_template | nullable, string, max:500 | Site template |

### SEO Fields

| Field | Rules | Description |
|-------|-------|-------------|
| content_meta_title | nullable, string, max:500 | Meta title |
| content_meta_keywords | nullable, string, max:500 | Meta keywords |
| content_meta_description | nullable, string, max:1000 | Meta description |
| og_title | nullable, string, max:500 | Open Graph title |
| og_description | nullable, string, max:1000 | Open Graph description |
| og_image | nullable, string, max:500 | Open Graph image URL |
| og_type | nullable, string, max:50 | Open Graph type |
| twitter_title | nullable, string, max:500 | Twitter Card title |
| twitter_description | nullable, string, max:1000 | Twitter Card description |
| twitter_image | nullable, string, max:500 | Twitter Card image URL |
| twitter_card | nullable, string, max:50 | Twitter Card type |
| canonical_url | nullable, string, max:500 | Canonical URL |
| robots_meta | nullable, string, max:100 | Robots meta tag |
| sitemap_priority | nullable, numeric, min:0, max:1 | Sitemap priority |
| sitemap_changefreq | nullable, string, in:always,hourly,daily,weekly,monthly,yearly,never | Sitemap change frequency |
| exclude_from_sitemap | nullable, boolean | Exclude from sitemap |

## Error Responses

### 401 Unauthorized

```json
{
  "message": "Unauthenticated."
}
```

### 404 Not Found

```json
{
  "success": false,
  "message": "Content not found"
}
```

### 422 Validation Error

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "content_type": ["The content type field is required."]
  }
}
```

### 429 Too Many Requests

```json
{
  "message": "Too many requests, please try again later."
}
```

### 500 Server Error

```json
{
  "success": false,
  "message": "Failed to create content",
  "error": "Database connection error"
}
```

## Testing

The API includes comprehensive tests. Run them with:

```bash
php artisan test tests/Feature/Api/ContentApiTest.php
```

Test coverage includes:
- Public API access (list, show)
- Protected API access (create, update, delete)
- Authentication requirements
- Validation errors
- 404 handling
- Pagination
- Filtering and searching

## Examples

### cURL Examples

#### List content with pagination:
```bash
curl -X GET "https://your-site.com/api/content?limit=10&page=2"
```

#### Search content:
```bash
curl -X GET "https://your-site.com/api/content?search=hello"
```

#### Filter by type:
```bash
curl -X GET "https://your-site.com/api/content?content_type=page&is_active=1"
```

#### Create content with authentication:
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

## Notes

- All dates are returned in ISO 8601 format
- Soft deletes are used - deleted content can be restored from the admin panel
- The `link` field provides the public URL for the content
- The `edit_link` field provides the admin edit URL
- The `live_edit_link` field provides the live edit URL
- SEO fields are automatically generated if not provided
- URL slugs must be unique across all content types
