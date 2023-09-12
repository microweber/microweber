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
    }
}
