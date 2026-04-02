<?php

namespace Modules\MediaLibrary\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;

class MediaLibrary extends Page
{
    use WithPagination;
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = null;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Media Library';

    protected string $view = 'modules.media_library::filament.admin.pages.media-library-page';

    // --- View state ---
    public string $viewMode = 'grid';
    public string $search = '';
    public string $typeFilter = '';
    public ?int $selectedFolderId = null;
    public ?int $selectedMediaId = null;
    public bool $includeSubfolders = true;

    // --- Folder CRUD ---
    public string $newFolderName = '';
    public ?int $newFolderParentId = null;
    public string $renameFolderName = '';
    public ?int $renameFolderId = null;

    // --- File uploads ---
    public array $uploads = [];

    // --- Selected media detail (for side panel) ---
    public ?array $selectedMediaData = null;
    public string $editTitle = '';
    public string $editDescription = '';
    public string $editAltText = '';

    // --- Bulk selection ---
    public array $bulkSelected = [];

    protected int $perPage = 36;

    /** Thumbnail width in pixels for grid view. */
    public const GRID_THUMB_WIDTH = 300;

    public static function canAccess(): bool
    {
        return is_admin();
    }

    public static function getNavigationLabel(): string
    {
        return 'Media Library';
    }

    public function mount(): void
    {
        $this->viewMode = session('media_library_view', 'grid');
    }

    // =========================================================================
    // Media Query
    // =========================================================================

    public function getMediaProperty(): LengthAwarePaginator
    {
        $query = Media::query()->with('folder');

        // Folder filter
        if ($this->selectedFolderId !== null) {
            if ($this->includeSubfolders) {
                $folder = MediaFolder::find($this->selectedFolderId);
                if ($folder) {
                    $childIds = $folder->getAllChildFolderIds();
                    $allFolderIds = array_merge([$this->selectedFolderId], $childIds);
                    $query->whereIn('folder_id', $allFolderIds);
                }
            } else {
                $query->where('folder_id', $this->selectedFolderId);
            }
        }

        // Search
        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('filename', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Type filter
        if ($this->typeFilter !== '') {
            $query->where('media_type', $this->typeFilter);
        }

        return $query->orderByDesc('created_at')->paginate($this->perPage);
    }

    // =========================================================================
    // Folder Tree
    // =========================================================================

    public function getFoldersProperty(): array
    {
        return MediaFolder::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getFolderCountsProperty(): array
    {
        return Media::query()
            ->selectRaw('folder_id, COUNT(*) as count')
            ->groupBy('folder_id')
            ->pluck('count', 'folder_id')
            ->toArray();
    }

    public function getTotalMediaCountProperty(): int
    {
        return Media::count();
    }

    public function selectFolder(?int $folderId): void
    {
        $this->selectedFolderId = $folderId;
        $this->selectedMediaId = null;
        $this->selectedMediaData = null;
        $this->bulkSelected = [];
        $this->resetPage();
    }

    public function createFolder(): void
    {
        $name = trim($this->newFolderName);
        if ($name === '') {
            return;
        }

        MediaFolder::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_id' => $this->newFolderParentId,
            'created_by' => auth()->id(),
        ]);

        $this->newFolderName = '';
        $this->newFolderParentId = null;
        unset($this->folders);
        unset($this->folderCounts);
    }

    public function startRenameFolder(int $folderId): void
    {
        $folder = MediaFolder::find($folderId);
        if ($folder && !$folder->is_system) {
            $this->renameFolderId = $folderId;
            $this->renameFolderName = $folder->name;
        }
    }

