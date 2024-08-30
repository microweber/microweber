<div
    x-data="compose({ text: @entangle($model), autofocus: @json($autofocus ?? false) })"

>
    <div wire:ignore>
        <textarea placeholder="{{ $placeholder ?? '' }}"></textarea>
    </div>

</div>

@push('comments-scripts')

    <script>
        document.addEventListener("alpine:init", () => {
            window.Alpine.data("compose", ({ text, autofocus = false } = {}) => {
                // Store the editor as a non-reactive instance property
                let editor;

                return {
                    text,

                    init() {
                        if (editor) {
                            return;
                        }

                        const textarea = this.$el.querySelector("textarea");

                        if (!textarea) {
                            return;
                        }

                        this.loadEasyMDE().then(() => {
                            editor = new window.EasyMDE({
                                element: textarea,
                                hideIcons: [
                                    "heading",
                                    "image",
                                    "preview",
                                    "side-by-side",
                                    "fullscreen",
                                    "guide",
                                ],
                                autoDownloadFontAwesome:true,
                                spellChecker: false,
                                status: false,
                                insertTexts: {
                                    link: ["[",  "](https://)"],
                                },
                            });

                            editor.value(this.text);

                            if (autofocus) {
                                editor.codemirror.focus();
                                editor.codemirror.setCursor(editor.codemirror.lineCount(), 0);
                            }

                            editor.codemirror.on("change", () => {
                                this.text = editor.value();
                            });
                        });
                    },

                    clear() {
                        editor.value("");
                    },

                    loadEasyMDE() {
                        if (window.EasyMDE) {
                            return Promise.resolve();
                        }

                        const loadScript = new Promise((resolve) => {
                            const script = document.createElement("script");
                            script.src = mw.settings.libs_url + 'easymde' + '/easymde.min.js';
                            script.addEventListener("load", resolve);
                            document.getElementsByTagName("head")[0].appendChild(script);
                        });

                        const loadCss = new Promise((resolve) => {
                            const link = document.createElement("link");
                            link.type = "text/css";
                            link.rel = "stylesheet";
                            link.href = mw.settings.libs_url + 'easymde' + '/easymde.min.css';
                            link.addEventListener("load", resolve);
                            document.getElementsByTagName("head")[0].appendChild(link);
                        });

                        return Promise.all([loadScript, loadCss]);
                    },
                };
            });
        });
    </script>
@endpush
