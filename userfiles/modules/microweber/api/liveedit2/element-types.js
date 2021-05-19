import {ElementAnalyzerServiceBase} from './element-analizer.service'

export class DroppableElementAnalyzerService extends ElementAnalyzerServiceBase  {

    constructor(settings) {
        super();
        this.settings = settings;
        this._tagsCanAccept = ['DIV', 'ARTICLE', 'ASIDE', 'FOOTER', 'HEADER', 'MAIN', 'SECTION', 'DD', 'LI', 'TD', 'FORM'];
        this.init();
    }

    isConfigurable (target) {
        return this.isElement(target) || this.isModule(target) || this.isRow(target);
    }

    isEditableLayout (node) {
        return this.this.isLayout(node) && this.isInEdit(node);
    }

    isEditableModule (node) {
        return this.isModule(node) && this.isInEdit(node);
    }


    canAcceptByClass (node) {
        return this.tools.hasAnyOfClasses(node, this.dropableElements());
    }

    canAcceptByTag (node) {
        if(!node || node.nodeType !== 1) return false;
        return this._tagsCanAccept.indexOf(node.nodeName) !== -1;
    }

    allowDrop (node) {
        return this.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, [this.settings.allowDrop, this.settings.nodrop]);
    }

    canInsertBeforeOrAfter (node) {
        return this.canAccept(node.parentNode);
    }
    // whether or not "target" can accept elements
    canAccept (target) {
        if (this.canAcceptByTag(target) &&
            this.canAcceptByClass(target) &&
            this.isEditOrInEdit(target) &&
            this.allowDrop(target)) {
        }
        return false;
    }

    dropableElements (){
        return this._dropableElements;
    }

    getTarget (node) {
        if (!node || node === this.settings.document.body) return null;
        if (this.canAccept(node)) {
            return node;
        } else {
            return this.getTarget(node.parentElement);
        }
    }

    init () {
        this._dropableElements = [
            this.settings.elementClass,
            this.settings.cloneableClass,
            this.settings.editClass,
            this.settings.moduleClass,
            this.settings.colClass,
            this.settings.allowDrop,
        ];
    }
}


export const ElementAnalyzer = function (options) {
    options = options || {};

    this.settings = options;

    this.dropableService = new DroppableElementAnalyzerService(this.settings);

    this.getTargets = function (targets) {

    };

};

export const MenuItem = (data, scope) => {
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

export const MenuItems = {
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
