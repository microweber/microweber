@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp
<div>

    <div x-data="{
            typeFile: 'file',
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        }"
         x-init="() => {

         function changeTypeFile() {
           let url = state;
            let getFileExtension = url.split('.').pop();
            if (getFileExtension == 'jpg' || getFileExtension == 'jpeg' || getFileExtension == 'png' || getFileExtension == 'gif') {
                typeFile = 'image';
            } else {
                typeFile = 'file';
            }
        }
        changeTypeFile();

     }"
    >

        <span x-html="state"></span>

        <div x-show="typeFile == 'image'">
            <img :src="state" style="max-width: 100px; max-height: 100px;" />
        </div>

        <button
            class="w-full flex flex-col items-center justify-center p-12 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
            type="button" x-on:click="function() {

            mw.filePickerDialog((url) => {
                this.state = url;
            });

        }">
            <div>
                <svg fill="primary" class="w-12" viewBox="0 -1.5 35 35" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <title>upload1</title>
                    <path d="M29.426 15.535c0 0 0.649-8.743-7.361-9.74-6.865-0.701-8.955 5.679-8.955 5.679s-2.067-1.988-4.872-0.364c-2.511 1.55-2.067 4.388-2.067 4.388s-5.576 1.084-5.576 6.768c0.124 5.677 6.054 5.734 6.054 5.734h9.351v-6h-3l5-5 5 5h-3v6h8.467c0 0 5.52 0.006 6.295-5.395 0.369-5.906-5.336-7.070-5.336-7.070z"></path>
                </svg>
            </div>
            <span>
                Select media file or <b class="text-yellow-800 font-bold">Upload</b>
            </span>
        </button>

    </div>
</div>
