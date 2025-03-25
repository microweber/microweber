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

                        $wire.dispatchFormEvent('mwMediaBrowser::addMediaItem','{{ $statePath }}', {data: data})

                    })
                });
            </script>

            <div
                id="mw-image-dropzone"
                class="w-full flex flex-col p-3 items-center justify-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">

                <button
                    class="w-full py-6 full flex flex-col items-center justify-center"
                    type="button" x-on:click="()=> {

                    mw.filePickerDialog({pickerOptions: {multiple: true}}, (url) => {
                            if(!Array.isArray(url)) {
                                url = [url];
                            }
                            $wire.dispatchFormEvent('mwMediaBrowser::addMediaItemMultiple','{{ $statePath }}', {
                                    data: { urls: url }
                                })
                    });

                }">

                <span>
                    Select media file or <b class="text-yellow-500 font-bold">Upload</b>
                </span>
                </button>

                <hr class="h-px mb-8 mt-4 bg-gray-200 border-0 dark:bg-gray-700 w-full">


                <div class="w-full mb-3">


                        <div

                            ax-load="visible"

                            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('mw-media-browser', 'mw-filament/forms') }}"

                            x-data="mwMediaManagerComponent({
                                mediaIds: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
                            })"

                            x-ignore

                            x-on:end="


                                itemsSortedIds = $event.target.querySelectorAll('[x-sortable-item]');

                                itemsSortedIdsArray = [];
                                for (var i = 0; i < itemsSortedIds.length; i++) {
                                    itemsSortedIdsArray.push(itemsSortedIds[i].getAttribute('x-sortable-item'));
                                }
                                $wire.dispatchFormEvent('mwMediaBrowser::mediaItemsSort','{{ $statePath }}', {
                                    itemsSortedIds: itemsSortedIdsArray
                                })
                                mediaIds = itemsSortedIdsArray
                    "
                            class="admin-thumbs-holder-wrapper"
                        >

                @if($mediaItems and !empty($mediaItems))



                                <div class="mw-media-browser-delete-btn-wrapper" x-show="mediaIds && mediaIds.length > 0">
                                    <div x-show="selectedImages && selectedImages.length > 0"
                                         class="admin-thumbs-holder-bulk-actions">

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




                            <span class="mw-post-media-img" style="background-image: url('{{ $item->filename }}');"
                                  data-id="{{ $item->id }}">


                            </span>
                                        <div class="flex gap-2 items-center mw-post-media-img--header bg-black p-1 cursor-pointer z-10 items-center">


                                            <a @click="editImageFilename('{{ $item->id }}','{{ $item->filename }}')"
                                               class="image-settings settings-img  " x-data="{}" x-tooltip="{
                                                                                            content: 'Edit Image',
                                                                                            theme: $store.theme,
                                                                                        }"
                                            >
                                                @svg('mw-image-edit')
                                            </a>


                                            <a
                                                x-on:click="{{ '$wire.mountFormComponentAction(\'' . $statePath . '\', \'edit\', { id: \'' . $item->id . '\' })' }}"
                                               class="image-settings settings-img  " x-data="{}" x-tooltip="{
                                                                                            content: 'Image Settings',
                                                                                            theme: $store.theme,
                                                                                        }">
                                                @svg('mw-media-item-edit-small')
                                            </a>

                                            <a
                                                x-on:click="{{ '$wire.mountFormComponentAction(\'' . $statePath . '\', \'delete\', { id: \'' . $item->id . '\' })' }}"

                                               class="image-settings settings-img  " x-data="{}" x-tooltip="{
                                                                                            content: 'Delete Image',
                                                                                            theme: $store.theme,
                                                                                        }"
                                            >
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
