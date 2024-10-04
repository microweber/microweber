# Tag Module

## Overview

The Tag module provides a simple and efficient way to manage tags in your Microweber site.

It allows you to create, assign, and manage tags for various content types, enhancing the organization and retrieval of content.

## Features

- Create and manage tags and tag groups.
- Assign multiple tags to content.
- Retrieve content based on tags.
- Automatically delete unused tags.
- Support for localization.

## Installation

To install the Tag module, run the following command:

```sh
php artisan module:install Tag
```

## Run Module Migrations

After installation, run the module migrations to set up the necessary database tables:

```sh
php artisan module:migrate Tag
```

## Usage

### Adding Tags to Content

You can add tags to your content by using the `tag` method:

```php
$content = Content::find(1);
$content->tag('example-tag');
```

### Retrieving Tags

To retrieve the tags associated with a content item, use the `tagNames` method:

```php
$tags = $content->tagNames();
```

### Querying Content by Tags

You can query content based on tags using the following methods:

- `withAllTags(array $tags)`: Retrieve content that has all specified tags.
- `withAnyTag(array $tags)`: Retrieve content that has any of the specified tags.
- `withoutTags(array $tags)`: Retrieve content that does not have the specified tags.

### Deleting Tags

To remove tags from content, use the `untag` method:

```php
$content->untag('example-tag');
```

## Configuration

You can customize the Tag module's behavior by modifying the configuration file located at `Modules/Tag/config/tagging.php`.

