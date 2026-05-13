# Category Module — Usage

Day-to-day patterns for creating, organizing, querying, and attaching categories.

## Creating a category

```php
use Modules\Category\Models\Category;

$root = Category::create([
    'title'       => 'Tutorials',
    'description' => 'How-to guides and walkthroughs.',
    'parent_id'   => 0,
    'is_hidden'   => 0,
    'position'    => 1,
]);
```

The `url` slug is derived automatically from the title on save (no `url` column on the table — slugs are generated live via `Str::slug()`).

## Building a hierarchy

```php
$parent = Category::create(['title' => 'Tutorials', 'parent_id' => 0]);
$child  = Category::create(['title' => 'Microweber', 'parent_id' => $parent->id]);
$grand  = Category::create(['title' => 'Theme Building', 'parent_id' => $child->id]);
```

Read the hierarchy:

```php
// Direct children
$kids = $parent->children;

// All descendants (recursive)
function descendants(Category $c, array &$out = []): array {
    foreach ($c->children as $child) {
        $out[] = $child;
        descendants($child, $out);
    }
    return $out;
}

// Walk up the tree
$ancestors = [];
$cursor = $grand;
while ($cursor && $cursor->parent_id) {
    $cursor = $cursor->parent;
    $ancestors[] = $cursor;
}
```

`children` and `parent` are Eloquent relations defined on the `Category` model.

## Attaching content to a category

```php
use Modules\Post\Models\Post;
use Modules\Page\Models\Page;
use Modules\Product\Models\Product;

$post = Post::find($postId);

// Replace category attachments
$post->setCategories([$category1Id, $category2Id]);

// Read attached category IDs
$ids = $post->categoriesIds();

// Read full Category models
$categories = $post->categories;
```

Same methods work for Page and Product (they all extend `Content`).

## Querying content by category

```php
// Posts in a specific category
$posts = Post::active()
    ->whereCategoryIds($categoryId)
    ->orderBy('posted_at', 'desc')
    ->paginate(10);

// Posts in any of multiple categories
$posts = Post::active()
    ->whereCategoryIds([$cat1Id, $cat2Id])
    ->get();

// Mixed-type results in a category (uses the Content parent model)
$mixed = \Modules\Content\Models\Content::active()
    ->whereCategoryIds($categoryId)
    ->get();
```

## Querying categories themselves

```php
// All top-level categories
$top = Category::where('parent_id', 0)
    ->where('is_hidden', 0)
    ->orderBy('position')
    ->get();

// Find by slug
$category = Category::query()
    ->where('rel_type', 'content')
    ->byUrl('tutorials')  // custom scope on the model
    ->first();

// Find by title (lookup helper)
$category = Category::where('title', 'Tutorials')->first();
```

The `byUrl()` scope matches the slug-from-title against the input string with `Str::slug()` normalisation, so URL-encoded variants work.

## Hiding a category from menus

```php
$category->is_hidden = 1;
$category->save();
```

The Menu module + the public category-tree templates skip `is_hidden = 1` rows. The categorized content remains accessible via direct URL.

## Slug uniqueness within a parent

Categories under the same parent must have unique slugs. The save flow doesn't auto-suffix — you must ensure uniqueness yourself:

```php
$slug = \Str::slug($title);
$collision = Category::where('parent_id', $parentId)
    ->whereRaw("LOWER(title) = ?", [strtolower($title)])
    ->exists();

if ($collision) {
    // Append a numeric suffix or throw
}
```

## Reordering siblings

```php
// Drag-and-drop in the Filament admin updates `position` automatically.
// Programmatically:
$category->update(['position' => 3]);
```

## Removing content from a category

```php
// Detach a single content row from a category
\DB::table('categories_items')
    ->where('parent_id', $categoryId)
    ->where('rel_type', 'content')
    ->where('rel_id', $postId)
    ->delete();

// Or use the Content method (replaces the full attachment set)
$post->setCategories(array_diff($post->categoriesIds(), [$categoryId]));
```

## Building a category-archive page

```php
// In a controller / Livewire component
$category = Category::findOrFail($categoryId);
$posts = Post::active()
    ->whereCategoryIds($category->id)
    ->where('posted_at', '<=', now())
    ->orderBy('posted_at', 'desc')
    ->paginate(12);

return view('blog.category', compact('category', 'posts'));
```

## Translations

```php
$category->setTranslation('title', 'es', 'Tutoriales');
$category->setTranslation('description', 'es', 'Guías paso a paso.');

app()->setLocale('es');
echo $category->title;  // "Tutoriales"
```

(Provided the `Category` model uses the same Translatable trait — verify in `Modules/Category/Models/Category.php`.)

## Filament admin

`Modules\Category\Filament\Resources\CategoryResource` provides:

- Index with hierarchical tree view + drag-and-drop reorder
- Form with title, description, parent picker, is_hidden toggle, meta description
- Bulk delete with reassignment (move children to grandparent)
- Translations tab when multilanguage is enabled

## Test factory

```php
use Modules\Category\Models\Category;

$root = Category::factory()->create();
$child = Category::factory()->create(['parent_id' => $root->id]);
Category::factory()->count(10)->create();
```

Factory lives at `Modules/Category/Database/Factories/CategoryFactory.php`.
