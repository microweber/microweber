<x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
        class="relative z-0"
>
    <div
            x-data="{ state: $wire.entangle('{{ $getStatePath() }}'), initialized: false }"
            x-load-js="[@js(\Illuminate\Support\Facades\Vite::asset('src/MicroweberPackages/Filament/resources/js/tiny-editor.js'))]"
            x-init="(() => {
            $nextTick(() => {
                tinymce.createEditor('tiny-editor-{{ $getId() }}', {
                    target: $refs.tinymce,
                    deprecation_warnings: false,


                    toolbar_sticky_offset: 64,
                    skin: {
                        light: 'oxide',
                        dark: 'oxide-dark',
                        system: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide',
                    }[typeof theme === 'undefined' ? 'light' : theme],
                    content_css: {
                        light: 'default',
                        dark: 'dark',
                        system: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default',
                    }[typeof theme === 'undefined' ? 'light' : theme],

                    branding: false,
                    images_upload_handler: (blobInfo, success, failure, progress) => {
                        if (!blobInfo.blob()) return

                        $wire.upload(`componentFileAttachments.{{ $getStatePath() }}`, blobInfo.blob(), () => {
                            $wire.getFormComponentFileAttachmentUrl('{{ $getStatePath() }}').then((url) => {
                                if (!url) {
                                    failure('{{ __('Error uploading file') }}')
                                    return
                                }
                                success(url)
                            })
                        })
                    },
                    file_picker_callback: (cb, value, meta) => {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.addEventListener('change', (e) => {
                            const file = e.target.files[0];
                            const reader = new FileReader();
                            reader.addEventListener('load', () => {
                                $wire.upload(`componentFileAttachments.{{ $getStatePath() }}`, file, () => {
                                    $wire.getFormComponentFileAttachmentUrl('{{ $getStatePath() }}').then((url) => {
                                        if (!url) {
                                            cb('{{ __('Error uploading file') }}')
                                            return
                                        }
                                        cb(url)
                                    })
                                })
                            });
                            reader.readAsDataURL(file);
                        });

                        input.click();
                    },
                    automatic_uploads: true,

                    setup: function(editor) {
                        if(!window.tinySettingsCopy) {
                            window.tinySettingsCopy = [];
                        }

                        if (!window.tinySettingsCopy.some(obj => obj.id === editor.settings.id)) {
                            window.tinySettingsCopy.push(editor.settings);
                        }

                        editor.on('blur', function(e) {
                            state = editor.getContent()
                        })

                        editor.on('init', function(e) {
                            if (state != null) {
                                editor.setContent(state)
                            }
                        })

                        editor.on('OpenWindow', function(e) {
                            target = e.target.container.closest('.fi-modal')
                            if (target) target.setAttribute('x-trap.noscroll', 'false')

                            target = e.target.container.closest('.jetstream-modal')
                            if (target) {
                                targetDiv = target.children[1]
                                targetDiv.setAttribute('x-trap.inert.noscroll', 'false')
                            }
                        })

                        editor.on('CloseWindow', function(e) {
                            target = e.target.container.closest('.fi-modal')
                            if (target) target.setAttribute('x-trap.noscroll', 'isOpen')

                            target = e.target.container.closest('.jetstream-modal')
                            if (target) {
                                targetDiv = target.children[1]
                                targetDiv.setAttribute('x-trap.inert.noscroll', 'show')
                            }
                        })

                        function putCursorToEnd() {
                            editor.selection.select(editor.getBody(), true);
                            editor.selection.collapse(false);
                        }

                        $watch('state', function(newstate) {
                            // unfortunately livewire doesn't provide a way to 'unwatch' so this listener sticks
                            // around even after this component is torn down. Which means that we need to check
                            // that editor.container exists. If it doesn't exist we do nothing because that means
                            // the editor was removed from the DOM
                            if (editor.container && newstate !== editor.getContent()) {
                                editor.resetContent(newstate || '');
                                putCursorToEnd();
                            }
                        });
                    },

                }).render();
            });

            // We initialize here because if the component is first loaded from within a modal DOMContentLoaded
            // won't fire and if we want to register a Livewire.hook listener Livewire.hook isn't available from
            // inside the once body
            if (!window.tinyMceInitialized) {
                window.tinyMceInitialized = true;
                $nextTick(() => {
                    Livewire.hook('morph.removed', (el, component) => {
                        if (el.el.nodeName === 'INPUT' && el.el.getAttribute('x-ref') === 'tinymce') {
                            tinymce.get(el.el.id)?.remove();
                        }
                    });
                });
            }
        })()"
            x-cloak
            class="overflow-hidden"
            wire:ignore
    >
        @unless($isDisabled())
            <textarea
                    id="tiny-editor-{{ $getId() }}"

                    x-ref="tinymce"
                    placeholder="{{ $getPlaceholder() }}"
            ></textarea>
        @else
            <div
                    x-html="state"
                    @style([
                        'max-height: '.$getPreviewMaxHeight().'px' => $getPreviewMaxHeight() > 0,
                        'min-height: '.$getPreviewMinHeight().'px' => $getPreviewMinHeight() > 0,
                    ])
                    class="block w-full max-w-none rounded-lg border border-gray-300 bg-white p-3 opacity-70 shadow-sm transition duration-75 prose dark:prose-invert dark:border-gray-600 dark:bg-gray-700 dark:text-white overflow-y-auto"
            ></div>
        @endunless
    </div>
</x-dynamic-component>
