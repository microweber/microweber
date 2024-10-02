# RssFeed Module for Microweber

This module provides functionality for generating RSS feeds for posts and products.

## Features

- Generates RSS feeds in different formats (e.g., Atom, WordPress).
- Supports multilingual content.
- Fetches content dynamically from the database.



## Usage

You can access the RSS feeds via the following endpoints:

- `/rss` - Returns the main RSS feed.
- `/rss/posts` - Returns the RSS feed for posts.
- `/rss/products` - Returns the RSS feed for products.

## Testing

Run the tests using the following command:

```sh
php artisan test --filter RssFeed
```

