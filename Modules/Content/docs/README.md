# `Content` module

> **Slug:** `content`
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

### `content` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `content_type` | `string` | nullable |
  | `subtype` | `string` | nullable |
  | `url` | `string` | nullable |
  | `title` | `string` | nullable |
  | `parent` | `integer` | nullable |
  | `description` | `text` | nullable |
  | `position` | `integer` | nullable |
  | `content` | `longText` | nullable |
  | `content_body` | `longText` | nullable |
  | `is_active` | `integer` | nullable, has-default |
  | `is_featured` | `integer` | nullable, has-default |
  | `subtype_value` | `string` | nullable |
  | `custom_type` | `string` | nullable |
  | `custom_type_value` | `string` | nullable |
  | `active_site_template` | `string` | nullable |
  | `layout_file` | `string` | nullable |
  | `layout_name` | `string` | nullable |
  | `layout_style` | `string` | nullable |
  | `content_filename` | `string` | nullable |
  | `original_link` | `string` | nullable |
  | `is_home` | `integer` | nullable, has-default |
  | `is_pinged` | `integer` | nullable, has-default |
  | `is_shop` | `integer` | nullable, has-default |
  | `is_deleted` | `integer` | nullable, has-default |
  | `require_login` | `integer` | nullable, has-default |
  | `status` | `string` | nullable |
  | `content_meta_title` | `text` | nullable |
  | `content_meta_keywords` | `text` | nullable |
  | `session_id` | `string` | nullable |
  | `updated_at` | `dateTime` | nullable |
  | `created_at` | `dateTime` | nullable |
  | `expires_at` | `dateTime` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `posted_at` | `dateTime` | nullable |
  | `draft_of` | `integer` | nullable |
  | `copy_of` | `integer` | nullable |
  | `url` | `index` | — |
  | `title` | `index` | — |
  | `(unnamed)` | `dropIndex` | — |
  | `content_meta_description` | `text` | nullable |
  | `og_title` | `string` | nullable |
  | `og_description` | `text` | nullable |
  | `og_image` | `string` | nullable |
  | `og_type` | `string` | nullable |
  | `twitter_title` | `string` | nullable |
  | `twitter_description` | `text` | nullable |
  | `twitter_image` | `string` | nullable |
  | `twitter_card` | `string` | nullable |
  | `canonical_url` | `string` | nullable |
  | `robots_meta` | `string` | nullable |
  | `sitemap_priority` | `decimal` | has-default |
  | `sitemap_changefreq` | `string` | nullable |
  | `exclude_from_sitemap` | `boolean` | has-default |
  | `exclude_from_sitemap` | `index` | — |
  | `idx_content_exclude_sitemap` | `dropIndex` | — |
  | `idx_content_active_sitemap` | `dropIndex` | — |
  | `(unnamed)` | `dropColumn` | — |

### `content_related` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `content_id` | `integer` | nullable |
  | `related_content_id` | `integer` | nullable |
  | `position` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

