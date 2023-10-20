import MicroweberBaseClass from "../../services/containers/base-class";
import {DomService} from "../classes/dom";

export class ElementSettingsTarget extends MicroweberBaseClass {
    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;
    }

    getSettingsTarget(target) {
        if(!target){
            return target;
        }

        if(!target.firstElementChild) {
            return target;
        }



        var firstChild = target.firstElementChild;
       // var isCloneableImage = (target.classList.contains('cloneable') && target.nodeName === 'IMG');
        var isCloneableWithImageAsFirstChild = target.classList && target.classList.contains('cloneable') && firstChild && firstChild.nodeName === 'IMG';
        var isCloneableWithImageAsFirstChildAsBg = target.classList && target.classList.contains('cloneable') && firstChild && firstChild.classList && firstChild.classList.contains('img-as-background');

        if(isCloneableWithImageAsFirstChild){
            // move the element to the image for edit
            target = firstChild;
        } else if(isCloneableWithImageAsFirstChildAsBg){
            // move the element to the image for edit
            target = firstChild.firstElementChild;
        }

        return target;
    }


    canDropInTarget(target) {
        var can = DomService.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']);
        if(!can) {
            if(target.classList.contains('mw-col')) {
                if(target.firstElementChild && target.firstElementChild.classList.contains('mw-col-container')) {
                    if(target.firstElementChild.firstElementChild && target.firstElementChild.firstElementChild.classList.contains('allow-drop')) {
                        can = true;
                    }
                }
            }
        }
        return can;
    }




}
