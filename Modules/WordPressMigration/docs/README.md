# `WordPressMigration` module

> **Slug:** `word-press-migration`
> **Tier:** 2
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

### `wp_migration_jobs` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `source_url` | `string` | — |
  | `source_url_hash` | `char` | — |
  | `source_host` | `string` | — |
  | `status` | `string` | has-default |
  | `mode` | `string` | nullable |
  | `probe_result` | `json` | nullable |
  | `last_probed_at` | `timestamp` | nullable |
  | `options` | `json` | nullable |
  | `progress` | `json` | nullable |
  | `encrypted_credentials` | `text` | nullable |
  | `credentials_expire_at` | `timestamp` | nullable |
  | `last_error` | `text` | nullable |
  | `started_at` | `timestamp` | nullable |
  | `finished_at` | `timestamp` | nullable |
  | `timestamps` | `timestamps` | — |
  | `source_url_hash` | `unique` | — |
  | `source_host` | `index` | — |
  | `status` | `index` | — |
  | `credentials_expire_at` | `index` | — |

### `wp_migration_staging_content` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `job_id` | `unsignedBigInteger` | — |
  | `import_source` | `string` | — |
  | `source_guid` | `string` | — |
  | `title` | `string` | nullable |
  | `content_html` | `longText` | nullable |
  | `excerpt` | `text` | nullable |
  | `canonical_url` | `string` | nullable |
  | `source_host` | `string` | nullable |
  | `featured_image_url` | `string` | nullable |
  | `author_slug` | `string` | nullable |
  | `posted_at` | `timestamp` | nullable |
  | `categories` | `json` | nullable |
  | `tags` | `json` | nullable |
  | `excluded` | `boolean` | has-default |
  | `timestamps` | `timestamps` | — |
  | `last_commit_error` | `text` | nullable |
  | `last_committed_at` | `timestamp` | nullable |
  | `last_committed_at` | `dropColumn` | — |
  | `last_commit_error` | `dropColumn` | — |

### `wp_migration_staging_media` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `job_id` | `unsignedBigInteger` | — |
  | `staging_content_id` | `unsignedBigInteger` | nullable |
  | `source_url` | `string` | — |
  | `role` | `string` | has-default |
  | `source_url_hash` | `char` | — |
  | `excluded` | `boolean` | has-default |
  | `timestamps` | `timestamps` | — |
  | `staging_content_id` | `index` | — |

## Models

### `Modules\WordPressMigration\Models\StagingContent`

Source: `Models/StagingContent.php`. Table: `wp_migration_staging_content`. 

**Fillable:** `job_id`, `import_source`, `source_guid`, `title`, `content_html`, `excerpt`, `canonical_url`, `source_host`, `featured_image_url`, `author_slug`, `posted_at`, `categories`, `tags`, `excluded`, `last_commit_error`, `last_committed_at`

**Casts:**

  - `job_id` → `integer`
  - `excluded` → `boolean`
  - `posted_at` → `datetime`
  - `last_committed_at` → `datetime`
  - `categories` → `array`
  - `tags` → `array`

### `Modules\WordPressMigration\Models\StagingMedia`

Source: `Models/StagingMedia.php`. Table: `wp_migration_staging_media`. 

**Fillable:** `job_id`, `staging_content_id`, `source_url`, `role`, `source_url_hash`, `excluded`

**Casts:**

  - `job_id` → `integer`
  - `staging_content_id` → `integer`
  - `excluded` → `boolean`

### `Modules\WordPressMigration\Models\WordPressMigrationJob`

Source: `Models/WordPressMigrationJob.php`. Table: `wp_migration_jobs`. 

**Fillable:** `source_url`, `source_url_hash`, `source_host`, `status`, `mode`, `probe_result`, `last_probed_at`, `options`, `progress`, `encrypted_credentials`, `credentials_expire_at`, `last_error`, `started_at`, `finished_at`

**Casts:**

  - `encrypted_credentials` → `encrypted`
  - `last_probed_at` → `datetime`
  - `credentials_expire_at` → `datetime`
  - `started_at` → `datetime`
  - `finished_at` → `datetime`

## Service classes

### `Modules\WordPressMigration\Services\CommitReport`

Source: `Services/CommitReport.php`.

  - `commit(int $stagingId, int $contentId): void`
  - `fail(int $stagingId, string $message): void`
  - `committedCount(): int`
  - `failedCount(): int`
  - `isSuccessful(): bool`

### `Modules\WordPressMigration\Services\Extractors\SitemapPageExtractor`

Source: `Services/Extractors/SitemapPageExtractor.php`.

  - `extract(string $html, string $canonicalUrl = ''): ExtractedPageDTO`

### `Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher`

Source: `Services/Http/CurlHttpProbeFetcher.php`.

  - `fetch(string $url, int $timeout, ?string $authorization = null): array`

### `Modules\WordPressMigration\Services\Http\HttpProbeFetcher`

Source: `Services/Http/HttpProbeFetcher.php`.

  - `fetch(string $url, int $timeout, ?string $authorization = null): array`

