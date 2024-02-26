import { DomService } from "../../../core/classes/dom";

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
    const frame = mw.app.canvas.getFrame();
    frame.contentWindow.fontFamilyProvider = fontFamilyProvider;

    const editorControls = [
        [


            'plus',
            'ai',
            {
                group: {
                    icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5,4V7H10.5V19H13.5V7H19V4H5Z" /></svg>',
                    controls: [
                        'lineHeight',
                        'fontSelector',
                        ,
                    ]
                }
            },



            {
                group: {
                    controller: 'bold',
                    controls: ['italic', 'underline', 'strikeThrough']
                }
            },
            'format',
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
            'table',

            'removeFormat',
            'pin',
            'backToElementSettings',

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

        interactionControls: ['tableManager', 'linkTooltip'],

        id: 'live-edit-wysiwyg-editor',

        minHeight: 250,
        maxHeight: '70vh',
        state: mw.liveEditState,

        fontFamilyProvider: fontFamilyProvider,
        forced: true,
        canPin: true,
    });

    $(liveEditor).on('change', function(e, html){
        const node = mw.top().app.richTextEditorAPI.elementNode(mw.top().app.richTextEditorAPI.getSelection().focusNode);
        const edit = DomService.firstParentOrCurrentWithClass(node, 'edit');
        if(edit) {
            edit.classList.add('changed')
        }
    });


    frame.contentWindow.document.body.addEventListener('beforeinput', e => {
        let sel = liveEditor.api.getSelection();
        let focusNode = mw.top().app.richTextEditorAPI.elementNode(sel.focusNode);
        var isModification = /* e.inputType.includes('insert') || */ e.inputType.includes('delete');
        if(sel.type === 'Range'  ) {
            if(  isModification) {
                const anchorNode = mw.top().app.richTextEditorAPI.elementNode(sel.anchorNode);
                focusNode = mw.top().app.richTextEditorAPI.elementNode(sel.focusNode);
                if(anchorNode !== focusNode ) {
                    setTimeout(() => {
                        sel = liveEditor.api.getSelection();
                        focusNode = mw.top().app.richTextEditorAPI.elementNode(sel.focusNode);
                        if(focusNode.nextSibling && focusNode.nextSibling.nodeType === 3) {
                            focusNode.appendChild(focusNode.nextSibling)
                        }
                    });
                }
            }
            setTimeout(() => {

                var all =  mw.top().app.richTextEditorAPI.elementNode(mw.top().app.richTextEditorAPI.getSelection().focusNode).parentNode.querySelectorAll('*[style*="var"]');


                all.forEach(node => {
                    if (node.style) {
                        if (node.isContentEditable) {
                            [...node.style].filter(prop => node.style[prop].includes('var(')).forEach(prop => node.style.removeProperty(prop))
                        }
                    }
                });

            }, 1)

        }


    })


    holder.innerHTML = '';
    holder.appendChild(liveEditor.wrapper);


    var memPin = liveEditor.storage.get(liveEditor.settings.id + '-small-editor-pinned');
    if (typeof memPin === 'undefined' && typeof liveEditor.smallEditorApi !== 'undefined') {
        liveEditor.smallEditorApi.pin();
    }
    mw.app.register('richTextEditor', liveEditor);

    mw.app.register('richTextEditorAPI', liveEditor.api);
};