    public function renameFolder(): void
    {
        $name = trim($this->renameFolderName);
        if ($name === '' || $this->renameFolderId === null) {
            return;
        }

        $folder = MediaFolder::find($this->renameFolderId);
        if ($folder && !$folder->is_system) {
            $folder->update([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        $this->renameFolderId = null;
        $this->renameFolderName = '';
        unset($this->folders);
    }

    public function cancelRename(): void
    {
        $this->renameFolderId = null;
        $this->renameFolderName = '';
    }

    public function deleteFolder(int $folderId): void
    {
        $folder = MediaFolder::find($folderId);
        if (!$folder || $folder->is_system) {
            return;
        }

        $mediaCount = $folder->media()->count();
        $childCount = $folder->children()->count();

        if ($mediaCount > 0 || $childCount > 0) {
            $this->dispatch('notify', type: 'warning', message: 'Folder is not empty. Move or delete its contents first.');
            return;
        }

        $folder->delete();

        if ($this->selectedFolderId === $folderId) {
            $this->selectedFolderId = null;
        }

        unset($this->folders);
        unset($this->folderCounts);
    }

    // =========================================================================
    // View Mode
    // =========================================================================

    public function toggleView(string $mode): void
    {
        if (in_array($mode, ['grid', 'list'], true)) {
            $this->viewMode = $mode;
            session(['media_library_view' => $mode]);
        }
    }

    // =========================================================================
    // Media Selection & Detail Panel
    // =========================================================================

    public function selectMedia(int $mediaId): void
    {
        if ($this->selectedMediaId === $mediaId) {
            $this->selectedMediaId = null;
            $this->selectedMediaData = null;
            return;
        }

        $media = Media::with('folder')->find($mediaId);
        if (!$media) {
            return;
        }

        $this->selectedMediaId = $mediaId;
        $this->editTitle = $media->title ?? '';
        $this->editDescription = $media->description ?? '';
        $this->editAltText = data_get($media->metadata, 'alt_text', '');

        // Get usage info (content items referencing this media)
        $usedIn = [];
        if ($media->rel_type && $media->rel_id) {
            try {
                $model = app($media->rel_type);
                $record = $model->find($media->rel_id);
                if ($record) {
                    $usedIn[] = [
                        'title' => $record->title ?? ('ID: ' . $record->id),
                        'type' => class_basename($media->rel_type),
                        'id' => $record->id,
                    ];
                }
            } catch (\Throwable $e) {
                // Model class may not be resolvable
            }
        }

        $this->selectedMediaData = [
            'id' => $media->id,
            'title' => $media->title,
            'description' => $media->description,
            'filename' => $media->filename,
            'url' => $media->url,
            'media_type' => $media->media_type,
            'file_type' => $media->file_type,
            'file_size' => $media->file_size,
            'folder_name' => $media->folder?->name,
            'folder_id' => $media->folder_id,
            'created_at' => $media->created_at?->format('M d, Y H:i'),
            'alt_text' => data_get($media->metadata, 'alt_text', ''),
            'is_synced_to_cdn' => $media->is_synced_to_cdn,
            'cdn_url' => $media->cdn_url,
            'used_in' => $usedIn,
        ];
    }

    public function closeDetailPanel(): void
    {
        $this->selectedMediaId = null;
        $this->selectedMediaData = null;
    }

    public function saveMediaDetails(): void
    {
        if ($this->selectedMediaId === null) {
            return;
        }

        $media = Media::find($this->selectedMediaId);
        if (!$media) {
            return;
        }

        $metadata = $media->metadata ?? [];
        $metadata['alt_text'] = $this->editAltText;

        $media->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'metadata' => $metadata,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Media details saved.');

        // Refresh the detail panel data
        $this->selectMedia($this->selectedMediaId);
    }

    // =========================================================================
    // Bulk Actions
    // =========================================================================

    public function toggleBulkSelect(int $mediaId): void
    {
        if (in_array($mediaId, $this->bulkSelected, true)) {
            $this->bulkSelected = array_values(array_diff($this->bulkSelected, [$mediaId]));
        } else {
            $this->bulkSelected[] = $mediaId;
        }
    }

    public function selectAllVisible(): void
    {
        $media = $this->getMediaProperty();
        $this->bulkSelected = $media->pluck('id')->toArray();
    }

    public function deselectAll(): void
    {
        $this->bulkSelected = [];
    }

    public function bulkDelete(): void
    {
        if (empty($this->bulkSelected)) {
            return;
        }

        Media::whereIn('id', $this->bulkSelected)->delete();
        $this->bulkSelected = [];
        $this->selectedMediaId = null;
        $this->selectedMediaData = null;
        unset($this->folderCounts);
        unset($this->totalMediaCount);
        $this->dispatch('notify', type: 'success', message: 'Selected media deleted.');
    }

    public function bulkMoveToFolder(?int $folderId): void
    {
        if (empty($this->bulkSelected)) {
            return;
        }

        Media::whereIn('id', $this->bulkSelected)->update(['folder_id' => $folderId]);
        $this->bulkSelected = [];
        unset($this->folderCounts);
        $this->dispatch('notify', type: 'success', message: 'Selected media moved.');
    }

    // =========================================================================
    // File Upload
    // =========================================================================

    public function updatedUploads(): void
    {
        $this->validate([
            'uploads.*' => [
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,gif,svg,webp,bmp,tiff,mp4,avi,mov,wmv,webm,mp3,wav,ogg,flac,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf',
            ],
        ]);

        foreach ($this->uploads as $upload) {
            $filename = $upload->getClientOriginalName();
            $sanitized = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($filename, PATHINFO_FILENAME));
            $ext = strtolower($upload->getClientOriginalExtension());

            // Block executable extensions
            $blocked = ['php', 'phtml', 'php3', 'php4', 'php5', 'phps', 'js', 'sh', 'exe', 'bat', 'cmd', 'com', 'pif', 'scr'];
            if (in_array($ext, $blocked, true)) {
                continue;
            }

            $storedPath = $upload->store('userfiles/media', 'public');

            $mediaType = 'file';
            $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff'];
            $videoExts = ['mp4', 'avi', 'mov', 'wmv', 'webm'];
            $audioExts = ['mp3', 'wav', 'ogg', 'flac'];
            if (in_array($ext, $imageExts, true)) {
                $mediaType = 'picture';
            } elseif (in_array($ext, $videoExts, true)) {
                $mediaType = 'video';
            } elseif (in_array($ext, $audioExts, true)) {
                $mediaType = 'audio';
            }

            Media::create([
                'title' => $sanitized,
                'filename' => url('storage/' . $storedPath),
                'media_type' => $mediaType,
                'folder_id' => $this->selectedFolderId,
                'file_size' => $upload->getSize(),
                'created_by' => auth()->id(),
            ]);
        }

        $this->uploads = [];
        unset($this->folderCounts);
        unset($this->totalMediaCount);
        $this->dispatch('notify', type: 'success', message: 'Files uploaded successfully.');
    }

    public function deleteMedia(int $mediaId): void
    {
        $media = Media::find($mediaId);
        if ($media) {
            $media->delete();
        }

        if ($this->selectedMediaId === $mediaId) {
            $this->selectedMediaId = null;
            $this->selectedMediaData = null;
        }

        $this->bulkSelected = array_values(array_diff($this->bulkSelected, [$mediaId]));
        unset($this->folderCounts);
        unset($this->totalMediaCount);
    }

    // =========================================================================
    // Search debounce
    // =========================================================================

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Generate a cached thumbnail URL for grid display.
     * Uses the existing thumbnail() helper which handles {SITE_URL} placeholders,
     * caching, and webp conversion.
     */
    public function getThumbnailUrl(string $filename, int $width = 300): string
    {
        if (empty($filename)) {
            return '';
        }

        try {
            $url = thumbnail($filename, $width);
            return $url ?: $filename;
        } catch (\Throwable $e) {
            return $filename;
        }
    }

    public function formatFileSize(?int $bytes): string
    {
        if ($bytes === null || $bytes === 0) {
            return '-';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        return number_format($bytes / 1024, 1) . ' KB';
    }
}
