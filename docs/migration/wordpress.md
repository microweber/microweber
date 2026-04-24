# WordPress Migration — operator & scripted-use guide

> **Scope of this document:** Phase-10 deliverable. End-to-end walkthrough
> for operators migrating content from WordPress into Microweber, both
> via the Filament admin UI and via the artisan CLI. For scope
> boundaries see `wordpress-scope.md`; for the field mapping contract
> see `wordpress-mapping.md`; for the architecture decision record
> see `../adr/wordpress-migration.md`.

---

## 1. Quick start

Four one-liners that cover the 80% of cases. Each is explained in
detail below.

```bash
# 1. Preview an import without writing to live content.
php artisan microweber:import:wordpress https://example.com --dry-run --yes

# 2. Commit the staged rows onto live content.
#    (Replace 42 with the job id printed by the previous command.)
php artisan microweber:import:wordpress:commit 42 --yes

# 3. Check a job's status at any point.
php artisan microweber:import:wordpress:status 42

# 4. Import from a WordPress WXR export file.
php artisan microweber:import:wordpress /path/to/export.xml \
  --mode=wxr --yes
```

---

## 2. How the pipeline fits together

```
┌───────────────────────────────────────────────────────────────────┐
│                                                                   │
│   WordPress source                                                │
│   ──────────────────                                              │
│   https://example.com   →  probe   →  pick strongest mode         │
│   export.xml (WXR)      →  parse   →  mode=wxr                    │
│                                                                   │
│                                   ▼                               │
│                                                                   │
│   Importers (one per mode)                                        │
│   ────────────────────────                                        │
│   WpRestImporter   · wp-json/v2 endpoints                         │
│   RssFeedImporter  · /feed, paginated                             │
│   SitemapPageImporter · /sitemap.xml fan-out                      │
│   WxrImporter      · offline XML parse                            │
│                                                                   │
│                                   ▼                               │
│                                                                   │
│   Per-item MigrationItemDTO                                       │
│                                                                   │
│                                   ▼                               │
│                                                                   │
│   Dispatcher                                                      │
│   ──────────                                                      │
│   --dry-run  →  StagingWriter    →  wp_migration_staging_content  │
│   default    →  WordPressContentMapper →  content / content_data  │
│                                                                   │
│                                   ▼                               │
│                                                                   │
│   Filament admin UI                                               │
│   ──────────────────                                              │
│   Preview page (exclude rows)                                     │
│   Commit to live  →  StagingCommitter                             │
│                                                                   │
└───────────────────────────────────────────────────────────────────┘
```

Key invariants:

1. **Dry-run is zero-writes.** `--dry-run` goes through `StagingWriter`
   and never touches the live `content` or `media` tables. The same
   staging rows the admin preview page reads are what the CLI
   populates, so operators can stage from the shell and commit from
   the UI, or vice versa.
2. **Idempotency by (import_source, source_guid).** Re-running against
   the same source is safe — existing rows are upserted, never
   duplicated. Every row carries `content_data` markers
   `import_source=wordpress_{mode}` and `source_guid=<guid>`.
3. **Failures don't poison the batch.** The commit path runs each
   chunk inside its own DB transaction. A single mapping failure
   rolls back that chunk; subsequent chunks still proceed, and the
   failed row persists `last_commit_error` so the operator can
   `--retry-failed` after fixing the root cause.

---

## 3. CLI reference

### 3.1 `microweber:import:wordpress`

The headless driver. Probes a URL or parses a WXR file, walks the
importer, and either writes to live content or stages for preview.

```
microweber:import:wordpress <url>
  [--mode=rest|rss|sitemap|wxr]
  [--dry-run]
  [--yes]
  [--max=100]
```

