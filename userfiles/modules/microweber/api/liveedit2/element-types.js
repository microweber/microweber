(function (){


    var ElementAnalizerService = function (settings) {
        this.settings = settings;

        this.isConfigurable = function (target) {
            return this.isElement(target) || this.isModule(target) || this.isRow(target);
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

        var _tagsCanAccept = ['DIV', 'ARTICLE', 'ASIDE', 'FOOTER', 'HEADER', 'MAIN', 'SECTION', 'DD', 'LI', 'TD', 'FORM'];

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
            return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, order);
        };

        this.isEditOrInEdit = function (node) {
            return this.isEdit(node) || this.isInEdit(node);
        };


        this.canAccept = function (target, candidate) {

        };

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
            unEditableModules: [
                '[type="template_settings"]'
            ]
        };

        this.settings = mw.object.extend({}, defaults, options);


        this.service = new ElementAnalizerService(this.settings);





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
