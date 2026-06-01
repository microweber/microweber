{{--
    task-2026-05-31-flu814 — AI-1203: Filament v5 native mw-file-upload schema-component
    accessible-name gap.

    Pre-fix state probed via Playwright at /admin/logo-module-settings:
      Every <button> rendering the icon-only close-X SVG inside the image-preview,
      video-preview, and generic-file-preview branches carried NO aria-label, NO
      title, NO visible text content. The readonly URL input in the generic-file
      branch carried NO aria-label and NO associated <label>. AT users heard
      "button" / "edit, blank" with no indication of purpose. WCAG 4.1.2 Level A
      (icon-only buttons) + WCAG 3.3.2 Level A (input without label).

    Sister-fix to AI-1199 (Tabs aria-selected), AI-1200 (Toggle aria-checked),
    AI-1201 (Toggle accessible-name), AI-1202 (Section collapse-btn accessible-name).
    Same Filament v5 native-component accessibility-gap family.

    Fix: inject aria-label + title on all three clear-X buttons (semantic
         "Remove file" action) + aria-label on the readonly URL input
         (semantic "File URL" purpose). Translated via Microweber __() helper.

    Carries to every mw-file-upload consumer project-wide (Logo, Image,
    Slider, ContactForm, every Form::make()->schema([...->fileUpload()]) call
    that resolves to this view).
--}}
@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();

    $fileTypes = $getFileTypes();

    $mwAi1203RemoveFileLabel = __('Remove file');
    $mwAi1203FileUrlLabel = __('File URL');
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
                  // The state value may include a query string from the
                  // file-picker (e.g. .../big-logo-1.svg?t=12345). Strip
                  // anything after the first ? before extension match,
                  // otherwise an .svg?... URL falls through every branch
                  // and renders as a generic file (overlay-on-text-input
                  // mess from task-2026-04-29-ebe26c).
                  getFileExtension = getFileExtension.split('?')[0].toLowerCase();
             if (getFileExtension == 'webp' || getFileExtension == 'jpg' || getFileExtension == 'jpeg' || getFileExtension == 'png' || getFileExtension == 'gif' || getFileExtension == 'svg' || getFileExtension == 'avif' || getFileExtension == 'bmp' || getFileExtension == 'ico') {
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
     }">


        <div
            class="flex flex-col mw-file-upload-background-module-boxes items-center justify-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500 dark:text-white dark:!hover:bg-gray-700">


            <button x-show="!state" class="w-full py-6 full flex flex-col items-center justify-center" type="button"
                x-on:click="()=> {

                mw.filePickerDialog({
                    pickerOptions :{
                        type: acceptedFileTypes
                    }
                }, (url) => {
                    state = url;
                });

            }">
                <div>
                    <svg fill="currentColor" class="w-12 fill-gray-500" viewBox="0 -1.5 35 35" version="1.1"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="text-gray-300"
                            d="M29.426 15.535c0 0 0.649-8.743-7.361-9.74-6.865-0.701-8.955 5.679-8.955 5.679s-2.067-1.988-4.872-0.364c-2.511 1.55-2.067 4.388-2.067 4.388s-5.576 1.084-5.576 6.768c0.124 5.677 6.054 5.734 6.054 5.734h9.351v-6h-3l5-5 5 5h-3v6h8.467c0 0 5.52 0.006 6.295-5.395 0.369-5.906-5.336-7.070-5.336-7.070z">
                        </path>
                    </svg>
                </div>
                <span>
                    Select media file or <b class="text-yellow-500 font-bold">Upload</b>
                </span>
            </button>
            <div class="w-full" x-show="state && typeFile == 'image'">
                {{--
                    Image-preview layout — task-2026-04-29-41fe63.
                    Previously the preview used a `bg-black/80` body with
                    an absolute-positioned overlay header on top, and
                    `object-fit: cover` on the <img>. That made dark
                    SVG logos (and any image whose ink color matched
                    the dark BG) effectively invisible — the user
                    reported the preview "doesn't have icon and Style".

                    Restructure:
                      - A header strip (close button + filename) in
                        normal flow above the preview, not absolutely
                        positioned.
                      - A preview area with a light-gray base AND a
                        checkerboard pattern via background gradients,
                        so transparent + dark-on-dark SVGs are
                        always visible against a contrasting backdrop.
                      - object-fit: contain (not cover) so the entire
                        image is visible without cropping.
                      - In dark mode, swap the checker base to a
                        slightly darker neutral so the panel still
                        feels integrated with the surrounding UI.
                --}}
                <div class="w-full relative flex flex-col gap-0 items-stretch justify-center bg-white dark:bg-gray-800 rounded-md overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="flex gap-2 items-center px-3 py-2 bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <button type="button" class="text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md p-1" x-on:click="clearState()" aria-label="{{ $mwAi1203RemoveFileLabel }}" title="{{ $mwAi1203RemoveFileLabel }}">
                            <svg fill="currentColor" class="w-5" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z">
                                </path>
                            </svg>
                        </button>
                        <span class="truncate text-sm text-gray-800 dark:text-gray-100" x-text="fileUrlShort"></span>
                    </div>
                    <div
                        class="w-full flex items-center justify-center min-h-[300px] p-4 mw-file-upload-preview-checker"
                        style="background-color: #f3f4f6;
                               background-image:
                                 linear-gradient(45deg, #d1d5db 25%, transparent 25%),
                                 linear-gradient(-45deg, #d1d5db 25%, transparent 25%),
                                 linear-gradient(45deg, transparent 75%, #d1d5db 75%),
                                 linear-gradient(-45deg, transparent 75%, #d1d5db 75%);
                               background-size: 20px 20px;
                               background-position: 0 0, 0 10px, 10px -10px, -10px 0;">
                        <img :src="state" alt="{{ __('Preview image') }}" class="max-h-[280px] max-w-full" style="object-fit: contain" />
                    </div>
                </div>
            </div>


            <div class="w-full" x-show="state && typeFile == 'video'">
                <div class="w-full relative flex flex-col items-center justify-center bg-black/80 rounded-md overflow-hidden">
                    <div
                        class="absolute w-full top-0 text-white p-2 rounded-t-md bg-gradient-to-b from-black/40 to-black/5 z-10">
                        <div class="flex gap-2 items-center">
                            <button type="button" class="text-white bg-white/5 rounded-md hover:bg-white/20"
                                x-on:click="clearState()" aria-label="{{ $mwAi1203RemoveFileLabel }}" title="{{ $mwAi1203RemoveFileLabel }}">
                                <svg fill="currentColor" class="w-6" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z">
                                    </path>
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
                {{--
                    Generic-file fallback layout. Previously the close-button
                    overlay was `absolute h-full top-0` painting over a
                    same-row text input — they collided as the user reported
                    in task-2026-04-29-ebe26c (X-button + filename text both
                    overlapping the URL input).

                    Stack instead: a normal-flow header row with the close
                    button + filename, and a separate read-only URL input
                    below. No more z-index collisions.
                --}}
                <div class="w-full relative flex flex-col gap-2 items-stretch justify-center bg-black/80 rounded-md p-3">
                    <div class="flex gap-2 items-center text-white">
                        <button type="button" class="text-white bg-white/10 hover:bg-white/20 rounded-md p-1" x-on:click="clearState()" aria-label="{{ $mwAi1203RemoveFileLabel }}" title="{{ $mwAi1203RemoveFileLabel }}">
                            <svg fill="currentColor" class="w-5" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z">
                                </path>
                            </svg>
                        </button>
                        <span class="truncate text-sm" x-text="fileUrlShort"></span>
                    </div>
                    <input type="text" :value="state" readonly aria-label="{{ $mwAi1203FileUrlLabel }}" class="w-full text-xs bg-white/10 text-white border border-white/10 rounded-md px-2 py-1" />
                </div>
            </div>
        </div>

    </div>
</div>
