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

        mw.app.dispatch('liveEditRefreshHandlesPosition');

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
        //$(element).draggable();
        // make on percent
        $(element).draggable(
            {
              //  containment: "parent",
                scroll: false,
                start: function (event, ui) {

                },
                drag: function (event, ui) {
                    mw.app.dispatch('liveEditRefreshHandlesPosition');

                },
                stop: function (event, ui) {

                    mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                        node: this[0],
                        prop: 'top',
                        val: $(this).position().top
                    });

                    mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                        node: this[0],
                        prop: 'left',
                        val: $(this).position().left
                    });

                    mw.app.dispatch('liveEditRefreshHandlesPosition');

                }
            }
        );


        mw.app.dispatch('liveEditRefreshHandlesPosition');


   //     $(element).draggable().css("position", "absolute");
    }

    destroyFreeDraggableElement(element) {
        $(element).draggable('destroy');
    }

}