### `Modules\WordPressMigration\Services\Http\WpAppPasswordCredential`

Source: `Services/Http/WpAppPasswordCredential.php`.

  - `fromString(string $raw): self`
  - `of(string $username, string $password): self`
  - `username(): string`
  - `authorizationHeader(): string`

### `Modules\WordPressMigration\Services\Importers\RssFeedImporter`

Source: `Services/Importers/RssFeedImporter.php`.

  - `import(string $baseUrl): array`
  - `walk(string $baseUrl, array $seenGuids = [], int $maxItems = 1000): FeedWalkResult`
  - `parseRss(string $xml, ?string $sourceHost = null): array`
  - `parseAtom(string $xml, ?string $sourceHost = null): array`

### `Modules\WordPressMigration\Services\Importers\SitemapImporter`

Source: `Services/Importers/SitemapImporter.php`.

  - `crawl(string $sitemapUrl, int $maxUrls = 10000, ?DateTimeImmutable $modifiedSince = null,): SitemapCrawlResult`
  - `parseUrlset(string $xml, ?string $contentType = null): array`
  - `parseIndex(string $xml): array`
  - `inferContentType(string $url): ?string`

### `Modules\WordPressMigration\Services\Importers\SitemapPageImporter`

Source: `Services/Importers/SitemapPageImporter.php`.

  - `walk(string $sitemapUrl, array $seenGuids = [], int $maxItems = 1000, array $rssFallback = [],): SitemapImportResult`

### `Modules\WordPressMigration\Services\Importers\WpRestImporter`

Source: `Services/Importers/WpRestImporter.php`.

  - `walk(string $baseUrl, array $seenGuids = [], int $maxItems = 1000): WpRestImportResult`

### `Modules\WordPressMigration\Services\Importers\WxrImporter`

Source: `Services/Importers/WxrImporter.php`.

  - `import(string $path, array $seenGuids = [], int $maxItems = 10000): WxrImportResult`
  - `importString(string $xml, array $seenGuids = [], int $maxItems = 10000): WxrImportResult`

### `Modules\WordPressMigration\Services\Media\HtmlMediaRewriter`

Source: `Services/Media/HtmlMediaRewriter.php`.

  - `rewrite(string $html, MediaRehoster $rehoster, array $context = []): string`

### `Modules\WordPressMigration\Services\Media\MediaRehoster`

Source: `Services/Media/MediaRehoster.php`.

  - `rehost(string $url, array $context = []): ?string`

### `Modules\WordPressMigration\Services\Media\MicroweberMediaRehoster`

Source: `Services/Media/MicroweberMediaRehoster.php`.

  - `rehost(string $url, array $context = []): ?string`

### `Modules\WordPressMigration\Services\Media\RehostReceipt`

Source: `Services/Media/RehostReceipt.php`.

### `Modules\WordPressMigration\Services\Media\WordPressMediaRehoster`

Source: `Services/Media/WordPressMediaRehoster.php`.

  - `fetch(string $url, array $context = []): ?RehostReceipt`
  - `rehost(string $url, array $context = []): ?string`

### `Modules\WordPressMigration\Services\SourceSlugResolver`

Source: `Services/SourceSlugResolver.php`.

  - `resolve(string $canonicalUrl, ?int $excludeContentId = null): ?string`

### `Modules\WordPressMigration\Services\StagingCommitter`

Source: `Services/StagingCommitter.php`.

  - `commit(int $jobId): CommitReport`
  - `commitFailedOnly(int $jobId): CommitReport`

### `Modules\WordPressMigration\Services\StagingWriter`

Source: `Services/StagingWriter.php`.

  - `stage(int $jobId, MigrationItemDTO $dto): StagingContent`

### `Modules\WordPressMigration\Services\Taxonomy\TaxonomyIndex`

Source: `Services/Taxonomy/TaxonomyIndex.php`.

  - `prime(array $wpCategories, array $wpTags, array $wpUsers): TaxonomyLookup`

### `Modules\WordPressMigration\Services\Taxonomy\TaxonomyLookup`

Source: `Services/Taxonomy/TaxonomyLookup.php`.

  - `categoryLocalId(string $slug): ?int`
  - `tagLocalId(string $slug): ?int`
  - `userLocalId(string $slug): ?int`
  - `empty(): self`

### `Modules\WordPressMigration\Services\WordPressContentMapper`

Source: `Services/WordPressContentMapper.php`.

  - `map(MigrationItemDTO $dto): Content`
  - `findExisting(string $guid): ?Content`

### `Modules\WordPressMigration\Services\WordPressMigrationJobRepository`

Source: `Services/WordPressMigrationJobRepository.php`.

  - `storeProbeResult(WordPressSiteProbeResult $result, ?string $wpApplicationPassword = null, array $options = [],): WordPressMigrationJob`
  - `findByUrl(string $sourceUrl): ?WordPressMigrationJob`
  - `updateProgress(WordPressMigrationJob $job, array $progress): WordPressMigrationJob`
  - `markRunning(WordPressMigrationJob $job): WordPressMigrationJob`
  - `markFinished(WordPressMigrationJob $job): WordPressMigrationJob`
  - `markFailed(WordPressMigrationJob $job, string $error): WordPressMigrationJob`
  - `markCanceled(WordPressMigrationJob $job): WordPressMigrationJob`
  - `clearCredentials(WordPressMigrationJob $job): WordPressMigrationJob`
  - `pruneExpiredCredentials(): int`
  - `hashUrl(string $sourceUrl): string`

