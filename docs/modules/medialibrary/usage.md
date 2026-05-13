# MediaLibrary Module — Usage

Operator-facing patterns for browsing, organizing, and searching the media library.

## Opening the browser

Navigate to `/admin/media-library` (URL slug varies by panel config). The page renders:

- Left sidebar: folder tree with file counts
- Main canvas: file grid (default) or list view (toggleable)
- Right rail / modal: detail panel for the currently-selected file

## Folder operations

- **Create folder:** click "New folder" in the sidebar header. The folder is inserted as a child of the currently-selected parent.
- **Rename:** double-click a folder name or click the pencil icon. The text becomes editable; press Enter to save, Escape to cancel.
- **Delete folder:** trash icon next to the folder. Files inside the folder are moved to the parent (NOT deleted) — folder deletion is non-destructive.
- **Drag-and-drop:** drag a folder onto another to re-parent it; drag onto the breadcrumb's "Root" entry to move to top level.

## File operations

### Upload

Drag files anywhere onto the page or click the "Upload" button. Files are validated against `max_upload_mb` and `allowed_extensions` (Media module options) before being written to disk.

### Move

- Select one or more files (checkbox in grid, click in list)
- Click "Move to folder" in the bulk-action bar
- Pick the target folder

Or drag files onto a folder in the sidebar.

### Delete

- Select one or more files
- Click "Delete" in the bulk-action bar
- Confirm

Deletes remove both the `media` row AND the file from disk (via `Storage::disk('userfiles')->delete($filename)`).

### Edit metadata

Click a file to open the detail panel. Editable fields:

- Title
- Description
- Alt text (for accessibility)
- Folder (move via dropdown)

Changes save on blur (each field) or via the "Save" button at the bottom of the panel.

## Search

The search input at the top of the page filters across:

- `filename` (substring match)
- `title`
- `description`

Search is debounced — typing stops the previous query and waits 300ms before issuing the new one.

To clear the search: click the × in the search input, or backspace to empty + the filter resets to the current folder.

## View modes

Toggle between **grid** (thumbnails + filename) and **list** (filename + size + date + actions). The choice persists in the user's session.

## Bulk selection

- Click a file checkbox to add to selection
- Shift-click extends the selection to the clicked file
- "Select all visible" button selects every file currently in the grid (matching the current search/folder filter)
- "Clear selection" empties the set

Bulk-action bar appears at the bottom of the page when ≥1 file is selected: Move, Delete, Tag (if Tags module installed).

## Unsplash integration

If `UNSPLASH_ACCESS_KEY` is set in `.env`, an "Unsplash" tab appears in the browser:

1. Type a search term (e.g. "office workspace")
2. Click an image to download it to the current folder
3. The download is silent — the new `media` row appears in the grid immediately

The Unsplash service is implemented at `Modules\MediaLibrary\Support\Unsplash` and is a thin HTTP client over the Unsplash REST API:

```php
$unsplash = app(\Modules\MediaLibrary\Support\Unsplash::class);
$results = $unsplash->search('mountain lake', $page = 1);
$saved = $unsplash->download($results['results'][0]['id']);
```

## Picker mode (embedded in forms)

When the MediaLibrary is embedded as a picker inside another Filament form (e.g. choosing a featured image for a Post), the browser opens in a modal instead of a full page:

- Sidebar is collapsed by default
- View mode forced to grid
- Selecting a file fires a `mediaSelected` Livewire event that the parent form listens for
- "Confirm" button at the bottom of the modal closes it + returns the chosen `Media` id to the parent

Form fields integrate via:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('hero_media_id')
    ->suffixAction(\Filament\Forms\Components\Actions\Action::make('pickMedia')
        ->icon('heroicon-o-photo')
        ->modalContent(view('media-library::picker', ['multiselect' => false]))
        ->modalSubmitAction(false)
    )
```

The exact form-side wiring depends on the parent module; see how `PostResource` does it for the canonical example.

## Keyboard shortcuts

Inside the browser:

- `/` — focus the search input
- `Escape` — close the detail panel (if open), then clear the search, then clear the selection
- `Delete` — bulk-delete selected files (with confirmation)
- `Ctrl/Cmd + A` — select all visible

## Auditing

Every action emits a Livewire event the parent panel can listen for:

- `folder.created`, `folder.renamed`, `folder.deleted`
- `media.uploaded`, `media.moved`, `media.deleted`, `media.updated`
- `unsplash.downloaded`

Audit listeners can log these to a custom table or push to a Slack channel for compliance.
