<x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
        class="relative z-0"
>
    {{-- task-2026-09-27 — MwRichEditor now runs on the Microweber MWEditor engine
         (mw.Editor), the same editor Live Edit uses. TinyMCE has been removed.
         The editor lib is lazy-loaded once from frontend-assets-libs and attached
         in "div" mode to the field's textarea, which it keeps in sync (its
         _syncTextArea writes back to the textarea on every change). --}}
    @once
        <script>
            window.mwEnsureRichEditorLib = window.mwEnsureRichEditorLib || function () {
                if (window.__mwRichEditorLibPromise) return window.__mwRichEditorLibPromise;
                window.__mwRichEditorLibPromise = new Promise(function (resolve) {
                    var base = '{{ public_asset('vendor/microweber-packages/frontend-assets-libs/') }}';
                    // Editor styles (once).
                    ['api/editor/editor.css', 'api/editor/area-styles.css'].forEach(function (href) {
                        if (!document.querySelector('link[data-mw-editor-css="' + href + '"]')) {
                            var l = document.createElement('link');
                            l.rel = 'stylesheet';
                            l.href = base + href;
                            l.setAttribute('data-mw-editor-css', href);
                            document.head.appendChild(l);
                        }
                    });
                    var ready = function () {
                        // mw.Editor pulls its deps in via mw.require; poll until ready.
                        var tries = 0;
                        (function wait() {
                            if (window.mw && typeof window.mw.Editor === 'function') { resolve(); return; }
                            if (tries++ > 100) { resolve(); return; }
                            setTimeout(wait, 50);
                        })();
                    };
                    if (window.mw && typeof window.mw.Editor === 'function') { resolve(); return; }
                    var existing = document.querySelector('script[data-mw-editor-lib]');
                    if (existing) { ready(); return; }
                    var s = document.createElement('script');
                    s.src = base + 'api/editor.js';
                    s.setAttribute('data-mw-editor-lib', '1');
                    s.onload = ready;
                    s.onerror = function () { resolve(); };
                    document.head.appendChild(s);
                });
                return window.__mwRichEditorLibPromise;
            };
        </script>
    @endonce

    <div
            x-data="{ state: $wire.entangle('{{ $getStatePath() }}'), editor: null }"
            x-init="(() => {
                $nextTick(async () => {
                    await window.mwEnsureRichEditorLib();
                    if (!(window.mw && typeof mw.Editor === 'function')) { return; }

                    const ta = $refs.mweditor;
                    if (!ta) { return; }
                    // Seed the textarea with the current state so div-mode picks it up.
                    ta.value = (state === null || state === undefined) ? '' : state;

                    editor = mw.Editor({
                        mode: 'div',
                        selector: ta,
                        skin: 'le2',
                        minHeight: {{ (int) ($getPreviewMinHeight() ?: 220) }},
                        controls: [[
                            'format', 'bold', 'italic', 'underline', 'strikeThrough',
                            'link', 'unlink', 'ul', 'ol',
                            { group: { controller: 'alignLeft', controls: ['alignLeft','alignCenter','alignRight','alignJustify'] } },
                            { group: { className: 'mw-editor-color-group', controller: 'textColor', controls: ['textColor','textBackgroundColor'] } },
                            'removeFormat'
                        ]],
                    });

                    $refs.mweditorHolder.appendChild(editor.wrapper);

                    // Editor -> Livewire state.
                    $(editor).on('change', function (e, html) {
                        if (html !== state) { state = html; }
                    });

                    // Livewire state -> editor (external programmatic changes).
                    $watch('state', function (newState) {
                        if (!editor || !editor.editArea) { return; }
                        var current = editor.editArea.innerHTML;
                        if ((newState || '') !== current) {
                            editor.setContent(newState || '', false);
                        }
                    });
                });
            })()"
            x-cloak
            class="overflow-hidden"
            wire:ignore
    >
        @unless($isDisabled())
            <div x-ref="mweditorHolder" class="mw-rich-editor-holder"></div>
            <textarea
                    x-ref="mweditor"
                    data-id="mw-rich-editor-{{ $getId() }}"
                    placeholder="{{ $getPlaceholder() }}"
                    style="display:none"
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
