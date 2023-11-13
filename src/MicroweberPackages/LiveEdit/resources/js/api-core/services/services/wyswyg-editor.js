import MicroweberBaseClass from "../containers/base-class.js";

export class WyswygEditor extends MicroweberBaseClass {
    constructor() {
        super();
        this.editor = null;
        this.savedCursorPosition = null;
    }

    initEditor(element) {
        this.savedCursorPosition = this.getCursorPos(element);

        const liveEditIframe = mw.app.canvas.getWindow();
        const liveEditIframeDocument = mw.app.canvas.getDocument();

        if (liveEditIframeDocument && liveEditIframe && liveEditIframe.tinyMCE) {
            const mwTinymceEditor = liveEditIframe.tinyMCE;

            const config = {
                selector: '[contenteditable="true"]',
                skin: 'noskin',
                editable_root: false,
                object_resizing: false,
                resize: false,
                media_live_embeds: false,
                paste_as_text: false,
                paste_auto_cleanup_on_paste: true,
                paste_convert_headers_to_strong: false,
                toolbar: false,
                keep_styles: true,
                promotion: false,
                statusbar: false,
                menubar: false,
              //  forced_root_block: false,
                default_link_target: "_blank",
                link_assume_external_targets: true,
                powerpaste_word_import: 'clean',
                powerpaste_html_import: 'clean',
                verify_html: false,
                cleanup: false,
                valid_elements: '*[*],script[*],style[*]',
                valid_children: '*[*],script[*],style[*]',
                extended_valid_elements: '*[*],script[*],style[*],a[*]',
                inline: true,

                setup: (editor) => {
                    editor.on('init', () => {
                        setTimeout(() => {
                            this.setCursorPos(editor, this.savedCursorPosition);
                        }, 110);
                    });

                    editor.on('paste_preprocess', (e) => {
                        // Remove style tags
                        e.content = e.content.replace(/<\s*style[\s\S]*?<\s*\/\s*style\s*>/gi, '');

                        // Remove inline style attributes
                        e.content = e.content.replace(/ style=".*?"/gi, '');
                    });

                    editor.on('keydown', (e) => {
                        if (e.key === 'Backspace' || e.key === 'Delete') {
                            const cursorNode = editor.selection.getNode();
                            if (cursorNode && (cursorNode.classList.contains('safe-mode') || cursorNode.closest('.safe-mode'))) {
                                if (!cursorNode.innerText.trim()) {
                                    e.preventDefault();
                                }
                            }
                        }
                    });
                },
            };

            // Initialize the new editor directly on the existing contenteditable element
            mwTinymceEditor.init(config).then((initializedEditor) => {
                this.editor = initializedEditor;
            }).catch((error) => {
                console.error('Error initializing TinyMCE:', error);
            });
        }
    }
    getCursorPos(element) {
        const liveEditIframeWindow = mw.app.canvas.getWindow();
        const sel = liveEditIframeWindow.getSelection();
        const range = sel.rangeCount > 0 ? sel.getRangeAt(0) : null;

        if (range) {
            const node = range.startContainer.nodeType === Node.TEXT_NODE ? range.startContainer : range.startContainer.childNodes[range.startOffset];
            const offset = range.startContainer.nodeType === Node.TEXT_NODE ? range.startOffset : 0;

            return { node, offset };
        }

        return null;
    }

    setCursorPos(editor, cursorPosition) {
        if (editor && editor.selection && cursorPosition) {
            const range = editor.selection.getRng().cloneRange();
            range.setStart(cursorPosition.node, cursorPosition.offset);
            range.collapse(true);
            editor.selection.setRng(range);
        }
    }
}
