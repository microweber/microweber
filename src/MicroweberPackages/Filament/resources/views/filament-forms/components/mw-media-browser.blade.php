@php
    $mediaItems = $getMediaItemsArray();
    $statePath = $getStatePath();
    $componentKey = $getKey();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    {{-- task-2026-09-17-mediabrowser-redesign — two-pane Pictures editor.
         LEFT: thumbnail grid (server-rendered @foreach, x-sortable reorder) +
         the drop target + top action row. RIGHT: per-image detail panel
         (Caption / Alt text + AI / Link + Page picker / Thumbnail crop / Remove).
         All server round-trips go through this.$wire.callSchemaComponentMethod
         (the only call that reaches the server inside the Live Edit modal) using
         the ABSOLUTE dotted component key ($getKey() = "form.mediaIds"); a bare
         state path silently no-ops. Drag-drop upload + reorder persistence are
         bound in mwMediaManagerComponent.init() (theme bundle mw-media-browser.js). --}}

    @php
        $mwMediaBrowserPickerHandler = "() => { mw.filePickerDialog({pickerOptions: {multiple: true}}, (url) => { if (!Array.isArray(url)) { url = [url]; } \$wire.callSchemaComponentMethod('" . $componentKey . "', 'addMediaItemMultiple', { data: { urls: url } }); }); }";
    @endphp

    <div
        class="mw-mb"
        data-mw-media-dropzone="1"
        data-component-key="{{ $componentKey }}"
        data-state-path="{{ $statePath }}"
        id="mw-image-dropzone"
        x-data="mwMediaManagerComponent({
            mediaIds: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            showBulkDeleteButton: true,
            selectedImages: [],
        })"
    >

        {{-- ── Top action row ─────────────────────────────────────────────── --}}
        <div class="mw-mb-toolbar">
            <label class="mw-mb-selectall">
                <input type="checkbox"
                       class="mw-mb-selectall-cb"
                       @change="$event.target.checked ? selectAllMedia() : deselectAllMedia()"
                       :checked="mediaIds.length && selectedImages.length === mediaIds.length">
                <span>Select all</span>
            </label>

            <div class="mw-mb-actions">
                <button type="button" class="mw-mb-btn" x-on:click="{{ $mwMediaBrowserPickerHandler }}">
                    <x-heroicon-m-photo class="mw-mb-btn-ico" aria-hidden="true" />
                    <span>Media library</span>
                </button>
                <button type="button" class="mw-mb-btn" x-on:click="{{ $mwMediaBrowserPickerHandler }}">
                    <x-heroicon-m-arrow-up-tray class="mw-mb-btn-ico" aria-hidden="true" />
                    <span>Upload</span>
                </button>
                <button type="button" class="mw-mb-btn mw-mb-btn--ghost" @click="toggleGenerate()">
                    <x-heroicon-m-sparkles class="mw-mb-btn-ico" aria-hidden="true" />
                    <span>Generate</span>
                </button>
            </div>
        </div>

        {{-- +Generate prompt row (revealed by the Generate button) --}}
        <div class="mw-mb-generate" x-show="showGenerate" x-cloak x-transition>
            <input type="text" class="mw-mb-input mw-mb-generate-input"
                   placeholder="Describe the image to generate…"
                   x-model="generatePrompt"
                   @keydown.enter.prevent="runGenerate()">
            <button type="button" class="mw-mb-btn mw-mb-btn--primary" @click="runGenerate()" :disabled="generating">
                <span x-show="!generating">Generate</span>
                <span x-show="generating" x-cloak>Generating…</span>
            </button>
            <button type="button" class="mw-mb-btn" @click="toggleGenerate()">Cancel</button>
        </div>
        <p class="mw-mb-generate-error" x-show="generateError" x-cloak x-text="generateError"></p>

        {{-- ── Body: grid (left) + detail panel (right) ───────────────────── --}}
        <div class="mw-mb-body">

            {{-- LEFT --}}
            <div class="mw-mb-left">
                <div class="admin-thumbs-holder mw-mb-grid" x-sortable>
                    @foreach($mediaItems as $item)
                        @php
                            $mwItemPayload = [
                                'id' => $item->id,
                                'filename' => $item->filename,
                                'caption' => $item->mw_caption,
                                'alt' => $item->mw_alt,
                                'link' => $item->mw_link,
                                'crop' => $item->mw_crop ?: 'center',
                                'cropPosition' => $item->mw_crop_position,
                                'w' => $item->mw_w,
                                'h' => $item->mw_h,
                                'size' => $item->mw_size,
                            ];
                        @endphp
                        <div
                            x-sortable-handle
                            x-sortable-item="{{ $item->id }}"
                            x-data-id="{{ $item->id }}"
                            data-mb-item="{{ json_encode($mwItemPayload) }}"
                            class="admin-thumb-item background-image-holder ui-sortable-handle mw-mb-tile"
                            :class="{ 'is-selected': selectedImages.includes('{{ $item->id }}'), 'is-active': String(detailId) === '{{ $item->id }}' }"
                            @click="selectImage($event.currentTarget)"
                        >
                            <span class="mw-post-media-img" data-id="{{ $item->id }}">
                                <img src="{{ $item->filename }}"
                                     alt=""
                                     loading="lazy"
                                     decoding="async"
                                     draggable="false"
                                     class="d-block w-100 h-100"
                                     style="object-fit: cover; -webkit-user-drag: none; user-select: none;">
                            </span>

                            {{-- bulk-select checkbox, top-left --}}
                            <label class="mw-mb-check" @click.stop>
                                <input type="checkbox" x-model="selectedImages" value="{{ $item->id }}">
                                <span class="mw-mb-check-box" aria-hidden="true"></span>
                            </label>

                            {{-- quick actions, top-right --}}
                            <div class="mw-mb-tile-actions" @click.stop>
                                @if($item->mw_link)
                                    <span class="mw-mb-chip" title="This image links to {{ $item->mw_link }}">
                                        @svg('mw-image-edit', 'mw-mb-chip-ico')
                                    </span>
                                @endif
                                <button type="button" class="mw-mb-chip mw-mb-chip--danger"
                                        title="Remove image" @click.stop="deleteMediaById('{{ $item->id }}')">
                                    @svg('mw-media-item-delete-small', 'mw-mb-chip-ico')
                                </button>
                            </div>

                            {{-- filename ribbon on the active tile --}}
                            <div class="mw-mb-tile-name" x-show="String(detailId) === '{{ $item->id }}'" x-cloak>
                                {{ basename(parse_url($item->filename, PHP_URL_PATH) ?: $item->filename) }}
                            </div>
                        </div>
                    @endforeach

                    {{-- Add-or-drop tile --}}
                    <button type="button" class="mw-mb-addtile" x-on:click="{{ $mwMediaBrowserPickerHandler }}">
                        <x-heroicon-o-plus class="mw-mb-addtile-ico" aria-hidden="true" />
                        <span>Add or drop</span>
                    </button>
                </div>

                <div class="mw-mb-underbar">
                    <p class="mw-mb-hint">Drag images to reorder</p>
                    <div class="mw-mb-bulk" x-show="selectedImages.length" x-cloak>
                        <button type="button" class="mw-mb-linklike" @click="deselectAllMedia()">
                            Clear (<span x-text="selectedImages.length"></span>)
                        </button>
                        <button type="button" class="mw-mb-linklike mw-mb-linklike--danger" @click="bulkDeleteSelectedMedia()">
                            Delete selected
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT: detail panel — mounted only once an image is selected, so
                 the grid uses the full modal width until then. --}}
            <div class="mw-mb-detail" x-show="detailId" x-cloak>
                <div class="mw-mb-detail-body">
                    <div class="mw-mb-detail-head">
                        <span class="mw-mb-detail-thumb"><img :src="detail.filename" alt=""></span>
                        <div class="mw-mb-detail-headmeta">
                            <div class="mw-mb-detail-name" x-text="detailName()"></div>
                            <div class="mw-mb-detail-dims" x-show="detail.w && detail.h" x-cloak>
                                <span x-text="detail.w"></span> × <span x-text="detail.h"></span><span x-show="detail.size" x-cloak> · <span x-text="humanSize(detail.size)"></span></span>
                            </div>
                        </div>
                    </div>

                    <label class="mw-mb-field">
                        <span class="mw-mb-label">Caption</span>
                        <input type="text" class="mw-mb-input" x-model="detail.caption"
                               @change="saveMeta('caption')" placeholder="Add a caption">
                    </label>

                    <label class="mw-mb-field">
                        <span class="mw-mb-label-row">
                            <span class="mw-mb-label">Alt text</span>
                            <button type="button" class="mw-mb-writeforme" @click="writeAltForMe()" :disabled="writingAlt">
                                <x-heroicon-m-sparkles class="mw-mb-writeforme-ico" aria-hidden="true" />
                                <span x-show="!writingAlt">Write for me</span>
                                <span x-show="writingAlt" x-cloak>Writing…</span>
                            </button>
                        </span>
                        <input type="text" class="mw-mb-input" x-model="detail.alt"
                               @change="saveMeta('altText')" placeholder="Describe the image">
                    </label>

                    <div class="mw-mb-field">
                        <span class="mw-mb-label">Link</span>
                        <div class="mw-mb-link-row">
                            <input type="text" class="mw-mb-input" x-model="detail.link"
                                   @change="saveMeta('link')" placeholder="https://">
                            <div class="mw-mb-pagepick">
                                <button type="button" class="mw-mb-btn mw-mb-btn--sm" @click="togglePagePicker()">Page</button>
                                <div class="mw-mb-pagepick-menu" x-show="showPagePicker" x-cloak @click.outside="showPagePicker = false">
                                    <input type="text" class="mw-mb-input mw-mb-input--sm" placeholder="Filter pages…" x-model="pageFilter">
                                    <div class="mw-mb-pagepick-list">
                                        <template x-for="p in filteredPages()" :key="p.url">
                                            <button type="button" class="mw-mb-pagepick-item" @click="pickPage(p)" x-text="p.title"></button>
                                        </template>
                                        <div class="mw-mb-pagepick-empty" x-show="!pages.length" x-cloak>Loading pages…</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mw-mb-field">
                        <span class="mw-mb-label">Thumbnail crop</span>
                        <div class="mw-mb-seg" role="tablist" aria-label="Thumbnail crop">
                            <button type="button" class="mw-mb-seg-cell" role="tab"
                                    :class="{ 'is-on': detail.crop === 'center' }"
                                    :aria-selected="detail.crop === 'center'"
                                    @click="setCrop('center')">Center</button>
                            <button type="button" class="mw-mb-seg-cell" role="tab"
                                    :class="{ 'is-on': detail.crop === 'top' }"
                                    :aria-selected="detail.crop === 'top'"
                                    @click="setCrop('top')">Top</button>
                            <button type="button" class="mw-mb-seg-cell" role="tab"
                                    :class="{ 'is-on': detail.crop === 'custom' }"
                                    :aria-selected="detail.crop === 'custom'"
                                    @click="setCrop('custom')">Custom</button>
                        </div>
                        <div class="mw-mb-crop-custom" x-show="detail.crop === 'custom'" x-cloak>
                            <label class="mw-mb-crop-range">
                                <span>Horizontal</span>
                                <input type="range" min="0" max="100" x-model.number="cropX" @input="onCropRange()" @change="saveMeta('crop')">
                            </label>
                            <label class="mw-mb-crop-range">
                                <span>Vertical</span>
                                <input type="range" min="0" max="100" x-model.number="cropY" @input="onCropRange()" @change="saveMeta('crop')">
                            </label>
                        </div>
                    </div>

                    <div class="mw-mb-detail-foot">
                        <button type="button" class="mw-mb-btn mw-mb-btn--danger-outline" @click="removeDetail()">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
