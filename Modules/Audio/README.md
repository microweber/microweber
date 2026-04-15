# Audio

Audio player module for embedding and playing audio files on website pages.

## Structure

- Filament admin
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="audio" />
```

### Publish assets

```sh
php artisan module:publish Audio
```

### Configuration

```php
config('modules.audio.name')
```

### Views

```php
view('modules.audio::index')
```

