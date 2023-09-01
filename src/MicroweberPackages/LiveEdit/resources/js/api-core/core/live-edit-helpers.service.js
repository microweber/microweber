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
            isIcon = DomService.parentsOrCurrentOrderMatchOrOnlyFirst(target, ['edit', 'module'])
        }

        return isIcon;
    }
}
