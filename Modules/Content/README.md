# Content Module

The Content module provides functionality for managing pages, posts, products and other content types in your application.

## Installation

### Run module migrations
```sh
php artisan module:migrate Content
```

### Publish module assets
```sh
php artisan module:publish Content
```

## Helper Functions

### Content Type Checks

#### `is_page()`
Checks if current content is a page.
```php
if (is_page()) {
    // Current content is a page
}
```

#### `is_post()`
Checks if current content is a post.
```php
if (is_post()) {
    // Current content is a post
}
```

#### `is_home()`
Checks if current page is home page.
```php
if (is_home()) {
    // Current page is home page
}
```

#### `is_category()`
Checks if current content is a category.
```php
if (is_category()) {
    // Current content is a category
}
```

#### `is_product()`
Checks if current content is a product.
```php
if (is_product()) {
    // Current content is a product
}
```

### ID Getters

#### `page_id()`
Gets current page ID.
```php
$pageId = page_id();
```

#### `main_page_id()`
Gets main page ID.
```php
$mainPageId = main_page_id();
```

#### `post_id()`
Gets current post ID.
```php
$postId = post_id();
```

#### `product_id()`
Gets current product ID.
```php
$productId = product_id();
```

#### `content_id()`
Gets current content ID.
```php
$contentId = content_id();
```

#### `category_id()`
Gets current category ID.
```php
$categoryId = category_id();
```

### Content Retrieval

#### `get_content($params = false)`
Gets content items based on parameters.
```php
// Get all active pages
$content = get_content([
    'content_type' => 'page',
    'is_active' => 1
]);
```

#### `get_content_admin($params)`
Gets content items for admin with additional checks.
```php
$content = get_content_admin([
    'content_type' => 'page'
]);
```

#### `get_posts($params = false)`
Gets posts with optional filtering.
```php
$posts = get_posts(['is_active' => 1]);
```

#### `get_pages($params = false)`
Gets pages with optional filtering.
```php
$pages = get_pages(['parent_id' => 0]);
```

#### `get_products($params = false)`
Gets products with optional filtering.
```php
$products = get_products(['is_active' => 1]);
```

#### `get_content_by_id($params)`
Gets content by ID.
```php
$content = get_content_by_id(123);
```

### Content Links & Titles

#### `content_link($id = false)`
Gets content URL.
```php
$url = content_link(123);
```

#### `content_edit_link($id = false)`
Gets content edit URL.
```php
$editUrl = content_edit_link(123);
```

#### `content_create_link($contentType = 'page')`
Gets content creation URL.
```php
$createUrl = content_create_link('post');
```

#### `content_title($id = false)`
Gets content title.
```php
$title = content_title(123);
```

#### `content_description($id = false)`
Gets content description.
```php
$description = content_description(123);
```

### Content Management

#### `save_content($data, $delete_the_cache = true)`
Saves content data.
```php
$contentId = save_content([
    'title' => 'My Page',
    'content_type' => 'page'
]);
```


#### `delete_content($data)`
Deletes content.
```php
delete_content(['id' => 123]);
```

### Navigation & Structure

#### `content_parents($id = 0)`
Gets parent content items.
```php
$parents = content_parents(123);
```

#### `get_content_children($id = 0)`
Gets child content items.
```php
$children = get_content_children(123);
```

#### `pages_tree($params = false)`
Gets pages hierarchy.
```php
$tree = pages_tree();
```

#### `next_content($content_id = false)`
Gets next content item.
```php
$next = next_content(123);
```

#### `prev_content($content_id = false)`
Gets previous content item.
```php
$prev = prev_content(123);
```

#### `next_post($content_id = false)`
Gets next post.
```php
$nextPost = next_post(123);
```

#### `prev_post($content_id = false)`
Gets previous post.
```php
$prevPost = prev_post(123);
```

#### `breadcrumb($params = false)`
Generates breadcrumb navigation.
```php
$breadcrumbs = breadcrumb();
```

### Custom Fields & Data

#### `content_custom_fields($content_id, $full = true, $field_type = false)`
Gets content custom fields.
```php
$fields = content_custom_fields(123);
```

#### `get_content_field($data, $debug = false)`
Gets content field HTML.
```php
$fieldHtml = get_content_field([
    'field_type' => 'text',
    'name' => 'title'
]);
```

#### `save_content_field($data, $delete_the_cache = true)`
Saves content field data.
```php
save_content_field([
    'content_id' => 123,
    'field_type' => 'text',
    'name' => 'title',
    'value' => 'New Title'
]);
```

#### `content_data($content_id, $field_name = false)`
Gets content data.
```php
$data = content_data(123, 'field_name');
```

#### `content_attributes($content_id)`
Gets content attributes.
```php
$attributes = content_attributes(123);
```

### Schema.org Integration

#### `getSchemaOrgScriptByContentIds($contentIds)`
Generates Schema.org script for content items.
```php
$script = getSchemaOrgScriptByContentIds([123, 456]);
```

#### `getSchemaOrgContentFilled($graph, $contentId)`
Fills Schema.org graph with content data.
```php
$graph = getSchemaOrgContentFilled($graph, 123);
```

### Utility Functions

#### `helper_body_classes()`
Gets body CSS classes based on current content.
```php
$classes = helper_body_classes();
```

#### `content_date($id = false)`
Gets content creation date.
```php
$date = content_date(123);
```

#### `paging($params)`
Generates pagination.
```php
$pagination = paging([
    'limit' => 10,
    'current_page' => 1
]);
