# `WordPressMigration` module

> **Slug:** `word-press-migration`
> **Tier:** 2
>
> Tier-2 module — service / API surface on top of shared infrastructure.
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

Migrations under `Modules/WordPressMigration/database/migrations/`:

  - `database/migrations/2026_04_23_000001_create_wp_migration_jobs_table.php`
  - `database/migrations/2026_04_23_000002_create_wp_migration_staging_tables.php`
  - `database/migrations/2026_04_24_000003_add_last_commit_error_to_wp_migration_staging_content.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\WordPressMigration\Models\StagingContent` | `Models/StagingContent.php` |
| `Modules\WordPressMigration\Models\StagingMedia` | `Models/StagingMedia.php` |
| `Modules\WordPressMigration\Models\WordPressMigrationJob` | `Models/WordPressMigrationJob.php` |

## Service classes

  - `Modules\WordPressMigration\Services\CommitReport`
  - `Modules\WordPressMigration\Services\Extractors\SitemapPageExtractor`
  - `Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher`
  - `Modules\WordPressMigration\Services\Http\HttpProbeFetcher`
  - `Modules\WordPressMigration\Services\Http\WpAppPasswordCredential`
  - `Modules\WordPressMigration\Services\Importers\RssFeedImporter`
  - `Modules\WordPressMigration\Services\Importers\SitemapImporter`
  - `Modules\WordPressMigration\Services\Importers\SitemapPageImporter`
  - `Modules\WordPressMigration\Services\Importers\WpRestImporter`
  - `Modules\WordPressMigration\Services\Importers\WxrImporter`
  - `Modules\WordPressMigration\Services\Media\HtmlMediaRewriter`
  - `Modules\WordPressMigration\Services\Media\MediaRehoster`
  - `Modules\WordPressMigration\Services\Media\MicroweberMediaRehoster`
  - `Modules\WordPressMigration\Services\Media\RehostReceipt`
  - `Modules\WordPressMigration\Services\Media\WordPressMediaRehoster`
  - `Modules\WordPressMigration\Services\SourceSlugResolver`
  - `Modules\WordPressMigration\Services\StagingCommitter`
  - `Modules\WordPressMigration\Services\StagingWriter`
  - `Modules\WordPressMigration\Services\Taxonomy\TaxonomyIndex`
  - `Modules\WordPressMigration\Services\Taxonomy\TaxonomyLookup`
  - `Modules\WordPressMigration\Services\WordPressContentMapper`
  - `Modules\WordPressMigration\Services\WordPressMigrationJobRepository`
  - `Modules\WordPressMigration\Services\WordPressSiteProbe`
  - `Modules\WordPressMigration\Services\WordPressSiteProbeResult`

## Filament admin

  - `Modules\WordPressMigration\Filament\Pages\WordPressMigrationImportPage`
  - `Modules\WordPressMigration\Filament\Pages\WordPressMigrationPreviewPage`
  - `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource`
  - `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\CreateWordPressMigration`
  - `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ListWordPressMigrations`
  - `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ViewWordPressMigration`
  - `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\WordPressMigrationLogsPage`
  - `Modules\WordPressMigration\Filament\Widgets\WordPressImportCtaWidget`

## Tests

Run: `php vendor/bin/phpunit Modules/WordPressMigration/Tests`

Test files:

  - `Tests/Feature/ImportWordPressCommandTest.php`
  - `Tests/Feature/ImportWordPressCommitCommandTest.php`
  - `Tests/Feature/ImportWordPressFixtureSmokeTest.php`
  - `Tests/Feature/ImportWordPressStatusCommandTest.php`
  - `Tests/Feature/SourceSlugResolverTest.php`
  - `Tests/Feature/StagingCommitterTest.php`
  - `Tests/Feature/StagingWriterTest.php`
  - `Tests/Feature/TaxonomyIndexTest.php`
  - `Tests/Feature/WordPressContentMapperMediaRewriteTest.php`
  - `Tests/Feature/WordPressContentMapperTaxonomyTest.php`
  - `Tests/Feature/WordPressContentMapperTest.php`
  - `Tests/Feature/WordPressImportCtaWidgetTest.php`
  - `Tests/Feature/WordPressMigrationImportPageTest.php`
  - `Tests/Feature/WordPressMigrationJobRepositoryTest.php`
  - `Tests/Feature/WordPressMigrationPreviewPageTest.php`
  - `Tests/Feature/WordPressMigrationProgressPollingTest.php`
  - `Tests/Feature/WordPressMigrationResourceTest.php`
  - `Tests/Support/FakeHttpProbeFetcher.php`
  - `Tests/Unit/HtmlMediaRewriterTest.php`
  - `Tests/Unit/RssFeedImporterFixtureTest.php`
  - `Tests/Unit/RssFeedImporterTest.php`
  - `Tests/Unit/RssFeedWalkerTest.php`
  - `Tests/Unit/SitemapImporterTest.php`
  - `Tests/Unit/SitemapPageExtractorTest.php`
  - `Tests/Unit/SitemapPageImporterTest.php`
  - `Tests/Unit/TaxonomyLookupTest.php`
  - `Tests/Unit/WordPressMediaRehosterTest.php`
  - `Tests/Unit/WordPressSiteProbeTest.php`
  - `Tests/Unit/WpAppPasswordCredentialTest.php`
  - `Tests/Unit/WpRestImporterFixtureTest.php`
  - …2 more.

## Service providers

  - `Modules\WordPressMigration\Providers\WordPressMigrationServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
