# Backup

Site backup system. Create full or selective backups in multiple formats, schedule automated backups, and manage backup history with retention policies.

## Key Features

- Full site backup (database + files)
- Multiple backup formats: JSON, CSV, XLSX, ZIP batch
- Scheduled automated backups (cron-based, runs every minute)
- Backup history tracking
- Automatic cleanup of stale backups
- Selective table backup
- Progress logging during backup
- Backup storage on a dedicated filesystem disk

## Backup Formats

| Format | Class |
|---|---|
| JSON | `Formats\JsonBackup` |
| CSV | `Formats\CsvBackup` |
| XLSX | `Formats\XlsxBackup` |
| ZIP (batch) | `Formats\ZipBatchBackup` |
| Default | `Formats\DefaultBackup` |

## Key Classes

| Class | Purpose |
|---|---|
| `Backup` | Core backup execution |
| `BackupTables` | Table selection for backup |
| `Services\AutomatedBackupService` | Scheduled backup management |
| `Models\Backup` | Backup record model |
| `Models\BackupSchedule` | Schedule configuration |
| `Models\BackupHistory` | Backup execution history |

## Database Tables

Migrations in `Database/migrations/` create tables for backup records, schedules, and history.

## Admin Panel (Filament)

- **BackupResource** -- create and manage backups (also in Settings)
- **BackupScheduleResource** -- configure automated backup schedules
- **BackupHistoryResource** -- view backup execution history

## Artisan Commands

- `php artisan backup:run` -- execute pending backup schedules

## Storage

Backups are stored at `storage/backup_content/{environment}/` using a dedicated `backup` filesystem disk.

## API Routes

Defined in `routes/api.php` for programmatic backup operations.

## Configuration

See `config/backup.php` for retention policies and storage settings.
