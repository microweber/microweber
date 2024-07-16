import BaseComponent from "../../js/containers/base-class";

//
//  moved to meta tags in src/MicroweberPackages/MetaTags/Entities/AdminFilamentJsLibsScriptTag.php
//  import tinymce from "tinymce";
// import 'tinymce/themes/silver/theme'
//
//
// import 'tinymce/models/dom';
// import 'tinymce/icons/default/icons';
//
// import 'tinymce/plugins/autoresize/plugin';

// moved to userfiles/modules/microweber/api/libs/tinymce/plugins/mwlink/plugin.min.js
// const MWLink = editor => {
//
//
//     editor.ui.registry.addButton('mwLink', {
//         icon: '<svg viewBox="0 0 24 24"> <path fill="currentColor" d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z" /></svg>',
//         icon: 'link',
//         onAction: (_) => {
//
//             var linkEditor = new mw.LinkEditor({
//                 mode: 'dialog',
//                 hideTextFied: true
//             });
//
//
//             linkEditor.promise().then(function (data) {
//                 var modal = linkEditor.dialog;
//                 if (data) {
//
//                     editor.execCommand('CreateLink', false, data.url);
//                     modal.remove();
//                 } else {
//                     modal.remove();
//                 }
//             });
//         }
//     });
//
//
// }


export class RichTextEditor extends BaseComponent {
    constructor(options = {}) {
        super();
        this.#config(options);

        // moved to plugins/mwlink/plugin.min.js
        //
        // this.on('setup', editor => {
        //     MWLink(editor);
        // });

        this.init();
    }


    #config(options) {
        if (typeof options.target === 'string') {
            options.selector = options.target;
        }
        if (typeof options.selector === 'object') {
            options.target = options.selector;
        }
        this.settings = Object.assign({}, this.#defaultOptions(), options);
        console.log(this.settings)
    }

    #defaultOptions() {
        return {
            base_url: mw.settings.libs_url + 'tinymce/',
            document_base_url: mw.settings.site_url,
            relative_urls: false,
            remove_script_host: false,


            cache_suffix: '?v=1',
            target: null,
            inline: false,
            promotion: false,
            element_format: 'xhtml',
            extended_valid_elements: 'div[*],module[*]',


            statusbar: false,

            menubar: 'edit insert view format table tools',
            noneditable_class: 'module',
            /* plugins: [
                 'noneditable',
                 'advlist', 'lists', 'mwLink', 'image', 'charmap', 'preview',
                 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                 'media', 'table', 'help', 'wordcount'
             ],*/
            plugins: [
                "advlist", "autolink", "lists", "mwlink", "link", "image", "charmap",   "preview", "anchor",
                "searchreplace", "visualblocks", "code", "insertdatetime", "media", "table"
            ],


            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor backcolor | mwlink unlink  | alignleft aligncenter ' +
                'alignright alignjustify | fontfamily fontsizeinput | bullist numlist outdent indent | ' +
                'removeformat ',
            init_instance_callback: editor => {
                editor.on('Change Undo Redo', (e) => {
                    this.dispatch('change', tinymce.activeEditor.getContent());
                    // this.registerChange(tinymce.activeEditor.getContent())
                });
            },
            setup: editor => {
                this.dispatch("setup", editor);
            }

        }
    }

    init() {
        tinymce.init(this.settings);
    }
}
