<div
    x-data="{
        showUploadZone: false,
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
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search media..."
                    class="mw-media-search-input"
                />
            </div>

            <select wire:model.live="typeFilter" class="mw-media-filter-select">
                <option value="">All types</option>
                <option value="picture">Images</option>
                <option value="video">Videos</option>
                <option value="audio">Audio</option>
                <option value="file">Documents</option>
            </select>
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

    {{-- Upload zone --}}
    <div
        x-show="showUploadZone || dragOver"
        x-cloak
        class="mw-media-upload-zone"
        :class="{ 'drag-over': dragOver }"
        x-on:dragover.prevent="dragOver = true"
        x-on:dragleave.prevent="dragOver = false"
        x-on:drop.prevent="
            dragOver = false;
            const files = $event.dataTransfer.files;
            if (files.length) {
                $wire.uploadMultiple('uploads', files);
            }
        "
    >
        <div class="mw-media-upload-zone-inner">
            <x-heroicon-o-cloud-arrow-up class="w-10 h-10 text-gray-400" />
            <p>Drag files here or click to browse</p>
            <label class="mw-media-upload-browse-btn">
                Browse files
                <input
                    type="file"
                    multiple
                    wire:model="uploads"
                    class="sr-only"
                    accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt"
                />
            </label>
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

        {{-- Center: Media grid/list --}}
        <div class="mw-media-content">
            @php $media = $this->getMediaProperty(); @endphp

            @if($media->isEmpty())
                <div class="mw-media-empty">
                    <x-heroicon-o-photo class="w-16 h-16 text-gray-300" />
                    <p>No media found</p>
                    @if($search || $typeFilter || $selectedFolderId)
                        <button wire:click="$set('search', ''); $set('typeFilter', ''); $set('selectedFolderId', null);" class="mw-media-empty-reset">
                            Clear filters
                        </button>
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
                                            x-intersect.once="
                                                const img = $el.querySelector('img');
                                                if (img) {
                                                    img.src = img.dataset.src;
                                                    img.onload = () => { loaded = true; };
                                                    img.onerror = () => { error = true; loaded = true; };
                                                }
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

                                <div class="mw-media-grid-label">
                                    {{ Str::limit($item->title ?: basename($item->filename), 24) }}
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
                    @if($selectedMediaData['is_synced_to_cdn'])
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
