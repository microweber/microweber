import MicroweberBaseClass from "../containers/base-class.js";
import CursorPosition from "../../core/libs/cursor-position.js";

export class WyswygEditor extends MicroweberBaseClass {
    constructor() {
        super();
        this.editor = null;
        this.savedCursorPosition = null;

        this.config = {
            selector: '[contenteditable="true"]',
            skin: 'noskin',
            editable_root: false,
            //forced_root_block: '', deprecateds
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
            default_link_target: "_blank",
            link_assume_external_targets: true,
            powerpaste_word_import: 'clean',
            powerpaste_html_import: 'clean',
            verify_html: false,
            inline_styles : false,
            auto_focus : false,
            typeahead_urls: false,
            resize_img_proportional: false,
            allow_unsafe_link_target: true,
            format_empty_lines: false,


            cleanup: false,
            valid_elements: '*[*],script[*],style[*]',
            valid_children: '*[*],script[*],style[*]',
            extended_valid_elements: '*[*],script[*],style[*],a[*]',
            inline: true,

            setup: (editor) => {
                // editor.on('init', () => {
                //     setTimeout(() => {
                //         this.setCursorPos(editor, this.savedCursorPosition);
                //      }, 1010);
                // });
                // editor.on('init', () => {
                //    console.log('mce on init');
                // });
                editor.on('paste_preprocess', (e) => {
                    // Remove style tags
                    e.content = e.content.replace(/<\s*style[\s\S]*?<\s*\/\s*style\s*>/gi, '');

                    // Remove inline style attributes
                    e.content = e.content.replace(/ style=".*?"/gi, '');
                });
                editor.on('NodeChange', (e) => {
                    // Clear inline styles after formatting
                  //  if (e.element.nodeName === 'STRONG' || e.element.nodeName === 'SPAN') {
                  //      editor.dom.remove(e.element, true);
                  //  }
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
    }

    initEditor(element) {


        this.savedCursorPosition = this.getCursorPos(element);




        const liveEditIframe = mw.app.canvas.getWindow();
        const liveEditIframeDocument = mw.app.canvas.getDocument();

        if (liveEditIframeDocument && liveEditIframe && liveEditIframe.tinyMCE) {
            const mwTinymceEditor = liveEditIframe.tinyMCE;
            // Initialize the new editor directly on the existing contenteditable element
            mwTinymceEditor.init(this.config).then((initializedEditor) => {
                this.editor = initializedEditor;
                 this.setCursorPos(this.savedCursorPosition,element);
              // mwTinymceEditor.activeEditor.focus();
            }).catch((error) => {
                console.error('Error initializing TinyMCE:', error);
            });
        }
    }

    getCursorPos(element) {
        let offset = CursorPosition.getCurrentCursorPosition(element);

        return offset;
    }

    setCursorPos(offset,element ) {
        CursorPosition.setCurrentCursorPosition(offset,element);

    }
}
