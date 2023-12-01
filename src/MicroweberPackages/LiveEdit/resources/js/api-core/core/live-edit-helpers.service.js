import {DomService} from "./classes/dom";

export default {

    targetIsImageElement: target => {
        if (!target) {
            return false;
        }
        if (!target.classList) {
            return false;
        }
        if (target.nodeName === 'IMG') {
            //has class .element
            if (target.classList.contains('element')) {
                return true;
            }

        }
    },

    targetIsIcon: target => {

        if (!target) {
            return false;
        }

        if (!target.classList) {
            return false;
        }

        if (!target.className) {
            return false;
        }

        if (!target.className.includes) { // is svg
            return false;
        }


        const iconClasses = ['icon', 'mw-icon', 'material-icons', 'mdi'];
        var isIcon = target.className.includes('mw-micon-');

        if (!isIcon) {
            for (let i = 0; i < iconClasses.length; i++) {
                if (target.classList.contains(iconClasses[i])) {
                    isIcon = true;
                    break;
                }
            }
        }

        if (isIcon) {
            isIcon = DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['edit', 'module']);
        }

        return isIcon;
    },

    targetIsInacesibleModule: target => {


        //check if case is inaccessibleModuleIfParentIsLayout
        var isInaccesibleInLayoutParent = DomService.hasAnyOfClassesOnNodeOrParent(target, ['inaccessibleModuleIfFirstParentIsLayout']);
        if(isInaccesibleInLayoutParent){
            // check if parent is layout
            var parentIsLayout = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['module-layouts']);
            if(parentIsLayout){
                return true;
            }
        }

        // check if module is inaccesible and move the handle to the parent if its layout
        var isInaccesibleFirstChild = false;
        var isInaccesible = DomService.hasAnyOfClassesOnNodeOrParent(target, ['no-settings', 'inaccessibleModule']);
        if (target.firstChild) {
            isInaccesibleFirstChild = DomService.hasAnyOfClassesOnNodeOrParent(target.firstChild, ['no-settings', 'inaccessibleModule']);
        }
        if (isInaccesible || isInaccesibleFirstChild) {
            return true;
        }



    },

    targetGetFirstModuleOfType: (target, moduleType) => {
        var isInsideModule = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['module']);
        if (isInsideModule) {
            var typeAttr = isInsideModule.getAttribute('type');
            if (!typeAttr) {
                typeAttr = isInsideModule.getAttribute('data-type');
            }
            if (typeAttr) {
                if (typeAttr === moduleType) {
                    return isInsideModule;
                }
            }
        }
    },
    targetIsInEditField: element => {
        var isInsideEditField = DomService.firstParentOrCurrentWithAnyOfClasses(element, ['edit']);
        return isInsideEditField;

    },


    targetIsDisabledWriteInEditField: element => {
        if (element.classList.contains('no-typing')) {
            return true;
        }
        if (element.classList.contains('edit')) {
            var isInContainer = element.hasAttribute('data-layout-container');
            if (isInContainer) {
                return false;
            }

        }

        return false;
    },


    targetHasAbilityToDropElementsInside: target => {
        var items = /^(span|h[1-6]|hr|ul|ol|input|table|b|em|i|a|img|textarea|br|canvas|font|strike|sub|sup|dl|button|small|select|big|abbr|body)$/i;
        if (typeof target === 'string') {
            return !items.test(target);
        }
        if (!DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['allow-drop', 'nodrop'])) {
            return false;
        }
        if (DomService.hasAnyOfClasses(target, ['plain-text'])) {
            return false;
        }
        var x = items.test(target.nodeName);
        if (x) {
            return false;
        }
        if (DomService.hasParentsWithClass(target, 'module')) {
            if (DomService.hasParentsWithClass(target, 'edit')) {
                return true;
            } else {
                return false;
            }
        } else if (mw.tools.hasClass(target, 'module')) {
            return false;
        }
        return true;
    },


    targetGetFirstRowElement: target => {
        var row = DomService.firstParentOrCurrentWithAnyOfClasses(target, ['mw-row', 'row']);
        if (row) {
            return row;
        }
        return false;
    },
    targetGetFirstColElement: target => {
        if (mw.top().app && mw.top().app.templateSettings) {
            var col_classes_bs = mw.top().app.templateSettings.helperClasses.external_grids_col_classes;
            var col_classes_mw = mw.top().app.templateSettings.helperClasses.mw_grids_col_classes;
            var col_classes = col_classes_bs.concat(col_classes_mw);
            var col = DomService.firstParentOrCurrentWithAnyOfClasses(target, col_classes);
            if (col) {
                return col;
            }
            return false;
        }
    }


}