| Flag            | Default | Meaning                                                              |
|-----------------|---------|----------------------------------------------------------------------|
| `url`           | —       | WordPress site URL; or a local `.xml` path when `--mode=wxr`         |
| `--mode`        | auto    | Force a specific importer. When omitted, probe picks the strongest (`rest > rss > sitemap`) |
| `--dry-run`     | off     | Write to `wp_migration_staging_*` instead of live `content`          |
| `--yes`         | off     | Auto-accept the confirmation prompt (required for CI)                |
| `--max`         | 100     | Cap on items walked this run; must be a positive integer             |

**Exit codes**

| Code | Meaning                                                    |
|------|------------------------------------------------------------|
| 0    | Success (items staged or committed)                        |
| 1    | Unreachable source / no usable importer                    |
| 2    | Validation error (bad URL, unknown mode, missing file)     |
| 3    | Importer raised mid-run                                    |

### 3.2 `microweber:import:wordpress:status`

Inspect a job. Useful as a "done yet?" poll from a CI script.

```
microweber:import:wordpress:status <job-id> [--json]
```

- Human output (default) prints status, mode, URL, progress counters,
  and staging counts in a labelled block.
- `--json` emits a machine-readable payload. Keys are stable:
  `job_id`, `status`, `mode`, `source_url`, `source_host`,
  `started_at`, `finished_at`, `progress.{processed, imported,
  failed, total, stop_reason}`, `staging.{staged, excluded,
  last_commit_error_rows}`, `last_error`.

**Exit codes**

| Code | Meaning                                        |
|------|------------------------------------------------|
| 0    | Job found (status itself is the signal)        |
| 2    | Validation error                               |
| 4    | Job not found                                  |

### 3.3 `microweber:import:wordpress:commit`

Promotes staging rows for a job onto live content. Same code path as
the Filament preview page's "Commit to live" button.

```
microweber:import:wordpress:commit <job-id>
  [--yes]
  [--retry-failed]
```

- Runs staging rows in chunks inside DB transactions. A chunk that
  throws is fully rolled back; its rows are flagged with
  `last_commit_error` and the command continues to the next chunk.
- `--retry-failed` narrows the commit to rows carrying a prior
  `last_commit_error`. Use this after fixing a root cause
  (missing taxonomy, flaky media origin, etc.).

**Exit codes**

| Code | Meaning                                     |
|------|---------------------------------------------|
| 0    | Commit finished with zero failures          |
| 2    | Validation error                            |
| 4    | Job not found                               |
| 5    | Commit finished with ≥ 1 failed row         |

---

## 4. End-to-end example — "migrate blog.example.com over the weekend"

Goal: a staged import on Friday afternoon, an operator eyeball on
Saturday via the Filament admin UI, and a scripted commit on Sunday
morning once the team has signed off.

### 4.1 Friday — stage the import

```bash
# Stage up to 500 items. No live content is written at this step.
php artisan microweber:import:wordpress https://blog.example.com \
  --mode=rest \
  --dry-run \
  --yes \
  --max=500

# Output ends with a "Dry-run complete: N items staged for preview"
# summary. Capture the job id from the command — or pluck it via:
JOB_ID=$(php artisan microweber:import:wordpress:status \
  "$(mysql -Ne 'select max(id) from wp_migration_jobs;')" --json \
  | jq -r '.job_id')
```

Check it landed:

```bash
php artisan microweber:import:wordpress:status "$JOB_ID" --json | jq
```

Expected:

```json
{
  "job_id": 42,
  "status": "finished",
  "mode": "rest",
  "progress": { "processed": 500, "imported": 500, "failed": 0, "total": null },
  "staging": { "staged": 500, "excluded": 0, "last_commit_error_rows": 0 }
}
```

### 4.2 Saturday — operator preview

The operator opens **Content → Import from WordPress** in the
Filament sidebar, clicks into the job, and hits **Preview staging**.
They uncheck 4 rows that look like draft cruft, then leave the rest
untouched. No CLI involved; changes live on the staging rows.

### 4.3 Sunday — commit to live

```bash
php artisan microweber:import:wordpress:commit "$JOB_ID" --yes
```

A clean commit prints:

