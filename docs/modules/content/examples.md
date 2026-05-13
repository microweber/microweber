# Content Module — Examples

End-to-end recipes for cross-cutting content operations.

## Recipe 1: Listen to ContentWasCreated and trigger a Slack notification

```php
// In any ServiceProvider's boot()
use Modules\Content\Events\ContentWasCreated;
use Illuminate\Support\Facades\Http;

\Event::listen(ContentWasCreated::class, function (ContentWasCreated $event) {
    $content = $event->content;
    if ($content->content_type !== 'post') return;
    if (! $content->is_active) return;

    Http::post(config('services.slack.webhook'), [
        'text' => sprintf(
            'New blog post published: *%s* — %s',
            $content->title,
            $content->link
        ),
    ]);
});
```

## Recipe 2: Bulk-publish all draft posts older than 24 hours

```php
use Modules\Content\Models\Content;

$published = Content::where('content_type', 'post')
    ->where('is_active', 0)
    ->where('is_deleted', 0)
    ->where('created_at', '<=', now()->subDay())
    ->update([
        'is_active' => 1,
        'posted_at' => now(),
    ]);

echo "Published {$published} drafts.";
```

## Recipe 3: Search across all content types

```php
use Modules\Content\Models\Content;

$results = Content::active()
    ->where(function ($q) use ($keyword) {
        $q->where('title', 'like', "%{$keyword}%")
          ->orWhere('content_body', 'like', "%{$keyword}%")
          ->orWhere('description', 'like', "%{$keyword}%");
    })
    ->orderByRaw("CASE WHEN title LIKE ? THEN 0 ELSE 1 END", ["%{$keyword}%"])
    ->limit(20)
    ->get(['id', 'title', 'content_type', 'url']);

foreach ($results as $row) {
    echo "[{$row->content_type}] {$row->title} → {$row->link}\n";
}
```

## Recipe 4: Read and write content_data sidecar

```php
use Modules\Content\Models\Content;

$content = Content::find(5);

// Compute reading time from word count and store it
$wordCount = str_word_count(strip_tags($content->content_body));
$readingTime = max(1, ceil($wordCount / 200));
$content->setContentDataByFieldName('reading_time_minutes', (string) $readingTime);
$content->setContentDataByFieldName('word_count', (string) $wordCount);

// Later, in a Blade view
$minutes = $content->getContentDataByFieldName('reading_time_minutes') ?: '5';
echo "<span class=\"reading-time\">{$minutes} min read</span>";
```

## Recipe 5: Recompute reading time for every existing post

```php
use Modules\Content\Models\Content;

Content::where('content_type', 'post')
    ->where('is_deleted', 0)
    ->chunkById(50, function ($posts) {
        foreach ($posts as $post) {
            $words = str_word_count(strip_tags($post->content_body));
            $minutes = max(1, ceil($words / 200));
            $post->setContentDataByFieldName('reading_time_minutes', (string) $minutes);
        }
    });
```

The `chunkById` is important — `chunk` can skip rows when you mutate the iteration order.

## Recipe 6: Define a custom Event content type

```php
// app/Scopes/EventScope.php
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
```

```php
// app/Models/Event.php
namespace App\Models;

use App\Scopes\EventScope;
use Modules\Content\Models\Content;

class Event extends Content
{
    protected $table = 'content';

    protected static function booted(): void
    {
        parent::booted();
        static::addGlobalScope(new EventScope);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['content_type'] = 'event';
    }

    public function start(): ?\Carbon\Carbon
    {
        $v = $this->getContentDataByFieldName('event_start');
        return $v ? \Carbon\Carbon::parse($v) : null;
    }

    public function setStart(\DateTimeInterface $start): void
    {
        $this->setContentDataByFieldName('event_start', $start->format('Y-m-d H:i:s'));
    }
}
```

```php
// Usage
$event = \App\Models\Event::create([
    'title'     => 'Summer Conference 2026',
    'url'       => 'summer-conference-2026',
    'is_active' => 1,
]);
$event->setStart(now()->addMonth());

$upcoming = \App\Models\Event::active()
    ->get()
    ->filter(fn ($e) => $e->start() && $e->start()->isFuture());
```

## Recipe 7: Export all content as JSON

```php
use Modules\Content\Models\Content;

$rows = Content::where('is_deleted', 0)
    ->orderBy('id')
    ->get()
    ->toArray();

file_put_contents(
    storage_path('app/content-export-' . now()->format('Y-m-d') . '.json'),
    json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);
```

## Recipe 8: Soft-delete and restore

```php
use Modules\Content\Models\Content;

// Soft-delete
$content = Content::find(5);
$content->is_deleted = 1;
$content->save();

// List trashed
Content::trashed()->orderBy('updated_at', 'desc')->get();

// Restore
Content::trashed()->where('id', 5)->update(['is_deleted' => 0]);
```

The `ContentWasDeleted` event fires on the first call, `ContentWasRestored` on the restore — chain these for audit logs.

## Recipe 9: Revision rollback

```php
$revisionId = 42;
$revision = \DB::table('content_fields')->find($revisionId);

$content = \Modules\Content\Models\Content::find($revision->rel_id);
$content->update(['content_body' => $revision->value]);
```

The revision row stores the value at the time of the prior save — restoring it overwrites the current `content_body` with the saved version. The next save creates a NEW revision row, so rollbacks are reversible.

## Recipe 10: Translate a page to Spanish

```php
$content = \Modules\Content\Models\Content::find(5);

$content->setTranslation('title', 'es', 'Sobre Nosotros');
$content->setTranslation('content_body', 'es', '<p>Información de la empresa.</p>');
$content->setTranslation('content_meta_description', 'es', 'Conoce nuestra historia.');

// Or in bulk
foreach ([
    'title'                    => 'Sobre Nosotros',
    'content_body'             => '<p>Información.</p>',
    'content_meta_title'       => 'Sobre Nosotros — YourBrand',
    'content_meta_description' => 'Conoce nuestra historia.',
] as $field => $value) {
    $content->setTranslation($field, 'es', $value);
}

// Read in a Spanish-locale request, the title accessor returns the Spanish value automatically
app()->setLocale('es');
echo $content->title;  // "Sobre Nosotros"
```
