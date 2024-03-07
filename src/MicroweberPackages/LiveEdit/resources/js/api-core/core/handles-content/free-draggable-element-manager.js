import MicroweberBaseClass from "../../services/containers/base-class";
import { ElementManager } from "../classes/element";


export class FreeDraggableElementManager extends MicroweberBaseClass {


    constructor() {
        super();
    }


    static toPercent(node, container){
        if(!node || node.nodeType !== 1) {
            return
        }
        if(!container) {
            container = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['mw-layout-container'])
        }
        if(!container) {
            return
        }
        const containerOff = container.getBoundingClientRect();
        const el = ElementManager(node);
        const off = getComputedStyle(node);


        el.css({
            left: ((off.left / containerOff.width) * 100) + '%',
            top: ((off.top / containerOff.height) * 100) + '%',
        })

    }


    setLayoutHeight(layout) {
        if(!layout || layout.nodeType !== 1) {
            return
        }
        const container = layout.classList.contains('mw-layout-container') ? layout : layout.querySelector('.mw-layout-container');
        if(!container) {
            return
        }

        const containerOff = ElementManager(container).offset();

        mw.app.dispatch('liveEditRefreshHandlesPosition');

        let containerheight = 50;

        container.querySelectorAll('.element,.module').forEach(node => {

                const el = ElementManager(node);
                const off = ElementManager(node).offset();

                if(((off.offsetTop - containerOff.offsetTop) + off.height) > containerheight) {
                    containerheight = (off.offsetTop - containerOff.offsetTop) + off.height;
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

        container.querySelectorAll('.element .element,.module').forEach(node => {


                const el = ElementManager(node);
                const off = ElementManager(node).offset();

                if(((off.offsetTop - containerOff.offsetTop) + off.height) > containerheight) {
                    containerheight = (off.offsetTop - containerOff.offsetTop) + off.height;
                }
                el.css({
                    top: off.offsetTop - containerOff.offsetTop,
                    left: off.offsetLeft - containerOff.offsetLeft,
                    width: off.width,
                    height: off.height,
                    position: 'absolute'
                })

                this.makeFreeDraggableElement(node, container)

        })
        container.style.height = containerheight + 'px';

    }

    makeFreeDraggableElement(element, container) {

        const scope = this;

        $(element).draggable(
            {
                // containment: container,
                scroll: false,
                start: function (event, ui) {

                },
                drag: function (event, ui) {


                    scope.setLayoutHeight(container)

                    mw.app.dispatch('liveEditRefreshHandlesPosition');

                },
                stop: function (event, ui) {

                    FreeDraggableElementManager.toPercent(this);

                    mw.app.dispatch('liveEditRefreshHandlesPosition');

                }
            }
        );


        mw.app.dispatch('liveEditRefreshHandlesPosition');


    }

    destroyFreeDraggableElement(element) {
        $(element).draggable('destroy');
    }

}
