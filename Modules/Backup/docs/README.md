# `Backup` module

> **Slug:** `backup`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Backup/database/migrations/`:

  - `Database/migrations/2026_03_22_000001_create_backup_schedules_table.php`
  - `Database/migrations/2026_03_22_000002_create_backup_history_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Backup\Models\Backup` | `Models/Backup.php` |
| `Modules\Backup\Models\BackupHistory` | `Models/BackupHistory.php` |
| `Modules\Backup\Models\BackupSchedule` | `Models/BackupSchedule.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Backup\Http\Controllers\Admin\BackupController`
  - `Modules\Backup\Http\Controllers\Admin\LanguageController`

## Service classes

  - `Modules\Backup\Services\AutomatedBackupService`

## Filament admin

  - `Modules\Backup\Filament\Resources\BackupHistoryResource`
  - `Modules\Backup\Filament\Resources\BackupHistoryResource\Pages\ListBackupHistory`
  - `Modules\Backup\Filament\Resources\BackupHistoryResource\Pages\ViewBackupHistory`
  - `Modules\Backup\Filament\Resources\BackupResource`
  - `Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups`
  - `Modules\Backup\Filament\Resources\BackupScheduleResource`
  - `Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\CreateBackupSchedule`
  - `Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\EditBackupSchedule`
  - `Modules\Backup\Filament\Resources\BackupScheduleResource\Pages\ListBackupSchedules`

## Tests

Run: `php vendor/bin/phpunit Modules/Backup/Tests`

Test files:

  - `Tests/AutomatedBackupTest.php`
  - `Tests/Filament/BackupResourceTest.php`
  - `Tests/GenerateBackupTest.php`
  - `Tests/GenerateBackupTestUserfiles.php`
  - `Tests/RestoreBackupTest.php`
  - `Tests/SessionStepperTest.php`
  - `Tests/Unit/Filament/BackupHistoryResourceTest.php`
  - `Tests/Unit/Filament/BackupResourceTest.php`
  - `Tests/Unit/Filament/BackupScheduleResourceTest.php`
  - `Tests/ZExportTest.php`

## Service providers

  - `Modules\Backup\Providers\BackupServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
