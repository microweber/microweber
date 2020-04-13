mw.Editor.controllers = {
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = mw.Editor.core.button({
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
            var el = mw.Editor.core.button({
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
            var el = mw.Editor.core.button({
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
                        console.log(url)
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
            opt.controller.element.$select.displayValue(size);
        };
        this.render = function () {
            var dropdown = new mw.Editor.core.dropdown({
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
                opt.controller.element.$select.displayValue(fam);

        };
        this.render = function () {
            var dropdown = new mw.Editor.core.dropdown({
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
            this.root = mw.Editor.core.element();
            this.root.$node.addClass('mw-ui-btn-nav mw-editor-state-component')
            var undo = mw.Editor.core.button({
                props: {
                    className: 'mdi-undo'
                }
            });
            undo.$node.on('mousedown touchstart', function (e) {
                rootScope.state.undo();
            });

            var redo = mw.Editor.core.button({
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
};
