
import {DomService} from './classes/dom.js';
import helpers from './live-edit-helpers.service.js';

export class ElementAnalyzerServiceBase {

    constructor(settings) {
        this.settings = settings;
        this.tools = DomService;
    }

    isRow (node) {
        if(!node) {
            return false;
        }
        if(!node.classList){
            return false;
        }
        return node.classList.contains(this.settings.rowClass);
    }

    isModule (node) {
        if(!node) {
            return false;
        }
        if(!node.classList){
            return false;
        }
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type !== 'layouts';
    }

    isLayout (node) {
        if(!node) {
            return false;
        }
        if(!node.classList){
            return false;
        }
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type === 'layouts';
    }

    isInLayout (node) {
        if(!node) {
            return false;
        }
        node = node.parentNode;
        while(node && node !== this.settings.document.body) {
            if(node.classList.contains(this.settings.moduleClass) && node.dataset.type === 'layouts') {
                return true;
            }
            node = node.parentNode
        }
    }

    isElement (node) {
        if(!node) {
            return false;
        }
        if(!node.classList){
            return false;
        }
        return node.classList.contains(this.settings.elementClass);
    }

    isEmptyElement (node) {
        if(!node) {
            return false;
        }
        if(!node.classList){
            return false;
        }
        return node.classList.contains(this.settings.emptyElementClass);
    }

    isEdit (node) {
        if(!node) {
            return false;
        }
        if(!node.classList){
            return false;
        }

        return node.classList.contains(this.settings.editClass);
    }

    isInEdit (node) {
        var order = [
            this.settings.editClass,
            this.settings.moduleClass,
        ];
        if (node && node.parentNode) {
            return this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, order);
        }
    }

    isEditOrInEdit (node) {
        return this.isEdit(node) || this.isInEdit(node);
    }

    isPlainText (node) {
        if(!node) {
            return false;
        }
        if(!node.classList){
            return false;
        }
        return node.classList.contains(this.settings.plainElementClass);
    }

    getType(node) {
        if(helpers.targetIsIcon(node)) {
            return 'icon';
        } else if(this.isEdit(node)) {
            return 'edit';
        } else if(this.isElement(node)) {
            return 'element';
        } else if(this.isModule(node)) {
            return 'module';
        }  else if(this.isLayout(node)) {
            return 'layout';
        }
    }
}
