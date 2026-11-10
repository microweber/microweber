# Microweber CDN Sync

Standalone CDN sync package for Laravel. Uploads any Eloquent model's local files to S3-compatible object storage (AWS S3, Hetzner, MinIO, CloudFront in front of S3…) and tracks every upload in a polymorphic sync log so you always know the remote URL, size and hash of each file.

## Features

- **Model-agnostic** — any Eloquent model becomes syncable by implementing `CdnSyncable` (or using the `HasCdnSync` trait for sane defaults).
- **Polymorphic sync log** — a `cdn_sync_log` row per file records `rel_type`/`rel_id`, local path, CDN path, public URL, disk, bucket, content type, size, md5 hash and `is_synced` flag.
- **Change detection** — re-syncing skips files whose md5 hasn't changed; changed files are re-uploaded.
- **Dynamic S3 disk** — reads credentials from `config/cdn-sync.php`, env, or (in Microweber) the `options` table, and builds the Laravel filesystem disk on the fly if it isn't declared in `config/filesystems.php`.
- **Public CDN URLs** — builds the right URL for AWS, path-style/MinIO endpoints or a custom `cdn_url` (e.g. CloudFront).
- **Bucket CORS** — one `cdn-sync:cors` command sets a GET/HEAD-from-any-origin policy so every site domain can load userfiles cross-origin.
- **Optional Filament settings page** via `CdnSyncPlugin`.

## Installation

```bash
composer require microweber-packages/microweber-cdn-sync
```

The package registers its service provider through `spatie/laravel-package-tools`. Publish the config and migrations, then migrate:

```bash
php artisan vendor:publish --tag=cdn-sync-config
php artisan vendor:publish --tag=cdn-sync-migrations
php artisan migrate
```

(The migrations are also loaded automatically from the package, so `php artisan migrate` works without publishing them.)

## Usage

### 1. Make a model syncable

Use the `HasCdnSync` trait and implement `CdnSyncable`. The trait supplies default `getCdnRelType()`, `getCdnRelId()` and a `getCdnSyncFiles()` that auto-detects common file columns (`filename`, `file_path`, `path`, `image`, `file`) — override `getCdnSyncFiles()` to declare exactly which files to push.

```php
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\CdnSync\Contracts\CdnSyncable;
use MicroweberPackages\CdnSync\Traits\HasCdnSync;

class Media extends Model implements CdnSyncable
{
    use HasCdnSync;

    /** @return array<int, string> */
    public function getCdnSyncFiles(): array
    {
        return [$this->filename]; // local path(s) to upload
    }
}
```

The trait also adds helpers and query scopes:

```php
$media->isFullySyncedToCdn();   // bool
$media->hasAnyCdnSync();        // bool
$media->getCdnUrl();            // ?string — first synced file's public URL
$media->getCdnUrl($localPath);  // ?string — URL for a specific file
$media->cdnSyncLogs;            // MorphMany<CdnSyncLog>

Media::syncedToCdn()->get();    // scope
Media::notSyncedToCdn()->get(); // scope
```

### 2. Sync via the facade (or inject `CdnSyncService`)

```php
use MicroweberPackages\CdnSync\Facades\CdnSync;

// Upload one model's files; returns ['success'=>bool,'synced'=>[],'failed'=>[],'errors'=>[]]
$result = CdnSync::sync($media);

// Upload one specific file
CdnSync::syncFile($media, 'uploads/2024/01/photo.jpg');

// Delete a model's remote files (marks the log rows unsynced)
CdnSync::delete($media);

// Bulk sync an iterable of models
$summary = CdnSync::bulkSync(Media::notSyncedToCdn()->cursor());
// ['total'=>N,'success'=>N,'failed'=>N,'errors'=>[]]

// Introspection / health
CdnSync::isConfigured();     // bool — key+secret+bucket present and enabled
CdnSync::getStats();         // ['total_synced'=>…,'configured'=>bool,'by_type'=>[…]]
CdnSync::testConnection();   // ['success'=>bool,'message'=>string] (writes+reads a probe file)
CdnSync::getDisk();          // the resolved Illuminate Filesystem disk
```

Dependency injection works identically:

```php
use MicroweberPackages\CdnSync\Services\CdnSyncService;

public function __construct(private CdnSyncService $cdn) {}
```

### 3. Bucket CORS

Public userfiles (CSS, fonts, media) are read cross-origin by every site domain. Apply one bucket-level CORS policy (GET/HEAD from any origin):

```bash
php artisan cdn-sync:cors          # apply the policy
php artisan cdn-sync:cors --show   # print the bucket's current CORS rules
```

### 4. Filament admin page (optional)

Register the plugin on a panel to get the bundled CDN settings page:

```php
use MicroweberPackages\CdnSync\Filament\CdnSyncPlugin;

$panel->plugin(CdnSyncPlugin::make());
```

## Configuration

`config/cdn-sync.php` (all keys read from env):

| Key | Env | Default | Purpose |
| --- | --- | --- | --- |
| `enabled` | `CDN_SYNC_ENABLED` | `false` | Master on/off switch |
| `disk` | `CDN_SYNC_DISK` | `cdn` | Filesystem disk name (auto-built if undeclared) |
| `key` | `CDN_SYNC_KEY` | `''` | S3 access key |
| `secret` | `CDN_SYNC_SECRET` | `''` | S3 secret key |
| `region` | `CDN_SYNC_REGION` | `us-east-1` | S3 region |
| `bucket` | `CDN_SYNC_BUCKET` | `''` | Bucket name |
| `endpoint` | `CDN_SYNC_ENDPOINT` | `''` | Custom endpoint (MinIO/Hetzner) |
| `url` | `CDN_SYNC_URL` | `''` | Disk base URL |
| `use_path_style_endpoint` | `CDN_SYNC_USE_PATH_STYLE` | `false` | Path-style addressing |
| `cdn_url` | `CDN_SYNC_CDN_URL` | `''` | CDN base URL (e.g. CloudFront) for public links |
| `path_prefix` | `CDN_SYNC_PATH_PREFIX` | `cdn-sync` | Prefix for all uploaded objects |
| `delete_local` | `CDN_SYNC_DELETE_LOCAL` | `false` | Delete the local file after upload |
| `acl` | `CDN_SYNC_ACL` | `public-read` | Object ACL |

In Microweber, any of the above (e.g. `cdn_sync_bucket`) may also be set through the `options` table — `getConfigValue()` checks `get_option(..., 'cdn_sync')` before falling back to config/env.

## Key classes

- Facade: `MicroweberPackages\CdnSync\Facades\CdnSync`
- Service: `MicroweberPackages\CdnSync\Services\CdnSyncService`
- Contract: `MicroweberPackages\CdnSync\Contracts\CdnSyncable`
- Trait: `MicroweberPackages\CdnSync\Traits\HasCdnSync`
- Model: `MicroweberPackages\CdnSync\Models\CdnSyncLog` (table `cdn_sync_log`)
- Command: `MicroweberPackages\CdnSync\Console\Commands\CdnSyncCorsCommand` (`cdn-sync:cors`)
- Filament plugin: `MicroweberPackages\CdnSync\Filament\CdnSyncPlugin`
- Provider: `MicroweberPackages\CdnSync\CdnSyncServiceProvider`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
