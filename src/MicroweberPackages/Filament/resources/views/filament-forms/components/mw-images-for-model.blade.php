@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp

@php
    $suffix = '';

    $suffix = $this->getId();



@endphp


<div>

    <div>
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">


    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
         {{ $getLabel() }}
    </span>


        </label>
    </div>


    <script>


        document.addEventListener('alpine:init', () => {

            Alpine.data('mwModelImagesComponent', ({state}) => ({
                state,
                init() {


                    console.log('init')
                    console.log(this.state)

                    //  this.state.categoryIds = selectedCategories;


                },
                removeUploadedFileUsing: async (fileKey) => {


                    console.log(this)

                    //   alert(fileKey)
                    //      Livewire.dispatch('test', { id: 12345 })

                    //this.$dispatch('attachMediaIdToModel', { url: 'edit-user' })


                    //return await this.$wire.removeFormUploadedFile(@js($getStatePath()), fileKey)
                },

                pickFile() {
                    mw.filePickerDialog((url) => {

                        //   this.state.newImageUrl = (url);

                        // $wire.removeFormUploadedFile(url)
                        // $dispatch('addMediaItem', { url: url})
                        //$wire.dispatchFormEvent('attachMediaIdToModel', { url: url})
                    });
                }


            }))

        })


    </script>


    <div
        x-data="mwModelImagesComponent({
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}
            })"
    >

        <button

            class="w-full py-6 full flex flex-col items-center justify-center"
            type="button" @click="pickFile()">
            <div>
                <svg fill="currentColor" class="w-12 fill-gray-500" viewBox="0 -1.5 35 35" version="1.1"
                     xmlns="http://www.w3.org/2000/svg">
                    <path class="text-gray-300"
                          d="M29.426 15.535c0 0 0.649-8.743-7.361-9.74-6.865-0.701-8.955 5.679-8.955 5.679s-2.067-1.988-4.872-0.364c-2.511 1.55-2.067 4.388-2.067 4.388s-5.576 1.084-5.576 6.768c0.124 5.677 6.054 5.734 6.054 5.734h9.351v-6h-3l5-5 5 5h-3v6h8.467c0 0 5.52 0.006 6.295-5.395 0.369-5.906-5.336-7.070-5.336-7.070z"></path>
                </svg>
            </div>
            <span>
                    Select media file or <b class="text-yellow-500 font-bold">Upload</b>
                </span>
        </button>


        <div wire:ignore id="mw-images-for-content-{{$suffix}}">


        </div>


        <button type="button" @click="removeUploadedFileUsing('test')">
            removeUploadedFileUsing
        </button>


        <button type="button" wire:click="dispatchFormEvent('attachMediaIdToModel', '{{ $getStatePath() }}', 'test')">
            Add item
        </button>


        <x-filament::button
            size="sm"
            color="info"
            @click="$dispatch('attachMediaIdToModel', 'Hello World!')"

        >
            Test
        </x-filament::button>

    </div>


</div>
