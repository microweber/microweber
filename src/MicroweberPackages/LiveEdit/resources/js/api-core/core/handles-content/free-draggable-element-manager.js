import MicroweberBaseClass from "../../services/containers/base-class";


export class FreeDraggableElementManager extends MicroweberBaseClass {

    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;
    }


    makeFreeDraggableElement(element) {
        $(element).draggable();
   //     $(element).draggable().css("position", "absolute");
    }

    destroyFreeDraggableElement(element) {
        $(element).draggable('destroy');

    }

}
