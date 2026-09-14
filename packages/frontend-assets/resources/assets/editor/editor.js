import { DomService } from "../api-core/core/classes/dom.js";


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

    // task-2026-09-14-editor-minimal — compact floating text toolbar.
    // Primary pill mirrors the design: Heading dropdown · B I U S · link ·
    // align · color · ✦ Rewrite · ⋯. EVERY other control moves into the "⋯"
    // overflow group so the bar stays a small pill; nothing is removed, just
    // relocated. Strings render inline; { group: { icon, controls } } renders a
    // flyout menu (see MWEditor.addControllerGroup).
    const moreMenuIcon =
        '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M16,12A2,2 0 0,1 18,10A2,2 0 0,1 20,12A2,2 0 0,1 18,14A2,2 0 0,1 16,12M10,12A2,2 0 0,1 12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12M4,12A2,2 0 0,1 6,10A2,2 0 0,1 8,12A2,2 0 0,1 6,14A2,2 0 0,1 4,12Z" /></svg>';

    const editorControls = [
        [
            // Heading / paragraph block format ("Heading 1 ▾").
            'format',

            // Inline text styles — shown as separate buttons.
            'bold',
            'italic',
            'underline',
            'strikeThrough',

            // Link.
            'link',

            // Text alignment — single button, alignment options in its flyout.
            {
                group: {
                    controller: 'alignLeft',
                    controls: ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify']
                }
            },

            // Text color (the swatch button).
            'textColor',

            // ✦ Rewrite (AI).
            'ai',

            // Pin / unpin the toolbar — kept on the main bar.
            'pin',

            // "⋯" overflow — everything else, in a compact dropdown menu.
            {
                group: {
                    className: 'mw-editor-overflow-menu',
                    icon: moreMenuIcon,
                    controls: [
                        'fontSize',
                        'lineHeight',
                        'fontSelector',
                        'ul',
                        'ol',
                        'image',
                        'table',
                        'unlink',
                        'textBackgroundColor',
                        'textEffectClassApplier',
                        'removeFormat',
                        'plus',
                        // task-2026-09-07-elementmenu — "⋮" element action menu
                        // (Duplicate / Edit styles / Delete).
                        'elementMore',
                        'backToElementSettings',
                    ]
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
        smallEditorSkin: (function() {
            try {
                var topHtml = mw.top().document.documentElement;
                if (topHtml && topHtml.classList.contains('dark')) return 'dark';
            } catch(e) {}
            return 'light';
        })(),

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

    try {
        var topHtml = mw.top().document.documentElement;
        if (topHtml && typeof liveEditor.setSmallEditorSkin === 'function') {
            var _editorThemeObserver = new MutationObserver(function() {
                var isDark = topHtml.classList.contains('dark');
                var currentSkin = liveEditor.settings.smallEditorSkin;
                var newSkin = isDark ? 'dark' : 'light';
                if (currentSkin !== newSkin) {
                    liveEditor.setSmallEditorSkin(newSkin);
                }
            });
            _editorThemeObserver.observe(topHtml, { attributes: true, attributeFilter: ['class'] });
        }
    } catch(e) {}
};



