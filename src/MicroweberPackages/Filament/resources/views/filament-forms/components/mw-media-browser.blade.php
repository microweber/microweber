@php
    $mediaItems = $getMediaItemsArray();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>


    <div>
        <style>

            .mw-post-media-img--header {
                @apply absolute top-[5px] left-[5px] z-10
            }

        </style>

        @php
            $suffix = '';

            $suffix = $this->getId();

        @endphp

        <div>

            <script>
                document.addEventListener('livewire:init', function () {
                    mw.dropZone('#mw-image-dropzone').on('fileUploaded', res => {
                        var data = {}
                        data.url = res.src

                        $wire.callSchemaComponentMethod('{{ $statePath }}', 'addMediaItem', {data: data})

                    })
                });
            </script>

            {{--
                NOVICE #9 (task-2026-05-13-899d57) — affordance rewrite.

                The previous single-button copy read "Select media file or
                **Upload**" — neither word was clickable in a way the user
                could distinguish, and "media file" is jargon a novice site
                owner doesn't speak. Worse, both words triggered the same
                file-picker dialog, so the user who clicked "Upload"
                expecting an upload flow got the same browser dialog as
                someone clicking "Select" — and bounced because the dialog
                "looked like the OS dialog, not the Microweber media
                library" (audit verbatim).

                Replaced with TWO clearly-distinct buttons inside the
                drop zone:

                  1. PRIMARY:  "Choose from Media Library" (pill button)
                  2. SECONDARY: "Upload from your device"  (underline link)

                Both still dispatch `mw.filePickerDialog` because that's
                the canonical Microweber file-browser entry — the dialog
                itself contains both library + upload tabs. The visual
                split tells the user "you have options" up front, which
                was the missing affordance.

                Copy: "media file" → "image". Added a named tip line for
                the drop zone so the drag-and-drop affordance is
                explicit, not implicit.

                Deferred to a separate ticket: a recent-uploads thumbnail
                strip directly inside this picker. That would need a new
                Livewire endpoint exposing the latest N media rows
                site-wide (not just the ones already attached to THIS
                module) — out of scope for the bounded slice here.
            --}}
            @php
                $mwMediaBrowserPickerHandler = "() => { mw.filePickerDialog({pickerOptions: {multiple: true}}, (url) => { if (!Array.isArray(url)) { url = [url]; } \$wire.callSchemaComponentMethod('" . $statePath . "', 'addMediaItemMultiple', { data: { urls: url } }); }); }";
            @endphp
            <div
                id="mw-image-dropzone"
                class="mw-media-browser-dropzone w-full flex flex-col p-4 items-center justify-center border-2 border-dashed border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
            >

                <x-heroicon-o-photo class="w-8 h-8 text-gray-400 mb-3" />

                {{-- task-2026-05-30-mediabrowser AI-1137 — primary + secondary buttons carry inline min-height:44px !important for WCAG 2.5.5 touch-target floor. Tailwind JIT min-h-[44px] arbitrary class is NOT picked up post-build on this Blade (same constraint as task-2026-05-30-tplcust). --}}
                <div class="flex flex-col items-center gap-2 mb-2">
                    <button
                        type="button"
                        class="mw-media-browser-primary-btn inline-flex items-center justify-center px-4 py-2 rounded-full bg-indigo-600 text-white font-semibold text-sm shadow hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                        style="min-height:44px !important;"
                        title="Browse images already in your Media Library"
                        x-on:click="{{ $mwMediaBrowserPickerHandler }}">
                        <x-heroicon-m-photo class="w-4 h-4 me-2 -ms-1" aria-hidden="true" />
                        Choose from Media Library
                    </button>

                    <button
                        type="button"
                        class="mw-media-browser-secondary-btn inline-flex items-center justify-center text-sm font-medium text-indigo-600 underline underline-offset-4 hover:text-indigo-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 rounded"
                        style="min-height:44px !important;"
                        title="Pick an image from your phone or computer to add to the Media Library"
                        x-on:click="{{ $mwMediaBrowserPickerHandler }}">
                        Upload from your device
                    </button>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                    Tip: you can also drag images directly into this box.
                </p>

                <style>
                    .admin-thumbs-holder-images-wrap:not(:has(.background-image-holder)) .admin-thumbs-holder-bulk-actions {
                     display: none;

                     }


                </style>



                <hr class="h-px mb-8 mt-4 bg-gray-200 border-0 dark:bg-gray-700 w-full">


                <div class="admin-thumbs-holder-images-wrap w-full mb-3" >


                        <div


                            x-data="mwMediaManagerComponent({
                                mediaIds: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
                                showBulkDeleteButton: true,
                                selectedImages: [],
                                itemsSortedIds: [],
                                itemsSortedIdsArray: [],

                            })"



                            x-on:end="


                                itemsSortedIds = $event.target.querySelectorAll('[x-sortable-item]');

                                itemsSortedIdsArray = [];
                                for (var i = 0; i < itemsSortedIds.length; i++) {
                                    itemsSortedIdsArray.push(itemsSortedIds[i].getAttribute('x-sortable-item'));
                                }
                                $wire.callSchemaComponentMethod('{{ $statePath }}', 'mediaItemsSort', {
                                    itemsSortedIds: itemsSortedIdsArray
                                })
                                mediaIds = itemsSortedIdsArray
                    "
                            class="admin-thumbs-holder-wrapper"
                        >
















                @if($mediaItems and !empty($mediaItems))






                                <div class="mw-media-browser-delete-btn-wrapper">


                                    <div x-show="showBulkDeleteButton"

                                         class="admin-thumbs-holder-bulk-actions">

                                        <x-filament::button size="xs" icon="heroicon-m-check-circle" color="success" @click="selectAllMedia()">
                                            Select All
                                        </x-filament::button>

                                        <x-filament::button size="xs" icon="heroicon-m-x-circle" color="warning" @click="deselectAllMedia()">
                                            Deselect All
                                        </x-filament::button>

                                        <x-filament::button size="xs" icon="heroicon-m-trash" color="danger" @click="bulkDeleteSelectedMedia()">
                                            Delete selected
                                        </x-filament::button>

                                    </div>



                                </div>








                                <div class="admin-thumbs-holder" x-sortable>
                                @foreach($mediaItems as $item)

                                    <div
                                        x-sortable-handle
                                        x-sortable-item="{{ $item->id }}"
                                        x-data-id="{{ $item->id }}"
                                        class="background-image-holder admin-thumb-item ui-sortable-handle"

                                    >




                            {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB cycle-52 sweep:
                                 admin media-browser thumbnail. The existing .mw-post-media-img
                                 CSS (general-styles.css:707) already declares absolute+cover
                                 via Tailwind utilities, so a real <img> with object-fit:cover
                                 inside a <span> wrapper preserves the visual exactly. --}}
                            <span class="mw-post-media-img" data-id="{{ $item->id }}">
                                <img src="{{ $item->filename }}"
                                     alt=""
                                     loading="lazy"
                                     decoding="async"
                                     class="d-block w-100 h-100"
                                     style="object-fit: cover;">
                            </span>



                                  <div class="flex gap-2 items-center mw-post-media-img--header bg-black p-1 cursor-pointer z-10 items-center">
                                      <a @click="editImageFilename('{{ $item->id }}','{{ $item->filename }}')"
                                         class="image-settings settings-img" x-data="{}" x-tooltip="{
                                                                              content: 'Edit Image',
                                                                              theme: $store.theme,
                                                                          }">
                                          @svg('mw-image-edit')
                                      </a>

                                      <a @click="editMediaOptionsById('{{ $item->id }}')"
                                         class="image-settings settings-img" x-data="{}" x-tooltip="{
                                                                              content: 'Image Settings',
                                                                              theme: $store.theme,
                                                                          }">
                                          @svg('mw-media-item-edit-small')
                                      </a>

                                      <a @click="deleteMediaById('{{ $item->id }}')"
                                         class="image-settings settings-img" x-data="{}" x-tooltip="{
                                                                              content: 'Delete Image',
                                                                              theme: $store.theme,
                                                                          }">
                                          @svg('mw-media-item-delete-small')
                                      </a>

                                      <label class="form-check form-check-inline">
                                          <input type="checkbox" x-model="selectedImages" value="{{ $item->id }}"
                                                 class="form-check-input">
                                      </label>
                                  </div>

                                    </div>

                                @endforeach


                            </div>
                            @endif

                        </div>


                </div>
            </div>


        </div>
    </div>



</x-dynamic-component>
