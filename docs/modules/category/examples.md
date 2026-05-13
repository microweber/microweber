# Category Module — Examples

## Recipe 1: Build a 3-level category hierarchy

```php
use Modules\Category\Models\Category;

$tutorials = Category::create([
    'title' => 'Tutorials',
    'description' => 'Step-by-step walkthroughs.',
    'parent_id' => 0,
    'is_hidden' => 0,
    'position' => 1,
]);

$mw = Category::create([
    'title' => 'Microweber',
    'parent_id' => $tutorials->id,
    'position' => 1,
]);

$themes = Category::create([
    'title' => 'Theme Building',
    'parent_id' => $mw->id,
    'position' => 1,
]);

$plugins = Category::create([
    'title' => 'Plugin Development',
    'parent_id' => $mw->id,
    'position' => 2,
]);
```

## Recipe 2: Render a sidebar nav using `categories_tree`

```html
<aside class="category-tree">
    {!! categories_tree([
        'parent_id'      => 0,
        'rel_type'       => 'content',
        'ul_class'       => 'nav flex-column',
        'li_class'       => 'nav-item',
        'max_depth'      => 3,
        'include_empty'  => false,
    ]) !!}
</aside>
```

## Recipe 3: Category archive page (Blog category)

```php
use Modules\Category\Models\Category;
use Modules\Post\Models\Post;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::query()
            ->where('rel_type', 'content')
            ->byUrl($slug)
            ->firstOrFail();

        $posts = Post::active()
            ->whereCategoryIds($category->id)
            ->where('posted_at', '<=', now())
            ->orderBy('posted_at', 'desc')
            ->paginate(12);

        return view('blog.category', compact('category', 'posts'));
    }
}
```

## Recipe 4: Mixed-type category (posts + products)

```php
use Modules\Content\Models\Content;

// Show everything in the "Featured" category
$everything = Content::active()
    ->whereCategoryIds($featuredCategoryId)
    ->orderByDesc('created_at')
    ->paginate(20);

foreach ($everything as $item) {
    echo match ($item->content_type) {
        'post'    => "[Post] {$item->title}\n",
        'product' => "[Product] {$item->title} — \${$item->price}\n",
        'page'    => "[Page] {$item->title}\n",
        default   => "[{$item->content_type}] {$item->title}\n",
    };
}
```

## Recipe 5: Bulk-assign existing posts to a new category

```php
use Modules\Category\Models\Category;
use Modules\Post\Models\Post;

$category = Category::create(['title' => 'Legacy Posts', 'parent_id' => 0]);

// Attach all posts older than 2 years
Post::active()
    ->where('created_at', '<', now()->subYears(2))
    ->chunkById(50, function ($posts) use ($category) {
        foreach ($posts as $post) {
            $existing = $post->categoriesIds();
            $post->setCategories(array_merge($existing, [$category->id]));
        }
    });
```

## Recipe 6: Re-parent a subtree

```php
use Modules\Category\Models\Category;

// Move "Theme Building" out from under "Microweber" and place it under "Tutorials" directly
$themes = Category::find($themesId);
$themes->parent_id = $tutorialsId;
$themes->save();

// All descendants stay attached to $themes — no recursion needed
```

## Recipe 7: Detect and break category cycles

```php
function wouldCreateCycle(int $categoryId, int $newParentId): bool
{
    if ($categoryId === $newParentId) return true;

    $cursor = $newParentId;
    while ($cursor) {
        $row = \DB::table('categories')->where('id', $cursor)->first();
        if (! $row) return false;
        if ((int) $row->parent_id === $categoryId) return true;
        $cursor = (int) $row->parent_id;
    }
    return false;
}

// Use before re-parenting
if (wouldCreateCycle($id, $newParentId)) {
    throw new \RuntimeException("Cannot make {$newParentId} the parent of {$id} — would create a cycle.");
}
```

## Recipe 8: Translated category titles for a multilingual site

```php
$category = Category::find($id);
$category->setTranslation('title', 'es', 'Tutoriales');
$category->setTranslation('description', 'es', 'Guías paso a paso.');
$category->setTranslation('title', 'de', 'Anleitungen');

app()->setLocale('es');
echo $category->title;  // Tutoriales

app()->setLocale('de');
echo $category->title;  // Anleitungen
```

## Recipe 9: Hide a category branch from public pages

```php
$category->update(['is_hidden' => 1]);

// All public templates skip is_hidden = 1 — but content categorized
// under this branch is still reachable via direct URL.
```

## Recipe 10: REST API tree dump

```bash
TOKEN=$(curl -s -X POST https://yoursite.com/api/login \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@yoursite.com","password":"…"}' | jq -r .token)

# Get top-level categories
curl -H "Authorization: Bearer $TOKEN" \
    "https://yoursite.com/api/categories?parent_id=0&limit=100" | jq .

# For each one, fetch children
TOP_ID=3
curl -H "Authorization: Bearer $TOKEN" \
    "https://yoursite.com/api/categories?parent_id=$TOP_ID" | jq .
```

For a single-shot tree dump, the `categories_tree` helper inside a controller is faster than multiple REST round-trips.
