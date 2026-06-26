# Microweber Event Manager

A standalone event manager package for Laravel. Provides a simple event binding and triggering system that can be used in any Laravel application.

## Installation

```bash
composer require microweber-packages/event-manager
```

The service provider will be auto-discovered by Laravel.

## Usage

### Binding events

```php
event_bind('my_event', function($data) {
    // handle event
});
```

### Triggering events

```php
event_trigger('my_event', ['key' => 'value']);
```

### Using the service directly

```php
$eventManager = app('event_manager');
$eventManager->on('my_event', function($data) { /* ... */ });
$eventManager->trigger('my_event', $data);
```

## Testing

```bash
composer test
```

## License

MIT