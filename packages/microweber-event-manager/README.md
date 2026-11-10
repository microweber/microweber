# Microweber Event Manager

A standalone event manager for Laravel. Provides a simple, instance-based event binding and triggering system that can be used in any Laravel application. Listener state is held on instances (not static), so closures are garbage-collected when the container is flushed — safe for long-running processes and test suites.

## Features

- `on()` / `trigger()` event binding and firing, returning collected listener responses
- `response()` – fire an event and merge listener overrides back into a criteria array (Microweber's "filter" pattern)
- `unbind()` / `unbindAll()` / `hasListeners()`
- Shutdown callbacks via `registerShutdownEvent()`
- Global procedural helpers: `event_bind()`, `event_trigger()`, `event_unbind()`, `event_unbind_all()`
- Deferred service provider; auto-unbinds all listeners on app termination

## Installation

```bash
composer require microweber-packages/microweber-event-manager
```

The service provider is auto-discovered and binds `EventService` as a singleton (deferred).

## Usage

### Global helpers (simplest)

```php
// Bind a listener
event_bind('order.paid', function ($data) {
    // handle event
});

// Trigger it — returns a list of every listener's return value (or null if none)
$responses = event_trigger('order.paid', ['order_id' => 42]);

event_unbind('order.paid');  // remove listeners for one event
event_unbind_all();          // remove everything
```

### Via the facade

```php
use MicroweberPackages\Event\Facades\EventManager;

EventManager::on('user.registered', fn ($data) => Log::info('welcome', $data));
EventManager::trigger('user.registered', ['id' => 1]);

EventManager::hasListeners('user.registered'); // true
EventManager::unbind('user.registered');
```

### Via dependency injection

```php
use MicroweberPackages\Event\EventService;

public function __construct(private EventService $events) {}

$this->events->on('cache.clear', fn () => Cache::flush());
$this->events->trigger('cache.clear');
```

### The `response()` filter pattern

`response()` fires an event and merges any listener-returned arrays back over the
criteria — listeners can override values or add `__`-prefixed keys. Microweber uses
this to let modules mutate shared data:

```php
$criteria = EventManager::response('content.save.params', [
    'title'   => 'Hello',
    'is_active' => 1,
]);
// $criteria now reflects any overrides returned by registered listeners
```

### Shutdown callbacks

```php
EventManager::registerShutdownEvent(function () {
    // runs during PHP shutdown
});
```

## Key classes

- `MicroweberPackages\Event\EventService` — high-level manager (singleton)
- `MicroweberPackages\Event\LaravelEvent` — instance-based dispatcher adapter
- `MicroweberPackages\Event\Facades\EventManager` — facade
- `src/helpers.php` — global `event_*` helpers (auto-loaded via composer `files`)

## Testing

```bash
composer test
```

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
