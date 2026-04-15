# RssFeed

RSS feed generator and reader. Publish site content as RSS and display external RSS feeds.

## Structure

- HTTP controllers
- Route definitions
- Blade views

## Usage

### Module tag

```html
<module type="rssfeed" />
```

### Publish assets

```sh
php artisan module:publish RssFeed
```

### Configuration

```php
config('modules.rssfeed.name')
```

### Views

```php
view('modules.rssfeed::index')
```

