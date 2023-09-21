import MicroweberBaseClass from "../../services/containers/base-class";

export class ElementSettingsTarget extends MicroweberBaseClass {
    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;
    }

    getSettingsTarget(target) {
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

}
