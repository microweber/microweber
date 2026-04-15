# Content

Core content management module. Handles pages, posts, and custom content types with full CRUD, revision history, SEO metadata, multilanguage support, and a RESTful API.

## Key Features

- Unified content model for pages, posts, products, and custom types
- Content revision history tracking
- Related content associations
- SEO metadata fields (meta title, description, OG tags)
- Multilanguage translation support
- Hierarchical content tree (admin JS tree)
- Bulk operations: assign, copy, delete, reorder
- RESTful API with Sanctum authentication

## Key Classes

| Class | Purpose |
|---|---|
| `Services\ContentManager` | Core CRUD operations (`app('content_manager')`) |
| `Repositories\ContentRepository` | Query layer (`app('content_repository')`) |
| `Models\Content` | Eloquent model for all content types |
| `Models\ContentRelated` | Content relationship associations |
| `Observers\ContentObserver` | Lifecycle hooks on content changes |

## Events

- `ContentIsCreating` / `ContentIsUpdating` -- before save
- `ContentWasCreated` / `ContentWasUpdated` -- after save
- `ContentWasDeleted` / `ContentWasRestored` / `ContentWasDestroyed` -- deletion lifecycle

## Database Tables

- `content` -- main content storage with indexes
- `content_related` -- many-to-many content relationships
- `content_revisions_history` -- revision tracking

## Admin Panel (Filament)

- **ContentResource** -- full CRUD (list, create, edit, view)
- **ContentModuleSettings** -- module configuration
- **ContentTableList** -- Livewire content listing component

## API Endpoints

**Public** (no auth): `GET /api/content`, `GET /api/content/{id}`

**Protected** (Sanctum): `POST /api/content`, `PUT /api/content/{id}`, `DELETE /api/content/{id}`

**Admin** (legacy): `save_content`, `delete_content`, `content/reorder`, `content/set_published`, etc.

## Usage

```php
$pages = get_content(['content_type' => 'page', 'limit' => 10]);
$post = get_content_by_id(5);
save_content(['title' => 'New Page', 'content_type' => 'page']);
app('content_manager')->set_published(['id' => 5]);
```