### `Modules\WordPressMigration\Services\WordPressSiteProbe`

Source: `Services/WordPressSiteProbe.php`.

  - `probe(string $rawUrl): WordPressSiteProbeResult`
  - `normalizeUrl(string $raw): ?string`

### `Modules\WordPressMigration\Services\WordPressSiteProbeResult`

Source: `Services/WordPressSiteProbeResult.php`.

  - `isUsable(): bool`
  - `toArray(): array`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\WordPressMigration\Filament\Pages\WordPressMigrationImportPage` | System Settings | WordPress Migration |
  | `Modules\WordPressMigration\Filament\Pages\WordPressMigrationPreviewPage` | System Settings | Preview WordPress import |
  | `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource` | System Settings | Import from WordPress |
  | `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\CreateWordPressMigration` | — | — |
  | `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ListWordPressMigrations` | — | — |
  | `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\ViewWordPressMigration` | — | — |
  | `Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages\WordPressMigrationLogsPage` | — | — |
  | `Modules\WordPressMigration\Filament\Widgets\WordPressImportCtaWidget` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/WordPressMigration/Tests`

### `Tests/Feature/SourceSlugResolverTest.php`

  - `strips_trailing_slash_and_returns_last_real_segment`
  - `url_encoded_segments_are_decoded_then_normalized`
  - `root_only_url_returns_null`
  - `index_html_and_index_php_are_treated_as_non_slugs`
  - `special_characters_collapse_to_single_hyphen_matching_has_slug_trait`
  - `unicode_letters_are_preserved`
  - `collision_walks_up_to_the_next_free_suffix`
  - `exclude_content_id_skips_its_own_row`

### `Tests/Feature/StagingCommitterTest.php`

  - `a_throwing_mapper_rolls_back_the_entire_batch`
  - `a_successful_retry_clears_the_previous_error_before_running`

### `Tests/Feature/WordPressImportCtaWidgetTest.php`

  - `widget_is_hidden_once_any_content_row_exists`
  - `widget_is_registered_against_the_admin_dashboard`

### `Tests/Feature/WordPressMigrationImportPageTest.php`

  - `check_action_runs_probe_and_persists_a_job`
  - `start_import_runs_rss_pipeline_and_finishes_when_feed_is_available`
  - `start_import_refuses_when_source_is_unreachable`

### `Tests/Feature/WordPressMigrationJobRepositoryTest.php`

  - `application_password_is_stored_encrypted_and_round_trips`
  - `mark_failed_records_the_error_and_is_terminal`
  - `options_are_merged_across_subsequent_probes`

### `Tests/Feature/WordPressMigrationPreviewPageTest.php`

  - `the_page_renders_staged_rows_for_a_job`

### `Tests/Feature/WordPressMigrationResourceTest.php`

  - `resource_exposes_all_four_pages`
  - `standalone_import_and_preview_pages_do_not_register_their_own_nav_entries`
  - `list_page_filters_by_status`
  - `view_page_renders_the_job_detail`

### `Tests/Unit/SitemapImporterTest.php`

  - `crawl_of_flat_urlset_collects_entries_in_document_order`

### `Tests/Unit/SitemapPageExtractorTest.php`

  - `falls_back_to_document_title_and_strips_site_name_suffix`
  - `falls_back_to_first_body_image_when_og_image_missing`
  - `extracts_published_time_from_time_element_when_meta_absent`
  - `uses_article_tag_as_body_root_and_keeps_inline_links`
  - `uses_main_when_no_article_is_present`
  - `returns_empty_body_and_warning_when_content_too_thin`
  - `is_usable_requires_both_title_and_html`
  - `prefers_author_meta_over_json_ld`

### `Tests/Unit/WordPressMediaRehosterTest.php`

  - `non_http_schemes_are_rejected`
  - `extensionless_url_is_rehosted_via_mime_sniff`
  - `storage_path_includes_job_id_and_hash_prefix_and_extension`
  - `redirect_following_is_delegated_to_the_downloader`

### `Tests/Unit/WordPressSiteProbeTest.php`

  - `url_normalization`

### `Tests/Unit/WpAppPasswordCredentialTest.php`

  - `of_accepts_already_split_user_and_password`
  - `missing_colon_is_rejected`
  - `empty_username_is_rejected`
  - `colon_inside_password_half_is_preserved`

### `Tests/Unit/WxrImporterTest.php`

  - `seen_guids_prevent_rewriting_already_imported_items`
  - `missing_file_returns_unreachable_with_a_warning`
  - `streaming_parse_handles_a_multi_megabyte_file_without_exhausting_memory`

## Service providers

  - `Modules\WordPressMigration\Providers\WordPressMigrationServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
