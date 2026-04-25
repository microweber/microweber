# `Backup` module

> **Slug:** `backup`
> **Tier:** 1
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `backup_schedules` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `type` | `string` | has-default |
  | `tables` | `text` | nullable |
  | `include_media` | `boolean` | has-default |
  | `frequency` | `string` | has-default |
  | `time` | `string` | nullable |
  | `day_of_week` | `integer` | nullable |
  | `day_of_month` | `integer` | nullable |
  | `retention_days` | `integer` | has-default |
  | `is_active` | `boolean` | has-default |
  | `last_run_at` | `timestamp` | nullable |
  | `next_run_at` | `timestamp` | nullable |
  | `timestamps` | `timestamps` | — |
  | `is_active` | `index` | — |
  | `next_run_at` | `index` | — |

### `backup_history` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `backup_schedule_id` | `foreignId` | nullable, foreign-key |
  | `type` | `string` | — |
  | `backup_type` | `string` | — |
  | `filename` | `string` | — |
  | `filepath` | `string` | — |
  | `size` | `bigInteger` | nullable |
  | `status` | `string` | has-default |
  | `tables` | `text` | nullable |
  | `include_media` | `boolean` | has-default |
  | `error_message` | `text` | nullable |
  | `started_at` | `timestamp` | nullable |
  | `completed_at` | `timestamp` | nullable |
  | `timestamps` | `timestamps` | — |
  | `status` | `index` | — |
  | `created_at` | `index` | — |

## Models

### `Modules\Backup\Models\Backup`

Source: `Models/Backup.php`. 

### `Modules\Backup\Models\BackupHistory`

Source: `Models/BackupHistory.php`. Table: `backup_history`. 

**Fillable:** `backup_schedule_id`, `type`, `backup_type`, `filename`, `filepath`, `size`, `status`, `tables`, `include_media`, `error_message`, `started_at`, `completed_at`

**Casts:**

  - `tables` → `array`
  - `include_media` → `boolean`
  - `size` → `integer`
  - `started_at` → `datetime`
  - `completed_at` → `datetime`

### `Modules\Backup\Models\BackupSchedule`

Source: `Models/BackupSchedule.php`. Table: `backup_schedules`. 

**Fillable:** `name`, `type`, `tables`, `include_media`, `frequency`, `time`, `day_of_week`, `day_of_month`, `retention_days`, `is_active`, `last_run_at`, `next_run_at`

**Casts:**

  - `tables` → `array`
  - `include_media` → `boolean`
  - `day_of_week` → `integer`
  - `day_of_month` → `integer`
  - `retention_days` → `integer`
  - `is_active` → `boolean`
  - `last_run_at` → `datetime`
  - `next_run_at` → `datetime`

## API endpoints

Route files exist but no parseable `Route::method` calls were
found:

  - `routes/api.php`

## Controllers

### `Modules\Backup\Http\Controllers\Admin\BackupController`

Source: `Http/Controllers/Admin/BackupController.php`.

  - `get()`
  - `restore(Request $request)`
  - `download(Request $request)`
  - `upload(Request $request)`
  - `start(Request $request)`
  - `generateSessionId()`
  - `delete(Request $request)`
  - `log($log)`

### `Modules\Backup\Http\Controllers\Admin\LanguageController`

Source: `Http/Controllers/Admin/LanguageController.php`.

  - `upload(Request $request)`
  - `export(Request $request)`

## Service classes

### `Modules\Backup\Services\AutomatedBackupService`

Source: `Services/AutomatedBackupService.php`.

  - `processDueSchedules(): array`
  - `executeSchedule(BackupSchedule $schedule): BackupHistory`
  - `executeManualBackup(string $backupType, array $options = []): BackupHistory`
  - `cleanupStaleBackups(int $hours = 24): int`
  - `getStatistics(): array`
  - `getStorageInfo(): array`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Backup\Filament\Resources\BackupHistoryResource` | System Settings | — |
  | `Modules\Backup\Filament\Resources\BackupHistoryResource\Pages\ListBackupHistory` | — | — |
  | `Modules\Backup\Filament\Resources\BackupHistoryResource\Pages\ViewBackupHistory` | — | — |
  | `Modules\Backup\Filament\Resources\BackupResource` | System Settings | — |
  | `Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups` | — | — |
  | `Modules\Backup\Filament\Resources\BackupScheduleResource` | System Settings | — |
  | `Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\CreateBackupSchedule` | — | — |
  | `Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\EditBackupSchedule` | — | — |
  | `Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\ListBackupSchedules` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Backup/Tests`

### `Tests/AutomatedBackupTest.php`

  - `it_calculates_next_run_for_hourly_schedule`
  - `it_calculates_next_run_for_monthly_schedule`
  - `it_does_not_include_inactive_schedules_in_due_query`
  - `it_can_create_backup_history_record`
  - `it_can_mark_history_as_completed`
  - `it_calculates_formatted_size`
  - `it_scopes_completed_backups`
  - `it_scopes_running_backups`
  - `it_scopes_scheduled_backups`
  - `it_retrieves_backup_statistics`
  - …5 more.

### `Tests/Filament/BackupResourceTest.php`

  - `it_can_render_backup_history_list_page`
  - `it_backup_resource_has_model`

### `Tests/Unit/Filament/BackupHistoryResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_download_action_exists_for_completed_backups`

### `Tests/Unit/Filament/BackupResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_restore_action_exists`
  - `it_delete_action_removes_record`

### `Tests/Unit/Filament/BackupScheduleResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_global_search_returns_results`

## Service providers

  - `Modules\Backup\Providers\BackupServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
