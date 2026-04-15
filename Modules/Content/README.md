# Content

Core content management module. Handles pages, posts, products, and custom content types with full CRUD, revision history, SEO metadata, multilanguage support, and a RESTful API.

## Querying Content

```php
// Get active pages with pagination
$pages = get_content(['content_type' => 'page', 'is_active' => 1, 'limit' => 10]);

// Get a single item by ID, URL, or title
$item = get_content_by_id(5);
$item = app('content_manager')->getByUrl('blog/my-post');
$item = app('content_manager')->getByTitle('About Us');

// Filter by parent, category, keyword
$children = get_content(['parent' => 3, 'content_type' => 'post']);
$tagged = \Modules\Content\Models\Content::active()
    ->where('content_type', 'post')
    ->whereCategoryIds(7)
    ->paginate(10);

// Use Eloquent scopes
$trashed = \Modules\Content\Models\Content::trashed()->get();
$drafts  = \Modules\Content\Models\Content::inactive()->get();
```

## Creating and Updating Content

```php
// Create via helper
$id = save_content([
    'title'        => 'New Article',
    'content_type' => 'post',
    'content_body' => '<p>Body text</p>',
    'is_active'    => 1,
]);

// Update via helper
save_content(['id' => $id, 'title' => 'Updated Title']);

// Publish / unpublish
app('content_manager')->set_published(['id' => $id]);
app('content_manager')->set_unpublished(['id' => $id]);

// Bulk assign to a category, copy, or delete
app('content_manager')->helpers->bulk_assign(['ids' => '1,2,3', 'category_id' => 5]);
app('content_manager')->helpers->copy(['id' => $id]);
delete_content(['id' => $id]);
```

## REST API

```bash
# List content (public, no auth)
curl https://yoursite.com/api/content?content_type=page&limit=10

# Get single item
curl https://yoursite.com/api/content/5

# Create (Sanctum token required, admin only)
curl -X POST https://yoursite.com/api/content \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"title":"New Page","content_type":"page"}'

# Update
curl -X PUT https://yoursite.com/api/content/5 \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"title":"Renamed Page"}'

# Delete
curl -X DELETE https://yoursite.com/api/content/5 \
  -H "Authorization: Bearer $TOKEN"
```

## Handling Events

```php
use Modules\Content\Events\ContentWasCreated;

// In a listener or EventServiceProvider
Event::listen(ContentWasCreated::class, function ($event) {
    Log::info('Content created: ' . $event->content->title);
});
```

**Available events:** `ContentIsCreating`, `ContentIsUpdating`, `ContentWasCreated`, `ContentWasUpdated`, `ContentWasDeleted`, `ContentWasRestored`, `ContentWasDestroyed`.

## Template Rendering

```html
<!-- Render a content list inside a Blade/Microweber template -->
<module type="posts" content_type="post" limit="5" />

<!-- Get the page tree -->
<?php echo pages_tree(['ul_class' => 'nav']); ?>

<!-- Link helpers -->
<a href="<?php echo content_link($post['id']); ?>">Read more</a>
```

## Key Classes

| Class | Purpose |
|---|---|
| `Services\ContentManager` | Core CRUD (`app('content_manager')`) |
| `Repositories\ContentRepository` | Query layer (`app('content_repository')`) |
| `Models\Content` | Eloquent model for all content types |
| `Observers\ContentObserver` | Lifecycle hooks on content changes |

## Database Tables

`content`, `content_related`, `content_revisions_history`.

## Admin Panel (Filament)

**ContentResource** (full CRUD), **ContentModuleSettings**, **ContentTableList** (Livewire listing).
