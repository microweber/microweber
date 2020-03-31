mw.Editor.controllers = {
    bold: function (scope, api, rootScope) {
        this.render = function () {
            var scope = this;
            var el = mw.Editor.core.button({
                props: {
                    innerHTML: 'bold'
                }
            });

            el.$node.on('mousedown touchstart', function (e) {
                e.preventDefault();
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
                    innerHTML: 'italic'
                }
            });
            el.$node.on('mousedown touchstart', function (e) {
                e.preventDefault();
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
                    { label: '8px', value: '8px' },
                    { label: '22px', value: '22px' },
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

}
