import {ElementAnalyzerServiceBase} from './element-analizer.service.js'

export class DroppableElementAnalyzerService extends ElementAnalyzerServiceBase  {

    constructor(settings) {
        super(settings);
        this.settings = settings;
        this._tagsCanAccept = ['DIV', 'ARTICLE', 'ASIDE', 'FOOTER', 'HEADER', 'MAIN', 'SECTION', 'DD', 'LI', 'TD', 'FORM', 'DD'];
        this.init();
    }

    isConfigurable (target) {
        return this.isElement(target) || this.isModule(target) || this.isRow(target);
    }

    isEditableLayout (node) {
        return this.this.isLayout(node) && this.isInEdit(node);
    }

    canMoveModule (node) {
        return this.isModule(node) && this.isInEdit(node);
    }


    canAcceptByClass (node) {
        let can = this.tools.hasAnyOfClasses(node, this.dropableElements()) || node.className.indexOf('col-') !== -1;

        if(can) {
            if(node.classList.contains('mw-row')) {
                can = false;
            }
        }

        return can;
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

    canAccept (target) {
        // whether or not "target" can accept elements



        return !!(
            this.canAcceptByClass(target) &&
            this.isEditOrInEdit(target) &&
            this.allowDrop(target) &&
            target.dataset.layoutContainer === undefined);

    }

    canReceiveElements(target) {
        return this.isEdit(target) && this.canAcceptByTag(target);
    }

    dropableElements (){
        return this._dropableElements;
    }

    getIteractionTarget(node) {
        return this.tools.firstParentWithAnyOfClasses(node, [
            this.settings.elementClass,
            this.settings.editClass,
            this.settings.moduleClass,
            'allow-drop',
            'col',
        ]);
    }

    getTarget (node, draggedElement) {

        const target = this.getIteractionTarget(node);

        if(!target) {
            return;
        }

        if( !this.isEditOrInEdit(target) || !this.allowDrop(target)) {
            return null;
        }

        const res = {
            target,
            canInsert: false,
            beforeAfter: false
        }

        var draggedElementIsLayoutRestricted = this.settings.strictLayouts && this.isLayout(draggedElement);
        var isStrictCase = this.settings.strict && !this.isLayout(draggedElement) && !this.isInLayout(target);


        if(isStrictCase) {

            return null;
        }


        if (target.classList.contains('allow-drop')) {

            res.canInsert = true;
        } else if (this.isEdit(target)) {


            res.canInsert = !draggedElementIsLayoutRestricted;
        } else if ( this.isElement(target) && !draggedElementIsLayoutRestricted  ) {




            if (this.canAcceptByTag(target)) {

                res.canInsert = !draggedElementIsLayoutRestricted;
            }
            res.beforeAfter = true;
        } else if( this.isModule(target) && !draggedElementIsLayoutRestricted )  {

            if(this.canInsertBeforeOrAfter(target)) {

                res.beforeAfter = true;
            } else {

                return null;
            }
        } else if(this.isLayout(target)) {

            if(this.canInsertBeforeOrAfter(target)) {

              res.beforeAfter = true;
            } else {

                return null;
            }
        }

        if(target.classList.contains('mw-row')) {
            res.canInsert = false;
        }

        return res;
    }

    init () {
        this._dropableElements = [
            this.settings.elementClass,
            this.settings.cloneableClass,
            this.settings.editClass,
            this.settings.moduleClass,
            this.settings.colClass,
            this.settings.allowDrop,
            'allow-drop'
        ];
    }
}