### `content_revisions_history` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `field` | `text` | nullable |
  | `value` | `longText` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `user_ip` | `string` | nullable |
  | `checksum` | `string` | nullable |
  | `session_id` | `string` | nullable |
  | `url` | `longText` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Content\Models\Content`

Source: `Models/Content.php`. Table: `content`. 

**Fillable:** `id`, `subtype`, `subtype_value`, `content_type`, `parent`, `layout_file`, `active_site_template`, `title`, `url`, `content_meta_title`, `content`, `description`, `content_body`, `content_meta_keywords`, `content_meta_description`, `og_title`, `og_description`, `og_image`, `og_type`, `twitter_title`, `twitter_description`, `twitter_image`, `twitter_card`, `canonical_url`, `robots_meta`, `sitemap_priority`, `sitemap_changefreq`, `exclude_from_sitemap`, `original_link`, `require_login`, `created_by`, `is_home`, `is_shop`, `is_active`, `is_deleted`, `is_featured`, `session_id`, `updated_at`, `created_at`, `posted_at`, `low_stock_threshold`

### `Modules\Content\Models\ContentRelated`

Source: `Models/ContentRelated.php`. Table: `content_related`. 

**Fillable:** `content_id`, `related_content_id`, `position`

### `Modules\Content\Models\ModelFilters\ContentFilter`

Source: `Models/ModelFilters/ContentFilter.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByAuthor`

Source: `Models/ModelFilters/Traits/FilterByAuthor.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByCategory`

Source: `Models/ModelFilters/Traits/FilterByCategory.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByContentData`

Source: `Models/ModelFilters/Traits/FilterByContentData.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByContentFields`

Source: `Models/ModelFilters/Traits/FilterByContentFields.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByCustomFields`

Source: `Models/ModelFilters/Traits/FilterByCustomFields.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByDate`

Source: `Models/ModelFilters/Traits/FilterByDate.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait`

Source: `Models/ModelFilters/Traits/FilterByDateBetweenTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByKeywordTrait`

Source: `Models/ModelFilters/Traits/FilterByKeywordTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByOffersTrait`

Source: `Models/ModelFilters/Traits/FilterByOffersTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByOrdersTrait`

Source: `Models/ModelFilters/Traits/FilterByOrdersTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByPage`

Source: `Models/ModelFilters/Traits/FilterByPage.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByPriceTrait`

Source: `Models/ModelFilters/Traits/FilterByPriceTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByQtyTrait`

Source: `Models/ModelFilters/Traits/FilterByQtyTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByStockTrait`

Source: `Models/ModelFilters/Traits/FilterByStockTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByTagsTrait`

Source: `Models/ModelFilters/Traits/FilterByTagsTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByTitleTrait`

Source: `Models/ModelFilters/Traits/FilterByTitleTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByTrashedTrait`

Source: `Models/ModelFilters/Traits/FilterByTrashedTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByUrlTrait`

Source: `Models/ModelFilters/Traits/FilterByUrlTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\FilterByVisibleTrait`

Source: `Models/ModelFilters/Traits/FilterByVisibleTrait.php`. 

### `Modules\Content\Models\ModelFilters\Traits\OrderByTrait`

Source: `Models/ModelFilters/Traits/OrderByTrait.php`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `ContentApiController::index` |
  | `GET` | `/{content}` | `ContentApiController::show` |
  | `POST` | `/` | `ContentApiController::store` |
  | `PUT` | `/{content}` | `ContentApiController::update` |
  | `PATCH` | `/{content}` | `ContentApiController::update` |
  | `DELETE` | `/{content}` | `ContentApiController::destroy` |
  | `GET` | `/` | `ContentApiController::index` |
  | `GET` | `/{content}` | `ContentApiController::show` |
  | `POST` | `/` | `ContentApiController::store` |
  | `PUT` | `/{content}` | `ContentApiController::update` |
  | `PATCH` | `/{content}` | `ContentApiController::update` |
  | `DELETE` | `/{content}` | `ContentApiController::destroy` |

## Controllers

### `Modules\Content\Http\Controllers\Api\ContentApiController`

Source: `Http/Controllers/Api/ContentApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`
  - `get_admin_js_tree_json(array $params = []): mixed`

## Service classes

### `Modules\Content\Services\ContentManager`

Source: `Services/ContentManager.php`.

  - `pagingLinks($base_url = false, $pages_count = false, $paging_param = 'current_page', $keyword_param = 'keyword')`
  - `pagesTree($params)`
  - `get($params = false)`
  - `getById($id)`
  - `getByUrl($url = '', $noRecursive = false)`
  - `getByTitle($title = '')`
  - `getContentIdFromUrl($url = '')`
  - `save($data)`
  - `delete($id)`
  - `getParents($id = 0)`
  - `getChildren($id = 0)`
  - `getPages($params = false)`
  - `getPosts($params = false)`
  - `getProducts($params = false)`
  - `getCustomFields($contentId, $returnFull = true, $fieldType = false)`
  - `getTags($contentId = false, $returnFullTagsData = false)`
  - `getMedia($contentId)`
  - `getCategories($contentId)`
  - `getContentData($contentId)`
  - `getEditField($data)`
  - `saveContentField($data, $deleteCache = true)`
  - `reorder($data)`
  - `setPublished($id)`
  - `setUnpublished($id)`
  - `get_page($id = 0)`
  - `get_by_id($id)`
  - `get_by_url($url = '', $no_recursive = false)`
  - `get_by_title($title = '')`
  - `get_content_id_from_url($url = '')`
  - `get_children($id = 0)`
  - `get_data($params = false)`
  - `data($content_id, $field_name = false)`
  - `tags($content_id = false, $return_full = false)`
  - `attributes($content_id)`
  - `paging($params)`
  - `paging_links($base_url = false, $pages_count = false, $paging_param = 'current_page', $keyword_param = 'keyword')`
  - `pages_tree($params)`
  - `define_constants($content = false)`
  - `get_inherited_parent($content_id)`
  - `get_parents($id = 0)`
  - `breadcrumb($params = false)`
  - `link($id = 0)`
  - `create_link($contentType = 'page')`
  - `edit_link($id = 0)`
  - `save_edit($post_data)`
  - `homepage()`
  - `save_content($data, $delete_the_cache = true)`
  - `custom_fields($content_id, $full = true, $field_type = false)`
  - `save_content_field($data, $delete_the_cache = true)`
  - `edit_field($data)`
  - `prev_content($content_id = false)`
  - `next_content($content_id = false, $mode = 'next', $content_type = false)`
  - `set_unpublished($params)`
  - `set_published($params)`
  - `save_content_data_field($data, $delete_the_cache = true)`
  - `get_pages($params = false)`
  - `get_posts($params = false)`
  - `get_products($params = false)`
  - `title($id)`
  - `description($id)`
  - `get_related_content_ids_for_content_id($content_id = false)`

## Events

  - `Modules\Content\Events\ContentIsCreating`
  - `Modules\Content\Events\ContentIsUpdating`
  - `Modules\Content\Events\ContentWasCreated`
  - `Modules\Content\Events\ContentWasDeleted`
  - `Modules\Content\Events\ContentWasDestroyed`
  - `Modules\Content\Events\ContentWasRestored`
  - `Modules\Content\Events\ContentWasUpdated`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Content\Filament\Admin\ContentResource` | Website | — |
  | `Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent` | — | — |
  | `Modules\Content\Filament\Admin\ContentResource\Pages\EditContent` | — | — |
  | `Modules\Content\Filament\Admin\ContentResource\Pages\ListContents` | — | — |
  | `Modules\Content\Filament\Admin\ContentResource\Pages\ViewContent` | — | — |
  | `Modules\Content\Filament\ContentModuleSettings` | — | — |
  | `Modules\Content\Filament\ContentTableList` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Content/Tests`

### `Tests/Filament/ContentResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/ContentExportTest.php`

  - `it_export_with_wrong_format`

### `Tests/Unit/ContentManagerTest.php`

  - `it_content_save_itself_as_parent`

### `Tests/Unit/Filament/ContentResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_index_page_supports_search`
  - `it_create_page_validates_required_fields`
  - `it_edit_page_pre_fills_form_data`
  - `it_delete_action_removes_record`
  - `it_can_create_product_content`

### `Tests/Unit/LangTest.php`

  - `it_lang_data`

### `Tests/Unit/PermalinkTest.php`

  - `it_front_controller_post`

## Service providers

  - `Modules\Content\Providers\ContentServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
