# Sitemap Module for Microweber

This module provides functionality for generating and managing sitemaps for your application. It allows you to create XML sitemaps for various content types, including categories, tags, products, posts, and pages.

## Features

- Generates XML sitemaps for different content types.
- Supports multilingual content.
- Automatically updates the sitemap when content changes.
- Provides a structured sitemap index.

## Usage

You can access the sitemaps via the following endpoints:

- `/sitemap.xml` - Returns the main sitemap index.
- `/sitemap.xml/categories` - Returns the sitemap for categories.
- `/sitemap.xml/tags` - Returns the sitemap for tags.
- `/sitemap.xml/products` - Returns the sitemap for products.
- `/sitemap.xml/posts` - Returns the sitemap for posts.
- `/sitemap.xml/pages` - Returns the sitemap for pages.

## Testing

Run the tests using the following command:

```sh
php artisan test --filter Sitemap
```
