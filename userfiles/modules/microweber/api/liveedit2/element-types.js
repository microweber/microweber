(function (){

    var ElementAnalyzerService = function (settings) {

        var dropableElements;

        this.isConfigurable = function (target) {
            return this.isElement(target) || this.isModule(target) || this.isRow(target);
        };

        this.isRow = function(node) {
            return mw.tools.hasClass(node, this.settings.rowClass);
        };

        this.isModuleButNotLayout = function(node) {
            return node.dataset.type !== 'layouts';
        };
        this.isLayout = function(node) {
            return node.dataset.type === 'layouts';
        };

        this.isEditableLayout = function(node) {
            return this.this.isLayout(node) && this.isInEdit(node);
        };

        this.isEditableModule = function(node) {
            return this.isModule(node) && this.isInEdit(node);
        };

        this.isElement = function(node) {
            return mw.tools.hasClass(node, this.settings.elementClass);
        };

        this.isEmpty = function(node) {
            return mw.tools.hasClass(node, this.settings.emptyElementClass);
        };

        var _tagsCanAccept = ['DIV', 'ARTICLE', 'ASIDE', 'FOOTER', 'HEADER', 'MAIN', 'SECTION', 'DD', 'LI', 'TD', 'FORM'];

        this.canAcceptByClass = function (node) {
            return mw.tools.hasAnyOfClasses(node, this.dropableElements());
        };

        this.canAcceptByTag = function (node) {
            if(!node || node.nodeType !== 1) return false;
            return _tagsCanAccept.indexOf(node.nodeName) !== -1;
        };

        this.isEdit = function(node) {
            return mw.tools.hasClass(node, this.settings.editClass);
        };

        this.isInEdit = function(node) {
            var order = [
                this.settings.editClass,
                this.settings.moduleClass,
            ];
            return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, order);
        };

        this.isEditOrInEdit = function (node) {
            return this.isEdit(node) || this.isInEdit(node);
        };

        this.allowDrop = function (node) {
            return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, [this.settings.allowDrop, this.settings.nodrop]);
        };

        this.canInsertBeforeOrAfter = function (node, candidate) {
            return this.canAccept(node.parentNode);
        };


        this.isPlainText = function (node) {
            return mw.tools.hasClass(node, this.settings.plainElementClass);
        };

        this.canAccept = function (target) {
            if (this.canAcceptByTag(target)
                && this.canAcceptByClass(target)
                && this.isEditOrInEdit(target)
                && this.allowDrop(target)) {
            }
            return false;
        };

        this.dropableElements = function (){
            return dropableElements;
        };

        this.getTarget = function (node) {
            if (!node || node === document.body) return null;
            if (this.canAccept(node)) {
                return node;
            } else {
                return this.getTarget(node.parentElement);
            }
        };

        this.init = function () {
            this.settings = settings;
            dropableElements = [
                settings.elementClass,
                settings.cloneableClass,
                settings.editClass,
                settings.moduleClass,
                settings.colClass,
                settings.allowDrop,
            ];
        };

        this.getPosition()

        this.init();
    };


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
            allowDrop: 'allow-drop',
            unEditableModules: [
                '[type="template_settings"]'
            ],
            frameworksClasses: {
                col: []
            }
        };

        this.settings = mw.object.extend({}, defaults, options);

        this.service = new ElementAnalyzerService(this.settings);
        
    };
})();


(function (){
    var MenuItem = function (data, scope) {
        var btn = document.createElement('span');
        btn.className = 'mw-handle-menu-item';
        if(data.icon) {
            var iconClass = data.icon;
            if (iconClass.indexOf('mdi-') === 0) {
                iconClass = 'mdi ' + iconClass;
            }
            var icon = document.createElement('span');
            icon.className = iconClass + ' mw-handle-menu-item-icon';
            btn.appendChild(icon);
        }
        btn.appendChild(document.createTextNode(data.title));
        if(data.className){
            btn.className += (' ' + data.className);
        }
        if(data.id){
            btn.id = data.id;
        }
        if(typeof data.visible === 'function'){
            if(!data.visible()) {
                btn.style.display = 'none';
            }
        }
        if(data.action){
            btn.onclick = function (e) {
                e.preventDefault();
                data.action.call(scope, e, this, data);
            };
        }
        return btn;
    };
    var MenuItems = {
        module: [
            {
                title: 'Edit HTML',
                icon: 'mw-icon-code',
                action: function () {
                    mw.editSource(mw._activeElementOver);
                }
            },
            {
                title: 'Edit Style',
                icon: 'mdi mdi-layers',
                action: function () {
                    mw.liveEditSettings.show();
                    mw.sidebarSettingsTabs.set(3);
                    if(mw.cssEditorSelector){
                        mw.liveEditSelector.active(true);
                        mw.liveEditSelector.select(mw._activeElementOver);
                    } else {
                        mw.$(mw.liveEditWidgets.cssEditorInSidebarAccordion()).on('load', function () {
                            setTimeout(function(){
                                mw.liveEditSelector.active(true);
                                mw.liveEditSelector.select(mw._activeElementOver);
                            }, 333);
                        });
                    }
                    mw.liveEditWidgets.cssEditorInSidebarAccordion();
                }
            },
            {
                title: 'Remove',
                icon: 'mw-icon-bin',
                className:'mw-handle-remove',
                action: function () {
                    mw.drag.delete_element(mw._activeElementOver);
                    mw.handleElement.hide()
                }
            }
        ]

};
    var ElementAnalizer = function (options) {

    };
})();
