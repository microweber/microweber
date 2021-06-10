
import {DomService} from './dom';

export class ElementAnalyzerServiceBase {

    constructor(settings) {
        this.settings = settings;
        this.tools = DomService;
    }

    isRow (node) {
        return node.classList.contains(this.settings.rowClass);
    }

    isModule (node) {
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type !== 'layouts';
    }
    isLayout (node) {
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type === 'layouts';
    }

    isElement (node) {
        return node.classList.contains(this.settings.elementClass);
    }

    isEmptyElement (node) {
        return node.classList.contains(this.settings.emptyElementClass);
    }

    isEdit (node) {
        return node.classList.contains(this.settings.editClass);
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
        return node.classList.contains(this.settings.plainElementClass);
    }
}
