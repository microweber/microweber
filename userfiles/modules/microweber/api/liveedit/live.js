(function () {
    function Helpers(config) {
        var scope = this;
        this.isBlockLevel = function(node){
            node = node || (this.data ? scope.data().target : null);
            return mw.tools.isBlockLevel(node);
        };
        this._data = {};
        this.data = function (data) {
            if(!data) {
                return this._data;
            }
            this._data = data;
        };

        this.isInlineLevel = function(node){
            node = node || scope.data().target;
            return mw.tools.isInlineLevel(node);
        };

        this.canAccept = function(target, what){
            var accept = target.dataset('accept');
            if(!accept) return true;
            
            accept = accept.trim().split(',').map(Function.prototype.call, String.prototype.trim);
            var wtype = 'all';
            if(mw.tools.hasClass(what, 'module-layout')){
                wtype = 'layout';
            }
            else if(mw.tools.hasClass(what, 'module')){
                wtype = 'module';
            }
            else if(mw.tools.hasClass(what, 'element')){
                wtype = 'element';
            }
            if(wtype === 'all') return true

            return accept.indexOf(wtype) !== -1;
        };
        this.getBlockElements = function(selector, root){
            root = root || document.body;
            selector = selector || '*';
            var all = root.querySelectorAll(selector), i = 0, final = [];
            for( ; i<all.length; i++){
                if(this.scope.helpers.isBlockLevel(all[i])){
                    final.push(all[i]);
                }
            }
            return final;
        };


        this.getElementsLike = function(selector, root){
            root = root || document.body;
            selector = selector || '*';
            var all = root.querySelectorAll(selector), i = 0, final = [];
            for( ; i<all.length; i++){
                if(!this.scope.helpers.isColLike(all[i]) &&
                    !this.scope.helpers.isRowLike(all[i]) &&
                    !this.scope.helpers.isEdit(all[i]) &&
                    this.scope.helpers.isBlockLevel(all[i])){
                    final.push(all[i]);
                }
            }
            return final;
        };

        this.isEdit = function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.edit);
        };

        this.isModule = function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.module) && (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, [this.scope.cls.module, this.scope.cls.edit]));
        };

        this.isElement = function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, this.scope.cls.element);
        };

        this.isEmpty = function(node){
            node = node || this.scope.data.target;
            return mw.tools.hasClass(node, 'mw-empty');
        };

        this.isRowLike = function(node){
            node = node || this.scope.data.target;
            var is = false;
            if(!node.className) return is;
            is = mw.tools.hasAnyOfClasses(node, this.scope.settings.rows);
            if(is){
                return is;
            }
            return mw.tools.matches(node, this.scope.settings.rowMatches);
        };

        this.isColLike = function(node){
            node = node || this.scope.data.target;
            var is = false;
            if(!node.className) return is;
            is = mw.tools.hasAnyOfClasses(node, this.scope.settings.columns);
            if(is){
                return is;
            }
            if(mw.tools.hasAnyOfClasses(node, ['mw-col-container', 'mw-ui-col-container'])){
                return false;
            }
            return mw.tools.matches(node, this.scope.settings.columnMatches);
        };

        this.isLayoutModule = function(node){
            node = node || this.scope.data.target;
            return false;

        };

        this.noop = function(){

        };
    }
    var LiveEdit = function (options) {
        options = options || {};

        var defaults = {
            elementsTypes: {
                elementClass: 'element',
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
            },
            target: document.body
        };

        this.helpers = new Helpers();




        this.targetAction = function (node) {


        };



    };
})();
