# Blog

Blog display module. Renders post listings with search, category/tag filtering, pagination, and configurable layouts as a Livewire component.

## How It Works

The Blog module does not store data of its own. It reads posts from the Content module (`content_type = 'post'`) and renders them through a `BlogComponent` Livewire class. You place the module tag on any page to display a blog feed.

## Setting Up a Blog Section

1. Create a page in the admin (e.g. "Blog").
2. Drop the blog module onto that page in the live editor, or add the tag to the template:

```html
<module type="blog" />
```

3. Configure display options through the **BlogSettings** admin page (posts per page, layout, show/hide categories and tags).

## Module Settings

Settings are stored per module instance and loaded automatically in `BlogComponent::mount()`:

| Setting | Default | Description |
|---|---|---|
| `posts_per_page` | 10 | Number of posts per page |
| `layout` | grid | Display layout (grid, list, etc.) |
| `show_categories` | true | Show category filter sidebar |
| `show_tags` | true | Show tag filter chips |

## Frontend Filtering

The Livewire component exposes query-string-bound filters that update the listing without a full page reload:

```
/blog?search=laravel              -- keyword search
/blog?selectedCategory=5          -- filter by category ID
/blog?selectedTags[]=2&selectedTags[]=7  -- filter by tag IDs
/blog?sortBy=title&sortOrder=asc  -- change sort order
/blog?limit=20                    -- override posts per page
```

Active filters display as removable chips. Removing a filter resets pagination automatically.

## Template Customization

The component resolves a Blade view at `modules.blog::livewire.blog.{template}`. Pass a template name via the query string or module option:

```
/blog?template=masonry
```

If the requested template view does not exist, it falls back to `default`. To create a custom template, add a Blade file at:

```
Modules/Blog/Resources/views/livewire/blog/masonry.blade.php
```

The view receives `$posts` (paginated collection), `$total`, `$count`, `$moduleId`, and `$moduleType`.

## Using the BlogComponent Directly

```php
// In a Blade view
<livewire:module-blog :module-id="'blog-main'" />
```

## How Posts Are Queried Internally

```php
// Simplified version of BlogComponent::getPosts()
Post::query()
    ->where('content_type', 'post')
    ->where('is_active', 1)
    ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
    ->when($categoryId, fn($q) => $q->whereCategoryIds($categoryId))
    ->when($tagIds, fn($q) => $q->withAnyTag($tagIds))
    ->orderBy($sortBy, $sortOrder)
    ->paginate($limit);
```

## Key Classes

| Class | Purpose |
|---|---|
| `Livewire\BlogComponent` | Main display component (tag: `module-blog`) |
| `FrontendFilter\BlogFilter` | Query filter logic used by Post model |
| `Filament\BlogSettings` | Admin settings page |

## Admin Panel (Filament)

**BlogSettings** -- configure default posts per page, layout style, and filter visibility for all blog instances.
