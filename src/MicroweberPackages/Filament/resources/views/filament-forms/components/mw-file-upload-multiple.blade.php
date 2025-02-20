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
            acceptedFileTypes: '{{ implode(',', $fileTypes) }}',
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}
         }"
    >


        <div class="w-full flex flex-col items-center justify-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">


            <button
                class="w-full py-6 full flex flex-col items-center justify-center"
                type="button" x-on:click="()=> {

                mw.filePickerDialog({
                    pickerOptions: {
                        multiple:true,
                        type: acceptedFileTypes,
                    }
                }, (url) => {
                    if (!state) {
                        state = [];
                    }
                    let urlFileType = '';
                    let getFileExtension = url.split('.').pop();
                    if (getFileExtension == 'svg' || getFileExtension == 'webp' || getFileExtension == 'jpg' || getFileExtension == 'jpeg' || getFileExtension == 'png' || getFileExtension == 'gif') {
                        urlFileType = 'image';
                    } else {
                        urlFileType = 'file';
                    }
                    state.push({
                        'fileUrl': url,
                        'fileType': urlFileType,
                        'fileExtension': getFileExtension,
                        'fileUrlShort': url.split('/').pop()
                    });
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

            <div x-show="state && state.length > 0" class="grid grid-cols-3 gap-4 w-full p-4">
                <template x-for="fileItem in state">

                    <div class="w-full" x-show="fileItem">
                        <div class="w-full relative flex flex-col items-center justify-center bg-black/80 rounded-md">
                            <div class="absolute w-full h-full top-0 text-white p-2 rounded-t-md bg-gradient-to-b from-black/40 to-black/5 mh-[300px]"
                            >
                                <div class="flex gap-2 items-center">
                                    <button class="text-white bg-white/5 rounded-md" x-on:click="() => {
                                        if (state && state !== null && typeof state == 'object') {
                                            state = state.filter(item => item !== fileItem);
                                        } else {
                                            state = [];
                                        }
                                    }">
                                        <svg fill="currentColor" class="w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z"></path>
                                        </svg>
                                    </button>
                                    <span x-html="()=> {
                                        if (typeof fileItem.fileUrlShort !== 'undefined') {
                                            return fileItem.fileUrlShort.length > 25 ? fileItem.fileUrlShort.substring(0, 25) + '...' : fileItem.fileUrlShort;
                                        }
                                    }"></span>

                                </div>
                            </div>

                            <div
                                :style="`width:100%;height:200px;background: url(${fileItem.fileUrl});background-position: center;background-repeat: no-repeat;background-size: contain;`"
                                x-show="fileItem.fileType == 'image'">

                            </div>

                            <div class="flex items-center justify-center" style="width:100%;height:200px;padding:40px;" x-show="fileItem.fileType !== 'image'">


                                <svg x-show="fileItem.fileExtension == 'pdf'"
                                     width="90px"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 56 64" enable-background="new 0 0 56 64" xml:space="preserve">
                                <g>
                                    <path fill="#8C181A" d="M5.1,0C2.3,0,0,2.3,0,5.1v53.8C0,61.7,2.3,64,5.1,64h45.8c2.8,0,5.1-2.3,5.1-5.1V20.3L37.1,0H5.1z"/>
                                    <path fill="#6B0D12" d="M56,20.4v1H43.2c0,0-6.3-1.3-6.1-6.7c0,0,0.2,5.7,6,5.7H56z"/>
                                    <path opacity="0.5" fill="#FFFFFF" enable-background="new    " d="M37.1,0v14.6c0,1.7,1.1,5.8,6.1,5.8H56L37.1,0z"/>
                                </g>
                                                                    <path fill="#FFFFFF" d="M14.9,49h-3.3v4.1c0,0.4-0.3,0.7-0.8,0.7c-0.4,0-0.7-0.3-0.7-0.7V42.9c0-0.6,0.5-1.1,1.1-1.1h3.7
                                    c2.4,0,3.8,1.7,3.8,3.6C18.7,47.4,17.3,49,14.9,49z M14.8,43.1h-3.2v4.6h3.2c1.4,0,2.4-0.9,2.4-2.3C17.2,44,16.2,43.1,14.8,43.1z
                                     M25.2,53.8h-3c-0.6,0-1.1-0.5-1.1-1.1v-9.8c0-0.6,0.5-1.1,1.1-1.1h3c3.7,0,6.2,2.6,6.2,6C31.4,51.2,29,53.8,25.2,53.8z M25.2,43.1
                                    h-2.6v9.3h2.6c2.9,0,4.6-2.1,4.6-4.7C29.9,45.2,28.2,43.1,25.2,43.1z M41.5,43.1h-5.8V47h5.7c0.4,0,0.6,0.3,0.6,0.7
                                    s-0.3,0.6-0.6,0.6h-5.7v4.8c0,0.4-0.3,0.7-0.8,0.7c-0.4,0-0.7-0.3-0.7-0.7V42.9c0-0.6,0.5-1.1,1.1-1.1h6.2c0.4,0,0.6,0.3,0.6,0.7
                                    C42.2,42.8,41.9,43.1,41.5,43.1z"/>
                                </svg>

                                <svg x-show="fileItem.fileExtension == 'csv'"
                                     width="100px" viewBox="-4 0 64 64" xmlns="http://www.w3.org/2000/svg">

                                    <path d="M5.106 0c-2.802 0-5.073 2.272-5.073 5.074v53.841c0 2.803 2.271 5.074 5.073 5.074h45.774c2.801 0 5.074-2.271 5.074-5.074v-38.605l-18.903-20.31h-31.945z" fill-rule="evenodd" clip-rule="evenodd" fill="#45B058"/>

                                    <path d="M20.306 43.197c.126.144.198.324.198.522 0 .378-.306.72-.703.72-.18 0-.378-.072-.504-.234-.702-.846-1.891-1.387-3.007-1.387-2.629 0-4.627 2.017-4.627 4.88 0 2.845 1.999 4.879 4.627 4.879 1.134 0 2.25-.486 3.007-1.369.125-.144.324-.233.504-.233.415 0 .703.359.703.738 0 .18-.072.36-.198.504-.937.972-2.215 1.693-4.015 1.693-3.457 0-6.176-2.521-6.176-6.212s2.719-6.212 6.176-6.212c1.8.001 3.096.721 4.015 1.711zm6.802 10.714c-1.782 0-3.187-.594-4.213-1.495-.162-.144-.234-.342-.234-.54 0-.361.27-.757.702-.757.144 0 .306.036.432.144.828.739 1.98 1.314 3.367 1.314 2.143 0 2.827-1.152 2.827-2.071 0-3.097-7.112-1.386-7.112-5.672 0-1.98 1.764-3.331 4.123-3.331 1.548 0 2.881.467 3.853 1.278.162.144.252.342.252.54 0 .36-.306.72-.703.72-.144 0-.306-.054-.432-.162-.882-.72-1.98-1.044-3.079-1.044-1.44 0-2.467.774-2.467 1.909 0 2.701 7.112 1.152 7.112 5.636.001 1.748-1.187 3.531-4.428 3.531zm16.994-11.254l-4.159 10.335c-.198.486-.685.81-1.188.81h-.036c-.522 0-1.008-.324-1.207-.81l-4.142-10.335c-.036-.09-.054-.18-.054-.288 0-.36.323-.793.81-.793.306 0 .594.18.72.486l3.889 9.992 3.889-9.992c.108-.288.396-.486.72-.486.468 0 .81.378.81.793.001.09-.017.198-.052.288z" fill="#ffffff"/>

                                    <g fill-rule="evenodd" clip-rule="evenodd">

                                        <path d="M56.001 20.357v1h-12.8s-6.312-1.26-6.128-6.707c0 0 .208 5.707 6.003 5.707h12.925z" fill="#349C42"/>

                                        <path d="M37.098.006v14.561c0 1.656 1.104 5.791 6.104 5.791h12.8l-18.904-20.352z" opacity=".5" fill="#ffffff"/>

                                    </g>

                                </svg>

                                <svg
                                    x-show="fileItem.fileExtension == 'ppt'"
                                    width="110px"
                                    viewBox="-4 0 64 64" xmlns="http://www.w3.org/2000/svg">

                                    <path d="M5.112-.004c-2.802 0-5.073 2.273-5.073 5.074v53.841c0 2.803 2.271 5.074 5.073 5.074h45.774c2.801 0 5.074-2.271 5.074-5.074v-38.605l-18.902-20.31h-31.946z" fill-rule="evenodd" clip-rule="evenodd" fill="#E34221"/>

                                    <g fill-rule="evenodd" clip-rule="evenodd">

                                        <path d="M55.977 20.352v1h-12.799s-6.312-1.26-6.129-6.707c0 0 .208 5.707 6.004 5.707h12.924z" fill="#DC3119"/>

                                        <path d="M37.074 0v14.561c0 1.656 1.104 5.791 6.104 5.791h12.799l-18.903-20.352z" opacity=".5" fill="#ffffff"/>

                                    </g>

                                    <path d="M14.964 49.011h-3.331v4.141c0 .414-.324.738-.756.738-.414 0-.738-.324-.738-.738v-10.298c0-.594.486-1.081 1.08-1.081h3.745c2.413 0 3.763 1.657 3.763 3.619 0 1.963-1.387 3.619-3.763 3.619zm-.181-5.906h-3.15v4.573h3.15c1.423 0 2.395-.936 2.395-2.287 0-1.349-.972-2.286-2.395-2.286zm11.197 5.906h-3.332v4.141c0 .414-.324.738-.756.738-.414 0-.738-.324-.738-.738v-10.298c0-.594.486-1.081 1.08-1.081h3.746c2.412 0 3.763 1.657 3.763 3.619 0 1.963-1.387 3.619-3.763 3.619zm-.18-5.906h-3.151v4.573h3.151c1.423 0 2.395-.936 2.395-2.287-.001-1.349-.972-2.286-2.395-2.286zm14.112 0h-3.277v10.047c0 .414-.324.738-.756.738-.414 0-.738-.324-.738-.738v-10.047h-3.259c-.36 0-.647-.288-.647-.684 0-.361.287-.648.647-.648h8.03c.36 0 .648.288.648.685.001.359-.288.647-.648.647z" fill="#ffffff"/>

                                </svg>

                                <svg

                                    x-show="(fileItem.fileExtension !== 'csv' && fileItem.fileExtension !== 'pdf' && fileItem.fileExtension !== 'ppt')"
                                    width="100px"

                                    viewBox="-4 0 64 64" xmlns="http://www.w3.org/2000/svg">

                                    <g fill-rule="evenodd" clip-rule="evenodd">

                                        <path d="M5.113.007c-2.803 0-5.074 2.271-5.074 5.074v53.84c0 2.803 2.271 5.074 5.074 5.074h45.774c2.801 0 5.074-2.271 5.074-5.074v-38.606l-18.903-20.308h-31.945z" fill="#8199AF"/>

                                        <path d="M55.976 20.352v1h-12.799s-6.312-1.26-6.129-6.707c0 0 .208 5.707 6.004 5.707h12.924z" fill="#617F9B"/>

                                        <path d="M37.074 0v14.561c0 1.656 1.104 5.791 6.104 5.791h12.799l-18.903-20.352z" opacity=".5" fill="#ffffff"/>

                                    </g>

                                </svg>


                            </div>

                        </div>
                    </div>

                </template>
            </div>

        </div>

    </div>
</div>
