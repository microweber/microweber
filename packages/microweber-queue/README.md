# microweber-packages/microweber-queue

Standalone Laravel package for queue management. Extracted from Microweber CMS and usable in any Laravel app.

## Features

- **Process pending jobs** – process reserved/pending rows from the `jobs` table safely
- **Chunked Bus dispatch** – split huge workloads (e.g. 10 000 newsletter emails) into chunks so no single job hits the worker timeout
- **Filament admin** – list, delete, and re-dispatch jobs; manage failed jobs (retry / delete / flush)
- **Migrations** for `jobs` and `failed_jobs` tables

## Install (standalone Laravel)

```bash
composer require microweber-packages/microweber-queue
php artisan vendor:publish --tag=microweber-queue-config
php artisan migrate
```

Register the Filament plugin on your panel:

```php
use MicroweberPackages\Queue\Filament\QueuePlugin;

$panel->plugin(QueuePlugin::make());
```

## Chunked dispatch (avoid timeouts)

```php
use MicroweberPackages\Queue\Facades\ChunkedDispatcher;
use App\Jobs\SendEmailToSubscriber;

// 10_000 subscriber IDs → many small jobs via Laravel Bus batch
ChunkedDispatcher::dispatch(
    items: $subscriberIds,
    jobFactory: fn (array $chunk) => new SendEmailToSubscriber($chunk, $campaignId),
    chunkSize: 100,
    queue: 'newsletter',
    name: 'campaign-42',
);
```

Or with the helper:

```php
chunked_dispatch($subscriberIds, fn (array $ids) => new SendEmailToSubscriber($ids, $campaignId), 100);
```

## Config

See `config/microweber-queue.php` for chunk size, process limits, and Filament navigation.

## Tests

```bash
composer test
composer analyse   # PHPStan level 9
```
