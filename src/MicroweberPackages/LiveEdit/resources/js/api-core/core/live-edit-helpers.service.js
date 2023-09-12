import { DomService } from "./classes/dom";

export default {
    targetIsIcon: target => {

        if(!target) {
            return false;
        }

        if(!target.classList) {
            return false;
        }

        if(!target.className) {
            return false;
        }

        if(!target.className.includes) { // is svg
            return false;
        }


        const iconClasses = ['icon', 'mw-icon', 'material-icons', 'mdi'];
        var isIcon = target.className.includes('mw-micon-');

        if(!isIcon) {
            for (let i = 0; i < iconClasses.length; i++) {
                if (target.classList.contains(iconClasses[i])) {
                    isIcon = true;
                    break;
                }
            }
        }

        if(isIcon) {
            isIcon = DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['edit', 'module']);
        }

        return isIcon;
    },

    targetIsInacesibleModule: target => {
        // check if module is inaccesible and move the handle to the parent if its layout
        var isInaccesibleFirstChild = false;
        var isInaccesible = DomService.hasAnyOfClassesOnNodeOrParent(target, ['no-settings', 'inaccessibleModule']);
        if(target.firstChild){
              isInaccesibleFirstChild = DomService.hasAnyOfClassesOnNodeOrParent(target.firstChild, ['no-settings', 'inaccessibleModule']);
        }
        if (isInaccesible || isInaccesibleFirstChild) {
            return true;
        }

    },

    targetGetFirstModuleOfType: (target, moduleType) => {
        let currentElement = target;

        while (currentElement) {
            if (currentElement && currentElement.classList && currentElement.classList.contains('module')) {
                const typeAttr = currentElement.getAttribute('type') || currentElement.getAttribute('data-type');
                if (typeAttr === moduleType) {
                    return currentElement;
                }
            }

            // Move up to the parent element
            if(currentElement.parentElement) {
                currentElement = currentElement.parentElement;
            }
        }

        // Return null if no matching module is found
        return null;
    }
}
