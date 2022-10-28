MWEditor.controllers = {
    align: function (scope, api, rootScope) {
        this.root = MWEditor.core.element();
        this.root.$node.addClass('mw-editor-state-component mw-editor-state-component-align');
        this.buttons = [];

        var arr = [
            {align: 'left', icon: 'left', action: 'justifyLeft'},
            {align: 'center', icon: 'center', action: 'justifyCenter'},
            {align: 'right', icon: 'right', action: 'justifyRight'},
            {align: 'justify', icon: 'justify', action: 'justifyFull'}
        ];
        this.render = function () {
            var scope = this;
            arr.forEach(function (item) {
                var el = MWEditor.core.button({
                    props: {
                        className: 'mdi-format-align-' + item.icon
                    }
                });
                el.on('mousedown touchstart', function (e) {
                    api.execCommand(item.action);
                });
                scope.root.append(el);
                scope.buttons.push(el);
            });
            return scope.root;
        };
        this.checkSelection = function (opt) {
            var align = opt.css.alignNormalize();
            for (var i = 0; i< this.buttons.length; i++) {
                var state = arr[i].align === align;
                rootScope.controllerActive(this.buttons[i].node, state);
            }
        };
        this.element = this.render();
    },
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-bold',
                    tooltip: rootScope.lang('Bold')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('bold');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().bold) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    strikeThrough: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-strikethrough',
                    tooltip: rootScope.lang('Strike through')
                }
            });

            el.on('mousedown touchstart', function (e) {
                api.execCommand('strikeThrough');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            if(opt.css.is().striked) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    italic: function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-italic',
                    tooltip: rootScope.lang('Italic')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('italic');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
            if(opt.css.is().italic) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
        };
        this.element = this.render();
    },
    'underline': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-underline',
                    tooltip: rootScope.lang('Underline')
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('underline');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
            if(opt.css.is().underlined) {
                rootScope.controllerActive(opt.controller.element.node, true);
            } else {
                rootScope.controllerActive(opt.controller.element.node, false);
            }
        };
        this.element = this.render();
    },
    'image': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-folder-multiple-image',
                    tooltip: rootScope.lang('Insert Image')
                }
            });
            el.on('click', function (e) {
                e.preventDefault();
                api.saveSelection();
                var dialog;
                var picker = new mw.filePicker({
                    type: 'images',
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
                        url.forEach(function (src){
                            api.insertImage(src.toString());
                        });
                        dialog.remove();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false
                });

            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    link: function(scope, api, rootScope){

        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-link',
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
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    fontSize: function (scope, api, rootScope) {
        this.checkSelection = function (opt) {
            var css = opt.css;
            var font = css.font();
            var size = font.size;
            opt.controller.element.displayValue(size);
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: '8px', value: 8 },
                    { label: '10px', value: 10 },
                    { label: '12px', value: 12 },
                    { label: '14px', value: 14 },
                    { label: '16px', value: 16 },
                    { label: '18px', value: 18 },
                    { label: '20px', value: 20 },
                    { label: '22px', value: 22 },
                    { label: '24px', value: 24 },
                    { label: '28px', value: 28 },
                    { label: '32px', value: 32 },
                    { label: '36px', value: 36 },
                    { label: '42px', value: 42 },
                ],
                placeholder: rootScope.lang('Font Size')
            });
            dropdown.select.on('change', function (e, val) {
                if(val) {
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
            var size = font.height;
            opt.controller.element.displayValue(size);
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                icon: 'format-line-spacing',
                data: [
                    { label: 'normal', value: 'normal' },
                    { label: '14px', value:'14px' },
                    { label: '16px', value:'16px' },
                    { label: '19px', value:'19px' },
                    { label: '21px', value:'21px' },
                    { label: '24px', value:'24px' },
                    { label: '25px', value:'25px' },
                    { label: '27px', value:'27px' },
                    { label: '30px', value:'30px' },
                    { label: '35px', value:'35px' },
                    { label: '40px', value:'40px' },
                    { label: '45px', value:'45px' },
                    { label: '50px', value:'50px' },
                    { label: '55px', value:'55px' },
                    { label: '60px', value:'60px' },
                ],
                placeholder: rootScope.lang('Line height')
            });
            dropdown.select.on('change', function (e, val) {
                api.lineHeight(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    format: function (scope, api, rootScope) {

        this._availableTags = [
            { label: '<h1>Title</h1>', value: 'h1' },
            { label: '<h2>Title</h2>', value: 'h2' },
            { label: '<h3>Title</h3>', value: 'h3' },
            { label: '<h4>Title</h4>', value: 'h4' },
            { label: '<h5>Title</h5>', value: 'h5' },
            { label: '<h6>Title</h6>', value: 'h6' },
            { label: 'Paragraph', value: 'p' },
            { label: 'Block', value: 'div' },
            { label: 'Pre formated', value: 'pre' }
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
                    return this._availableTags[i].label;
                }
            }
        };

        this.checkSelection = function (opt) {
            var el = opt.api.elementNode(opt.selection.focusNode);
            var parentEl = mw.tools.firstParentOrCurrentWithTag(el, this.availableTags());
            opt.controller.element.displayValue(parentEl ? this.getTagDisplayName(parentEl.nodeName) : '');
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: this._availableTags,
                placeholder: rootScope.lang('Format')
            });
            dropdown.select.on('change', function (e, val) {
                api.execCommand('formatBlock', false, e.detail.value);
                /*var sel = scope.getSelection();
                var range = sel.getRangeAt(0);
                var el = scope.actionWindow.document.createElement(val.value);

                var disableSelection = true;

                if(sel.isCollapsed || disableSelection) {
                    var selectionElement = api.elementNode(sel.focusNode);
                    if(scope.$editArea[0] !== selectionElement) {
                        mw.tools.setTag(selectionElement, val.value);
                    } else {
                        while (selectionElement.firstChild) {
                            el.appendChild(selectionElement.firstChild);
                        }
                        selectionElement.appendChild(el);
                    }
                    var newRange = scope.actionWindow.document.createRange();
                    newRange.setStart(sel.anchorNode, sel.anchorOffset);
                    newRange.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                } else {
                    range.surroundContents(el);
                }*/
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
                opt.controller.element.disabled = !opt.api.isSelectionEditable();

        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: 'Arial 1', value: 'Arial' },
                    { label: 'Verdana 1', value: 'Verdana' },
                ],
                placeholder: rootScope.lang('Font')
            });
            dropdown.select.on('change', function (e, val, b) {
                api.fontFamily(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    undoRedo: function(scope, api, rootScope) {
        this.render = function () {
            this.root = MWEditor.core.element();
            this.root.addClass('mw-ui-btn-nav mw-editor-state-component')
            var undo = MWEditor.core.button({
                props: {
                    className: 'mdi-undo',
                    tooltip: rootScope.lang('Undo')
                }
            });
            undo.on('mousedown touchstart', function (e) {
                rootScope.state.undo();
                rootScope._syncTextArea();
            });

            var redo = MWEditor.core.button({
                props: {
                    className: 'mdi-redo',
                    tooltip: rootScope.lang('Redo')
                }
            });
            redo.on('mousedown touchstart', function (e) {
                rootScope.state.redo();
                rootScope._syncTextArea();
            });
            this.root.node.appendChild(undo.node);
            this.root.node.appendChild(redo.node);
            $(rootScope.state).on('stateRecord', function(e, data){
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
            })
            .on('stateUndo stateRedo', function(e, data){
                if(!data.active || !data.active.target) {
                    undo.node.disabled = !data.hasNext;
                    redo.node.disabled = !data.hasPrev;
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
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
                $(scope).trigger(e.type, [data]);
            });
            setTimeout(function () {
                var data = rootScope.state.eventData();
                undo.node.disabled = !data.hasNext;
                redo.node.disabled = !data.hasPrev;
            }, 78);
            return this.root;
        };
        this.element = this.render();
    },
    'ul': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-list-bulleted'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph) {
                    scope.api.action(paragraph.parentNode, function () {
                        var ul = scope.actionWindow.document.createElement('ul');
                        var li = scope.actionWindow.document.createElement('li');
                        ul.appendChild(li);
                        while (paragraph.firstChild) {
                            li.appendChild(node.firstChild);
                        }
                        paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
                        paragraph.remove();
                    });
                } else {
                    api.execCommand('insertUnorderedList');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    'ol': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-list-numbered tip',
                    'data-tip': 'Ordered list'
                }
            });
            el.on('mousedown touchstart', function (e) {
                var sel = api.getSelection();
                var node = api.elementNode(sel.focusNode);
                var paragraph = mw.tools.firstParentOrCurrentWithTag(node, 'p');
                if(paragraph) {
                    scope.api.action(paragraph.parentNode, function () {
                        var ul = scope.actionWindow.document.createElement('ol');
                        var li = scope.actionWindow.document.createElement('li');
                        ul.appendChild(li);
                        while (paragraph.firstChild) {
                            li.appendChild(paragraph.firstChild);
                        }
                        paragraph.parentNode.insertBefore(ul, paragraph.nextSibling);
                        paragraph.remove();
                    });
                } else {
                    api.execCommand('insertOrderedList');
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
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
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
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
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    removeFormat: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-clear',
                    tooltip: 'Remove Format'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('removeFormat');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    unlink: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-link-off', tooltip: 'Unlink'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.execCommand('unlink');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    textColor: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    className: 'mdi-format-color-text', tooltip: 'Text color'
                }
            });
            el.on('change', function (e, val) {
                api.execCommand('foreColor', false, val);
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    textBackgroundColor: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.colorPicker({
                props: {
                    className: 'mdi-format-color-fill', tooltip: 'Text background color'
                }
            });
            el.on('change', function (e, val) {
                api.execCommand('backcolor', false, val);
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    table: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-table-large', tooltip: 'Insert Table'
                }
            });
            el.on('mousedown touchstart', function (e) {
                if((e.which || e.button) === 1) {
                    var table = '<table id="test" class="mw-ui-table" style="width: 100%" border="1" width="100%"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>';
                    api.insertHTML(table);
                }
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },
    wordPaste: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-file-word', tooltip: 'Paste from Word'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.saveSelection();
                var dialog;
                var ok = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-ui-btn mw-ui-btn-info',
                        innerHTML: rootScope.lang('OK')
                    }
                });
                var cancel = MWEditor.core.element({
                    tag: 'span',
                    props: {
                        className: 'mw-ui-btn',
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

                var footer = mw.element();
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
                    content: cleanEl.node,
                    footer: footer.node
                });
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },



};
