@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();


    $fileTypes = ($getFileTypes())
@endphp
<div>

    <div>
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">


    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
         {{ $getLabel() }}
    </span>


        </label>
    </div>

    <div x-data="{
            typeFile: 'file',
            acceptedFileTypes: '{{ implode(',', $fileTypes) }}',
            fileUrlShort: '',
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            clearState() {
                this.state = '';
                this.fileUrlShort = '';
                this.typeFile = 'file';
                $dispatch('file-cleared');
            }
        }"
         x-effect="() => {
             if (state && state.length > 0) {
                  let getFileExtension = state.split('.').pop();
             if (getFileExtension == 'webp' || getFileExtension == 'jpg' || getFileExtension == 'jpeg' || getFileExtension == 'png' || getFileExtension == 'gif') {
                typeFile = 'image';
            } else if (getFileExtension == 'mp4' || getFileExtension == 'mov' || getFileExtension == 'avi' || getFileExtension == 'm4v' || getFileExtension == 'mkv') {
                typeFile = 'video';
            } else if (getFileExtension == 'wav' || getFileExtension == 'midi' || getFileExtension == 'mp3' || getFileExtension == 'ogg' || getFileExtension == 'flac') {
                typeFile = 'audio';
            } else {
                typeFile = 'file';
            }

            fileUrlShort = state.split('/').pop();
         }
     }"
    >


        <div class="w-full flex flex-col mw-file-upload-background-module-boxes items-center justify-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500 dark:text-white dark:!hover:bg-gray-700">


            <button
                x-show="!state"
                class="w-full py-6 full flex flex-col items-center justify-center"
                type="button" x-on:click="()=> {

                mw.filePickerDialog({
                    pickerOptions :{
                        type: acceptedFileTypes
                    }
                }, (url) => {
                    state = url;
                });

            }">
                <div>
                    <svg fill="currentColor" class="w-12 fill-gray-500" viewBox="0 -1.5 35 35" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <path class="text-gray-300"  d="M29.426 15.535c0 0 0.649-8.743-7.361-9.74-6.865-0.701-8.955 5.679-8.955 5.679s-2.067-1.988-4.872-0.364c-2.511 1.55-2.067 4.388-2.067 4.388s-5.576 1.084-5.576 6.768c0.124 5.677 6.054 5.734 6.054 5.734h9.351v-6h-3l5-5 5 5h-3v6h8.467c0 0 5.52 0.006 6.295-5.395 0.369-5.906-5.336-7.070-5.336-7.070z"></path>
                    </svg>
                </div>
                <span>
                    Select media file or <b class="text-yellow-500 font-bold">Upload</b>
                </span>
            </button>
            <div class="w-full" x-show="state && typeFile == 'image'">
                <div class="w-full relative flex flex-col items-center justify-center bg-black/80 rounded-md">
                    <div class="absolute w-full h-full top-0 text-white p-2 rounded-t-md bg-gradient-to-b from-black/40 to-black/5 min-h-[300px]"
                         >
                        <div class="flex gap-2 items-center">
                        <button type="button" class="text-white bg-white/5 rounded-md" x-on:click="clearState()">
                            <svg fill="currentColor" class="w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z"></path>
                            </svg>
                        </button>
                        <span x-html="fileUrlShort"></span>
                        </div>
                    </div>

                    <img :src="state" class="min-h-[300px]" />
                </div>
            </div>


 <div class="w-full" x-show="state && typeFile == 'video'">
     <div class="w-full relative flex flex-col items-center justify-center bg-black/80 rounded-md">
         <div class="absolute w-full top-0 text-white p-2 rounded-t-md bg-gradient-to-b from-black/40 to-black/5 z-10">
             <div class="flex gap-2 items-center">
                 <button type="button" class="text-white bg-white/5 rounded-md hover:bg-white/20" x-on:click="clearState()">
                     <svg fill="currentColor" class="w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z"></path>
                     </svg>
                 </button>
                 <span x-html="fileUrlShort"></span>
             </div>
         </div>
         <video class="w-full" style="min-height: 300px" controls>
             <source :src="state">
             Your browser does not support the video tag.
         </video>
     </div>
 </div>






            <div class="w-full" x-show="state && typeFile !== 'image' && typeFile !== 'video' && typeFile !== 'audio'">
                <div class="w-full relative flex flex-col items-center justify-center bg-black/80 rounded-md">
                    <div class="absolute w-full h-full top-0 text-white p-2 rounded-t-md bg-gradient-to-b from-black/40 to-black/5"
                         >
                        <div class="flex gap-2 items-center">
                        <button type="button" class="text-white bg-white/5 rounded-md" x-on:click="clearState()">
                            <svg fill="currentColor" class="w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z"></path>
                            </svg>
                        </button>
                        <span x-html="fileUrlShort"></span>
                        </div>
                    </div>

                    <input type="text" :value="state" class="max-h-[15rem]" />
                </div>
            </div>
        </div>

    </div>
</div>
