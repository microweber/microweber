
import {DomService} from './dom';

export class ElementAnalyzerServiceBase {

    constructor(settings) {
        this.settings = settings;
        this.tools = DomService;
    }

    isRow (node) {
        return node.classList.has(this.settings.rowClass);
    }

    isModuleButNotLayout (node) {
        return node.dataset.type !== 'layouts';
    }
    isLayout (node) {
        return node.dataset.type === 'layouts';
    }

    isElement (node) {
        return node.classList.has(this.settings.elementClass);
    }

    isEmpty (node) {
        return node.classList.has(this.settings.emptyElementClass);
    }


    isEdit (node) {
        return node.classList.has(this.settings.editClass);
    }

    isInEdit (node) {
        var order = [
            this.settings.editClass,
            this.settings.moduleClass,
        ];
        return this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, order);
    }

    isEditOrInEdit (node) {
        return this.isEdit(node) || this.isInEdit(node);
    }

    isPlainText (node) {
        return node.classList.has(this.settings.plainElementClass);
    }

}
