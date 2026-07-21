{{-- task-2026-05-17-8c8d0b / AI-855 Defect 1 — persistent visible dropzone
     affordance. Pre-fix the upload zone was x-show="showUploadZone || dragOver"
     (default false) so users saw no drag-drop target unless they first clicked
     the Upload button. Three-clicks-and-context-switches per upload vs. the
     drag-from-desktop one-gesture baseline. Fix: showUploadZone defaults to
     true so the dropzone affordance is always visible; existing toggle still
     works for the click-to-show flow. Native HTML5 drag handlers preserved
     on the zone. The .mw-media-library-dropzone class is appended alongside
     .mw-media-upload-zone for designer's contract-test selector. --}}
<div
    x-data="{
        showUploadZone: true,
        dragOver: false,
        confirmBulkDelete: false,
        showMoveToFolder: false,
        showCreateFolder: false,
    }"
    class="mw-media-library"
>
    {{-- ================================================================== --}}
    {{-- Toolbar: Search, Filters, View Toggle, Upload                     --}}
    {{-- ================================================================== --}}
    <div class="mw-media-toolbar">
        <div class="mw-media-toolbar-left">
            <div class="mw-media-search">
                <x-heroicon-m-magnifying-glass class="mw-media-search-icon" />
                {{-- task-2026-05-17-8c8d0b / AI-855 Defect 3 — sibling of
                     AI-817 hidden-label WCAG 3.3.2 family. Placeholder
                     alone is not a label per WCAG; add aria-label so
                     screen readers announce the field's purpose. --}}
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search media..."
                    aria-label="Search media"
                    class="mw-media-search-input"
                />
            </div>

            <select wire:model.live.debounce.500ms="typeFilter" class="mw-media-filter-select">
                <option value="">All types</option>
                <option value="picture">Images</option>
                <option value="video">Videos</option>
                <option value="audio">Audio</option>
                <option value="file">Documents</option>
            </select>

            {{-- AI-1039 / task-2026-05-22-558c11 — visible "From" and "To" labels next to the
                 date pickers so the inputs are self-describing without requiring tooltip hover.
                 HTML5 type="date" is kept (locale-aware browser formatting); labels clarify context. --}}
            <label class="mw-media-filter-date-wrap">
                <span class="mw-media-filter-date-label">From</span>
                <input
                    type="date"
                    wire:model.live.debounce.500ms="dateFrom"
                    class="mw-media-filter-date"
                    aria-label="From date"
                />
            </label>
            <label class="mw-media-filter-date-wrap">
                <span class="mw-media-filter-date-label">To</span>
                <input
                    type="date"
                    wire:model.live.debounce.500ms="dateTo"
                    class="mw-media-filter-date"
                    aria-label="To date"
                />
            </label>

            @if($search || $typeFilter || $dateFrom || $dateTo || $selectedFolderId)
                <button wire:click="clearFilters" class="mw-media-clear-filters" title="Clear all filters">
                    <x-heroicon-m-x-circle class="w-4 h-4" />
                </button>
            @endif
        </div>

        <div class="mw-media-toolbar-right">
            {{-- View toggle --}}
            <div class="mw-media-view-toggle">
                <button
                    wire:click="toggleView('grid')"
                    class="mw-media-view-btn {{ $viewMode === 'grid' ? 'active' : '' }}"
                    title="Grid view"
                >
                    <x-heroicon-m-squares-2x2 class="w-4 h-4" />
                </button>
                <button
                    wire:click="toggleView('list')"
                    class="mw-media-view-btn {{ $viewMode === 'list' ? 'active' : '' }}"
                    title="List view"
                >
                    <x-heroicon-m-list-bullet class="w-4 h-4" />
                </button>
            </div>

            {{-- Unsplash button --}}
            <button
                wire:click="switchTab('{{ $activeTab === 'unsplash' ? 'library' : 'unsplash' }}')"
                class="mw-media-unsplash-btn {{ $activeTab === 'unsplash' ? 'active' : '' }}"
                title="Search Unsplash stock photos"
            >
                <x-heroicon-m-camera class="w-4 h-4" />
                Unsplash
            </button>

            {{-- Upload button --}}
            <button
                @click="showUploadZone = !showUploadZone"
                class="mw-media-upload-btn"
            >
                <x-heroicon-m-arrow-up-tray class="w-4 h-4" />
                Upload
            </button>
        </div>
    </div>

    {{-- Bulk actions bar --}}
    @if(count($bulkSelected) > 0)
        <div class="mw-media-bulk-bar">
            <span class="mw-media-bulk-count">{{ count($bulkSelected) }} selected</span>
            <button wire:click="selectAllVisible" class="mw-media-bulk-action">Select all</button>
            <button wire:click="deselectAll" class="mw-media-bulk-action">Deselect</button>
            <button @click="showMoveToFolder = true" class="mw-media-bulk-action">
                <x-heroicon-m-folder-arrow-down class="w-4 h-4" /> Move
            </button>
            @if($this->isCdnConfigured())
                <button wire:click="bulkSyncToCdn" class="mw-media-bulk-action">
                    <x-heroicon-m-cloud-arrow-up class="w-4 h-4" /> CDN Sync
                </button>
            @endif
            <button @click="confirmBulkDelete = true" class="mw-media-bulk-action mw-media-bulk-danger">
                <x-heroicon-m-trash class="w-4 h-4" /> Delete
            </button>
        </div>

        {{-- Bulk delete confirmation --}}
        <div x-show="confirmBulkDelete" x-cloak class="mw-media-confirm-bar">
            <span>Delete {{ count($bulkSelected) }} items permanently?</span>
            <button wire:click="bulkDelete" @click="confirmBulkDelete = false" class="mw-media-confirm-yes">Yes, delete</button>
            <button @click="confirmBulkDelete = false" class="mw-media-confirm-no">Cancel</button>
        </div>

        {{-- Move to folder selector --}}
        <div x-show="showMoveToFolder" x-cloak class="mw-media-confirm-bar">
            <span>Move to:</span>
            <button wire:click="bulkMoveToFolder(null)" @click="showMoveToFolder = false" class="mw-media-bulk-action">Root (no folder)</button>
            @foreach($this->folders as $folder)
                <button wire:click="bulkMoveToFolder({{ $folder['id'] }})" @click="showMoveToFolder = false" class="mw-media-bulk-action">{{ $folder['name'] }}</button>
            @endforeach
            <button @click="showMoveToFolder = false" class="mw-media-confirm-no">Cancel</button>
        </div>
    @endif

    {{-- Upload zone with progress indicators --}}
    <div
        x-data="{
            uploading: false,
            progress: 0,
            fileCount: 0,
            uploadComplete: false,
            uploadError: false,
            errorMessage: '',
            handleFiles(files) {
                if (this.uploading || !files.length) return;
                this.uploading = true;
                this.progress = 0;
                this.fileCount = files.length;
                this.uploadComplete = false;
                this.uploadError = false;
                this.errorMessage = '';
                $wire.uploadMultiple('uploads', files,
                    () => {
                        this.progress = 100;
                        this.uploadComplete = true;
                        setTimeout(() => {
                            this.uploading = false;
                            this.uploadComplete = false;
                            this.progress = 0;
                            showUploadZone = false;
                        }, 2000);
                    },
                    (error) => {
                        this.uploadError = true;
                        this.errorMessage = 'Upload failed. Check file size (max 10MB) and type.';
                        this.uploading = false;
                        this.progress = 0;
                    },
                    (event) => {
                        this.progress = event.detail.progress;
                    }
                );
            }
        }"
        x-show="showUploadZone || dragOver"
        x-cloak
        class="mw-media-upload-zone mw-media-library-dropzone"
        :class="{
            'drag-over': dragOver,
            'is-uploading': uploading,
            'is-complete': uploadComplete,
            'is-error': uploadError
        }"
        x-on:dragover.prevent="dragOver = true"
        x-on:dragleave.prevent="dragOver = false"
        x-on:drop.prevent="
            dragOver = false;
            handleFiles($event.dataTransfer.files);
        "
    >
        <div class="mw-media-upload-zone-inner">
            {{-- Default state --}}
            <template x-if="!uploading && !uploadComplete && !uploadError">
                <div class="mw-media-upload-default">
                    <x-heroicon-o-cloud-arrow-up class="w-10 h-10 text-gray-400" />
                    <p>Drag files here or click to browse</p>
                    <label class="mw-media-upload-browse-btn">
                        Browse files
                        <input
                            type="file"
                            multiple
                            class="sr-only"
                            accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt"
                            x-on:change="handleFiles($event.target.files)"
                        />
                    </label>
                </div>
            </template>

            {{-- Uploading state --}}
            <template x-if="uploading && !uploadComplete">
                <div class="mw-media-upload-progress">
                    <div class="mw-media-upload-spinner"></div>
                    <p class="mw-media-upload-status">
                        Uploading <span x-text="fileCount"></span> file<span x-show="fileCount > 1">s</span>...
                    </p>
                    <div class="mw-media-upload-progress-bar">
                        <div class="mw-media-upload-progress-fill" :style="'width: ' + progress + '%'"></div>
                    </div>
                    <span class="mw-media-upload-percent" x-text="Math.round(progress) + '%'"></span>
                </div>
            </template>

            {{-- Complete state --}}
            <template x-if="uploadComplete">
                <div class="mw-media-upload-complete">
                    <x-heroicon-o-check-circle class="w-10 h-10" style="color: var(--mw-success, #2fb344);" />
                    <p>Upload complete</p>
                </div>
            </template>

            {{-- Error state --}}
            <template x-if="uploadError">
                <div class="mw-media-upload-error">
                    <x-heroicon-o-exclamation-triangle class="w-10 h-10" style="color: var(--mw-danger, #d63939);" />
                    <p x-text="errorMessage"></p>
                    <button
                        class="mw-media-upload-browse-btn"
                        @click="uploadError = false; errorMessage = '';"
                    >Try again</button>
                </div>
            </template>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- 3-Panel Layout                                                     --}}
    {{-- ================================================================== --}}
    <div class="mw-media-panels" x-on:dragover.prevent="dragOver = true">

        {{-- Left: Folder sidebar --}}
        <aside class="mw-media-sidebar">
            <div class="mw-media-sidebar-header">
                <span class="mw-media-sidebar-title">Folders</span>
                <button @click="showCreateFolder = !showCreateFolder" class="mw-media-sidebar-add" title="New folder">
                    <x-heroicon-m-plus class="w-4 h-4" />
                </button>
            </div>

            {{-- Create folder form --}}
            <div x-show="showCreateFolder" x-cloak class="mw-media-folder-create">
                <input
                    type="text"
                    wire:model="newFolderName"
                    placeholder="Folder name"
                    class="mw-media-folder-input"
                    wire:keydown.enter="createFolder"
                />
                <div class="mw-media-folder-create-actions">
                    <button wire:click="createFolder" @click="showCreateFolder = false" class="mw-media-folder-create-btn">Create</button>
                    <button @click="showCreateFolder = false" class="mw-media-folder-cancel-btn">Cancel</button>
                </div>
            </div>

            {{-- All Media --}}
            <button
                wire:click="selectFolder(null)"
                class="mw-media-folder-item {{ $selectedFolderId === null ? 'active' : '' }}"
            >
                <x-heroicon-m-photo class="w-4 h-4" />
                <span>All Media</span>
                <span class="mw-media-folder-count">{{ $this->totalMediaCount }}</span>
            </button>

            {{-- Folder tree --}}
            @foreach($this->folders as $folder)
                @include('modules.media_library::filament.admin.partials.folder-item', ['folder' => $folder, 'depth' => 0])
            @endforeach
        </aside>

        {{-- Center: Media grid/list OR Unsplash search --}}
        @if($activeTab === 'unsplash')
            <div class="mw-media-content">
                {{-- Unsplash search bar --}}
                <div class="mw-media-unsplash-search">
                    <form wire:submit="searchUnsplash" style="display:flex;gap:8px;flex:1;">
                        <input
                            type="text"
                            wire:model="unsplashSearch"
                            placeholder="Search free stock photos..."
                            class="mw-media-unsplash-input"
                        />
                        <button type="submit" class="mw-media-unsplash-search-btn">
                            <x-heroicon-m-magnifying-glass class="w-4 h-4" />
                            Search
                        </button>
                    </form>
                </div>

                {{-- Loading indicator --}}
                <div wire:loading wire:target="searchUnsplash,loadMoreUnsplash" class="mw-media-unsplash-loading">
                    <div class="mw-media-upload-spinner"></div>
                    <span>Searching...</span>
                </div>

                {{-- Results grid --}}
                @if(count($unsplashResults) > 0)
                    <div class="mw-media-unsplash-grid" wire:loading.class="opacity-50" wire:target="searchUnsplash">
                        @foreach($unsplashResults as $photo)
                            @php
                                // Proxy API uses flat keys (url_small), direct API uses nested (urls.small)
                                $thumbUrl = $photo['url_small'] ?? $photo['url_thumb'] ?? $photo['urls']['small'] ?? $photo['urls']['thumb'] ?? '';
                                $altText = $photo['alt_description'] ?? $photo['description'] ?? '';
                                $photographer = $photo['author'] ?? $photo['user']['name'] ?? '';
                            @endphp
                            <div class="mw-media-unsplash-item" wire:key="unsplash-{{ $photo['id'] }}">
                                <img
                                    src="{{ $thumbUrl }}"
                                    alt="{{ $altText }}"
                                    loading="lazy"
                                />
                                <div class="mw-media-unsplash-overlay">
                                    <button
                                        wire:click="downloadUnsplashPhoto('{{ $photo['id'] }}')"
                                        class="mw-media-unsplash-download {{ in_array($photo['id'], $unsplashDownloading) ? 'is-downloading' : '' }}"
                                        {{ in_array($photo['id'], $unsplashDownloading) ? 'disabled' : '' }}
                                    >
                                        @if(in_array($photo['id'], $unsplashDownloading))
                                            <div class="mw-media-upload-spinner" style="width:14px;height:14px;border-width:2px;"></div>
                                            Downloading...
                                        @else
                                            <x-heroicon-m-arrow-down-tray class="w-4 h-4" />
                                            Add to library
                                        @endif
                                    </button>
                                    @if($photographer)
                                        <span class="mw-media-unsplash-credit">{{ $photographer }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Load more --}}
                    @if($unsplashPage < $unsplashTotalPages)
                        <div class="mw-media-unsplash-load-more">
                            <button wire:click="loadMoreUnsplash" class="mw-media-unsplash-more-btn" wire:loading.attr="disabled" wire:target="loadMoreUnsplash">
                                Load more photos
                            </button>
                        </div>
                    @endif
                @elseif($unsplashSearch && !$unsplashLoading)
                    <div class="mw-media-unsplash-empty" wire:loading.remove wire:target="searchUnsplash">
                        <x-heroicon-o-camera class="w-16 h-16 text-gray-300" />
                        <p>No photos found for "{{ $unsplashSearch }}"</p>
                    </div>
                @else
                    <div class="mw-media-unsplash-empty" wire:loading.remove wire:target="searchUnsplash">
                        <x-heroicon-o-camera class="w-16 h-16 text-gray-300" />
                        <p>Search for free stock photos from Unsplash</p>
                    </div>
                @endif
            </div>
        @else
        <div class="mw-media-content">
            @php $media = $this->getMediaProperty(); @endphp

            @if($media->isEmpty())
                {{-- AI-123 / TICKET-CN (cycle-136 2026-05-09): novice-UX
                     blank-state — heading + helpful description + a
                     contextual CTA. Branches:
                       (a) FILTERED empty: "no match" + Clear filters CTA.
                       (b) UNFILTERED empty: first-time user — explain
                           what the Media Library is + how to add the
                           first asset (drag-drop OR Upload button). --}}
                <div class="mw-media-empty">
                    <x-heroicon-o-photo class="w-16 h-16 text-gray-300" />
                    @if($search || $typeFilter || $dateFrom || $dateTo || $selectedFolderId)
                        <h3 class="mw-media-empty-heading">No media match those filters</h3>
                        <p class="mw-media-empty-description">
                            Try a different search term, or clear the active filters to see every file.
                        </p>
                        <button wire:click="clearFilters" class="mw-media-empty-reset">
                            Clear filters
                        </button>
                    @else
                        <h3 class="mw-media-empty-heading">Your Media Library is empty</h3>
                        <p class="mw-media-empty-description">
                            Drop images, videos, or documents here — or use the
                            <strong>Upload</strong> button at the top of this page —
                            to start building your library. Uploaded files are then
                            available everywhere across your site (page editors,
                            module pickers, exports).
                        </p>
                    @endif
                </div>
            @else
                {{-- Grid view --}}
                @if($viewMode === 'grid')
                    <div class="mw-media-grid" wire:loading.class="opacity-50">
                        @foreach($media as $item)
                            <div
                                wire:click="selectMedia({{ $item->id }})"
                                class="mw-media-grid-item {{ $selectedMediaId === $item->id ? 'selected' : '' }} {{ in_array($item->id, $bulkSelected) ? 'bulk-selected' : '' }}"
                            >
                                {{-- Bulk checkbox --}}
                                <label
                                    class="mw-media-grid-checkbox"
                                    wire:click.stop="toggleBulkSelect({{ $item->id }})"
                                >
                                    <input type="checkbox" {{ in_array($item->id, $bulkSelected) ? 'checked' : '' }} />
                                </label>

                                {{-- Thumbnail with lazy loading via IntersectionObserver --}}
                                <div class="mw-media-grid-thumb">
                                    @if($item->file_type === 'image' || in_array($item->media_type, ['picture', 'image']))
                                        <div
                                            class="mw-media-grid-lazy"
                                            x-data="{ loaded: false, error: false }"
                                            x-init="
                                                $nextTick(() => {
                                                    const img = $el.querySelector('img');
                                                    if (img) {
                                                        img.src = img.dataset.src;
                                                        img.onload = () => { loaded = true; };
                                                        img.onerror = () => { error = true; loaded = true; };
                                                    }
                                                })
                                            "
                                        >
                                            {{-- Skeleton placeholder --}}
                                            <div class="mw-media-grid-skeleton" x-show="!loaded" x-cloak></div>
                                            {{-- Actual image (src set by IntersectionObserver) --}}
                                            <img
                                                data-src="{{ $this->getThumbnailUrl($item->filename, \Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary::GRID_THUMB_WIDTH) }}"
                                                alt="{{ $item->title }}"
                                                x-show="loaded && !error"
                                                x-cloak
                                                x-transition.opacity.duration.200ms
                                            />
                                            {{-- Error fallback --}}
                                            <div class="mw-media-grid-icon" x-show="error" x-cloak>
                                                <x-heroicon-o-photo class="w-10 h-10" />
                                            </div>
                                        </div>
                                    @else
                                        <div class="mw-media-grid-icon">
                                            @if($item->file_type === 'video')
                                                <x-heroicon-o-film class="w-10 h-10" />
                                            @elseif($item->file_type === 'audio')
                                                <x-heroicon-o-musical-note class="w-10 h-10" />
                                            @elseif($item->file_type === 'document')
                                                <x-heroicon-o-document-text class="w-10 h-10" />
                                            @else
                                                <x-heroicon-o-paper-clip class="w-10 h-10" />
                                            @endif
                                            <span class="mw-media-grid-ext">.{{ pathinfo($item->filename, PATHINFO_EXTENSION) }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- AI-1038 / task-2026-05-22-5beaa2 — tooltip shows full filename + file-size
                                     on hover so a bare numeric basename (from legacy placeholder URLs)
                                     gets context when the user hovers over the thumbnail label. --}}
                                @php
                                    $mediaLabel = $item->title ?: basename($item->filename);
                                    $mediaTooltip = $item->filename;
                                    if (!empty($item->file_size) && $item->file_size > 0) {
                                        $mediaTooltip .= ' (' . round($item->file_size / 1024, 1) . ' KB)';
                                    }
                                @endphp
                                <div class="mw-media-grid-label" title="{{ $mediaTooltip }}">
                                    {{ Str::limit($mediaLabel, 24) }}
                                </div>

                                {{-- 3-dot menu --}}
                                <div class="mw-media-item-menu" x-data="{ open: false }" wire:click.stop>
                                    <button @click.stop="open = !open" class="mw-media-item-menu-btn" type="button">
                                        <x-heroicon-m-ellipsis-vertical class="w-4 h-4" />
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak class="mw-media-item-menu-dropdown">
                                        <button type="button" @click="navigator.clipboard.writeText('{{ $item->filename }}'); open = false; $dispatch('notify', {type:'success', message:'URL copied'})">
                                            <x-heroicon-m-clipboard-document class="w-4 h-4" /> Copy URL
                                        </button>
                                        <a href="{{ $item->filename }}" target="_blank" rel="noopener noreferrer" @click="open = false">
                                            <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" /> Open in new tab
                                        </a>
                                        <button type="button" wire:click.stop="selectMedia({{ $item->id }})" @click="open = false">
                                            <x-heroicon-m-pencil class="w-4 h-4" /> Edit
                                        </button>
                                        <button type="button" wire:click.stop="deleteMedia({{ $item->id }})" wire:confirm="Delete this media item?" @click="open = false" class="text-danger">
                                            <x-heroicon-m-trash class="w-4 h-4" /> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- List view --}}
                    <div class="mw-media-list" wire:loading.class="opacity-50">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:32px;"></th>
                                    <th style="width:50px;">Preview</th>
                                    <th>Title</th>
                                    <th>Folder</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Date</th>
                                    <th style="width:40px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($media as $item)
                                    <tr
                                        wire:click="selectMedia({{ $item->id }})"
                                        class="{{ $selectedMediaId === $item->id ? 'selected' : '' }} {{ in_array($item->id, $bulkSelected) ? 'bulk-selected' : '' }}"
                                    >
                                        <td>
                                            <label wire:click.stop="toggleBulkSelect({{ $item->id }})">
                                                <input type="checkbox" {{ in_array($item->id, $bulkSelected) ? 'checked' : '' }} />
                                            </label>
                                        </td>
                                        <td>
                                            @if($item->file_type === 'image' || in_array($item->media_type, ['picture', 'image']))
                                                <img src="{{ $this->getThumbnailUrl($item->filename, 80) }}" alt="" class="mw-media-list-thumb" loading="lazy" />
                                            @else
                                                <span class="mw-media-list-type-icon">
                                                    @if($item->file_type === 'video')
                                                        <x-heroicon-m-film class="w-5 h-5" />
                                                    @elseif($item->file_type === 'audio')
                                                        <x-heroicon-m-musical-note class="w-5 h-5" />
                                                    @else
                                                        <x-heroicon-m-document class="w-5 h-5" />
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $item->title ?: basename($item->filename) }}</td>
                                        <td>{{ $item->folder?->name ?? '-' }}</td>
                                        <td><span class="mw-media-type-badge">{{ $item->media_type }}</span></td>
                                        <td>{{ $this->formatFileSize($item->file_size) }}</td>
                                        <td>{{ $item->created_at?->format('M d, Y') }}</td>
                                        <td wire:click.stop>
                                            <div class="mw-media-item-menu" x-data="{ open: false }">
                                                <button @click.stop="open = !open" class="mw-media-item-menu-btn" type="button">
                                                    <x-heroicon-m-ellipsis-vertical class="w-4 h-4" />
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-cloak class="mw-media-item-menu-dropdown">
                                                    <button type="button" @click="navigator.clipboard.writeText('{{ $item->filename }}'); open = false; $dispatch('notify', {type:'success', message:'URL copied'})">
                                                        <x-heroicon-m-clipboard-document class="w-4 h-4" /> Copy URL
                                                    </button>
                                                    <a href="{{ $item->filename }}" target="_blank" rel="noopener noreferrer" @click="open = false">
                                                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" /> Open in new tab
                                                    </a>
                                                    <button type="button" wire:click.stop="selectMedia({{ $item->id }})" @click="open = false">
                                                        <x-heroicon-m-pencil class="w-4 h-4" /> Edit
                                                    </button>
                                                    <button type="button" wire:click.stop="deleteMedia({{ $item->id }})" wire:confirm="Delete this media item?" @click="open = false" class="text-danger">
                                                        <x-heroicon-m-trash class="w-4 h-4" /> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Pagination --}}
                <div class="mw-media-pagination">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
        @endif

        {{-- Right: Detail panel --}}
        @if($selectedMediaData)
            <aside class="mw-media-detail">
                <div class="mw-media-detail-header">
                    <span>Details</span>
                    <button wire:click="closeDetailPanel" class="mw-media-detail-close" title="Close">
                        <x-heroicon-m-x-mark class="w-4 h-4" />
                    </button>
                </div>

                {{-- Preview --}}
                <div class="mw-media-detail-preview">
                    @if($selectedMediaData['file_type'] === 'image' || in_array($selectedMediaData['media_type'], ['picture', 'image']))
                        <img src="{{ $selectedMediaData['url'] }}" alt="{{ $selectedMediaData['title'] }}" />
                    @else
                        <div class="mw-media-detail-icon">
                            @if($selectedMediaData['file_type'] === 'video')
                                <x-heroicon-o-film class="w-16 h-16" />
                            @elseif($selectedMediaData['file_type'] === 'audio')
                                <x-heroicon-o-musical-note class="w-16 h-16" />
                            @else
                                <x-heroicon-o-document-text class="w-16 h-16" />
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Editable fields --}}
                <div class="mw-media-detail-fields">
                    <label>Title</label>
                    <input type="text" wire:model="editTitle" class="mw-media-detail-input" />

                    <label>Description</label>
                    <textarea wire:model="editDescription" rows="2" class="mw-media-detail-input"></textarea>

                    <label>Alt text</label>
                    <input type="text" wire:model="editAltText" class="mw-media-detail-input" placeholder="Describe the image" />

                    <button wire:click="saveMediaDetails" class="mw-media-detail-save">
                        Save changes
                    </button>
                </div>

                {{-- Copy URL --}}
                <div class="mw-media-detail-url" x-data="{ copied: false }">
                    <label>URL</label>
                    <div class="mw-media-detail-url-row">
                        <input type="text" value="{{ $selectedMediaData['url'] }}" readonly class="mw-media-detail-input" style="font-size: 0.75rem;" />
                        <button
                            class="mw-media-detail-copy-btn"
                            title="Copy URL"
                            @click="
                                navigator.clipboard.writeText('{{ e($selectedMediaData['url']) }}');
                                copied = true;
                                setTimeout(() => copied = false, 2000);
                            "
                        >
                            <template x-if="!copied"><x-heroicon-m-clipboard class="w-4 h-4" /></template>
                            <template x-if="copied"><x-heroicon-m-check class="w-4 h-4" style="color: var(--mw-success, #2fb344);" /></template>
                        </button>
                    </div>
                </div>

                {{-- Info --}}
                <div class="mw-media-detail-info">
                    <div class="mw-media-detail-info-row">
                        <span>File</span>
                        <span class="mw-media-detail-info-value truncate" title="{{ $selectedMediaData['filename'] }}">{{ basename($selectedMediaData['filename']) }}</span>
                    </div>
                    <div class="mw-media-detail-info-row">
                        <span>Type</span>
                        <span class="mw-media-detail-info-value">{{ $selectedMediaData['media_type'] }}</span>
                    </div>
                    <div class="mw-media-detail-info-row">
                        <span>Size</span>
                        <span class="mw-media-detail-info-value">{{ $this->formatFileSize($selectedMediaData['file_size']) }}</span>
                    </div>
                    @if($selectedMediaData['width'] && $selectedMediaData['height'])
                        <div class="mw-media-detail-info-row">
                            <span>Dimensions</span>
                            <span class="mw-media-detail-info-value">{{ $selectedMediaData['width'] }} × {{ $selectedMediaData['height'] }} px</span>
                        </div>
                    @endif
                    @if($selectedMediaData['folder_name'])
                        <div class="mw-media-detail-info-row">
                            <span>Folder</span>
                            <span class="mw-media-detail-info-value">{{ $selectedMediaData['folder_name'] }}</span>
                        </div>
                    @endif
                    <div class="mw-media-detail-info-row">
                        <span>Uploaded</span>
                        <span class="mw-media-detail-info-value">{{ $selectedMediaData['created_at'] }}</span>
                    </div>
                    @if($this->isCdnConfigured() && $selectedMediaData['is_synced_to_cdn'])
                        <div class="mw-media-detail-info-row">
                            <span>CDN</span>
                            <span class="mw-media-detail-info-value" style="color: var(--mw-success, #2fb344);">Synced</span>
                        </div>
                    @endif
                </div>

                {{-- Used in --}}
                @if(!empty($selectedMediaData['used_in']))
                    <div class="mw-media-detail-used-in">
                        <label>Used in</label>
                        @foreach($selectedMediaData['used_in'] as $usage)
                            <div class="mw-media-detail-usage-item">
                                <span>{{ $usage['type'] }}:</span> {{ $usage['title'] }}
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Delete button --}}
                <div class="mw-media-detail-actions">
                    <button
                        wire:click="deleteMedia({{ $selectedMediaData['id'] }})"
                        wire:confirm="Delete this media item permanently?"
                        class="mw-media-detail-delete"
                    >
                        <x-heroicon-m-trash class="w-4 h-4" /> Delete
                    </button>
                </div>
            </aside>
        @endif
    </div>
