
MWEditor.controllers = {
    alignLeft: function (scope, api, rootScope) {
        var el;
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    id: 'mw-editor-state-component-align-left',
                    tooltip: rootScope.lang('Align left'),
                    innerHTML: '<svg  viewBox="0 0 24 24"><path fill="currentColor" d="M3,3H21V5H3V3M3,7H15V9H3V7M3,11H21V13H3V11M3,15H15V17H3V15M3,19H21V21H3V19Z" /></svg>'
                }
            });


            el.on('mousedown touchstart', function (e) {
                return api.textAlign('left');


            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'left' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    alignCenter: function (scope, api, rootScope) {



        var el
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    id: 'mw-editor-state-component-align-center',

                    tooltip: rootScope.lang('Align center'),
                    innerHTML: '<svg  viewBox="0 0 24 24"><path fill="currentColor" d="M3,3H21V5H3V3M7,7H17V9H7V7M3,11H21V13H3V11M7,15H17V17H7V15M3,19H21V21H3V19Z" /></svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                return api.textAlign('center');

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'center' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    alignRight: function (scope, api, rootScope) {




        var el
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    id: 'mw-editor-state-component-align-right',

                    tooltip: rootScope.lang('Align right'),
                    innerHTML: '<svg  viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3,3H21V5H3V3M9,7H21V9H9V7M3,11H21V13H3V11M9,15H21V17H9V15M3,19H21V21H3V19Z" />\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                return api.textAlign('right');

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'right' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    alignJustify: function (scope, api, rootScope) {



        var el;
        this.render = function () {
            var scope = this;
            el = MWEditor.core.button({
                props: {
                    id: 'mw-editor-state-component-align-justify',
                    tooltip: rootScope.lang('Justify'),
                    innerHTML: '<svg   viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3,3H21V5H3V3M3,7H21V9H3V7M3,11H21V13H3V11M3,15H21V17H3V15M3,19H21V21H3V19Z" />\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {
                return api.textAlign('justify');

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            rootScope.controllerActive(el.get(0), 'justify' === opt.css.alignNormalize());
        };
        this.element = this.render();
    },
    align: function (scope, api, rootScope) {
        this.root = MWEditor.core.element();
        this.root.$node.addClass('mw-editor-state-component mw-editor-state-component-align');
        this.buttons = [];

        var arr = [
            {align: 'left',
                icon: '<svg  viewBox="0 0 24 24"><path fill="currentColor" d="M3,3H21V5H3V3M3,7H15V9H3V7M3,11H21V13H3V11M3,15H15V17H3V15M3,19H21V21H3V19Z" /></svg>', action: 'left'},
            {align: 'center', icon: '<svg  viewBox="0 0 24 24">\n' +
                    '    <path fill="currentColor" d="M3,3H21V5H3V3M7,7H17V9H7V7M3,11H21V13H3V11M7,15H17V17H7V15M3,19H21V21H3V19Z" />\n' +
                    '</svg>', action: 'center'},
            {align: 'right', icon: '<svg  viewBox="0 0 24 24">\n' +
                    '    <path fill="currentColor" d="M3,3H21V5H3V3M9,7H21V9H9V7M3,11H21V13H3V11M9,15H21V17H9V15M3,19H21V21H3V19Z" />\n' +
                    '</svg>', action: 'right'},
            {align: 'justify', icon: '<svg   viewBox="0 0 24 24">\n' +
                    '    <path fill="currentColor" d="M3,3H21V5H3V3M3,7H21V9H3V7M3,11H21V13H3V11M3,15H21V17H3V15M3,19H21V21H3V19Z" />\n' +
                    '</svg>', action: 'justify'}
        ];
        this.render = function () {
            var scope = this;
            arr.forEach(function (item) {
                var el = MWEditor.core.button({
                    props: {
                        id: 'mw-editor-state-component-align-' + item.align,

                        innerHTML:  item.icon
                    }
                });

                var className = 'mw-editor-state-component-align-' + item.align;




                el.addClass(className);
                el.on('mousedown touchstart', function (e) {

                    return api.textAlign(item.action);


                });
                scope.root.append(el);
                scope.buttons.push(el);
            });
            return scope.root;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element, !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

            var align = opt.css.alignNormalize();
            for (var i = 0; i< this.buttons.length; i++) {
                var state = arr[i].align === align;
                rootScope.controllerActive(this.buttons[i].get(0), state);
            }
        };
        this.element = this.render();
    },
    backToElementSettings: function (scope, api, rootScope) {

        this.target = null;

        this.render = function () {
            var iconSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m336-280-56-56 144-144-144-143 56-56 144 144 143-144 56 56-144 143 144 144-56 56-143-144-144 144Z"/></svg>';
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    id: 'mw-back-to-element-settings-editor-button',
                    tooltip: rootScope.lang('Close text editor'),
                    innerHTML: iconSVG
                }
            });

            el.on('mousedown touchstart', e => {
                var isInLiveEdit = false;
                if (mw.top().app && mw.top().app.canvas) {
                    isInLiveEdit = mw.top().app.canvas.getWindow();
                }

                if (isInLiveEdit && this.target) {
                    mw.top().win.mw.app.liveEdit.handles.get('element').set(this.target);
                    setTimeout(() => {
                        const target = mw.top().app.liveEdit.handles.get('element').getTarget();

                        mw.top().app.liveEdit.selectNode(target)
                        mw.top().app.richTextEditor?.smallEditor?.hide();
                        mw.top().app.liveEdit.play();
                        mw.top().app.liveEdit.stopTyping();

                        mw.app.canvas.getDocument().querySelectorAll('[contenteditable]').forEach(node => node.contentEditable = false);
                        //      mw.top().app.liveEdit.handles.get('element').set(target);
                    }, 100);
                }
            });

            return el;
        };
        this.checkSelection = function (opt, ee, tt) {
            var allowed = false;
            var sel = api.getSelection();
            var focusNode = sel.focusNode;
            if (focusNode) {
                var elementNode = api.elementNode(focusNode)
                if (elementNode && mw.top().app.liveEdit) {
                    elementNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(elementNode, ['edit', 'element']);
                    allowed = !!elementNode;
                }
            }
            if (!allowed) {
                rootScope.hide(opt.controller.element.get(0), true);
                this.target = null;
            } else {
                rootScope.show(opt.controller.element.get(0));
                this.target = elementNode;
            }
        };
        this.element = this.render();
    },
    plus: function (scope, api, rootScope) {

        this.target = null;

        this.render = function () {
            var plusIconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M445.935-195.935v-250h-250v-68.13h250v-250h68.13v250h250v68.13h-250v250h-68.13Z"/></svg>';
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    id: 'mw-insert-module-editor-button',
                    tooltip: rootScope.lang('Insert Module'),
                    innerHTML: plusIconSVG
                }
            });

            el.on('mousedown touchstart', e => {
                var isInLiveEdit = false;
                if(mw.top().app && mw.top().app.canvas) {
                    isInLiveEdit = mw.top().app.canvas.getWindow();
                }

                if (this.target) {


                    mw.app.editor.dispatch('insertModuleRequest', this.target);
                    mw.top().app.liveEdit.stopTyping();
                    // setTimeout(() => {
                    //   mw.top().app.liveEdit.handles.hide();
                    //
                    //     mw.top().app.liveEdit.pause();
                    // },100);
                    mw.top().win.mw.app.liveEdit.handles.get('element').set(this.target)

                }
            });

            return el;
        };
        this.checkSelection = function (opt, ee, tt) {
            var allowed = false;
            var sel = api.getSelection();
            var focusNode = sel.focusNode;
            if (focusNode) {
                var elementNode = api.elementNode(focusNode)
                if (elementNode && mw.top().app.liveEdit) {
                    elementNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(elementNode, ['edit', 'element']);
                    allowed = !!elementNode && mw.top().app.liveEdit.elementHandleContent.settingsTarget.canDropInTarget(elementNode);

                }
            }
            if (!allowed) {
                rootScope.hide(opt.controller.element.get(0), true);
                this.target = null;
            } else {
                rootScope.show(opt.controller.element.get(0));
                this.target = elementNode;
            }
        };
        this.element = this.render();
    },
    ai: function (scope, api, rootScope) {
        this.render = function () {
            var aiIconSVG = '<svg color="gray.100" fill="currentColor" height="22" viewBox="0 0 22 22" width="22" xmlns="http://www.w3.org/2000/svg" class="ai-writer-container-1fy6kej"><path d="M12 0h-1L5 14h5v8h1l6-14h-5V0z"></path></svg>';
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    tooltip: rootScope.lang('AI Text Generator'),
                    innerHTML: aiIconSVG
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var actionTarget = mw.tools.firstBlockLikeLevel(node);

                var buttonsRewrite = [

                    {text: 'Simplify', instruction: 'Simplify'},
                    {text: 'Paraphrase', instruction: 'Paraphrase'},
                    {text: 'Lengthen', instruction: 'Lengthen'},
                    {text: 'Summarize', instruction: 'Summarize'},
                    {text: 'Fix Spelling and Grammar', instruction: 'Fix spelling and grammar',},


                ];
                var buttonsTone = [
                    {text: 'Friendly', instruction: 'Make the text more friendly'},
                    {text: 'Casual', instruction: 'Make the text sound casual'},
                    {text: 'Natural', instruction: 'Make the text sound natural'},
                    {text: 'Friendly', instruction: 'Make the text sound friendly'},
                    {text: 'Gentle', instruction: 'Make the text sound gentle'},
                ]

                var aiTextGeneratorHtml = `
                <div class="ai-text-generator-container">
                    <div class="d-flex gap-2">
                        <input placeholder="Write instructions. e.g.: Make it sound casual" autocomplete="off" class="form-control" id="ai-text-generator-topic" />
                        <button class="btn" id="ai-text-generator-submit" type="button"> ${aiIconSVG} Generate </button>
                    </div>
                    <div class="mt-2" id="ai-text-generator-options">
                        <div class="list-group">
                        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3" role="tablist">
                            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs" role="tab" tabindex="-1">${mw.lang('Rewrite')}</a>
                            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs" role="tab" tabindex="-1">${mw.lang('Tone')}</a>
                        </nav>

                        <div class="mw-aiwriter-tab">
                        ${buttonsRewrite.map(obj => `
                            ${ `<button type="button" data-instruction="${obj.instruction}" class="list-group-item list-group-item-action">${obj.text}</button>`}
                        `)
                        .join('')}
                        </div>
                        <div class="mw-aiwriter-tab">
                        ${buttonsTone.map(obj => `
                            ${ `<button type="button" data-instruction="${obj.instruction}" class="list-group-item list-group-item-action">${obj.text}</button>`}
                        `)
                        .join('')}
                        </div>
                        </div>

                        </div>
                    </div>
                </div>`;



                var rectActionTarget = el.node.getBoundingClientRect();

                var aiTextAutocompleteDialog = mw.dialog({
                    content: aiTextGeneratorHtml,
                    overlay: true,
                    overlayClose: true,
                    autoCenter: false,
                    width: 500,
                    /*position: {
                        x: rectActionTarget.left,
                        y: (rectActionTarget.top + 110)
                    }*/
                });
                aiTextAutocompleteDialog.dialogHeader.style.display = 'none';
                aiTextAutocompleteDialog.dialogContainer.style.padding = '5px';

                setTimeout(function() {
                    document.querySelectorAll('#ai-text-generator-options button[data-instruction]').forEach(function(node){
                        node.addEventListener('click', function () {
                            document.getElementById('ai-text-generator-options').style.display = 'none';
                            document.getElementById('ai-text-generator-topic').value = this.dataset.instruction;
                            $('#ai-text-generator-submit').click()
                        });
                    });

                    mw.tabs({
                        nav: aiTextAutocompleteDialog.dialogContainer.querySelectorAll('.mw-adm-liveedit-tabs'),
                        tabs: aiTextAutocompleteDialog.dialogContainer.querySelectorAll('.mw-aiwriter-tab'),
                    });
                    document.getElementById('ai-text-generator-topic').focus()
                    document.getElementById('ai-text-generator-topic').addEventListener('keyup', function(e){
                        if(e.keyCode === 13) {
                            $('#ai-text-generator-submit').click()
                        }
                    })


                }, 100);

                document.getElementById('ai-text-generator-submit').onclick = function () {

                    document.getElementById('ai-text-generator-submit').innerHTML = 'Generating...';

                    mw.spinner({element:  document.getElementById('ai-text-generator-submit'), decorate: true, size: 25});

                    var instruction = document.getElementById('ai-text-generator-topic').value;


                    var mwAdapter = {
                        url: 'https://textcomplete.microweberapi.com/?q=' + encodeURIComponent(actionTarget.textContent) + '&instruction=' + encodeURIComponent(instruction),
                        method: 'GET',
                    };




                    fetch(mwAdapter.url, mwAdapter).then(function (res) {
                        res.json().then(function (json) {
                          //  actionTarget.innerHTML = actionTarget.innerHTML + ' ' + json.text;
                            actionTarget.innerHTML = json.text;
                            aiTextAutocompleteDialog.remove();
                            mw.app.registerUndoState(actionTarget)
                        })
                    });


                };

            });
            return el;
        };
        this.checkSelection = function (opt, ee, tt) {


        };
        this.element = this.render();
    },
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {

                    tooltip: rootScope.lang('Bold'),
                    innerHTML: '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path d="m13.5 15.5h-3.5v-3h3.5a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5m-3.5-9h3a1.5 1.5 0 0 1 1.5 1.5 1.5 1.5 0 0 1-1.5 1.5h-3m5.6 1.29c0.97-0.68 1.65-1.79 1.65-2.79 0-2.26-1.75-4-4-4h-6.25v14h7.04c2.1 0 3.71-1.7 3.71-3.79 0-1.52-0.86-2.82-2.15-3.42z" fill="currentColor"/>\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart',  async function (e) {
                // if (mw.top().app && mw.top().app.canvas) {
                //     var liveEditIframe = (mw.app.canvas.getWindow());
                //
                //     if (liveEditIframe && liveEditIframe.tinyMCE) {
                //         // set by tinyMCE
                //         var editor = liveEditIframe.tinyMCE.activeEditor;
                //         if (editor) {
                //             // Execute the bold command
                //             editor.execCommand('Bold');
                //         }
                //         return;
                //     }
                //
                // }

                // api.execCommand('bold');
                //
                // return;

                var sel = api.getSelection();





                if(sel.getRangeAt(0).collapsed) {
                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = node;//mw.tools.firstBlockLikeLevel(node);
                    api.action(actionTarget.parentNode, function () {
                        var isBold = Number(rootScope.actionWindow.getComputedStyle(actionTarget).fontWeight) > 400;
                        api.cleanStyle('font-weight')
                        let weight;
                        if(isBold) {
                            weight = '400';
                        } else {
                            weight = '700';
                        }


                        if(rootScope.settings.editMode === 'liveedit') {
                            mw.top().app.cssEditor.temp(actionTarget, 'font-weight', weight);
                        } else {



                            const edit = mw.tools.firstParentOrCurrentWithClass(node, 'edit') || (rootScope.$editArea ? rootScope.$editArea[0] : false);

                            rootScope.state.record({
                                target: edit,
                                value: edit.innerHTML
                            });
                            if(rootScope.editArea === actionTarget) {

                                var newBlock = document.createElement('span');
                                var getFocusedNeighbours = api.getFocusedNeighbours(sel.focusNode);
                                sel.focusNode.after(newBlock);
                                getFocusedNeighbours.forEach(el => newBlock.appendChild(el));
                                newBlock.style.fontWeight = weight;
                                newBlock.querySelectorAll('*').forEach(n => n.style.fontWeight = weight)

                                rootScope.api.setCursorAtStart(newBlock);
                            } else {
                                actionTarget.style.fontWeight = weight;
                            }

                            rootScope.state.record({
                                target: edit,
                                value: edit.innerHTML
                            });
                        }

                    });
                } else {
                    api.cleanStyle('font-weight')
                    api.execCommand('bold');
                }

            });
            return el;
        };
        this.checkSelection = function (opt, ee, tt) {

            if(opt.css.is().bold) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }


            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    strikeThrough: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24" style="transform: scale(0.93)"><path fill="currentColor" d="M23,12V14H18.61C19.61,16.14 19.56,22 12.38,22C4.05,22.05 4.37,15.5 4.37,15.5L8.34,15.55C8.37,18.92 11.5,18.92 12.12,18.88C12.76,18.83 15.15,18.84 15.34,16.5C15.42,15.41 14.32,14.58 13.12,14H1V12H23M19.41,7.89L15.43,7.86C15.43,7.86 15.6,5.09 12.15,5.08C8.7,5.06 9,7.28 9,7.56C9.04,7.84 9.34,9.22 12,9.88H5.71C5.71,9.88 2.22,3.15 10.74,2C19.45,0.8 19.43,7.91 19.41,7.89Z" /></svg>',
                    tooltip: rootScope.lang('Strike through')
                }
            });

            el.on('mousedown touchstart', function (e) {
                //
                // api.execCommand('strikeThrough');
                //
                // return;



                var sel = api.getSelection();

                if(sel.getRangeAt(0).collapsed) {
                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = node// mw.tools.firstBlockLikeLevel(node);
                    api.action(actionTarget.parentNode, function () {
                        var isStrike =  (rootScope.actionWindow.getComputedStyle(actionTarget).textDecoration).includes('line-through');
                        let textDecoration;
                        if(isStrike) {
                             textDecoration = 'none';
                        } else {
                             textDecoration = 'line-through';
                        }
                        if(rootScope.settings.editMode === 'liveedit') {
                            mw.top().app.cssEditor.temp(actionTarget, 'text-decoration', textDecoration);
                        } else {
                            rootScope.state.record({
                                target: rootScope.$editArea[0],
                                value: rootScope.$editArea[0].innerHTML
                            });
                            if(rootScope.editArea === actionTarget) {

                                var newBlock = document.createElement('span');
                                var getFocusedNeighbours = api.getFocusedNeighbours(sel.focusNode);
                                sel.focusNode.after(newBlock);
                                getFocusedNeighbours.forEach(el => newBlock.appendChild(el));
                                newBlock.style.textDecoration = textDecoration;
                                newBlock.querySelectorAll('*').forEach(n => n.style.textDecoration = '')

                                rootScope.api.setCursorAtStart(newBlock);
                            } else {
                                actionTarget.style.textDecoration = textDecoration;
                                actionTarget.querySelectorAll('*').forEach(n => n.style.textDecoration = '')
                            }

                            rootScope.state.record({
                                target: rootScope.$editArea[0],
                                value: rootScope.$editArea[0].innerHTML
                            });
                        }
                    });
                } else {
                    api.execCommand('strikeThrough');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().striked) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    italic: function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M10,4V7H12.21L8.79,15H6V18H14V15H11.79L15.21,7H18V4H10Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Italic')
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();

                // api.execCommand('italic');
                //
                // return;



                if(sel.getRangeAt(0).collapsed) {


                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = node//mw.tools.firstBlockLikeLevel(node);
                    api.action(actionTarget.parentNode, function () {
                        var isItalic = rootScope.actionWindow.getComputedStyle(actionTarget).fontStyle !== 'normal';
                        var fontStyle
                        if(isItalic) {
                            fontStyle = 'normal';
                        } else {
                            fontStyle = 'italic';
                        }
                        if(rootScope.settings.editMode === 'liveedit') {
                            mw.top().app.cssEditor.temp(actionTarget, 'font-style', fontStyle);
                        } else {
                            rootScope.state.record({
                                target: rootScope.$editArea[0],
                                value: rootScope.$editArea[0].innerHTML
                            });
                            if(rootScope.editArea === actionTarget) {

                                var newBlock = document.createElement('span');
                                var getFocusedNeighbours = api.getFocusedNeighbours(sel.focusNode);
                                sel.focusNode.after(newBlock);
                                getFocusedNeighbours.forEach(el => newBlock.appendChild(el));
                                newBlock.style.fontStyle = fontStyle;
                                newBlock.querySelectorAll('*').forEach(n => n.style.fontStyle = '')

                                rootScope.api.setCursorAtStart(newBlock);
                            } else {
                                actionTarget.style.fontStyle = fontStyle;
                                actionTarget.querySelectorAll('*').forEach(n => n.style.fontStyle = '')
                            }

                            rootScope.state.record({
                                target: rootScope.$editArea[0],
                                value: rootScope.$editArea[0].innerHTML
                            });
                        }



                    });
                } else {
                    api.execCommand('italic');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
            if(opt.css.is().italic) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }
            rootScope.disabled(opt.controller.element.get(0), !opt.rangeInEditor || opt.isPlainText );
        };
        this.element = this.render();
    },
    'underline': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M5,21H19V19H5V21M12,17A6,6 0 0,0 18,11V3H15.5V11A3.5,3.5 0 0,1 12,14.5A3.5,3.5 0 0,1 8.5,11V3H6V11A6,6 0 0,0 12,17Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Underline')
                }
            });
            el.on('mousedown touchstart', function (e) {

                // api.execCommand('underline');
                //
                // return;



                var sel = api.getSelection();
                if(sel.getRangeAt(0).collapsed) {


                    var node = api.elementNode(sel.focusNode);
                    var actionTarget = node// mw.tools.firstBlockLikeLevel(node)
                    api.action(actionTarget.parentNode, function () {
                        var isUnderline = rootScope.actionWindow.getComputedStyle(actionTarget).textDecoration.indexOf('underline') === 0;
                        var textDecoration
                        if(isUnderline) {
                            textDecoration = 'none';
                        } else {
                             textDecoration = 'underline';
                        }
                        if(rootScope.settings.editMode === 'liveedit') {
                            mw.top().app.cssEditor.temp(actionTarget, 'text-decoration', textDecoration);
                        } else {
                            rootScope.state.record({
                                target: rootScope.$editArea[0],
                                value: rootScope.$editArea[0].innerHTML
                            });
                            if(rootScope.editArea === actionTarget) {

                                var newBlock = document.createElement('span');
                                var getFocusedNeighbours = api.getFocusedNeighbours(sel.focusNode);
                                sel.focusNode.after(newBlock);
                                getFocusedNeighbours.forEach(el => newBlock.appendChild(el));
                                newBlock.style.textDecoration = textDecoration;
                                newBlock.querySelectorAll('*').forEach(n => n.style.textDecoration = '')

                                rootScope.api.setCursorAtStart(newBlock);
                            } else {
                                actionTarget.style.textDecoration = textDecoration;
                                actionTarget.querySelectorAll('*').forEach(n => n.style.textDecoration = '')
                            }

                            rootScope.state.record({
                                target: rootScope.$editArea[0],
                                value: rootScope.$editArea[0].innerHTML
                            });
                        }

                    });
                } else {
                    api.execCommand('underline');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));

            if(opt.css.is().underlined) {
                rootScope.controllerActive(opt.controller.element.get(0), true);
            } else {
                rootScope.controllerActive(opt.controller.element.get(0), false);
            }
            rootScope.disabled(opt.controller.element.get(0), !opt.rangeInEditor ||opt.isPlainText );
        };
        this.element = this.render();
    },
    'image': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M19,19H5V5H19M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M13.96,12.29L11.21,15.83L9.25,13.47L6.5,17H17.5L13.96,12.29Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Insert Image')
                }
            });
            el.on('click', function (e) {
                e.preventDefault();
                api.saveSelection();
                var dialog;

                var picker = new mw.filePicker({
                    accept: 'images',
                    label: false,
                    autoSelect: false,
                    multiple: true,
                    footer: true,
                    _frameMaxHeight: true,
                    cancel: function () {
                        dialog.remove()
                    },
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if(!url) return;
                        if(!Array.isArray(url)) {
                            url = [url];
                        }
                        api.restoreSelection();

                        if(rootScope.activeNode && rootScope.activeNode.nodeName === 'IMG') {
                            rootScope.activeNode.src = url[0].toString();
                        } else {
                            url.forEach(function (src){
                                api.insertImage(src.toString());
                            });
                        }

                        dialog.remove();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false,
                    width: 860
                });

            });

            return el;
        };
        this.checkSelection = function (opt) {

            const node = opt.api.elementNode(opt.api.getSelection().focusNode);

           //  const byTag = mw.tools.firstParentOrCurrentWithTag(node, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p']);

            let isDisabled = opt.isPlainText || !opt.api.isSelectionEditable(opt.selection) || (!opt.api.targetSupportsFormatting(node) && rootScope.settings.mode !== 'div');


            rootScope.disabled(opt.controller.element.get(0),  isDisabled);
        };
        this.element = this.render();
    },
    link: function(scope, api, rootScope){

        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Insert link')
                }
            });

            el.on('click', function (e) {
                api.saveSelection();
                var sel = scope.getSelection();

                var target = mw.tools.firstParentWithTag(sel.focusNode, 'a');

                var val;
                if(target) {
                    val = {
                        url: target.href,
                        text: target.innerHTML,
                        target: target.target === '_blank'
                    };
                } else if(!sel.isCollapsed) {

                    val = {
                        url: '',
                        text: api.getSelectionHTML(),
                        target: target.target === '_blank'
                    };
                }

                var linkEditor = new mw.LinkEditor({
                    mode: 'dialog',
                    hideTextFied: true
                });

                if(val) {
                    linkEditor.setValue(val);
                }

                linkEditor.promise().then(function (data){
                    var modal = linkEditor.dialog;
                    if(data) {
                        api.restoreSelection();
                        api.link(data);
                        modal.remove();
                    } else {
                        modal.remove();
                    }
                });


            });
            return el;
        };
        this.checkSelection = function (opt) {

            // console.log(opt.isPlainText , opt.api.isSelectionEditable(opt.selection) , opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection) );
            rootScope.controllerActive(opt.controller.element.get(0), !!mw.tools.firstParentOrCurrentWithTag(api.elementNode(api.getSelection().focusNode), 'a'));

        };
        this.element = this.render();
    },

    fontSize: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.size;

            if(opt.api.isCrossSelection()) {
                api.getSelectionChildren().filter(node => node.nodeName !== 'BR').forEach(node => {
                    if(getComputedStyle(node).fontSize != size) {

                        size = ' '
                    }
                })
            }

            opt.controller.element.displayValue(size);
            opt.controller.element.find('.mw-editor-dropdown-option.active').removeClass('active');
            opt.controller.element.find('.mw-editor-dropdown-option.active').removeClass('active');
            rootScope.disabled(opt.controller.element, opt.isPlainText || !opt.api.isSelectionEditable() || !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))
        };
        this.render = function () {


            var dropdown = new MWEditor.core.dropdown({
                customValue: true,

                data: [
                    { label: '8', value: 8 },
                    { label: '10', value: 10 },
                    { label: '12', value: 12 },
                    { label: '14', value: 14 },
                    { label: '16', value: 16 },
                    { label: '17', value: 17 },
                    { label: '20', value: 20 },
                    { label: '22', value: 22 },
                    { label: '24', value: 24 },
                    { label: '28', value: 28 },
                    { label: '32', value: 32 },
                    { label: '36', value: 36 },
                    { label: '42', value: 42 },
                    { label: '52', value: 52 },
                    { label: '62', value: 62 },
                    { label: '72', value: 72 },
                    { label: '82', value: 82 },
                    { label: '92', value: 92 },
                ],
                placeholder: rootScope.lang('Font Size')
            });
            dropdown.root.addClass('mw-editor-font-size-selector');
            dropdown.select.on('change', function (e, val) {

                if(val) {
                    scope.api.cleanStyle('font-size')
                    api.fontSize(val.value);
                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    lineHeight: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {


            var css = opt.css;
            var font = css.font();
            var val = Math.round((parseFloat(font.height) / parseFloat(font.size)) * 10) / 10;

            opt.controller.element.displayValue(val);
            rootScope.disabled(opt.controller.element, opt.isPlainText || !opt.api.isSelectionEditable() || !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: 'normal', value: 'normal' },
                    { label: '1', value:'1' },
                    { label: '1.1', value:'1.1' },
                    { label: '1.2', value:'1.2' },
                    { label: '1.3', value:'1.3' },
                    { label: '1.4', value:'1.4' },
                    { label: '1.5', value:'1.5' },
                    { label: '1.6', value:'1.6' },
                    { label: '1.7', value:'1.7' },
                    { label: '1.8', value:'1.8' },
                    { label: '1.9', value:'1.9' },
                    { label: '2', value:'2' },

                ],
                placeholder: rootScope.lang('Line height')
            });
            dropdown.select.on('change', function (e, val) {
                if(val) {
                    api.lineHeight(val.value);
                }

            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    format: function (scope, api, rootScope) {

        var _proto = this;

        this._availableTags = [
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h1">Heading 1</mw-editor-option>', value: 'h1', title: 'Heading 1' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h2">Heading 2</mw-editor-option>', value: 'h2', title: 'Heading 2' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h3">Heading 3</mw-editor-option>', value: 'h3', title: 'Heading 3' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h4">Heading 4</mw-editor-option>', value: 'h4', title: 'Heading 4' },
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h5">Heading 5</mw-editor-option>', value: 'h5' , title: 'Heading 5'},
            { label: '<mw-editor-option class="mw-editor-option-dropdown-h6">Heading 6</mw-editor-option>', value: 'h6', title: 'Heading 6' },
            { label: 'Paragraph', value: 'p', title: 'Paragraph' },
            { label: 'Block', value: 'div', title: 'Block' },
            { label: 'Pre formated', value: 'pre', title: 'Pre formated' }
        ];

        this.availableTags = function () {
            if(this.__availableTags) {
                return this.__availableTags;
            }
            this.__availableTags = this._availableTags.map(function (item) {
                return item.value;
            });
            return this.availableTags();
        };

        this.getTagDisplayName = function (tag) {
            tag = (tag || '').trim().toLowerCase();
            if(!tag) return;
            for (var i = 0; i < this._availableTags.length; i++) {
                if(this._availableTags[i].value === tag) {
                    return this._availableTags[i].title;
                }
            }
        };

        this.checkSelection = function (opt) {
            var el = opt.api.elementNode(opt.selection.focusNode);
            var parentEl = mw.tools.firstParentOrCurrentWithTag(el, this.availableTags());


            opt.controller.element.get(0).querySelector('.mw-editor-select-display-value-content').textContent = (parentEl ? this.getTagDisplayName(parentEl.nodeName) : '');
            const element = opt.api.elementNode(opt.api.getSelection().focusNode);

            const unsuportedElements = ['table','tr','td','th','tbody','thead','section','article','aside','figcaption','figure','footer','header','hgroup','main','nav'];

            const elementSupports = unsuportedElements.indexOf(element.nodeName.toLowerCase()) === -1;



              rootScope.disabled(opt.controller.element, !elementSupports || opt.isPlainText || !opt.api.isSelectionEditable()  );


        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: this._availableTags,
                placeholder: rootScope.lang('Format')
            });

            dropdown.select.get(0).querySelector('.mw-editor-select-display-value-content').style.width = '70px';
            this.dropdown = dropdown
            dropdown.root.addClass('mw-editor-controller-component-format')
            dropdown.select.on('change', function (e, val) {
                if(e.detail) {

                    api.format(e.detail.value);


                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    fontSelector: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
                var font = css.font();
                var family_array = font.family.split(','), fam;
                if (family_array.length === 1) {
                    fam = font.family;
                } else {
                    fam = family_array.shift();
                }
                fam = fam.replace(/['"]+/g, '');
                opt.controller.element.displayValue(fam);
            rootScope.disabled(opt.controller.element, opt.isPlainText || !opt.api.isSelectionEditable()|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)))

        };
        this.render = function () {

            var defaultData = [
                { label:'Arial', value: 'Arial' },
                { label:'Tahoma', value: 'Tahoma' },
                { label:'Verdana', value: 'Verdana' },
                { label:'Georgia', value: 'Georgia' },
                { label:'Times New Roman', value: 'Times New Roman' },
            ];

            var dropdown = new MWEditor.core.dropdown({
                data: defaultData,
                placeholder: rootScope.lang('Font'),
                eachOption: function (obj, node){

                    // mw.top().app.cssEditor.temp(node, 'font-family', obj.value)

                    node.style.fontFamily = obj.value
                }
            });

            if(mw.top().app && mw.top().app.fontManager) {
                mw.top().app.fontManager.subscribeToSelectedFont(function (selectedFontEvent) {
                    var sel = api.getSelection();
                    var focusNode = sel.focusNode;
                    if (selectedFontEvent.applyToSelectedElement == focusNode) {
                        api.fontFamily(selectedFontEvent.fontFamily);
                    }
                });
                mw.top().app.fontManager.subscribe(function(fonts) {
                    var newDefaultData = [];
                    if (fonts) {
                        fonts.forEach(function (fontFamily) {
                            newDefaultData.push({ label: fontFamily, value: fontFamily });
                        });
                    }

                        newDefaultData.push({ label:'More...', value: '$more' });
                        defaultData = newDefaultData || [];
                        dropdown.setData(defaultData);

                });
            }

            // if(scope.settings.fontFamilyProvider) {
            //     scope.settings.fontFamilyProvider.on('change', function (data){
            //         dropdown.setData([...defaultData, ...data, ...[{ label:'More...', value: '$more' }]])
            //     });
            // }

            dropdown.select.on('change', function (e, val) {

                if(val) {
                    if(val.value == '$more') {
                        if(mw.top().app && mw.top().app.fontManager) {
                            var sel = api.getSelection();
                            var focusNode = sel.focusNode;
                            mw.top().app && mw.top().app.fontManager.manageFonts({
                               applySelectionToElement: focusNode
                            });
                        }
                    } else {
                        api.fontFamily(val.value);
                    }
                }

            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    undoRedo: function(scope, api, rootScope) {
        this.render = function () {
            this.root = MWEditor.core.element();
            this.root.addClass('mw-ui-btn-nav mw-editor-state-component');
            var undo = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z" /></svg>',
                    tooltip: rootScope.lang('Undo')
                }
            });
            undo.on('mousedown touchstart', function (e) {
                rootScope.state.undo();
                rootScope._syncTextArea();
            });

            var redo = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">' +
                        '<path fill="currentColor" d="M18.4,10.6C16.55,9 14.15,8 11.5,8C6.85,8 2.92,11.03 1.54,15.22L3.9,16C4.95,12.81 7.95,10.5 11.5,10.5C13.45,10.5 15.23,11.22 16.62,12.38L13,16H22V7L18.4,10.6Z" />\n' +
                        '</svg>',
                    tooltip: rootScope.lang('Redo')
                }
            });
            redo.on('mousedown touchstart', function (e) {
                rootScope.state.redo();
                rootScope._syncTextArea();
            });
            this.root.get(0).appendChild(undo.get(0));
            this.root.get(0).appendChild(redo.get(0));
            $(rootScope.state).on('stateRecord', function(e, data){

                rootScope.disabled(undo.get(0), !data.hasNext)
                rootScope.disabled(redo.get(0), !data.hasPrev)
            })
            .on('stateUndo stateRedo', function(e, data){
                if(!data.active || !data.active.target) {
                    rootScope.disabled(undo.get(0), !data.hasNext)
                    rootScope.disabled(redo.get(0), !data.hasPrev)
                    return;
                }
                if(scope.actionWindow.document.body.contains(data.active.target)) {
                    mw.$(data.active.target).html(data.active.value);
                } else{
                    if(data.active.target.id) {
                        mw.$(scope.actionWindow.document.getElementById(data.active.target.id)).html(data.active.value);
                    }
                }
                if(data.active.prev) {
                    mw.$(data.active.prev).html(data.active.prevValue);
                }
                // mw.drag.load_new_modules();
                rootScope.disabled(undo.get(0), !data.hasNext)
                rootScope.disabled(redo.get(0), !data.hasPrev)
                $(scope).trigger(e.type, [data]);
            });
            setTimeout(function () {
                var data = rootScope.state.eventData();
                rootScope.disabled(undo.get(0), !data.hasNext)
                rootScope.disabled(redo.get(0), !data.hasPrev)
            }, 78);
            return this.root;
        };
        this.element = this.render();
    },
    'ul': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M3,4H7V8H3V4M9,5V7H21V5H9M3,10H7V14H3V10M9,11V13H21V11H9M3,16H7V20H3V16M9,17V19H21V17H9" />\n' +
                        '</svg>'
                }
            });
            el.on('mousedown touchstart', function (e) {

                var isSafeMode = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(scope.api.elementNode(scope.api.getSelection().focusNode), ['safe-mode', 'regular-mode'])

                if(!isSafeMode) {
                    return api.execCommand('insertUnorderedList');
                }




                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                const parentLi = mw.tools.firstParentOrCurrentWithTag(node, 'li');
                const isInLi = node.nodeName !== 'LI' && !!parentLi;


                if(isInLi) {
                    api.setCursorAtStart(parentLi)
                }

                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph && !isInLi) {
                    node = mw.tools.setTag(paragraph, 'div');
                }


                var isSafeMode = api.isSafeMode(node);
                var parentList;
                if(isSafeMode) {
                    parentList = mw.tools.firstBlockLikeLevel(node);
                   parentList.parentNode.contentEditable = true;
                   parentList.contentEditable = 'inherit';

                }
                var edit = mw.tools.firstParentOrCurrentWithClass(parentList || node, 'edit');

                api.setCursorAtStart(parentList || node)

                var isInList = mw.tools.firstParentOrCurrentWithTag(node, ['ul', 'ol']);
                if(isInList) {
                    api.execCommand('RemoveList');
                }

                 node.ownerDocument.execCommand('insertunorderedList');
              //  api.execCommand('insertunorderedList');
                 setTimeout(function(){
                    if(edit) {
                        var all = edit.querySelectorAll('*[style*="var"]');
                        var allp = edit.querySelectorAll('h1 ul, h2 ul, h3 ul, h4 ul, h5 ul, h6 ul, p ul, h1 ol,h2 ol,h3 ol,h4 ol,h5 ol, h6 ol, p ol');
                        all.forEach(node => {
                            if (node.style) {
                                if (node.isContentEditable) {
                                    [...node.style].filter(prop => node.style[prop].includes('var(')).forEach(prop => node.style.removeProperty(prop))
                                }
                            }
                        });
                        allp.forEach(node => {
                            var pp = mw.tools.firstParentOrCurrentWithTag(node, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p']);
                            mw.tools.setTag(pp, 'div');
                        });
                    }
                    var li = node.querySelector('li');
                    if(li) {
                        api.setCursorAtStart(li)
                    }
                }, 10)
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
            rootScope.controllerActive(opt.controller.element.get(0),  mw.tools.firstParentOrCurrentWithTag(opt.controller.element.get(0), 'ul'));

        };
        this.element = this.render();
    },
    'ol': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M7,13V11H21V13H7M7,19V17H21V19H7M7,7V5H21V7H7M3,8V5H2V4H4V8H3M2,17V16H5V20H2V19H4V18.5H3V17.5H4V17H2M4.25,10A0.75,0.75 0 0,1 5,10.75C5,10.95 4.92,11.14 4.79,11.27L3.12,13H5V14H2V13.08L4,11H2V10H4.25Z" />\n' +
                        '</svg>',
                    'data-tip': 'Ordered list'
                }
            });
            el.on('mousedown touchstart', function (e) {


                var isSafeMode = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(scope.api.elementNode(scope.api.getSelection().focusNode), ['safe-mode', 'regular-mode'])

                if(!isSafeMode) {
                    return api.execCommand('insertOrderedList')
                }
                var sel = api.getSelection();
                var range = sel.getRangeAt(0);

                if(api.isCrossBlockSelection()) {

                    api.getSelectionChildNodes().forEach(node => {
                        if(node.nodeType === 1) {
                            node
                        }
                    })

                    return;
                }



                var node = api.elementNode(sel.focusNode);
                const parentLi = mw.tools.firstParentOrCurrentWithTag(node, 'li');
                const isInLi = node.nodeName !== 'LI' && !!parentLi;


                if(isInLi) {
                    api.setCursorAtStart(parentLi)
                }

                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph && !isInLi) {
                    node = mw.tools.setTag(paragraph, 'div');
                }


                var isSafeMode = api.isSafeMode(node);
                var parentList;
                if(isSafeMode) {
                    parentList = mw.tools.firstBlockLikeLevel(node);
                    parentList.parentNode.contentEditable = true;

                }
                var edit = mw.tools.firstParentOrCurrentWithClass(parentList || node, 'edit');

                api.setCursorAtStart(parentList || node)

                var isInList = mw.tools.firstParentOrCurrentWithTag(node, ['ul', 'ol']);
                if(isInList) {
                    api.execCommand('RemoveList');
                }

                node.ownerDocument.execCommand('insertorderedList');
                setTimeout(function(){
                    if(edit) {
                        var all = edit.querySelectorAll('[style*="var"]');
                        var allp = edit.querySelectorAll('h1 ul, h2 ul, h3 ul, h4 ul, h5 ul, h6 ul, p ul, h1 ol,h2 ol,h3 ol,h4 ol,h5 ol, h6 ol, p ol');
                        all.forEach(node => {
                            if(node.isContentEditable) {
                                [...node.style].filter(prop => node.style[prop].includes('var(')).forEach(prop => node.style.removeProperty(prop) )
                            }
                        });
                        allp.forEach(node => {
                            var pp = mw.tools.firstParentOrCurrentWithTag(node, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p']);
                            mw.tools.setTag(pp, 'div');
                        });
                    }
                    var li = node.querySelector('li');
                    if(li) {
                        api.setCursorAtStart(li)
                    }
                }, 10)
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
            rootScope.controllerActive(opt.controller.element.get(0),  mw.tools.firstParentOrCurrentWithTag(opt.controller.element.get(0), 'ol'));
        };
        this.element = this.render();
    },
    'indent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-increase',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('indent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    'outdent': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-indent-decrease',
                    'data-tip': 'Indent'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('outdent');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    removeFormat: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M6,5V5.18L8.82,8H11.22L10.5,9.68L12.6,11.78L14.21,8H20V5H6M3.27,5L2,6.27L8.97,13.24L6.5,19H9.5L11.07,15.34L16.73,21L18,19.73L3.55,5.27L3.27,5Z" />\n' +
                        '</svg>',
                    tooltip: 'Remove Format'
                }
            });
            el.on('mousedown touchstart', function (e) {


                api.execCommand('removeFormat');

                sel = scope.getSelection();

                var range = sel.getRangeAt(0);

                const commonAncestorContainer = api.elementNode(range.commonAncestorContainer);

                commonAncestorContainer.querySelectorAll('[style]').forEach(node => {
                    if(range.intersectsNode(node)) {
                        node.removeAttribute('style')
                    }
                })


            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },

    unlink: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.43 19.12,14.63 17.79,15L19.25,16.44C20.88,15.61 22,13.95 22,12A5,5 0 0,0 17,7M16,11H13.81L15.81,13H16V11M2,4.27L5.11,7.38C3.29,8.12 2,9.91 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12C3.9,10.41 5.11,9.1 6.66,8.93L8.73,11H8V13H10.73L13,15.27V17H14.73L18.74,21L20,19.74L3.27,3L2,4.27Z" />\n' +
                        '</svg>',
                    tooltip: 'Unlink'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                if(sel.isCollapsed) {
                    var node = api.elementNode(sel.focusNode);
                    node = mw.tools.firstParentOrCurrentWithTag(node, 'a');
                    scope.api.action(node.parentNode, function () {
                        while (node.firstChild) {
                            node.parentNode.insertBefore(node.firstChild, node);
                        }
                        node.parentNode.removeChild(node);
                    })
                } else {
                    api.execCommand('unlink');
                }

            });
            return el;
        };
        this.checkSelection = function (opt) {
            var sel = api.getSelection();
            var isLink =  mw.tools.firstParentWithTag(sel.focusNode, 'a');
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection) || !isLink /*|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode))*/);
         };
        this.element = this.render();
    },
    delete: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z" /></svg>',
                    tooltip: 'Delete'
                }
            });
            el.on('mousedown touchstart', function (e) {

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    pin: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M16,12V4H17V2H7V4H8V12L6,14V16H11.2V22H12.8V16H18V14L16,12M8.8,14L10,12.8V4H14V12.8L15.2,14H8.8Z" />\n' +
                        '</svg>',
                    tooltip: 'Pin/Unpin to top'
                }
            });
            el.addClass('mw-editor-button-pin');
            el.on('mousedown touchstart', function (e) {
                e.preventDefault();

                scope.smallEditorApi.toggle();


            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },
    clone: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z" /></svg>',
                    tooltip: 'Clone'
                }
            });
            el.on('mousedown touchstart', function (e) {


                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);

                var clone = node.cloneNode(true);
                if(clone.id) {
                    clone.id =  mw.id('mw-element-');
                }
                var all = clone.querySelectorAll('[id]'), l = all.length, i = 0;
                for ( ; i < l ; i++) {
                    all[i].id = mw.id('mw-element-');
                }


                api.action(mw.tools.firstBlockLevel(node), function () {
                    node.after(clone);
                });

            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), !opt.api.isSelectionEditable(opt.selection) || !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    textColor: function (scope, api, rootScope) {


        this.render = function () {
            var el = MWEditor.core.colorPicker({
                displayDocument: mw.top().win.document,
                props: {
                    innerHTML: `
                        <svg viewBox="0 0 24 24">
                            <path fill="currentColor" d="M9.62,12L12,5.67L14.37,12M11,3L5.5,17H7.75L8.87,14H15.12L16.25,17H18.5L13,3H11Z" />
                        </svg>
                    `,
                    tooltip: 'Text color',
                },
                api,
                getColor: function() {
                    return getComputedStyle(api.elementNode(api.getSelection().focusNode)).color
                }
            });
            el.on('changeStart', function (e, val) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode);
                scope.state.record({
                    target: el,
                    value: el.innerHTML
                });
            })
            el.on('changeEnd', function (e, val) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode);
                scope.state.record({
                    target: el,
                    value: el.innerHTML
                });
            })
            el.on('change', function (e, val) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode);
                if(sel.isCollapsed ) {
                    mw.top().app.cssEditor.temp(el, 'color', val)
                } else {

                    api.execCommandSimple('foreColor', false, val, false);
                }

            });
            return el;
        };
        this.checkSelection = function (opt) {


            var css = opt.css;
            var font = css.font();
            var color = font.color;


            var colorIndicator = opt.controller.element.get(0).querySelector('.mw-editor-color-picker-color-indicator');

            if(!colorIndicator) {
                colorIndicator = opt.controller.element.get(0).ownerDocument.createElement('span');
                colorIndicator.className = 'mw-editor-color-picker-color-indicator';
                opt.controller.element.get(0).appendChild(colorIndicator)
            }

            colorIndicator.style.backgroundColor = color;




            rootScope.disabled(opt.controller.element.get(0), !opt.rangeInEditor || opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },
    textBackgroundColor: function (scope, api, rootScope) {

        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    innerHTML: `
                        <svg viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19,11.5C19,11.5 17,13.67 17,15A2,2 0 0,0 19,17A2,2 0 0,0 21,15C21,13.67 19,11.5 19,11.5M5.21,10L10,5.21L14.79,10M16.56,8.94L7.62,0L6.21,1.41L8.59,3.79L3.44,8.94C2.85,9.5 2.85,10.47 3.44,11.06L8.94,16.56C9.23,16.85 9.62,17 10,17C10.38,17 10.77,16.85 11.06,16.56L16.56,11.06C17.15,10.47 17.15,9.5 16.56,8.94Z" />\n' +
                        </svg>
                    `,
                    tooltip: 'Text background color'
                },
                api,
                getColor: function() {
                    var color = getComputedStyle(api.elementNode(api.getSelection().focusNode)).backgroundColor
                    return color !== 'rgba(0, 0, 0, 0)' ? color : 'rgba(0, 0, 0, 1)'
                }
            });

            el.on('changeStart', function (e, val) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode);
                scope.api.cleanStyle('background-color');
                scope.state.record({
                    target: el,
                    value: el.innerHTML
                });
            })
            el.on('changeEnd', function (e, val) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode);
                scope.state.record({
                    target: el,
                    value: el.innerHTML
                });
            })
            el.on('change', function (e, val) {
                var sel = scope.getSelection();
                var el = scope.api.elementNode(sel.focusNode);
                if(sel.isCollapsed ) {

                    mw.top().app.cssEditor.temp(el, 'background-color', val)
                } else {

                    api.execCommandSimple('backcolor', false, val, false);
                }

            });
            return el;

        };
        this.checkSelection = function (opt) {


            var css = opt.css;
            var font = css.font();
            var color = css.background().color;


            var colorIndicator = opt.controller.element.get(0).querySelector('.mw-editor-color-picker-color-indicator');

            if(!colorIndicator) {
                colorIndicator = opt.controller.element.get(0).ownerDocument.createElement('span');
                colorIndicator.className = 'mw-editor-color-picker-color-indicator';
                opt.controller.element.get(0).appendChild(colorIndicator)
            }

            colorIndicator.style.backgroundColor = color;

            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },




    table: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M12.35 20H10V17H12.09C12.21 16.28 12.46 15.61 12.81 15H10V12H14V13.54C14.58 13 15.25 12.61 16 12.35V12H20V12.35C20.75 12.61 21.42 13 22 13.54V5C22 3.9 21.1 3 20 3H4C2.9 3 2 3.9 2 5V20C2 21.1 2.9 22 4 22H13.54C13 21.42 12.61 20.75 12.35 20M16 7H20V10H16V7M10 7H14V10H10V7M8 20H4V17H8V20M8 15H4V12H8V15M8 10H4V7H8V10M17 14H19V17H22V19H19V22H17V19H14V17H17V14" />\n' +
                        '</svg>', tooltip: 'Insert Table'
                }
            });
            el.on('mousedown touchstart', function (e) {
                if((e.which || e.button) === 1) {
                    var table = `
                    <div class="element">
                        <table class="mw-ui-table" border="1" width="100%">
                            <tr>
                                <td data-mwplaceholder="This is sample text for your page"></td>
                                <td data-mwplaceholder="This is sample text for your page"></td>
                            </tr>
                            <tr>
                                <td data-mwplaceholder="This is sample text for your page"></td>
                                <td data-mwplaceholder="This is sample text for your page"></td>
                            </tr>
                        </table>
                    </div>
                    `;
                    api.getSelection().deleteFromDocument();
                    api.insertHTML(table);
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection)|| !opt.api.targetSupportsFormatting(opt.api.elementNode(opt.api.getSelection().focusNode)));
        };
        this.element = this.render();
    },



    xwordPaste: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    innerHTML: '<svg viewBox="0 0 24 24">\n' +
                        '    <path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.2,20H13.8L12,13.2L10.2,20H8.8L6.6,11H8.1L9.5,17.8L11.3,11H12.6L14.4,17.8L15.8,11H17.3L15.2,20M13,9V3.5L18.5,9H13Z" />\n' +
                        '</svg>', tooltip: 'Paste from Word'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.saveSelection();
                var dialog;
                var ok = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component',
                        innerHTML: rootScope.lang('OK')
                    }
                });
                var cancel = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component',
                        innerHTML: rootScope.lang('Cancel')
                    }
                });
                var cleanEl = mw.element({
                    props: {
                        contentEditable: true,
                        autofocus: true,
                        style: {
                            height: '250px'
                        }
                    }
                });

                var footer = mw.element(`<div class="d-flex justify-content-between w-100"></div>`);
                cancel.on('click', function (){
                    dialog.remove();
                })
                ok.on('click', function (){
                    var content = cleanEl.html().trim();
                    dialog.remove();
                    api.restoreSelection();
                    if(content){
                        api.insertHTML(api.cleanWord(content));
                    }

                });
                footer.append(cancel);
                footer.append(ok);
                dialog = mw.dialog({
                    content: cleanEl.get(0),
                    footer: footer.get(0),
                    title: mw.lang('Paste')
                });
            });
            return el;
        };
        this.checkSelection = function (opt) {
            rootScope.disabled(opt.controller.element.get(0), opt.isPlainText || !opt.api.isSelectionEditable(opt.selection));
        };
        this.element = this.render();
    },




    textEffectClassApplier: function (scope, api, rootScope) {
        this.render = function () {
            var aiIconSVG = '<svg color="gray.100" fill="currentColor" height="22" viewBox="0 0 22 22" width="22" xmlns="http://www.w3.org/2000/svg" class="ai-writer-container-1fy6kej"><path d="M12 0h-1L5 14h5v8h1l6-14h-5V0z"></path></svg>';
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    tooltip: rootScope.lang('Text styles'),
                    innerHTML: aiIconSVG
                }
            });
            el.on('mousedown touchstart', function (e) {



                mw.top().app.dispatch('openElementSettingsModal', {
                    'scope':scope,
                    'setting': 'textEffect'
                });


            });
            return el;
        };
        this.checkSelection = function (opt, ee, tt) {


        };
        this.element = this.render();
    },





};
