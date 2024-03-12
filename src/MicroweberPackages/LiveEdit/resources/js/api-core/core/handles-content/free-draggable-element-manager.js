import MicroweberBaseClass from "../../services/containers/base-class";
import { ElementManager } from "../classes/element";




export class FreeDraggableElementManager extends MicroweberBaseClass {


    constructor() {
        super();
         // mw.require('https://cdnjs.cloudflare.com/ajax/libs/moveable/0.53.0/moveable.min.js');
         mw.top().app.canvas.getWindow().mw.require('https://cdnjs.cloudflare.com/ajax/libs/moveable/0.53.0/moveable.min.js');

    }


    static getStyle(node){
        const res = {};
        if(!node || node.nodeType !== 1) {
            return res
        }

        let i = 0, l = node.style.length;

        for ( ; i < l; i++ ) {
            const prop = node.style[i];
            if(prop.indexOf('--') !== -1) {
                continue;
            }
            res[prop] = node.style.getPropertyValue(prop)
        }

        return res;
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
            left: ((parseFloat(off.left) / containerOff.width) * 100) + '%',
            top: ((parseFloat(off.top) / containerOff.height) * 100) + '%',
        })

    }

    static toPixel(node){


        if(!node || node.nodeType !== 1) {
            return
        }

        const el = ElementManager(node);

        const css = getComputedStyle(node);


        el.css({
            left: css.left,
            top: css.top,
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


        const toBeFree = [];


        container.querySelectorAll('.element,.module').forEach(node => {

                if(node.querySelector('.element,.module')) {
                    return
                }


                const el = ElementManager(node);
                const off = ElementManager(node).offset();

                if(((off.offsetTop - containerOff.offsetTop) + off.height) > containerheight) {
                    containerheight = (off.offsetTop - containerOff.offsetTop) + off.height;
                }

                toBeFree.push({
                    el, node,
                    css: {
                        top: off.offsetTop - containerOff.offsetTop,
                        left: off.offsetLeft - containerOff.offsetLeft,
                        width: off.width,
                        maxWidth: '100%',
                        height: off.height,
                        position: 'absolute'
                    }
                })





        })
        container.style.height = containerheight + 'px';
        toBeFree.forEach(obj => {
            obj.el.css(obj.css)
            this.makeFreeDraggableElement(obj.node, container)
        })

    }

    adapters = {
        jQuery: function(element, container, scope) {
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
        },
        movable: function(element, container, scope) {
            const draggable = true;
            const throttleDrag = 1;
            const edgeDraggable = false;
            const startDragRotate = 0;
            const throttleDragRotate = 0;

            const Mvb = mw.top().app.canvas.getWindow().Moveable;

            if(!Mvb.mw) {
                Mvb.mw = {};
                Mvb.mw._movables = [];

                mw.top().app.liveEdit.handles.get('element').on('targetChange', node => {

                    Mvb.mw._movables.forEach(instance => {
                        instance.selfElement.style.display = 'none';
                    });
                    if(node.__mw_movable) {

                        node.__mw_movable.selfElement.style.display = 'block';
                    }

                })
                mw.top().app.liveEdit.handles.get('module').on('targetChange', node => {
                    Mvb.mw._movables.forEach(instance => {
                        instance.selfElement.style.display = 'none';
                    })
                    if(node.__mw_movable) {
                        node.__mw_movable.selfElement.style.display = 'block';
                    }
                })
            }


            const mvb = new Mvb(container, {
                 target: element,

                 draggable: draggable,
                 throttleDrag: throttleDrag,
                 edgeDraggable: edgeDraggable,
                 startDragRotate: startDragRotate,
                 throttleDragRotate: throttleDragRotate,
                 resizable: true,
                 rotatable: true,
                 snappable: true,
                 scalable: false,
                 snapContainer: container,

                maxSnapElementGuidelineDistance: 50,
                maxSnapElementGapDistance: 50,
                snapRotataionThreshold: 5,
                snapRotationDegrees: [0, 90, 180, 270],

                isDisplaySnapDigit: true,
                isDisplayInnerSnapDigit: true,
                snapGap: true,
                snapDirections: {"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true},
                elementSnapDirections: {"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true},
                snapThreshold: true,
                elementGuidelines: [".element", ".module", ".container"],
                hideDefaultLines: false,


                // radius
                roundable: false,
                isDisplayShadowRoundControls: "horizontal",
                roundClickable: "control",
                roundPadding: 15

             });
             element.dataset.hideResizer = 'true';
             element.dataset.hideOutline = 'true';

             Mvb.mw._movables.push(mvb)


             element.__mw_movable = mvb;

             mvb.selfElement.style.display = 'none';


             mvb.on("dragStart", e => {
                container.querySelectorAll('[data-hide-resizer]').forEach(node => {
                    if(node !== e.target){
                        FreeDraggableElementManager.toPixel(node);
                    }
                 })


            });



            const afterChanged = () => {
                container.querySelectorAll('[data-hide-resizer]').forEach(node => {

                        FreeDraggableElementManager.toPercent(node);

                        mw.top().app.cssEditor.style(node, FreeDraggableElementManager.getStyle(node));
                        node.removeAttribute('style')

                });

                mw.app.registerChangedState(container);
                mw.app.dispatch('liveEditRefreshHandlesPosition');
            }

             mvb.on("dragEnd", afterChanged)
             mvb.on("resizeEnd", afterChanged)
             mvb.on("rotateEnd", afterChanged)

             mvb.on("drag", e => {
                 e.target.style.top = e.top + 'px';
                 e.target.style.left = e.left + 'px';
                 mw.top().app.liveEdit.handles.hide();
                 mw.app.liveEdit.pause();
                 scope.setLayoutHeight(container)
             });
             mvb.on("resize", e => {
                e.target.style.width = `${e.width}px`;
                e.target.style.height = `${e.height}px`;
                e.target.style.transform = e.drag.transform;
                mw.top().app.liveEdit.handles.hide();
                mw.app.liveEdit.pause();
                scope.setLayoutHeight(container)
            });
            mvb.on("rotate", e => {
                e.target.style.transform = e.drag.transform;
                mw.top().app.liveEdit.handles.hide();
                mw.app.liveEdit.pause();
                scope.setLayoutHeight(container)
            });
        }
    }

    makeFreeDraggableElement(element, container) {
        const adapter = 'movable';
        this.adapters[adapter](element, container, this);
        mw.app.dispatch('liveEditRefreshHandlesPosition');
    }

    destroyFreeDraggableElement(element) {
        $(element).draggable('destroy');
    }

}
