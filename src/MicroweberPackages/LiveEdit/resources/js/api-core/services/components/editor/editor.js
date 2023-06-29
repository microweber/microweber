
 mw.require('editor.js');
mw.require('css_parser.js');
export const EditorComponent = function () {
    var holder = document.querySelector('#mw-live-edit-editor');

    var _fontFamilyProvider = function () {
        var _e = {};
        this.on = function (e, f) {
            _e[e] ? _e[e].push(f) : (_e[e] = [f])
        };
        this.dispatch = function (e, f) {
            _e[e] ? _e[e].forEach(function (c) {
                c.call(this, f);
            }) : '';
        };

        this.provide = function (fontsArray) {
            this.dispatch('change', fontsArray.map(function (font) {
                return {
                    label: font,
                    value: font,
                }
            }))
        }

    };



    var fontFamilyProvider = new _fontFamilyProvider();
    window.fontFamilyProvider = fontFamilyProvider;
    const frame = mw.app.get('canvas').getFrame();
    frame.contentWindow.fontFamilyProvider = fontFamilyProvider;


    const editorControls = [
        [

            'undoRedo',

            {
                group: {
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5,4V7H10.5V19H13.5V7H19V4H5Z" /></svg>',
                    controls: ['format', 'lineHeight']
                }
            },

            {
                group: {
                    controller: 'bold',
                    controls: ['italic', 'underline', 'strikeThrough', 'removeFormat']
                }
            },
            'fontSelector',

            'fontSize',


            {
                group: {
                    controller: 'alignLeft',
                    controls: ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify']
                }
            },

            {
                group: {
                    controller: 'ul',
                    controls: ['ol']
                }
            },


            'image',
            {
                group: {
                    controller: 'link',
                    controls: ['unlink']
                }
            },
            {
                group: {
                    controller: 'textColor',
                    controls: ['textBackgroundColor']
                }
            },


        ]
    ];


    const liveEditor = mw.Editor({
        document: frame.contentWindow.document,
        executionDocument: frame.contentWindow.document,
        actionWindow: frame.contentWindow,
        element: holder,
        mode: 'document',
        notEditableClasses: ['module'],
        regions: '.edit',
        skin: 'le2',
        editMode: 'liveedit',

        controls: null,
        smallEditor: editorControls,

        smallEditorPositionX: 'left',
        smallEditorSkin: 'lite',

        interactionControls: [],

        id: 'live-edit-wysiwyg-editor',

        minHeight: 250,
        maxHeight: '70vh',
        state: mw.liveEditState,

        fontFamilyProvider: fontFamilyProvider
    });

 

    holder.innerHTML = '';
    holder.appendChild(liveEditor.wrapper);


    var memPin = liveEditor.storage.get(liveEditor.settings.id + '-small-editor-pinned');
    if (typeof memPin === 'undefined' && typeof liveEditor.smallEditorApi !== 'undefined') {
        liveEditor.smallEditorApi.pin()
    }
    mw.app.register('richTextEditor', liveEditor);

    mw.app.register('richTextEditorAPI', liveEditor.api);
};



