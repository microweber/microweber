# MediaLibrary Module — Examples

## Recipe 1: Embed the picker in a custom Filament resource

```php
namespace App\Filament\Resources\PromoResource\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;

TextInput::make('hero_media_id')
    ->label('Hero image')
    ->suffixAction(
        Action::make('pickMedia')
            ->icon('heroicon-o-photo')
            ->modalHeading('Choose hero image')
            ->modalContent(view('media-library::picker', [
                'multiselect' => false,
                'media_type' => 'image',
            ]))
            ->modalSubmitActionLabel('Choose')
    );
```

The picker dispatches `selected.media` when the user confirms; the parent listens via Livewire's event API and binds the result.

## Recipe 2: Programmatically open the library in a specific folder

```php
// Generate a deep link to a specific folder
$url = url('/admin/media-library?folder=' . $folderId);
return redirect($url);
```

The page's `mount()` reads the `folder` query param and calls `selectFolder($id)` automatically.

## Recipe 3: Listen for Unsplash downloads to attribute compliantly

```php
// In a ServiceProvider boot()
\Livewire\Livewire::listen('unsplash.downloaded', function (int $mediaId, string $photoId) {
    $media = \Modules\Media\Models\Media::find($mediaId);

    // Read the attribution data the Unsplash service stored
    $photographer = $media->getContentDataByFieldName('unsplash_photographer');
    $url = $media->getContentDataByFieldName('unsplash_url');

    \Log::info("Unsplash download: media #{$mediaId} from {$photographer} ({$url})");
});
```

## Recipe 4: Bulk upload via a script (bypasses the UI)

The library reads from the `media` table — programmatic inserts appear in the browser:

```php
use Modules\Media\Models\Media;
use Illuminate\Support\Facades\Storage;

$folderId = 7;
$srcDir = '/srv/import/photos/';

foreach (glob($srcDir . '*.jpg') as $local) {
    $name = basename($local);
    $disk = Storage::disk('userfiles');
    $disk->put("uploads/import/{$name}", file_get_contents($local), 'public');

    Media::create([
        'rel_type' => 'content',
        'rel_id' => 0, // unattached — visible in library, not bound to any content
        'filename' => "/userfiles/uploads/import/{$name}",
        'media_type' => 'image',
        'media_folder_id' => $folderId,
        'title' => pathinfo($name, PATHINFO_FILENAME),
    ]);
}
```

Refresh the library page — the new files appear in the target folder.

## Recipe 5: Custom audit listener for media operations

```php
// In a ServiceProvider boot()
foreach ([
    'media.uploaded',
    'media.moved',
    'media.deleted',
    'media.updated',
    'folder.created',
    'folder.renamed',
    'folder.deleted',
] as $event) {
    \Livewire\Livewire::listen($event, function (...$args) use ($event) {
        \DB::table('audit_log')->insert([
            'event' => $event,
            'payload' => json_encode($args),
            'user_id' => auth()->id(),
            'created_at' => now(),
        ]);
    });
}
```

## Recipe 6: Show Unsplash attribution on the public site

```html
@php
    $media = $post->media()->first();
    $photographer = $media?->getContentDataByFieldName('unsplash_photographer');
    $unsplashUrl = $media?->getContentDataByFieldName('unsplash_url');
@endphp

<figure>
    {!! responsive_thumbnail($media->filename, 1200, 600, ['alt' => $post->title]) !!}
    @if($photographer)
        <figcaption class="text-sm text-muted">
            Photo by <a href="{{ $unsplashUrl }}" rel="noopener" target="_blank">{{ $photographer }}</a> on Unsplash
        </figcaption>
    @endif
</figure>
```

Unsplash's licence requires attribution on hosted photos — the MediaLibrary stores the photographer name + URL in `content_data` on download specifically so templates can render it.

## Recipe 7: Restrict library access by user role

```php
namespace App\Filament\Admin\Pages;

class MediaLibrary extends \Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'editor']) ?? false;
    }
}
```

Replace the original page registration in `app/Providers/Filament/AdminPanelProvider.php` with your subclass.

## Recipe 8: Hide a folder from the sidebar

```php
// Add a `is_hidden` flag to media_folders (migration)
\Schema::table('media_folders', function ($t) {
    $t->boolean('is_hidden')->default(false)->after('name');
});

// Subclass the page and filter the folders property
class MediaLibrary extends \Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary
{
    public function getFoldersProperty(): array
    {
        return parent::getFoldersProperty()
            ->filter(fn ($f) => ! $f->is_hidden)
            ->all();
    }
}
```

## Recipe 9: Bulk-tag selected files via a Tags module integration

If the Tags module is installed and applied to `Modules\Media\Models\Media` (custom mod), add a bulk action:

```php
class MediaLibrary extends \Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary
{
    public function bulkTag(string $tag): void
    {
        \Modules\Media\Models\Media::whereIn('id', $this->bulkSelected)
            ->each->tag([$tag]);
    }
}
```

Then expose `bulkTag` in the bulk-action bar template.

## Recipe 10: Schedule a thumbnail cache prune

`MediaLibrary` doesn't prune the thumbnail cache on its own — that's a Media-module concern. Schedule a cleanup:

```php
// app/Console/Kernel.php (or a Schedule::call closure)
$schedule->call(function () {
    $cutoff = now()->subDays(90);
    $rows = \Modules\Media\Models\MediaThumbnail::where('created_at', '<', $cutoff)->get();
    foreach ($rows as $t) {
        \Storage::disk('userfiles')->delete(ltrim($t->filename, '/'));
    }
    \Modules\Media\Models\MediaThumbnail::where('created_at', '<', $cutoff)->delete();
})->daily()->name('media:thumbnail-prune')->withoutOverlapping();
```
