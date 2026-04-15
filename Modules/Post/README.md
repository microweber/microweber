# Post

Blog post content type. Posts are Content items with `content_type = 'post'` and `subtype = 'post'`, managed through a dedicated model that extends `Content`.

## Relationship to the Content Module

`Post` extends `Modules\Content\Models\Content` and shares the same `content` database table. A global `PostScope` is applied automatically so queries only return posts. All Content helpers (`get_content`, `save_content`, events) work with posts -- the Post module adds a type-specific Eloquent model, API, and admin UI on top.

## Creating Posts

```php
use Modules\Post\Models\Post;

// Via Eloquent
$post = Post::create([
    'title'       => 'Getting Started with Microweber',
    'url'         => 'getting-started',
    'description' => 'A quick intro to the CMS.',
    'content_body'=> '<p>Full article body here.</p>',
    'is_active'   => 1,
    'parent'      => 8,  // parent blog page ID
]);

// Via the content helper (works the same)
$id = save_content([
    'title'        => 'My Post',
    'content_type' => 'post',
    'subtype'      => 'post',
]);

// Attach categories and tags
$post->setCategories([3, 7]);
$post->tag(['laravel', 'cms']);
```

## Querying Posts

```php
// All active posts, newest first
$posts = Post::active()->orderBy('created_at', 'desc')->paginate(10);

// Filter by category
$posts = Post::active()->whereCategoryIds(5)->get();

// Filter by tag
$posts = Post::active()->withAnyTag(['tutorial'])->get();

// Full-text keyword search
$posts = Post::active()
    ->where('title', 'like', '%microweber%')
    ->paginate(15);
```

## REST API

```bash
# List posts (public)
curl https://yoursite.com/api/posts?limit=10

# Get a single post
curl https://yoursite.com/api/posts/42

# Create post (Sanctum, admin only)
curl -X POST https://yoursite.com/api/posts \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"title":"New Post","content_body":"<p>Hello</p>"}'

# Update
curl -X PUT https://yoursite.com/api/posts/42 \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"title":"Updated Title"}'

# Delete
curl -X DELETE https://yoursite.com/api/posts/42 \
  -H "Authorization: Bearer $TOKEN"
```

## Template Tags

```html
<!-- List recent posts on any page -->
<module type="posts" limit="5" />

<!-- Inside a Blade view -->
@foreach(Post::active()->latest()->take(3)->get() as $post)
  <a href="{{ $post->link }}">{{ $post->title }}</a>
  <p>{{ $post->shortDescription(120) }}</p>
@endforeach
```

## Frontend Filtering

The `Post` model exposes a `frontendFilter` scope powered by `BlogFilter`, so the Blog module's Livewire component can filter by search, category, and tags with automatic query-string binding.

## Key Classes

| Class | Purpose |
|---|---|
| `Models\Post` | Eloquent model (extends Content, scoped to posts) |
| `Http\Controllers\Api\PostApiController` | RESTful CRUD controller |
| `Repositories\PostApiRepository` | Query/filter layer for the API |
| `Http\Resources\PostResource` | API JSON resource transformer |

## Admin Panel (Filament)

Posts are managed through the **PostResource** in the Filament admin with list, create, edit, and view pages, including SEO fields, category/tag assignment, and media uploads.
