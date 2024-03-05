import MicroweberBaseClass from "../../services/containers/base-class";


export class FreeDraggableElementManager extends MicroweberBaseClass {
    makeFreeDraggableElement(element) {

        $(element).draggable().css("position", "absolute");

    }

    destroyFreeDraggableElement(element) {
        $(element).draggable('destroy');
    }

}
