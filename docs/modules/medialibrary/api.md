# MediaLibrary Module — API Reference

MediaLibrary does NOT expose its own REST API — the data layer's REST endpoints live in the Media module at `/api/media`. See [`docs/modules/media/api.md`](../media/api.md) for that surface.

This file documents the **Livewire action surface** on the `MediaLibrary` Filament page + the `Unsplash` service.

## Livewire actions

Defined on `Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary`. Each action is a public method that Livewire wires to the corresponding template element.

### Lifecycle

| Method | Signature | Purpose |
|---|---|---|
| `mount(): void` | — | Initialize state: current folder, view mode, sort |
| `getMediaProperty(): LengthAwarePaginator` | — | The paginated media collection (filtered by folder + search) |
| `getFoldersProperty(): array` | — | The folder tree (rendered in the sidebar) |
| `getFolderCountsProperty(): array` | — | `[folder_id => count]` map for the sidebar badge counts |
| `getTotalMediaCountProperty(): int` | — | Total visible across all folders |

### Folder actions

| Method | Signature | Notes |
|---|---|---|
| `selectFolder(?int $folderId): void` | `null` for root | Switches the visible folder; resets pagination |
| `createFolder(): void` | reads `$this->newFolderName` | Inserts a new `media_folders` row under the current folder |
| `startRenameFolder(int $folderId): void` | — | Switches the folder name to inline-editable in the tree |
| `renameFolder(): void` | reads `$this->renamingFolderName` | Persists the rename |
| `cancelRename(): void` | — | Aborts the rename without saving |
| `deleteFolder(int $folderId): void` | — | Moves children to parent then deletes the folder row |

### View mode

| Method | Signature | Notes |
|---|---|---|
| `toggleView(string $mode): void` | `'grid'` or `'list'` | Persists to session |

### Selection + detail panel

| Method | Signature | Notes |
|---|---|---|
| `selectMedia(int $mediaId): void` | — | Opens the detail panel on the chosen file |
| `closeDetailPanel(): void` | — | Closes without saving |
| `saveMediaDetails(): void` | reads bound fields | Persists title/description/alt/folder edits |

### Bulk select

| Method | Signature | Notes |
|---|---|---|
| `toggleBulkSelect(int $mediaId): void` | — | Add/remove from the selection set |
| `selectAllVisible(): void` | — | Set the selection to every visible file |
| `clearSelection(): void` | — | Empty the set |
| `bulkMove(int $folderId): void` | — | Move every selected file to the target folder |
| `bulkDelete(): void` | — | Delete every selected file (DB + disk) |

### Unsplash

| Method | Signature | Notes |
|---|---|---|
| `unsplashSearch(): void` | reads `$this->unsplashQuery` | Calls `Unsplash::search()`, populates `$this->unsplashResults` |
| `unsplashDownload(string $photoId): void` | — | Calls `Unsplash::download()`, creates a `media` row in the current folder |

## `Unsplash` service

`Modules\MediaLibrary\Support\Unsplash` is a thin HTTP client over the Unsplash REST API.

```php
namespace Modules\MediaLibrary\Support;

class Unsplash
{
    public function __construct($app = null);
    public function search(string $keyword, int $page = 1): array;
    public function download(string $photoId): array;
}
```

### `search($keyword, $page = 1): array`

Returns the parsed Unsplash response:

```php
[
    'total' => 1247,
    'total_pages' => 125,
    'results' => [
        ['id' => 'abc123', 'urls' => ['regular' => '...', 'thumb' => '...'], 'alt_description' => 'Mountain lake', ...],
        ...
    ],
]
```

Throws if `UNSPLASH_ACCESS_KEY` is missing or the API returns an error.

### `download($photoId): array`

Downloads the given Unsplash photo, writes it to the configured userfiles disk, creates a `media` row (in the current folder if called from the page), and returns:

```php
[
    'media_id' => 87,
    'filename' => '/userfiles/uploads/unsplash/2026/05/abc123.jpg',
    'attribution' => [
        'photographer' => 'Jane Doe',
        'photographer_url' => 'https://unsplash.com/@janedoe',
        'unsplash_url' => 'https://unsplash.com/photos/abc123',
    ],
]
```

The attribution is stored in `content_data` on the `media` row so templates can render it for licence compliance.

## Picker integration

When MediaLibrary is embedded as a Filament form picker, the page listens for the `picker:open` event from the parent form and dispatches `picker:selected` with the chosen media id when the user confirms.

```php
// Parent form
use Filament\Forms\Components\Actions\Action;

TextInput::make('hero_media_id')
    ->suffixAction(
        Action::make('pickMedia')
            ->icon('heroicon-o-photo')
            ->modalContent(view('media-library::picker', ['multiselect' => false]))
            ->modalSubmitActionLabel('Choose')
    )
```

The picker view dispatches `selected.media` via Livewire; the parent listens via `wire:on="selected.media"` and binds the id into the form state.

## Events

Livewire events emitted by the MediaLibrary page:

- `media.uploaded` `(int $mediaId)` — after a successful upload
- `media.moved` `(int $mediaId, ?int $oldFolderId, ?int $newFolderId)` — after a move
- `media.deleted` `(int $mediaId)` — after delete
- `media.updated` `(int $mediaId)` — after detail-panel save
- `folder.created` `(int $folderId)`
- `folder.renamed` `(int $folderId, string $oldName, string $newName)`
- `folder.deleted` `(int $folderId)`
- `unsplash.downloaded` `(int $mediaId, string $photoId)`

Listen via the standard Livewire event API in another component or service provider.