</div>
{{-- task-2026-05-22-faf839 / AI-761 — postMessage row-count bridge.
     When this page is loaded inside an iframe by the image picker
     (filepicker.js _mountLib), it posts the total media count so the
     parent can show a skeleton during load and an empty state when the
     library is truly empty. The count is server-side rendered to avoid
     waiting for Livewire. Fires immediately on page load. --}}
@php
    // $media is defined inside the library-tab block above; on other tabs
    // (e.g. Unsplash) that block is skipped, so resolve it here too.
    $media = $media ?? $this->getMediaProperty();
    $mwMediaLibTotal = $media instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? (int) $media->total() : (int) $media->count();
@endphp
<script>
(function () {
    var count = {{ $mwMediaLibTotal }};
    if (window.parent && window.parent !== window) {
        window.parent.postMessage({ type: 'mw-filemanager:row-count', count: count }, '*');
    }
})();
</script>

{{-- Selection bridge — relays the `mw-media-file-selected` Livewire event (dispatched by
     MediaLibrary::selectMedia) to the parent image picker (filepicker.js) via postMessage,
     so clicking a thumbnail enables the picker's "Insert image" button. Restores the
     select-file wiring the legacy FileManager provided before the Filament migration.
     No-op when the page isn't embedded in an iframe. --}}
@script
<script>
    $wire.on('mw-media-file-selected', (event) => {
        var url = event ? (Array.isArray(event) ? (event[0] || {}).url : event.url) : null;
        if (window.parent && window.parent !== window) {
            window.parent.postMessage({ type: 'mw-filemanager:select-file', url: url || '' }, '*');
        }
    });
</script>
@endscript
