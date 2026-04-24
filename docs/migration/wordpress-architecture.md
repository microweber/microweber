# WordPress migration — contributor architecture reference

> **Scope of this document:** Phase-11 contributor reference. A
> one-stop index for engineers opening a PR against the
> `Modules/WordPressMigration` surface — points at every service, its
> test, and the ADR section that constrains its behaviour. Companion
> docs: `wordpress.md` (user-facing walkthrough),
> `wordpress-scope.md` (what is / isn't in scope),
> `wordpress-mapping.md` (field-by-field mapping contract),
> `wordpress-audit.md` (reuse map against existing Microweber seams),
> and `../adr/wordpress-migration.md` (cross-cutting policy ADR).

---

## 1. Module shape at a glance

```
Modules/WordPressMigration/
├── Console/Commands/               → headless CLIs (Phase 10)
├── Filament/
│   ├── Pages/                      → stateful import + preview pages
│   └── Resources/                  → admin UX (Phase 9)
├── DTOs/                           → MigrationItemDTO — the wire shape
├── Models/                         → WordPressMigrationJob, StagingContent, StagingMedia
├── Services/
│   ├── Importers/                  → one per mode: rest, rss, sitemap, wxr
│   ├── Extractors/                 → per-page HTML → DTO (sitemap path)
│   ├── Http/                       → fetcher abstraction + app-password credential
│   ├── Media/                      → media rehosting + HTML rewriting
│   ├── Taxonomy/                   → term-slug index + live lookup
│   ├── WordPressSiteProbe.php      → capability classifier
│   ├── WordPressContentMapper.php  → DTO → live content row
│   ├── StagingWriter.php           → DTO → staging row (dry-run)
│   ├── StagingCommitter.php        → staging → live, transactional
│   ├── CommitReport.php            → per-row commit outcome aggregate
│   ├── SourceSlugResolver.php      → guid → Microweber-friendly slug
│   └── WordPressMigrationJobRepository.php → job lifecycle + credentials
├── database/migrations/            → wp_migration_jobs + staging tables
└── Tests/
    ├── Unit/                       → importer/service unit coverage
    ├── Feature/                    → Livewire + Artisan feature coverage
    └── Support/                    → FakeHttpProbeFetcher and other fakes
```

The Filament admin UI and the Artisan CLI are both thin orchestration
layers over the `Services/` tree. Every invariant — idempotency,
transactional commit, whole-batch rollback, persisted-error retry
scope — is enforced by the services themselves, not by the calling
surface. A change that bypasses this split (e.g. validating guid
uniqueness in the Livewire page) is almost always the wrong shape.

---

## 2. Core data flow

```
URL → WordPressSiteProbe → capabilities list
                      ↓
             Importer (rest|rss|sitemap|wxr)
                      ↓
              MigrationItemDTO (per-item payload)
                      ↓
        ┌─────────────┴─────────────┐
        ▼ (dry-run)                 ▼ (live)
  StagingWriter             WordPressContentMapper
  wp_migration_staging_*    content + content_data + media
        ↓
  StagingCommitter (operator-triggered)
        ↓
  content + content_data + media
```

- **DTO is the stable contract.** Every importer speaks it; the
  mapper and writer consume it. Adding a new mode means adding a new
  importer that emits DTOs — no other surface changes.
- **Idempotency key is `(import_source, source_guid)`** on the live
  side and `(job_id, source_guid)` on staging. Re-runs upsert onto
  the same row. `import_source` is `wordpress_rest`,
  `wordpress_rss`, `wordpress_sitemap`, or `wordpress_wxr`.

---

## 3. ADR pointers

Every non-obvious cross-cutting policy lives in
`../adr/wordpress-migration.md` and must be consulted *before* a PR
changes that behaviour. Mapping:

| ADR section                      | Constrains                                                                                     |
|----------------------------------|------------------------------------------------------------------------------------------------|
| §1 Auth strategy per mode        | `Services/Http/WpAppPasswordCredential.php`, `Services/WordPressMigrationJobRepository.php::storeProbeResult` (credential hygiene + 24h TTL) |
| §2 Rate-limit + retry budget     | `Services/Importers/*::walk()`, `Services/Http/CurlHttpProbeFetcher.php`                        |
| §3 Idempotency key policy        | `Services/WordPressContentMapper.php` (`IMPORT_SOURCE_*`, `META_*` constants), `Services/StagingWriter.php`, `Services/StagingCommitter.php` |
| §4 Error handling + failure blast radius | `Services/StagingCommitter.php` (chunked transactions, `last_commit_error`), `Services/CommitReport.php` |

A PR that touches auth, idempotency keys, retry budgets, or the
rollback shape should link to the ADR section it is amending in the
PR description.

---

## 4. Service → test index

### 4.1 Importers (URL-driven)

| Service                                                           | Kind  | Purpose                                                                       | Tests                                                                                                 |
|-------------------------------------------------------------------|-------|-------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| `Services/Importers/WpRestImporter.php`                           | walk  | `/wp-json/wp/v2/{posts,pages}` paginator → DTO                                | `Tests/Unit/WpRestImporterTest.php`, `Tests/Unit/WpRestImporterFixtureTest.php`                       |
| `Services/Importers/RssFeedImporter.php`                          | walk  | `/feed` paginator → DTO                                                        | `Tests/Unit/RssFeedImporterTest.php`, `Tests/Unit/RssFeedImporterFixtureTest.php`, `Tests/Unit/RssFeedWalkerTest.php` |
| `Services/Importers/SitemapImporter.php`                          | fan-out | `/sitemap.xml` → per-post URL list                                          | `Tests/Unit/SitemapImporterTest.php`                                                                  |
| `Services/Importers/SitemapPageImporter.php`                      | walk  | Per-URL HTML fetch + `SitemapPageExtractor` → DTO                              | `Tests/Unit/SitemapPageImporterTest.php`                                                              |
| `Services/Extractors/SitemapPageExtractor.php`                    | pure  | HTML string → DTO (OpenGraph + heuristics)                                     | `Tests/Unit/SitemapPageExtractorTest.php`                                                             |

### 4.2 Importers (offline)

| Service                                                           | Kind  | Purpose                                                                        | Tests                                                                                                 |
|-------------------------------------------------------------------|-------|--------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| `Services/Importers/WxrImporter.php`                              | parse | Local `.xml` WXR export → DTO list + taxonomy index                            | `Tests/Unit/WxrImporterTest.php`                                                                      |

### 4.3 Probe + HTTP

| Service                                                           | Kind           | Purpose                                                                    | Tests                                                       |
|-------------------------------------------------------------------|----------------|----------------------------------------------------------------------------|-------------------------------------------------------------|
| `Services/WordPressSiteProbe.php`                                 | classifier     | URL → `WordPressSiteProbeResult` (capabilities, estimated counts)          | `Tests/Unit/WordPressSiteProbeTest.php`                     |
| `Services/WordPressSiteProbeResult.php`                           | value object   | Probe outcome shape: mode, capabilities, `estimated_posts`, `estimated_pages` | covered via `WordPressSiteProbeTest`                     |
| `Services/Http/HttpProbeFetcher.php`                              | interface      | Swappable fetcher — bound to `CurlHttpProbeFetcher` in prod, `FakeHttpProbeFetcher` in tests | —                                                  |
| `Services/Http/CurlHttpProbeFetcher.php`                          | implementation | curl-backed fetcher, honours ADR §2 retry budget                           | exercised end-to-end by fixture-backed tests                |
| `Services/Http/WpAppPasswordCredential.php`                       | value object   | Application-password storage shape; encrypts at rest, 24h TTL              | `Tests/Unit/WpAppPasswordCredentialTest.php`                |
| `Tests/Support/FakeHttpProbeFetcher.php`                          | test fake      | Scripted URL → response table for probe and importer tests                 | —                                                           |

### 4.4 Media

| Service                                                           | Kind       | Purpose                                                                 | Tests                                                                                                 |
|-------------------------------------------------------------------|------------|-------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| `Services/Media/MediaRehoster.php`                                | interface  | Download + dedup source URL → `RehostReceipt`                           | —                                                                                                     |
| `Services/Media/WordPressMediaRehoster.php`                       | implementation | Primary rehoster with sha256 dedup + mime sniffing                   | `Tests/Unit/WordPressMediaRehosterTest.php`                                                           |
| `Services/Media/MicroweberMediaRehoster.php`                      | adapter    | Alt binding that pipes through Microweber's existing media save path     | —                                                                                                     |
| `Services/Media/HtmlMediaRewriter.php`                            | pure       | Rewrite `<img src>` / `<a href>` in imported HTML to rehosted URLs      | `Tests/Unit/HtmlMediaRewriterTest.php`                                                                |
| `Services/Media/RehostReceipt.php`                                | value      | `{media_id, public_url}` returned to the mapper for inline rewrites     | covered via `WordPressMediaRehosterTest`                                                              |

### 4.5 Taxonomy + slug

| Service                                                           | Kind    | Purpose                                                                   | Tests                                                   |
|-------------------------------------------------------------------|---------|---------------------------------------------------------------------------|---------------------------------------------------------|
| `Services/Taxonomy/TaxonomyIndex.php`                             | builder | Channel-level category/tag/user blocks → `TaxonomyLookup`                 | `Tests/Feature/TaxonomyIndexTest.php`                   |
| `Services/Taxonomy/TaxonomyLookup.php`                            | query   | Slug/name → local category id with create-on-miss                         | `Tests/Unit/TaxonomyLookupTest.php`                     |
| `Services/SourceSlugResolver.php`                                 | pure    | Source guid/URL → Microweber content slug                                 | `Tests/Feature/SourceSlugResolverTest.php`              |

### 4.6 Dispatch (DTO → row)

| Service                                                           | Kind           | Purpose                                                                   | Tests                                                                                                                                            |
|-------------------------------------------------------------------|----------------|---------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------|
| `Services/WordPressContentMapper.php`                             | mapper         | DTO → live `content` + `content_data` + `media` rows, idempotent on `(import_source, source_guid)` | `Tests/Feature/WordPressContentMapperTest.php`, `Tests/Feature/WordPressContentMapperTaxonomyTest.php`, `Tests/Feature/WordPressContentMapperMediaRewriteTest.php` |
| `Services/StagingWriter.php`                                      | writer         | DTO → `wp_migration_staging_*` rows, idempotent on `(job_id, source_guid)` | `Tests/Feature/StagingWriterTest.php`                                                                                                           |
| `Services/StagingCommitter.php`                                   | promoter       | Staging → live, chunked transactions, whole-batch rollback, persisted `last_commit_error` | `Tests/Feature/StagingCommitterTest.php`                                                                                                        |
| `Services/CommitReport.php`                                       | value          | Per-row outcome: `committed[]`, `failed[]`, `skipped`                     | covered via `StagingCommitterTest`                                                                                                               |

### 4.7 Job lifecycle

| Service                                                           | Kind       | Purpose                                                                | Tests                                                   |
|-------------------------------------------------------------------|------------|------------------------------------------------------------------------|---------------------------------------------------------|
| `Services/WordPressMigrationJobRepository.php`                    | repository | Probe result → upsert, status transitions, credential TTL pruning      | `Tests/Feature/WordPressMigrationJobRepositoryTest.php` |
| `Models/WordPressMigrationJob.php`                                | Eloquent   | Row model with `encrypted_credentials` cast, status constants, `isTerminal()` | covered via repository test                      |
| `Models/StagingContent.php`, `Models/StagingMedia.php`            | Eloquent   | Staging row models; carry `last_commit_error` and the excluded flag    | covered via `StagingCommitterTest` + `StagingWriterTest` |

---

## 5. Filament surfaces (Phase 9)

| Surface                                                                                    | Type                | Test                                                                                        |
|--------------------------------------------------------------------------------------------|---------------------|---------------------------------------------------------------------------------------------|
| `Filament/Resources/WordPressMigrationResource.php`                                        | Resource            | `Tests/Feature/WordPressMigrationResourceTest.php`                                          |
| `Filament/Resources/WordPressMigrationResource/Pages/ListWordPressMigrations.php`          | Resource index      | covered via `WordPressMigrationResourceTest`                                                |
| `Filament/Resources/WordPressMigrationResource/Pages/CreateWordPressMigration.php`         | Create → redirect    | covered via `WordPressMigrationResourceTest`                                                |
| `Filament/Resources/WordPressMigrationResource/Pages/ViewWordPressMigration.php`           | Job detail + polled progress | `Tests/Feature/WordPressMigrationProgressPollingTest.php`, `WordPressMigrationResourceTest` |
| `Filament/Resources/WordPressMigrationResource/Pages/WordPressMigrationLogsPage.php`       | Per-item log view   | covered via `WordPressMigrationResourceTest`                                                |
| `Filament/Pages/WordPressMigrationImportPage.php`                                          | Stateful probe + start-import | `Tests/Feature/WordPressMigrationImportPageTest.php`                                 |
| `Filament/Pages/WordPressMigrationPreviewPage.php`                                         | Preview + commit + retry    | `Tests/Feature/WordPressMigrationPreviewPageTest.php`                                  |

Filament pages are deliberately thin — they read state, call into
`Services/`, and render. Business logic that tries to live on a page
is almost always better as a method on `StagingCommitter`,
`WordPressMigrationJobRepository`, or the mapper, where it can be
tested without a Livewire harness.

---

## 6. Artisan CLIs (Phase 10)

| Command                                                        | File                                                                         | Test                                                           |
|----------------------------------------------------------------|------------------------------------------------------------------------------|----------------------------------------------------------------|
| `microweber:import:wordpress`                                  | `Console/Commands/ImportWordPressCommand.php`                                | `Tests/Feature/ImportWordPressCommandTest.php`                 |
| `microweber:import:wordpress:status`                           | `Console/Commands/ImportWordPressStatusCommand.php`                          | `Tests/Feature/ImportWordPressStatusCommandTest.php`           |
| `microweber:import:wordpress:commit`                           | `Console/Commands/ImportWordPressCommitCommand.php`                          | `Tests/Feature/ImportWordPressCommitCommandTest.php`           |

Plus a single-purpose smoke: `Tests/Feature/ImportWordPressFixtureSmokeTest.php`
boots `tests/fixtures/wp/router.php` on a PHP built-in server and
asserts the CLI produces non-zero staged rows. Mirrors the
`.github/workflows/wordpress-import-smoke.yml` CI step so
regressions surface locally before CI.

---

## 7. Dusk end-to-end coverage

| Test                                                                           | Exercises                                                                                                         |
|--------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------|
| `tests/Browser/LiveAdminWordPressMigrationProbeTest.php`                       | URL probe against the WP fixture; surface capabilities + counts                                                   |
| `tests/Browser/LiveAdminWordPressMigrationRestTest.php`                        | REST-mode full walk + commit                                                                                      |
| `tests/Browser/LiveAdminWordPressMigrationRssTest.php`                         | RSS-mode full walk + commit                                                                                       |
| `tests/Browser/LiveAdminWordPressMigrationSitemapTest.php`                     | Sitemap-mode fan-out + commit                                                                                     |
| `tests/Browser/LiveAdminWordPressMigrationWxrTest.php`                         | WXR upload → import → frontend visibility                                                                         |
| `tests/Browser/LiveAdminWordPressMigrationPreviewCommitTest.php`               | Preview exclude → commit → excluded guid never reaches live                                                       |
| `tests/Browser/LiveAdminWordPressMigrationUxTest.php`                          | Phase-9 resource click-through: create → progress → preview → commit                                              |
| `tests/Browser/DocsWordPressImportScreenshotsTest.php` (group `docs`, opt-in) | Regenerates `docs/migration/screenshots/` — doc capture utility, not a regression test                            |

The first six are part of the default `composer test:browser` run.
The `UxTest` proves the resource pages stitch the flow together
without re-exercising per-importer correctness. The `docs` test is
opt-in: `php artisan dusk --group=docs` regenerates the walkthrough
screenshots on demand.

---

## 8. Database schema snapshot

Three migrations own the module's persistence:

| Migration                                                                                                  | Tables                                                                                                  |
|------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------|
| `database/migrations/2026_04_23_000001_create_wp_migration_jobs_table.php`                                 | `wp_migration_jobs` — one row per source URL / WXR file, holds probe payload, progress JSON, encrypted creds |
| `database/migrations/2026_04_23_000002_create_wp_migration_staging_tables.php`                             | `wp_migration_staging_content`, `wp_migration_staging_media` — dry-run destination, preview source      |
| `database/migrations/2026_04_24_000003_add_last_commit_error_to_wp_migration_staging_content.php`          | Adds `last_commit_error`, `last_committed_at` to `wp_migration_staging_content` for the retry-failed flow |

Live content lands on Microweber's existing `content` + `content_data`
+ `media` tables — the module **never** introduces a parallel live
table. See `wordpress-mapping.md` for the field-by-field contract.

---

## 9. Where to start contributing

- **Adding a new import mode:** implement a new walker under
  `Services/Importers/`, have it emit `MigrationItemDTO`, wire it
  into the CLI dispatcher and the Filament start-import path. No
  changes needed to the mapper, writer, committer, or any admin
  page. Tag the new mode in `WordPressContentMapper`'s
  `IMPORT_SOURCE_*` constants.
- **Changing what fields land on `content`:** edit
  `WordPressContentMapper::map()` and update its test triplet
  (`WordPressContentMapperTest`, `...TaxonomyTest`,
  `...MediaRewriteTest`). Do not re-implement mapping in the
  staging committer — the committer reconstructs a DTO and calls
  the same mapper.
- **Changing retry/commit chunk shape:** edit `StagingCommitter`
  and its test. The CommitReport shape is the stable JSON surface
  the admin UI renders — don't rename its fields without updating
  `WordPressMigrationPreviewPage` and the CLIs at the same time.
- **Changing credential handling:** consult ADR §1 first. Changes
  that widen credential persistence beyond the 24h TTL must update
  the ADR and the `pruneExpiredCredentials` test.
- **Adding an admin-UI affordance:** prefer the resource's View or
  Preview page over a new standalone Filament page. Only the
  import and preview pages are stateful enough to warrant living
  outside the resource.
