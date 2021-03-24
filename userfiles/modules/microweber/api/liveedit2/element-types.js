(function (){
    var ElementAnalizer = function (options) {
        options = options || {};

        var defaults = {
            elementClass: 'element',
            cloneableClass: 'cloneable',
            editClass: 'edit',
            moduleClass: 'module',
            rowClass: 'mw-row',
            colClass: 'mw-col',
            safeElementClass: 'safe-element',
            plainElementClass: 'plain-text',
            emptyElementClass: 'empty-element',
            nodrop: 'nodrop',
            unEditableModules: [
                '[type="template_settings"]'
            ]
        };

        this.isConfigurable = function (target) {
            return this.isElement(target) || this.isModule(target) || this.isRow(target);
        };

        this.settings = mw.object.extend({}, defaults, options);

        this.isEdit = function(node) {
            return mw.tools.hasClass(node, this.settings.editClass);
        };

        this.isRow = function(node) {
            return mw.tools.hasClass(node, this.settings.rowClass);
        };

        this.isModule = function(node) {
             return mw.tools.hasClass(node, this.settings.editClass)
                 && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.settings.moduleClass, this.settings.editClass]));
        };

        this.isElement = function(node) {
             return mw.tools.hasClass(node, this.settings.elementClass);
        };

        this.isEmpty = function(node) {
             return mw.tools.hasClass(node, this.settings.emptyElementClass);
        };

    };
})();