```
Commit complete: 496 committed, 4 skipped (excluded), 0 failed.
```

If anything failed:

```bash
# Inspect the failure reason.
php artisan microweber:import:wordpress:status "$JOB_ID" --json \
  | jq '.staging.last_commit_error_rows'

# Fix the root cause (e.g. a missing taxonomy) and retry only
# the rows that failed. Successfully retried rows leave staging;
# any still-failing rows keep their persisted error message.
php artisan microweber:import:wordpress:commit "$JOB_ID" \
  --retry-failed --yes
```

---

## 5. WXR offline imports

When the source site is offline, behind a login wall, or on
`wordpress.com` (which has a different API surface), export a WXR
file from the source WordPress admin via **Tools → Export**, then:

```bash
php artisan microweber:import:wordpress \
  /var/backups/example-2026-04.xml \
  --mode=wxr \
  --dry-run \
  --yes

# Review in the Filament preview page as usual, then commit:
php artisan microweber:import:wordpress:commit <job-id> --yes
```

WXR is the only mode that does not require network access to the
source. It reads the file in-process and produces the same
`MigrationItemDTO` payload as the URL-based importers, so the
downstream pipeline (staging → preview → commit) is identical.

---

## 6. CI / scripted pipelines

A typical CI smoke check:

```yaml
- name: Smoke-test WordPress importer
  run: |
    php -S 127.0.0.1:9876 -t tests/fixtures/wp \
      tests/fixtures/wp/router.php &
    sleep 2
    php artisan microweber:import:wordpress \
      http://127.0.0.1:9876 \
      --mode=rss --dry-run --yes --max=10
```

- `--yes` skips the prompt that would otherwise block the pipeline.
- `--dry-run` keeps the CI environment's live content table untouched.
- The command's non-zero exit codes (§3.1) are usable as-is for
  CI gate conditions — 0 for clean, 1 for unreachable, 2 for bad
  inputs, 3 for mid-run errors.

For a scripted production cutover, the `status --json` payload is
designed to be machine-parsed. A typical sequence:

```bash
JOB_ID=42

# Wait for the staging job to finish.
while true; do
  STATUS=$(php artisan microweber:import:wordpress:status \
    "$JOB_ID" --json | jq -r '.status')
  case "$STATUS" in
    finished)   echo "Staging done."; break ;;
    failed|unreachable|canceled)
                echo "Staging reached terminal state: $STATUS" >&2
                exit 1 ;;
    *)          sleep 10 ;;
  esac
done

# Commit. Exit 5 means some rows failed — propagate it.
php artisan microweber:import:wordpress:commit "$JOB_ID" --yes \
  || exit $?
```

---

## 7. Where things live

| Surface                              | File                                                                                       |
|--------------------------------------|--------------------------------------------------------------------------------------------|
| Driver command                       | `Modules/WordPressMigration/Console/Commands/ImportWordPressCommand.php`                   |
| Status command                       | `Modules/WordPressMigration/Console/Commands/ImportWordPressStatusCommand.php`             |
| Commit command                       | `Modules/WordPressMigration/Console/Commands/ImportWordPressCommitCommand.php`             |
| Filament admin resource              | `Modules/WordPressMigration/Filament/Resources/WordPressMigrationResource.php`             |
| Probe → capabilities                 | `Modules/WordPressMigration/Services/WordPressSiteProbe.php`                               |
| REST / RSS / sitemap / WXR importers | `Modules/WordPressMigration/Services/Importers/`                                           |
| Staging writer (dry-run)             | `Modules/WordPressMigration/Services/StagingWriter.php`                                    |
| Live mapper                          | `Modules/WordPressMigration/Services/WordPressContentMapper.php`                           |
| Committer (promote staging → live)   | `Modules/WordPressMigration/Services/StagingCommitter.php`                                 |

The CLI is a thin orchestration layer over these services — it does
not duplicate importer logic, and every invariant (idempotency,
transactional commit, whole-batch rollback) is enforced by the same
code the admin UI uses.
