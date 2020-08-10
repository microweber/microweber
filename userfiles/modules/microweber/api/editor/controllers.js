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
                el.$node.on('mousedown touchstart', function (e) {
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
                    className: 'mdi-format-bold'
                }
            });

            el.$node.on('mousedown touchstart', function (e) {
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
    'italic': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-format-italic'
                }
            });
            el.$node.on('mousedown touchstart', function (e) {
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
    'media': function(scope, api, rootScope){
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-folder-multiple-image'
                }
            });
            el.$node.on('click', function (e) {
                mw.fileWindow({
                    types: 'images',
                    change: function (url) {
                        url = url.toString();
                        api.insertImage(url);
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
    'link': function(scope, api, rootScope){

        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-link'
                }
            });
            el.$node.on('click', function (e) {
                api.saveSelection();
                var picker = mw.component({
                    url: 'link_editor_v2',
                    options: {
                        target: true,
                        text: true,
                        controllers: 'page, custom, content, section, layout, email, file',
                        values: {
                            url: 1,
                            text: 1,
                            targetBlank: el ? el.target === '_blank' : ''
                        }
                    }
                });
                $(picker).on('Result', function(e, result){
                    api.restoreSelection();
                    api.link(result);
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
                    { label: '22px', value: 22 },
                ]
            });
            $(dropdown.select).on('change', function (e, val) {
                api.fontSize(val.value);
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
            { label: 'Paragraph', value: 'p' },
            { label: 'Block', value: 'div' }
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
                data: this._availableTags
            });
            $(dropdown.select).on('change', function (e, val) {
                var sel = scope.getSelection();
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
                opt.controller.element.disabled = !opt.api.isSelectionEditable();

        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: 'Arial 1', value: 'Arial' },
                    { label: 'Verdana 1', value: 'Verdana' },
                ]
            });
            $(dropdown.select).on('change', function (e, val) {
                api.fontFamily(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    },
    undoRedo: function(scope, api, rootScope) {
        this.render = function () {
            this.root = MWEditor.core.element();
            this.root.$node.addClass('mw-ui-btn-nav mw-editor-state-component')
            var undo = MWEditor.core.button({
                props: {
                    className: 'mdi-undo'
                }
            });
            undo.$node.on('mousedown touchstart', function (e) {
                rootScope.state.undo();
            });

            var redo = MWEditor.core.button({
                props: {
                    className: 'mdi-redo'
                }
            });
            redo.$node.on('mousedown touchstart', function (e) {
                rootScope.state.redo();
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
            el.$node.on('mousedown touchstart', function (e) {
                api.execCommand('insertUnorderedList');
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
            el.$node.on('mousedown touchstart', function (e) {
                api.execCommand('insertOrderedList');
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
            el.$node.on('mousedown touchstart', function (e) {
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
            el.$node.on('mousedown touchstart', function (e) {
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
    table: function (scope, api, rootScope) {
        this.render = function () {
            var el = MWEditor.core.button({
                props: {
                    className: 'mdi-table-large', tooltip: 'Insert Table'
                }
            });
            el.on('mousedown touchstart', function (e) {
                api.insertHTML('<table class="mw-ui-table" border="1" width="100%"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>');
            });
            return el;
        };
        this.checkSelection = function (opt) {
            opt.controller.element.node.disabled = !opt.api.isSelectionEditable(opt.selection);
        };
        this.element = this.render();
    },


};
