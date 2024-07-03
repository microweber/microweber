<div>

    @php
        $suffix = '';

        $suffix = $this->getId();



    @endphp


    <div>



        <script>
            document.addEventListener('livewire:init', function() {

            });
        </script>




        <div>
            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">

            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                Image gallery
            </span>
            </label>
        </div>


        <div
            class="w-full flex flex-col items-center justify-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
            <button

                class="w-full py-6 full flex flex-col items-center justify-center"
                type="button" x-on:click="()=> {

                mw.filePickerDialog((url) => {
                   $dispatch('addMediaItem', { url: url })
                });

            }">

            <span>
                    Select media file or <b class="text-yellow-500 font-bold">Upload</b>
                </span>
            </button>
            <div class="w-full">
                @if($this->mediaItems and !empty($this->mediaItems))

                <div
                    x-data="{}"
                    x-sortable
                    x-on:end="console.log('end', $event);


                    itemsSortedIds = $event.target.querySelectorAll('[x-sortable-item]');


                    itemsSortedIdsArray = [];
                    for (var i = 0; i < itemsSortedIds.length; i++) {
                        itemsSortedIdsArray.push(itemsSortedIds[i].getAttribute('x-sortable-item'));
                    }

                    console.log(itemsSortedIdsArray)

                    $dispatch('mediaItemsSort', { itemsSortedIds: itemsSortedIdsArray })


                    "
                    class="flex flex-col gap-2"
                >
                    @foreach($this->mediaItems as $item)
                        <div
                            x-sortable-handle
                            x-sortable-item="{{ $item->id }}"
                            x-data-id="{{ $item->id }}"
                            class="py-2 bg-white px-4"
                        >
                            {{ $item->filename }}
                        </div>
                    @endforeach
                </div>
                @endif



            </div>
        </div>

    </div>
</div>



