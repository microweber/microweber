import MicroweberBaseClass from "../../services/containers/base-class";
import { ElementManager } from "../classes/element";


export class FreeDraggableElementManager extends MicroweberBaseClass {


    static toPercent(node, container){
        if(!node || node.nodeType !== 1) {
            return
        }
        if(!container) {
            container = mw.tools.firstParentWithAnyOfClasses(node, ['mw-layout-container'])
        }
        if(!container) {
            return
        }
        const containerOff = container.getBoundingClientRect();
        const el = ElementManager(node);
        const off = node.getBoundingClientRect();

        el.css({
            left: ((off.left / containerOff.width) * 100) + '%',
            top: ((off.top / containerOff.height) * 100) + '%',
        })

    }


    setLayoutHeight(layout) {
        if(!layout || layout.nodeType !== 1) {
            return
        }
        const container = layout.querySelector('.mw-layout-container');
        if(!container) {
            return
        }

        const containerOff = ElementManager(container).offset();


        let containerheight = 50;

        layout.querySelectorAll('.element,.module').forEach(node => {
            if(!mw.tools.firstParentWithAnyOfClasses(node, ['element', 'module'])) {
                const el = ElementManager(node);
                const off = ElementManager(node).offset();

                if(((off.offsetTop - containerOff.offsetTop) + off.height) > containerheight) {
                    containerheight = (off.offsetTop - containerOff.offsetTop) + off.height;
                }

            }
        })
        container.style.height = containerheight + 'px';

    }

    freeLayoutNodes(layout) {
        if(!layout || layout.nodeType !== 1) {
            return
        }
        const container = layout.querySelector('.mw-layout-container');
        if(!container) {
            return
        }

        container.style.position = 'relative';

        const containerOff = ElementManager(container).offset();


        let containerheight = 50;

        layout.querySelectorAll('.element,.module').forEach(node => {
            if(!mw.tools.firstParentWithAnyOfClasses(node, ['element', 'module'])) {
                const el = ElementManager(node);
                const off = ElementManager(node).offset();

                if(((off.offsetTop - containerOff.offsetTop) + off.height) > containerheight) {
                    containerheight = (off.offsetTop - containerOff.offsetTop) + off.height;
                }
                el.css({
                    top: off.offsetTop - containerOff.offsetTop,
                    left: off.offsetLeft - containerOff.offsetLeft,
                    position: 'absolute'
                })
            }
        })
        container.style.height = containerheight + 'px';

    }

    makeFreeDraggableElement(element) {


        const layout = $(element).parents( ".module-layouts").eq(0);
        layout.append(grid.get(0))

        $(element)
            .draggable({
                 grid: [ 20, 20 ],
                 start: function( event, ui ) {

                 },
                 drag: function( event, ui ) {
                    var snapTolerance = $(this).draggable('option', 'snapTolerance');
                    var topRemainder = ui.position.top % 20;
                    var leftRemainder = ui.position.left % 20;

                    if (topRemainder <= snapTolerance) {
                        ui.position.top = ui.position.top - topRemainder;
                    }

                    if (leftRemainder <= snapTolerance) {
                        ui.position.left = ui.position.left - leftRemainder;
                    }
                },
                containment: layout
            })
            .css("position", "absolute");

    }

    destroyFreeDraggableElement(element) {
        $(element).draggable('destroy');
    }

}
