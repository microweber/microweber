@php
    $folderId = $folder['id'];
    $count = $this->folderCounts[$folderId] ?? 0;
    $hasChildren = !empty($folder['children']);
    $isActive = $selectedFolderId === $folderId;
    $isRenaming = $renameFolderId === $folderId;
    $paddingLeft = ($depth * 16) + 8;
@endphp

<div class="mw-media-folder-group" x-data="{ expanded: true }">
    @if($isRenaming)
        <div class="mw-media-folder-rename" style="padding-left: {{ $paddingLeft }}px;">
            <input
                type="text"
                wire:model="renameFolderName"
                class="mw-media-folder-input"
                wire:keydown.enter="renameFolder"
                wire:keydown.escape="cancelRename"
            />
            <button wire:click="renameFolder" class="mw-media-folder-create-btn">Save</button>
            <button wire:click="cancelRename" class="mw-media-folder-cancel-btn">Cancel</button>
        </div>
    @else
        <div
            class="mw-media-folder-item {{ $isActive ? 'active' : '' }}"
            style="padding-left: {{ $paddingLeft }}px;"
        >
            @if($hasChildren)
                <button @click.stop="expanded = !expanded" class="mw-media-folder-expand">
                    <x-heroicon-m-chevron-right class="w-3 h-3" x-bind:class="expanded ? 'rotate-90' : ''" style="transition: transform 150ms;" />
                </button>
            @else
                <span class="mw-media-folder-expand-placeholder"></span>
            @endif

            <button wire:click="selectFolder({{ $folderId }})" class="mw-media-folder-name">
                <x-heroicon-m-folder class="w-4 h-4" />
                <span>{{ $folder['name'] }}</span>
            </button>

            <span class="mw-media-folder-count">{{ $count }}</span>

            <div class="mw-media-folder-actions" x-data="{ open: false }">
                <button @click.stop="open = !open" class="mw-media-folder-menu-btn">
                    <x-heroicon-m-ellipsis-vertical class="w-4 h-4" />
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="mw-media-folder-menu">
                    <button wire:click="startRenameFolder({{ $folderId }})" @click="open = false">Rename</button>
                    <button wire:click="deleteFolder({{ $folderId }})" wire:confirm="Delete this folder? It must be empty." @click="open = false" class="text-danger">Delete</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Children --}}
    @if($hasChildren)
        <div x-show="expanded" x-collapse>
            @foreach($folder['children'] as $child)
                @include('modules.media_library::filament.admin.partials.folder-item', ['folder' => $child, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>
