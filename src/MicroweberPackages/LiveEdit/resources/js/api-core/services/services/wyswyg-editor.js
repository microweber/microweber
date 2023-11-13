import MicroweberBaseClass from "../containers/base-class.js";

export class WyswygEditor extends MicroweberBaseClass {
    constructor() {
        super();

        this.editor = null;


    }

 async   initEditor() {

        var liveEditIframe = (mw.app.canvas.getWindow());
        if(liveEditIframe && liveEditIframe.tinyMCE) {
            var mwTinymceEditor = liveEditIframe.tinyMCE;

            this.editor =  await mwTinymceEditor.init({
                // plugins: [
                //     'image',
                //     'link',
                //     'lists',
                //     'autolink'
                // ],
                selector: '.edit',
                skin: 'noskin',
             //   theme: 'notheme',
                editable_root: false,
                editable_class: '.edit',

                media_live_embeds: false,
                paste_as_text: false,
                paste_auto_cleanup_on_paste: true,
                paste_convert_headers_to_strong: false,
                toolbar: false, // Disable toolbar in headless mode
                keep_styles: true,

                promotion: false,
                statusbar: false,
                menubar: false,
                forced_root_block: false,
                default_link_target: "_blank",
                link_assume_external_targets: true,
                powerpaste_word_import: 'clean',
                powerpaste_html_import: 'clean',
                valid_elements: '*[*],script,style',
                extended_valid_elements : 'script[*],style[*],a[*]',

                inline: true,

                setup: function (editor) {
                    editor.on('paste_preprocess', function (e) {
                        // Remove style tags
                        e.content = e.content.replace(/<\s*style[\s\S]*?<\s*\/\s*style\s*>/gi, '');

                        // Remove inline style attributes
                        e.content = e.content.replace(/ style=".*?"/gi, '');
                    });

                    editor.on('keydown', function (e) {
                        if (e.key === 'Backspace' || e.key === 'Delete') {
                            const cursorNode = editor.selection.getNode();
                            if (cursorNode && (cursorNode.classList.contains('safe-mode') || cursorNode.closest('.safe-mode'))) {
                                if (!cursorNode.innerText.trim()) {
                                    e.preventDefault();
                                }
                            }
                        }
                    });
                }
            });
        }
    }
   showEditorOnElement(element, options) {

       var liveEditIframe = (mw.app.canvas.getWindow());
     //  tinymce.remove("div.editable");
       if(liveEditIframe && liveEditIframe.tinyMCE) {
            var mwTinymceEditor = liveEditIframe.tinyMCE;
          ///  mwTinymceEditor.remove();
if(!this.editor) {
    this.initEditor()
}

           // alert('showEditorOnElement')
           // liveEditIframe.$(element).tinymce().remove();
console.log('showEditorOnElement')
console.log(mwTinymceEditor)
console.log(liveEditIframe)
console.log(element)
       }
//


   }


}
