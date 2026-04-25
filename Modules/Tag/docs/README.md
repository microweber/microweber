# `Tag` module

> **Slug:** `tag`
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

### `tagging_tagged` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `taggable_id` | `string` | — |
  | `taggable_id` | `integer` | — |
  | `taggable_type` | `string` | — |
  | `tag_name` | `string` | — |
  | `tag_slug` | `string` | — |
  | `tag_name` | `index` | — |
  | `tag_slug` | `index` | — |
  | `taggable_type` | `index` | — |
  | `taggable_id` | `index` | — |
  | `id` | `increments` | — |
  | `taggable_id` | `string` | indexed |
  | `taggable_id` | `integer` | indexed |
  | `taggable_type` | `string` | indexed |
  | `tag_name` | `string` | — |
  | `tag_slug` | `string` | indexed |
  | `timestamps` | `timestamps` | — |

### `tagging_tag_groups` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `slug` | `string` | — |
  | `name` | `string` | — |
  | `timestamps` | `timestamps` | — |
  | `slug` | `index` | — |

### `tagging_tags` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `slug` | `string` | indexed |
  | `name` | `string` | nullable |
  | `description` | `text` | nullable |
  | `suggest` | `boolean` | has-default |
  | `count` | `integer` | has-default |
  | `tag_group_id` | `integer` | nullable |
  | `locale` | `string` | nullable |
  | `timestamps` | `timestamps` | — |
  | `slug` | `index` | — |

## Models

### `Modules\Tag\Models\Tag`

Source: `Models/Tag.php`. 

### `Modules\Tag\Models\TagGroup`

Source: `Models/TagGroup.php`. 

### `Modules\Tag\Models\Tagged`

Source: `Models/Tagged.php`. 

**Fillable:** `tag_name`, `tag_slug`, `taggable_id`, `taggable_type`

### `Modules\Tag\Models\TranslateTaggingTagged`

Source: `Models/TranslateTaggingTagged.php`. 

### `Modules\Tag\Models\TranslateTaggingTags`

Source: `Models/TranslateTaggingTags.php`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `TagApiController::index` |
  | `GET` | `/{tag}` | `TagApiController::show` |
  | `POST` | `/` | `TagApiController::store` |
  | `PUT` | `/{tag}` | `TagApiController::update` |
  | `PATCH` | `/{tag}` | `TagApiController::update` |
  | `DELETE` | `/{tag}` | `TagApiController::destroy` |

## Controllers

### `Modules\Tag\Http\Controllers\Api\TagApiController`

Source: `Http/Controllers/Api/TagApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Tag\Filament\Resources\TagGroupResource` | Content | — |
  | `Modules\Tag\Filament\Resources\TagGroupResource\Pages\CreateTagGroup` | — | — |
  | `Modules\Tag\Filament\Resources\TagGroupResource\Pages\EditTagGroup` | — | — |
  | `Modules\Tag\Filament\Resources\TagGroupResource\Pages\ListTagGroups` | — | — |
  | `Modules\Tag\Filament\Resources\TagResource` | Content | — |
  | `Modules\Tag\Filament\Resources\TagResource\Pages\CreateTag` | — | — |
  | `Modules\Tag\Filament\Resources\TagResource\Pages\EditTag` | — | — |
  | `Modules\Tag\Filament\Resources\TagResource\Pages\ListTags` | — | — |
  | `Modules\Tag\Filament\Resources\TaggedResource` | Content | — |
  | `Modules\Tag\Filament\Resources\TaggedResource\Pages\CreateTagged` | — | — |
  | `Modules\Tag\Filament\Resources\TaggedResource\Pages\EditTagged` | — | — |
  | `Modules\Tag\Filament\Resources\TaggedResource\Pages\ListTagged` | — | — |
  | `Modules\Tag\Filament\TagsModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Tag/Tests`

### `Tests/Filament/TagResourceTest.php`

  - `it_can_render_tag_groups_list_page`
  - `it_resource_has_correct_model`

### `Tests/TagsTest.php`

  - `it_tag_content_model_with_array`

### `Tests/Unit/Filament/TagGroupResourceTest.php`

  - `it_index_page_shows_all_records`

### `Tests/Unit/Filament/TagResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_index_page_supports_search`
  - `it_create_page_validates_required_fields`

### `Tests/Unit/Filament/TaggedResourceTest.php`

  - `it_index_page_shows_all_records`

## Service providers

  - `Modules\Tag\Providers\TagServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
