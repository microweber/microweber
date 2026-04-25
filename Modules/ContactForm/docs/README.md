# `ContactForm` module

> **Slug:** `contact-form`
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

### `forms` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `slug` | `string` | nullable |
  | `list_id` | `integer` | nullable |
  | `module_id` | `integer` | nullable |
  | `description` | `longText` | nullable |
  | `confirmation_message` | `longText` | nullable |
  | `emails_notifications` | `longText` | nullable |
  | `emails_notifications_subject` | `longText` | nullable |
  | `is_active` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\ContactForm\Models\Form`

Source: `Models/Form.php`. 

**Fillable:** `name`, `slug`, `list_id`, `module_id`, `description`, `confirmation_message`, `emails_notifications`, `emails_notifications_subject`, `is_active`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `FormsApiController::index` |
  | `GET` | `/{form}` | `FormsApiController::show` |
  | `POST` | `/` | `FormsApiController::store` |
  | `PUT` | `/{form}` | `FormsApiController::update` |
  | `PATCH` | `/{form}` | `FormsApiController::update` |
  | `DELETE` | `/{form}` | `FormsApiController::destroy` |
  | `GET` | `/` | `FormsApiController::index` |
  | `GET` | `/{form}` | `FormsApiController::show` |
  | `POST` | `/` | `FormsApiController::store` |
  | `PUT` | `/{form}` | `FormsApiController::update` |
  | `PATCH` | `/{form}` | `FormsApiController::update` |
  | `DELETE` | `/{form}` | `FormsApiController::destroy` |

### `routes/web.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `api/contact_form_submit` | `ContactFormController::submit` |

## Controllers

### `Modules\ContactForm\Http\Controllers\Api\FormsApiController`

Source: `Http/Controllers/Api/FormsApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

### `Modules\ContactForm\Http\Controllers\ContactFormController`

Source: `Http/Controllers/ContactFormController.php`.

  - `submit()`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\ContactForm\Filament\ContactFormModuleSettings` | — | — |

## Service providers

  - `Modules\ContactForm\Providers\ContactFormServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
