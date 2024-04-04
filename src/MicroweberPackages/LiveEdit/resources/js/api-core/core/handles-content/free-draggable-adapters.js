
import { ElementManager } from "../classes/element";
import { ResizableInfo } from "../classes/resizable";
import liveEditHelpersService from "../live-edit-helpers.service";

export const movable = function(element, container) {



    const Mvb = mw.top().app.canvas.getWindow().Moveable;



        const toRemove = ['mw-le-resizer', 'moveable-control-box', 'mw-free-node-controller', 'mw-le-resizable--info']

        Array.from(container.children).forEach(node => {
            for (let i = 0; i < toRemove.length; i++) {
                if(node.classList.contains(toRemove[i])) {
                    node.remove();
                    break;
                }
            }
        })



        const mvb = new Mvb(container, {
            target: element,

            draggable: true,
            throttleDrag: 1,
            edgeDraggable: false,
            startDragRotate: 0,
            throttleDragRotate: 0,
            resizable: true,
            rotatable: true,
            selectable: true,
            snappable: true,
            scalable: false,
            snapContainer: container,

            /*
            snapRotataionThreshold: 5,
            snapRotationDegrees: [0, 90, 180, 270], */

            isDisplaySnapDigit: true,
            isDisplayInnerSnapDigit: true,
            snapGap: true,
            snapDirections: {"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true},
            elementSnapDirections: {"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true},
            snapThreshold: true,
            elementGuidelines: [".element", ".module", ".container", '.mw-layout-container'],
            hideDefaultLines: false,


            // radius
            roundable: false,
            isDisplayShadowRoundControls: "horizontal",
            roundClickable: "control",
            roundPadding: 15

         });

         container.__mvb = mvb


         mvb.selfElement.classList.add('no-element');

         mvb.info = new ResizableInfo({
            element: mvb.selfElement.querySelector('.moveable-s')
         })





        const beforeChange = (e) => {
            const ccss = getComputedStyle(e.target);
            const css = {
                width: ccss.width,
                height: ccss.height,
                minHeight: ccss.minHeight,
                top: ccss.top,
                left: ccss.left,
                transform: ccss.transform,
            }
             mw.top().app.state.record({
                target: '$liveEditStyle',
                value: {
                    selector:  mw.tools.generateSelectorForNode(e.target),
                    value: css
                }
            });
            container.querySelectorAll('[data-mw-free-element]').forEach(node => {
                // if(node !== e.target){
                    mw.top().app.freeDraggableElementTools.toPixel(node);
                // }
             });
             if(element.nodeName === 'IMG' && getComputedStyle(element).objectFit === 'fill'){
                mw.top().app.cssEditor.style(element, {
                    'object-fit': 'contain'
                }, false);
             }

        }
        const afterChanged = (e) => {


            container.querySelectorAll('[data-mw-free-element]').forEach(node => {
                mw.top().app.freeDraggableElementTools.toPercent(node);
                mw.top().app.cssEditor.style(node, mw.top().app.freeDraggableElementTools.getStyle(node), false);
                node.removeAttribute('style')
            });

            mw.top().app.freeDraggableElementTools.saveLayoutHeight(container);

            mvb.info.hide();

            const ccss = getComputedStyle(e.target);
            const css = {
                width: ccss.width,
                height: ccss.height,
                minHeight: ccss.minHeight,
                top: ccss.top,
                left: ccss.left,
                transform: ccss.transform,
            }
             mw.top().app.state.record({
                target: '$liveEditStyle',
                value: {
                    selector:  mw.tools.generateSelectorForNode(e.target),
                    value: css
                }
            });



            // mw.app.registerChangedState(mw.tools.firstParentOrCurrentWithClass(e.target, 'edit'), true);
            mw.app.dispatch('liveEditRefreshHandlesPosition');


            mw.top().app.registerChange(e.target);

            mw.top().app.registerAskUserToStay(true);
        }

        mvb.on("dragStart", beforeChange)
        mvb.on("resizeStart", beforeChange)
        mvb.on("rotateStart", beforeChange)

        mvb.on("dragEnd", afterChanged)
        mvb.on("resizeEnd", afterChanged)
        mvb.on("rotateEnd", afterChanged)

         mvb.on("drag", e => {



            e.target.style.transform = e.transform

             mw.top().app.liveEdit.handles.hide();
             mw.app.liveEdit.pause();
             mw.top().app.freeDraggableElementManager.setLayoutHeight(container)
         });
         mvb.on("scale", e => {

            e.target.style.transform = e.transform;
         })
         mvb.on("resize", e => {
            const heightProp = e.target.nodeName !== 'IMG' ? 'height' : 'height'
            e.target.style.width = `${e.width}px`;
            e.target.style.height = `auto`;
            e.target.style[heightProp] = `${e.height}px`;
            // e.target.style.transform = 'none';

            e.target.style.transform =  e.transform;
            if(liveEditHelpersService.targetIsIcon(e.target)) {
                e.target.style.fontSize = `${e.height}px`;
            }

            mw.top().app.liveEdit.handles.hide();
            mw.app.liveEdit.pause();
            mw.top().app.freeDraggableElementManager.setLayoutHeight(container)
            mvb.info.show(`${e.width}x${e.height}`)
        });
        mvb.on("rotate", e => {
            e.target.style.transform = e.drag.transform;
            mw.top().app.liveEdit.handles.hide();
            mw.app.liveEdit.pause();
            mw.top().app.freeDraggableElementManager.setLayoutHeight(container)
        });

        mw.top()._freeContainers.push({
            container,
            instance: mvb,
            element
        })



};
