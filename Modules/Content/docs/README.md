# `Content` module

> **Slug:** `content`
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

Migrations under `Modules/Content/database/migrations/`:

  - `database/migrations/2024_11_08_000001_create_content_table.php`
  - `database/migrations/2024_11_08_000002_add_indexes_to_content.php`
  - `database/migrations/2024_11_08_000003_create_related_content.php`
  - `database/migrations/2024_11_08_000004_create_content_revisions_history.php`
  - `database/migrations/2025_03_22_000001_add_seo_metadata_fields_to_content.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Content\Models\Content` | `Models/Content.php` |
| `Modules\Content\Models\ContentRelated` | `Models/ContentRelated.php` |
| `Modules\Content\Models\ModelFilters\ContentFilter` | `Models/ModelFilters/ContentFilter.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByAuthor` | `Models/ModelFilters/Traits/FilterByAuthor.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByCategory` | `Models/ModelFilters/Traits/FilterByCategory.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByContentData` | `Models/ModelFilters/Traits/FilterByContentData.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByContentFields` | `Models/ModelFilters/Traits/FilterByContentFields.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByCustomFields` | `Models/ModelFilters/Traits/FilterByCustomFields.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByDate` | `Models/ModelFilters/Traits/FilterByDate.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait` | `Models/ModelFilters/Traits/FilterByDateBetweenTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByKeywordTrait` | `Models/ModelFilters/Traits/FilterByKeywordTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByOffersTrait` | `Models/ModelFilters/Traits/FilterByOffersTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByOrdersTrait` | `Models/ModelFilters/Traits/FilterByOrdersTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByPage` | `Models/ModelFilters/Traits/FilterByPage.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByPriceTrait` | `Models/ModelFilters/Traits/FilterByPriceTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByQtyTrait` | `Models/ModelFilters/Traits/FilterByQtyTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByStockTrait` | `Models/ModelFilters/Traits/FilterByStockTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByTagsTrait` | `Models/ModelFilters/Traits/FilterByTagsTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByTitleTrait` | `Models/ModelFilters/Traits/FilterByTitleTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByTrashedTrait` | `Models/ModelFilters/Traits/FilterByTrashedTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByUrlTrait` | `Models/ModelFilters/Traits/FilterByUrlTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\FilterByVisibleTrait` | `Models/ModelFilters/Traits/FilterByVisibleTrait.php` |
| `Modules\Content\Models\ModelFilters\Traits\OrderByTrait` | `Models/ModelFilters/Traits/OrderByTrait.php` |

## API endpoints

Route files:

  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Content\Http\Controllers\Api\ContentApiController`

## Service classes

  - `Modules\Content\Services\ContentManager`

## Events

  - `Modules\Content\Events\ContentIsCreating`
  - `Modules\Content\Events\ContentIsUpdating`
  - `Modules\Content\Events\ContentWasCreated`
  - `Modules\Content\Events\ContentWasDeleted`
  - `Modules\Content\Events\ContentWasDestroyed`
  - `Modules\Content\Events\ContentWasRestored`
  - `Modules\Content\Events\ContentWasUpdated`

## Filament admin

  - `Modules\Content\Filament\Admin\ContentResource`
  - `Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent`
  - `Modules\Content\Filament\Admin\ContentResource\Pages\EditContent`
  - `Modules\Content\Filament\Admin\ContentResource\Pages\ListContents`
  - `Modules\Content\Filament\Admin\ContentResource\Pages\ViewContent`
  - `Modules\Content\Filament\ContentModuleSettings`
  - `Modules\Content\Filament\ContentTableList`

## Tests

Run: `php vendor/bin/phpunit Modules/Content/Tests`

Test files:

  - `Tests/Filament/ContentResourceTest.php`
  - `Tests/Unit/ContentApiControllerLiveEditSaveTest.php`
  - `Tests/Unit/ContentApiControllerLiveEditSaveTestXss.php`
  - `Tests/Unit/ContentApiControllerTest.php`
  - `Tests/Unit/ContentExportTest.php`
  - `Tests/Unit/ContentManagerTest.php`
  - `Tests/Unit/ContentOriginalLinkTest.php`
  - `Tests/Unit/ContentRepositoryTest.php`
  - `Tests/Unit/ContentTest.php`
  - `Tests/Unit/DataAttributesTest.php`
  - `Tests/Unit/DataFieldsTest.php`
  - `Tests/Unit/Filament/ContentResourceTest.php`
  - `Tests/Unit/LangTest.php`
  - `Tests/Unit/PermalinkTest.php`
  - `Tests/Unit/SchemaOrgTest.php`
  - `Tests/Unit/TestHelpers.php`
  - `tests/Filament/ContentResourceFormReactivityTest.php`

## Service providers

  - `Modules\Content\Providers\ContentServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
