import MicroweberBaseClass from "../containers/base-class.js";
import CursorPosition from "../../core/libs/cursor-position.js";

// deprecated
export class WyswygEditor extends MicroweberBaseClass {
    constructor() {
        super();
        this.editor = null;
        this.savedCursorPosition = null;

        this.config = {
            selector: '[contenteditable="true"]',
        //   editor_deselector : /(NoEditor|NoRichText|module)/,
            noneditable_class: 'module',
            skin: 'noskin',
            editable_root: false,
            //forced_root_block: '', deprecateds
            // forced_root_block: false,
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
            inline_styles : true,

            typeahead_urls: false,
            resize_img_proportional: false,
            allow_unsafe_link_target: true,
            format_empty_lines: false,
            strict_loading_mode: false,
            relative_urls: false,
            remove_script_host: false,
            inline_boundaries: false,
               convert_urls: false,
            convert_fonts_to_spans: false,
            convert_fonts: false,
            convert_urls_to_links: false,
            element_format : 'html',
            fix_list_elements : false,


        //    init_content_sync: 'true',
            model: 'dom',

            plugins: 'mwtinymce',



            cleanup: false,
            // valid_elements: '*[*],script[*],style[*]',
            // valid_children: '*[*],script[*],style[*]',
            // custom_elements: '*[*],script[*],style[*]',
            // extended_valid_elements: '*[*],script[*],style[*],a[*]',

            valid_elements: '*[*],script[*],style[*],+*[*]',
         valid_children: '*[*],script[*],style[*]',
      //    valid_children: '*[*],script[*],style[*],+*[*], +*[*], -*[*]',

            // all html elements are valid
       //     custom_elements: 'style,script,a,button,select,option,textarea,input,form,fieldset,legend,iframe,object,embed,video,source,track,audio,canvas,svg,math,del,ins,article,section,nav,aside,hgroup,header,footer,address,main,p,h1,h2,h3,h4,h5,h6,hr,pre,blockquote,ol,ul,li,dl,dt,dd,figure,figcaption,div,table,caption,thead,tbody,tfoot,tr,th,td,em,strong,s,cite,q,dfn,abbr,data,time,var,kbd,samp,sub,sup,i,b,u,mark,ruby,rt,rp,bdi,bdo,span,br,wbr,small,big,tt,object,param,map,area,script,style,link,meta,title,base,basefont,head,html,body,frameset,frame,noembed,noframes,font,ins,del,strike,center,acronym,applet,isindex,dir,menu,xmp,listing,plaintext,button,textarea,select,option,optgroup,label,keygen,output,datalist,progress,meter,details,summary,command,menuitem,legend,fieldset,figure,figcaption,abbr,acronym,address,applet,area,article,aside,audio,b,base,basefont,bdi,bdo,bgsound,big,blink,blockquote,body,br,button,canvas,caption,center,cite,code,col,colgroup,command,comment,dd,del,details,dfn,dir,div,dl,dt,em,embed,fieldset,figcaption,figure,font,footer,form,frame,frameset,h1,h2,h3,h4,h5,h6,head,header,hr,html,i,iframe,img,input,ins,kbd,keygen,label,legend,li,link,listing,map,mark,marquee,menu,meta,meter,nav,nobr,noembed,noframes,noscript,object,ol,optgroup,option,output,p,param,plaintext,pre,progress,q,rp,rt,ruby,s,samp,script,section,select,small,source,spacer,span,strike,strong,style,sub,summary,sup,table,tbody,td,textarea,tfoot,th,thead,time,title,tr,tt,u,ul,var,video,wbr,xmp',
         //   custom_elements: 'script,style',
        //    custom_elements: '*,*[*],script[*],style[*]',
            custom_elements: '*',
          //  custom_elements: '+*[*], +*[*], -*[*]',
            extended_valid_elements: '*[*],script[*],style[*],a[*]',

            invalid_elements: 'none',

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
                    // if (e.key === 'Backspace' || e.key === 'Delete') {
                    //     const cursorNode = editor.selection.getNode();
                    //     if (cursorNode && (cursorNode.classList.contains('safe-mode') || cursorNode.closest('.safe-mode'))) {
                    //         if (!cursorNode.innerText.trim()) {
                    // does not work on enter on empty lines
                    //             e.preventDefault();
                    //         }
                    //     }
                    // }
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

            // remove all active editors
      //      mwTinymceEditor.remove();

            try {
                // Initialize the new editor directly on the existing contenteditable element
                mwTinymceEditor.init(this.config).then((initializedEditor) => {
                    this.editor = initializedEditor;
                    this.setCursorPos(this.savedCursorPosition,element);
                    // mwTinymceEditor.activeEditor.focus();
                }).catch((error) => {
                    if(console && console.log) {
                        console.log('Error initializing TinyMCE:', error);
                    }
                });
            } catch (e) {
                if(console && console.log) {
                    console.log('Error initializing TinyMCE promise:', error);
                }
            }





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
