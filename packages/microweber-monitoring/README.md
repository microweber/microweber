# Microweber Monitoring

Error tracking and codebase-audit tooling for Microweber. Records exceptions and
log-level errors to a dedicated `error_tracking` table, surfaces them in a
Filament admin resource + stats widget, and ships a set of CLI audit commands.

## Features

- **`ErrorTrackingService`** — capture exceptions/errors with context, aggregate
  stats, list recent errors, mark resolved, and rank top error sources.
- **`ErrorTracking` Eloquent model** backed by the `error_tracking` table (migration included).
- **Filament integration** — `ErrorTrackingResource` (under the *System Settings*
  navigation group) and an `ErrorStatsWidget`, auto-registered through `FilamentRegistry`.
- **Audit artisan commands** — boot-query, orphan-config, strict PSR-4 and error-export audits.

## Installation

```bash
composer require microweber-packages/microweber-monitoring
```

Register `MicroweberPackages\Monitoring\Providers\MonitoringServiceProvider`. It
singletons `ErrorTrackingService`, loads the migration, registers the Filament
resource/widget, and (in console) the audit commands.

## Usage

### Tracking errors

```php
use MicroweberPackages\Monitoring\Services\ErrorTrackingService;

$tracker = app(ErrorTrackingService::class);

// Record an exception with arbitrary context; returns the new row id or false
try {
    // ...
} catch (\Throwable $e) {
    $tracker->trackException($e, ['user_id' => 1, 'route' => 'checkout']);
}

// Record a non-exception error
$tracker->trackError('warning', 'Payment gateway slow', ['ms' => 4200]);
```

### Reading error data

```php
$stats  = $tracker->getErrorStats('today');          // 'today' | other periods
$recent = $tracker->getRecentErrors(20, true);        // limit, unresolvedOnly
$top    = $tracker->getTopErrorSources(10);           // most frequent sources

$tracker->markAsResolved($errorId, 'Fixed in release 1.4');
```

### The model

```php
use MicroweberPackages\Monitoring\Models\ErrorTracking;

ErrorTracking::query()->whereNull('resolved_at')->count();
```

## Artisan commands

Registered only when running in the console:

```bash
# Boot a route and count/aggregate the queries it fires (perf regression guard)
php artisan monitoring:boot-query-audit --route=/ --threshold=50 --top=10

# Delete resolved errors older than N days
php artisan monitoring:cleanup-errors --days=90 --force

# Cross-check every config('foo.bar') callsite against config/*.php
php artisan monitoring:config-orphan-audit --strict --top=20

# Strict PSR-4 audit: file path -> declared namespace + class name
php artisan monitoring:psr4-strict-audit --top=20

# Export the error report
php artisan monitoring:export-errors --format=json --days=7 --unresolved-only --output=errors.json
```

## Key classes

- `MicroweberPackages\Monitoring\Services\ErrorTrackingService` — error capture + reporting service.
- `MicroweberPackages\Monitoring\Models\ErrorTracking` — `error_tracking` table model.
- `MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource` — admin CRUD.
- `MicroweberPackages\Monitoring\Filament\Widgets\ErrorStatsWidget` — dashboard stats.
- `MicroweberPackages\Monitoring\Console\Commands\*` — the `monitoring:*` audit commands.
- `MicroweberPackages\Monitoring\Providers\MonitoringServiceProvider` — service registration.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov

## License

MIT
