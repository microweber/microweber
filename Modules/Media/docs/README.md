# `Media` module

> **Slug:** `media`
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

### `media` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `title` | `text` | nullable |
  | `description` | `text` | nullable |
  | `filename` | `text` | nullable |
  | `media_type` | `text` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `session_id` | `string` | nullable |
  | `image_options` | `longText` | nullable |
  | `position` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `metadata` | `json` | nullable |
  | `metadata` | `dropColumn` | — |
  | `cdn_url` | `string` | nullable |
  | `cdn_provider` | `string` | nullable |
  | `cdn_metadata` | `json` | nullable |
  | `is_synced_to_cdn` | `boolean` | has-default |
  | `file_size` | `bigInteger` | nullable |
  | `file_hash` | `string` | nullable |
  | `(unnamed)` | `dropColumn` | — |
  | `folder_id` | `unsignedBigInteger` | nullable |
  | `folder_id` | `foreignId` | nullable, foreign-key |
  | `folder_id` | `index` | — |
  | `folder_id` | `index` | — |
  | `folder_id` | `unsignedBigInteger` | nullable |
  | `folder_id` | `foreignId` | nullable, foreign-key |
  | `folder_id` | `index` | — |
  | `folder_id` | `index` | — |
  | `(unnamed)` | `dropForeign` | — |
  | `folder_id` | `dropColumn` | — |

### `media_thumbnails` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `uuid` | `uuid` | nullable |
  | `filename` | `string` | nullable |
  | `image_options` | `longText` | nullable |
  | `timestamps` | `timestamps` | — |
  | `filename` | `index` | — |

### `media_folders` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | — |
  | `slug` | `string` | — |
  | `description` | `text` | nullable |
  | `parent_id` | `foreignId` | nullable, foreign-key |
  | `created_by` | `unsignedInteger` | nullable |
  | `is_system` | `boolean` | has-default |
  | `sort_order` | `integer` | has-default |
  | `timestamps` | `timestamps` | — |
  | `slug` | `index` | — |
  | `parent_id` | `index` | — |
  | `created_by` | `index` | — |

## Models

### `Modules\Media\Models\Media`

Source: `Models/Media.php`. 

**Fillable:** `id`, `folder_id`, `title`, `description`, `rel_id`, `rel_type`, `media_type`, `position`, `filename`, `session_id`, `image_options`, `metadata`, `cdn_url`, `cdn_provider`, `cdn_metadata`, `is_synced_to_cdn`, `file_size`, `file_hash`

**Casts:**

  - `image_options` → `json`
  - `metadata` → `json`
  - `cdn_metadata` → `json`
  - `is_synced_to_cdn` → `boolean`
  - `file_size` → `integer`

### `Modules\Media\Models\MediaFolder`

Source: `Models/MediaFolder.php`. Table: `media_folders`. 

**Fillable:** `name`, `slug`, `description`, `parent_id`, `created_by`, `is_system`, `sort_order`

**Casts:**

  - `is_system` → `boolean`
  - `sort_order` → `integer`

### `Modules\Media\Models\MediaThumbnail`

Source: `Models/MediaThumbnail.php`. 

**Casts:**

  - `image_options` → `json`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `MediaApiController::index` |
  | `GET` | `/{media}` | `MediaApiController::show` |
  | `POST` | `/` | `MediaApiController::store` |
  | `PUT` | `/{media}` | `MediaApiController::update` |
  | `PATCH` | `/{media}` | `MediaApiController::update` |
  | `DELETE` | `/{media}` | `MediaApiController::destroy` |

## Controllers

### `Modules\Media\Http\Controllers\Api\MediaApiController`

Source: `Http/Controllers/Api/MediaApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Media\Services\BulkUploadService`

Source: `Services/BulkUploadService.php`.

  - `uploadBatch(array $files, array $options = []): array`
  - `getProgress(): array`
  - `createDefaultFolders(?int $userId = null): Collection`

### `Modules\Media\Services\CdnIntegrationService`

Source: `Services/CdnIntegrationService.php`.

  - `uploadToCdn(Media $media, bool $deleteLocal = false): bool`
  - `deleteFromCdn(Media $media): bool`
  - `syncMedia(int $mediaId, bool $deleteLocal = false): array`
  - `bulkSync(array $mediaIds, bool $deleteLocal = false): array`
  - `getCdnUrl(Media $media): ?string`
  - `isConfigured(): bool`
  - `invalidateCache(array $paths): bool`
  - `getStats(): array`

### `Modules\Media\Services\ImageOptimizationService`

Source: `Services/ImageOptimizationService.php`.

  - `convertToWebp(string $sourcePath, array $options = []): ?array`
  - `getWebpOrOriginal(string $sourcePath, array $options = []): string`
  - `generateLazyImage(string $src, ?string $alt = null, array $attributes = []): string`
  - `generateResponsiveImage(string $src, array $sizes, ?string $alt = null, array $attributes = []): string`
  - `getOptimizedUrl(string $src, ?int $width = null, ?int $height = null, bool $allowWebp = true): string`
  - `isWebpSupported(): bool`
  - `isWebpEnabled(): bool`
  - `isLazyLoadingEnabled(): bool`
  - `clientSupportsWebp(): bool`
  - `clearWebpCache(): int`
  - `getStatistics(): array`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Media\Filament\Resources\MediaResource` | Website Settings | — |
  | `Modules\Media\Filament\Resources\MediaResource\Pages\CreateMedia` | — | — |
  | `Modules\Media\Filament\Resources\MediaResource\Pages\EditMedia` | — | — |
  | `Modules\Media\Filament\Resources\MediaResource\Pages\ListMedia` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Media/Tests`

### `Tests/Filament/MediaResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Models/MediaFolderTest.php`

  - `test_can_instantiate_model`
  - `test_model_has_expected_attributes`
  - `test_model_has_expected_relationships`
  - `test_model_has_expected_accessors`

### `Tests/Unit/Models/MediaTest.php`

  - `test_can_instantiate_model`
  - `test_fillable_attributes`
  - `test_casts_attributes`
  - `test_default_attributes`
  - `test_model_has_expected_relationships`
  - `test_model_has_expected_scopes`
  - `test_model_has_expected_accessors`

### `Tests/Unit/Services/BulkUploadServiceTest.php`

  - `test_can_instantiate_service`
  - `test_upload_batch_returns_result_structure`
  - `test_upload_batch_handles_empty_files`
  - `test_upload_batch_handles_invalid_files`
  - `test_get_progress_returns_correct_structure`
  - `test_service_has_expected_methods`

### `Tests/Unit/Services/CdnIntegrationServiceTest.php`

  - `test_can_instantiate_service`
  - `test_service_class_exists`
  - `test_service_has_expected_methods`

### `Tests/Unit/Services/ImageOptimizationServiceTest.php`

  - `test_can_instantiate_service`
  - `test_service_has_expected_methods`
  - `test_returns_original_path_for_invalid_image_files`
  - `test_returns_original_path_for_existing_webp_files`
  - `test_handles_various_image_extensions`
  - `test_can_generate_lazy_image_html`
  - `test_includes_placeholder_in_lazy_image`
  - `test_can_generate_lazy_image_with_custom_attributes`
  - `test_escapes_special_characters_in_lazy_image`
  - `test_returns_simple_lazy_image_when_sizes_empty`
  - …3 more.

## Service providers

  - `Modules\Media\Providers\ImageOptimizationServiceProvider`
  - `Modules\Media\Providers\MediaServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
