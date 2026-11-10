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

Or with the helper (composer `files` autoload, always available):

```php
chunked_dispatch($subscriberIds, fn (array $ids) => new SendEmailToSubscriber($ids, $campaignId), 100);
```

`dispatch()` returns the `Illuminate\Bus\Batch` (or `null`). Related service methods: `dispatchJobs($jobs, $batchSize)` to batch an already-built list of jobs, and `chunkCount($itemCount, $chunkSize)` to count the chunks without dispatching.

## Processing pending jobs

Drain reserved/pending rows from the `jobs` table (safe unserialize guarded by the `allowed_job_classes` / `allowed_job_class_prefixes` config):

```php
use MicroweberPackages\Queue\Services\QueueProcessor;

$processed = app(QueueProcessor::class)->process(limit: 10); // number of jobs run

// Helper equivalent:
$processed = process_pending_queue(10);
```

The package also exposes `ProcessQueueController` (HTTP endpoint) and fires a `ProcessQueueEvent` handled by `ProcessQueueListener` for event-driven draining.

## Configuration

Config file: `config/microweber-queue.php`.

| Key | Env | Default | Purpose |
|-----|-----|---------|---------|
| `chunk_size` | `MICROWEBER_QUEUE_CHUNK_SIZE` | `100` | Default chunk size for Bus dispatch |
| `process_max_attempts` | `MICROWEBER_QUEUE_PROCESS_MAX_ATTEMPTS` | `10` | Max attempts when processing reserved jobs |
| `process_limit` | `MICROWEBER_QUEUE_PROCESS_LIMIT` | `10` | Pending jobs reserved per controller run |
| `default_queue` | `MICROWEBER_QUEUE_DEFAULT` | `default` | Queue used when none is given |
| `allowed_job_classes` | — | Laravel internals | Exact classes allowed for unserialize |
| `allowed_job_class_prefixes` | — | `App\Jobs\`, `Modules\`, `MicroweberPackages\` | FQCN prefixes allowed for unserialize |
| `filament.enabled` | — | `true` | Register the Filament resources |
| `filament.navigation_group` | — | `System Settings` | Admin nav group |

## Key classes

- `MicroweberPackages\Queue\Services\ChunkedDispatcherService` — `dispatch()`, `dispatchJobs()`, `chunkCount()`
- `MicroweberPackages\Queue\Facades\ChunkedDispatcher`
- `MicroweberPackages\Queue\Services\QueueProcessor` — `process()`, `runJob()`, `isClassAllowed()`
- `MicroweberPackages\Queue\Filament\QueuePlugin` / `JobResource` / `FailedJobResource`
- Helpers: `chunked_dispatch()`, `process_pending_queue()`

## Tests

```bash
composer test
composer analyse   # PHPStan level 9
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Petko Yovchevski
