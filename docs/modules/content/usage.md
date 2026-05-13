# Content Module — Usage

The Content module provides the shared helpers + scopes + events that every typed content (Page / Post / Product) consumes. Day-to-day, you'll touch Content directly when:

- Working across multiple content types in one query
- Hooking into lifecycle events that fire for all types
- Reading or writing the shared `content_data` key/value store
- Bulk operations against the `content` table
- Defining a custom content type

## The helper layer

```php
// CREATE / UPDATE
$id = save_content([
    'title'        => 'New Article',
    'content_type' => 'post',
    'content_body' => '<p>Body</p>',
    'is_active'    => 1,
]);

save_content(['id' => $id, 'title' => 'Updated Title']);

// READ
$row = get_content_by_id(5);
$list = get_content([
    'content_type' => 'page',
    'is_active'    => 1,
    'limit'        => 10,
]);

// DELETE
delete_content(['id' => $id]);  // soft-delete (is_deleted = 1)

// LINK + TREE
$url = content_link($id);                   // public URL
$parents = content_parents($id);             // walk up the tree
$children = get_content_children($id);       // direct children
echo pages_tree(['ul_class' => 'navbar-nav']);
```

All helpers are autoloaded at app boot — no `use` statement needed.

## Direct Eloquent access

```php
use Modules\Content\Models\Content;

// Returns rows of ANY content_type (no global scope on Content itself)
$all = Content::active()->get();

// Filter manually
$pages = Content::active()->where('content_type', 'page')->get();
$posts = Content::active()->where('content_type', 'post')->get();
```

For typed access, use the typed model instead — `Page::query()`, `Post::query()`, `Product::query()` apply the corresponding global scope automatically.

## Scopes

Defined on the `Content` model (inherited by all typed children):

```php
Content::active();       // is_active = 1 AND is_deleted = 0
Content::inactive();     // is_active = 0
Content::trashed();      // is_deleted = 1
Content::published();    // is_active = 1 AND (posted_at IS NULL OR posted_at <= NOW())
```

Global scopes (per typed model):

```php
\Modules\Content\Scopes\PageScope::class      // WHERE content_type = 'page'
\Modules\Content\Scopes\PostScope::class      // WHERE content_type = 'post'
\Modules\Content\Scopes\ProductScope::class   // WHERE content_type = 'product'
\Modules\Content\Scopes\CategoryScope::class  // WHERE content_type = 'category'
```

Bypass with `withoutGlobalScope()`:

```php
\Modules\Page\Models\Page::withoutGlobalScope(\Modules\Content\Scopes\PageScope::class)->get();
```

## Lifecycle events

```php
use Modules\Content\Events\ContentWasCreated;
use Modules\Content\Events\ContentWasUpdated;
use Modules\Content\Events\ContentWasDeleted;

\Event::listen(ContentWasCreated::class, function ($event) {
    \Log::info('Content created: ' . $event->content->title);
});

\Event::listen(ContentWasUpdated::class, function ($event) {
    if ($event->content->content_type === 'post') {
        // Post-only logic
    }
});

\Event::listen(ContentWasDeleted::class, function ($event) {
    // Clean up related rows in custom tables
});
```

Full event list (all live in `Modules/Content/Events/`):

| Event | Fires when |
|---|---|
| `ContentIsCreating` | Before save on new row |
| `ContentIsUpdating` | Before save on existing row |
| `ContentWasCreated` | After insert |
| `ContentWasUpdated` | After update |
| `ContentWasDeleted` | After soft-delete (`is_deleted = 1`) |
| `ContentWasRestored` | When `is_deleted` flipped back to `0` |
| `ContentWasDestroyed` | After hard delete (`forceDelete()`) |

The standard Eloquent events (`saving`, `saved`, `creating`, `created`, `updating`, `updated`, `deleting`, `deleted`) also fire — these `Content*` events are a higher-level wrapper that includes typed metadata.

## `content_data` key/value sidecar

The `content_data` table stores arbitrary per-row metadata that doesn't justify a new column:

```php
use Modules\Content\Models\Content;

$content = Content::find(5);

// Write
$content->setContentDataByFieldName('reading_time_minutes', '7');
$content->setContentDataByFieldName('author_bio_url', 'https://author.example/me');

// Read
$minutes = $content->getContentDataByFieldName('reading_time_minutes');

// Bulk read
$all = $content->contentData;  // collection of all data rows
```

Useful for: reading time, custom author bylines, video URLs, featured-flag toggles, per-page tracking IDs.

## Revisions

The `content_fields` table stores a history of `content_body` mutations. Enabled by default (option `revision_history_enabled = 1`).

```php
$content = Content::find($id);
$revisions = \DB::table('content_fields')
    ->where('rel_id', $content->id)
    ->where('rel_type', 'content')
    ->where('field', 'content_body')
    ->orderBy('created_at', 'desc')
    ->limit(20)
    ->get();
```

To restore a revision:

```php
$revision = \DB::table('content_fields')->find($revisionId);
$content->update(['content_body' => $revision->value]);
```

## Defining a custom content type

```php
// 1. Create a global scope
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EventScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('content_type', 'event');
    }
}

// 2. Create a model that extends Content
namespace App\Models;

use Modules\Content\Models\Content;
use App\Scopes\EventScope;

class Event extends Content
{
    protected $table = 'content';

    protected static function booted(): void
    {
        static::addGlobalScope(new EventScope);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['content_type'] = 'event';
    }
}

// 3. Use it
\App\Models\Event::create([
    'title'     => 'Summer Conference 2026',
    'url'       => 'summer-conference-2026',
    'is_active' => 1,
]);

\App\Models\Event::active()->get();  // returns only event rows
```

## Bulk operations via the repository

`app('content_manager')` is the `ContentRepository` singleton — useful for admin-heavy operations:

```php
$repo = app('content_manager');

// Publish / unpublish
$repo->set_published(['id' => 5]);
$repo->set_unpublished(['id' => 5]);

// Bulk operations
$repo->helpers->bulk_assign(['ids' => '1,2,3', 'category_id' => 7]);
$repo->helpers->copy(['id' => 5]);

// Lookups
$repo->getByUrl('about-us');
$repo->getByTitle('Home');
$repo->createDefaultShopPage();
$repo->createDefaultBlogPage();
```

## Multilanguage support

The `content_translations` table provides per-locale overrides:

```php
$content = Content::find(5);
$content->setTranslation('title', 'es', 'Sobre Nosotros');
$content->setTranslation('content_body', 'es', '<p>Información de la empresa.</p>');

$content->getTranslation('title', 'es');  // "Sobre Nosotros"
```

Translation lookup is automatic when the request's locale matches an existing translation row.

## Filament admin

`Modules\Content\Filament\Resources\ContentResource` (rarely used directly — typed resources extend it) provides:

- Index with content-type filter chips
- Editor with rich-text, SEO panel, custom-fields management
- Revision viewer (rollback to any past `content_fields` row)
- Bulk publish / unpublish / delete

Most operators see the typed resources (`PageResource`, `PostResource`, `ProductResource`) instead, which extend the Content resource and add type-specific filters.
