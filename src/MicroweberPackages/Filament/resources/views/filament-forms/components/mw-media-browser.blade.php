@php
$mediaItems = $getMediaItemsArray()
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
                        Livewire.dispatch('addMediaItem', {data: data})

                    })
                });
            </script>
            <script>
                function mwMediaManagerComponent({mediaIds}) {
                    return {
                        mediaIds,
                        modalImageSettingsOpen: false,
                        selectedImages: [],

                        init() {


                        },


                        editMediaOptionsById(id) {


                            this.$wire.mountAction('editAction', {id: id})

                        },

                        async bulkDeleteSelectedMedia() {
                            const dialogConfirm = await mw.confirm('Are you sure you want to delete selected images?').promise()
                            if (dialogConfirm) {
                                this.$wire.dispatch('deleteMediaItemsByIds', {ids: this.selectedImages})
                            }
                        },

                        async deleteMediaById(id) {

                            const dialogConfirm = await mw.confirm('Are you sure you want to delete this image?').promise()
                            if (dialogConfirm) {
                                this.$wire.dispatch('deleteMediaItemById', {id: id})
                            }
                        },
                        async editImageFilename(id, url) {

                            const editedImage = await mw.top().app.editImageDialog.editImageUrl(url);

                            this.$wire.dispatch('updateImageFilename', {id: id, data: editedImage})

                        }
                    }
                }
            </script>

            <div
                id="mw-image-dropzone"
                class="w-full flex flex-col p-3 items-center justify-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">

                <button

                    class="w-full py-6 full flex flex-col items-center justify-center"
                    type="button" x-on:click="()=> {

                mw.filePickerDialog((url) => {

                      $wire.dispatchFormEvent(
                        'mwMediaBrowser::addMediaItem',
                        { data: { url: url } },
                    )

                });

                }">

                <span>
                    Select media file or <b class="text-yellow-500 font-bold">Upload</b>
                </span>
                </button>

                <hr class="h-px mb-8 mt-4 bg-gray-200 border-0 dark:bg-gray-700 w-full">


                <div class="w-full mb-3">
                    @if($mediaItems and !empty($mediaItems))

                        <div

                            x-data="mwMediaManagerComponent({
                            mediaIds: $wire.$entangle('mediaIds')
                        })"
                            x-on:end="



                    itemsSortedIds = $event.target.querySelectorAll('[x-sortable-item]');

                    itemsSortedIdsArray = [];
                    for (var i = 0; i < itemsSortedIds.length; i++) {
                        itemsSortedIdsArray.push(itemsSortedIds[i].getAttribute('x-sortable-item'));
                    }
                    $dispatch('mediaItemsSort', { itemsSortedIds: itemsSortedIdsArray })
                    "
                            class="admin-thumbs-holder-wrapper"
                        >


                            <div x-show="mediaIds && mediaIds.length > 0 && selectedImages && selectedImages.length > 0"
                                 class="admin-thumbs-holder-bulk-actions">


                                <button type="button" @click="bulkDeleteSelectedMedia()">Delete selected</button>


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
                                        <div class="mw-post-media-img--header">

                                            <a @click="editImageFilename('{{ $item->id }}','{{ $item->filename }}')"
                                               class="image-settings settings-img  "
                                            >
                                                @svg('mw-image-edit')
                                            </a>


                                            <a @click="editMediaOptionsById('{{ $item->id }}')"
                                               class="image-settings settings-img  ">
                                                @svg('mw-media-item-edit-small')
                                            </a>

                                            <a @click="deleteMediaById('{{ $item->id }}')">
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


                        </div>
                    @endif

                </div>
            </div>


        </div>
    </div>



</x-dynamic-component>
