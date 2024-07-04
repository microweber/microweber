<x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
        class="relative z-0"
>


<script>


</script>
    <div
            x-data="{ state: $wire.entangle('{{ $getStatePath() }}'), initialized: false }"
            x-load-js="[@js(\Illuminate\Support\Facades\Vite::asset('src/MicroweberPackages/Filament/resources/js/tiny-editor.js'))]"
            x-init="(() => {
            $nextTick(async () => {
                // tinymce.createEditor('tiny-editor-{{ $getId() }}', {

                  mw.richTextEditor({
                    target: document.querySelector('[data-id=\'tiny-editor-{{ $getId() }}\']'),
                    deprecation_warnings: false,


                    toolbar_sticky_offset: 64,


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

                })
            });


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
                    data-id="tiny-editor-{{ $getId() }}"

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
