
import { parse } from "vue/compiler-sfc";
import { ElementManager } from "../classes/element";
import { ResizableInfo } from "../classes/resizable";
import liveEditHelpersService from "../live-edit-helpers.service";

export const movable = function(element, container) {

      const closestElement = (x, y) => {
        const all = container.querySelectorAll('.mw-free-element-droppable');
        let closestEl = elements.eq(0);
        let offset = closestEl.offset();
        offset.left += closestEl.outerWidth() / 2;
        offset.top += closestEl.outerHeight() / 2;
        let minDist = Math.sqrt((offset.left - x) * (offset.left - x) + (offset.top - y) * (offset.top - y));

        all.forEach(el => {

          offset = el.offset();
          offset.left += el.outerWidth() / 2;
          offset.top += el.outerHeight() / 2;
          const dist = Math.sqrt((offset.left - x) * (offset.left - x) + (offset.top - y) * (offset.top - y));
          if (dist < minDist) {
            minDist = dist;
            closestEl = el;
          }
        });

        return closestEl;
      };


    const collision = (target) => {
         const all = container.querySelectorAll('.mw-free-element-droppable');
         const temp = [];
         for(let i = 0; i < all.length; i++) {
                if(all[i] === target) {
                    continue;
                }
                if(mw.tools.collision(target, all[i])) {
                    temp.push( all[i]);
                }
         }
    };



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
            rotationPosition: "bottom",
            rotationPosition: "right",
            draggable: true,
            throttleDrag: 1,
            edgeDraggable: false,
            startDragRotate: 0,
            throttleDragRotate: 0,
            resizable: true,
            rotatable: true,
            throttleRotate: 1,
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

         mvb.rotateInfo = new ResizableInfo({
            element: mvb.selfElement.querySelector('.moveable-rotation-control'),
            position: 'top'
         })
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

            const isFixed = container.classList.contains('mw-free-layout-container-fixed');

            mw.top().app.freeDraggableElementTools.getFreeElements(container).forEach(node => {
                if(!isFixed){
                    mw.top().app.freeDraggableElementTools.toPixel(node);
                }
             });
             if(element.nodeName === 'IMG' && getComputedStyle(element).objectFit === 'fill'){
                mw.top().app.cssEditor.style(element, {
                    'object-fit': 'contain'
                }, false);
             }

        }
        const afterChanged = (e) => {


            const isFixed = container.classList.contains('mw-free-layout-container-fixed');

            mw.top().app.freeDraggableElementTools.getFreeElements(container).forEach(node => {
                if(!isFixed){
                    mw.top().app.freeDraggableElementTools.toPercent(node);
                }

                mw.top().app.cssEditor.style(node, mw.top().app.freeDraggableElementTools.getStyle(node), false);
                node.removeAttribute('style')
            });

            mw.top().app.freeDraggableElementTools.saveLayoutHeight(container);

            mvb.rotateInfo.hide();
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

            const type = e.target.classList.contains('module') ? 'module' : 'element';

            mw.top().app.liveEdit.handles.get(type).set(e.target);



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

            let etop = e.top;

            if(etop < 0) {
                etop = 0;
            }

            e.target.style.left = `${e.left}px`;
            e.target.style.top = `${etop}px`;

            const col = collision(e.target);
            console.log(col)

           //  e.target.style.transform = e.transform

             mw.top().app.liveEdit.handles.hide();
             mw.app.liveEdit.pause();
             mw.top().app.freeDraggableElementManager.setLayoutHeight(container)
         });


         var prevX = 0;
         var prevY = 0;

         mvb.on("resize", e => {
            const heightProp = e.target.nodeName !== 'IMG' ? 'height' : 'height'
            e.target.style.width = `${e.width}px`;
            e.target.style.height = `auto`;
            e.target.style[heightProp] = `${e.height}px`;



            if(e.drag ) {


                const delta = [...e.delta];
                delta[0] = delta[0] > 1 ? 1 : delta[0] < -1 ? -1 : delta[0];
                delta[1] = delta[1] > 1 ? 1 : delta[1] < -1 ? -1 : delta[1];


                e.target.style.left = `${e.drag.left + (delta[0] )}px`;

                e.target.style.top = `${e.drag.top + (delta[1] )}px`;



            }


            if(liveEditHelpersService.targetIsIcon(e.target)) {
                e.target.style.fontSize = `${Math.min(e.height, e.width)}px`;
            }

            mw.top().app.liveEdit.handles.hide();
            mw.app.liveEdit.pause();
            mw.top().app.freeDraggableElementManager.setLayoutHeight(container)
            mvb.info.show(`${e.width}x${e.height}`);

        });
        mvb.on("rotate", e => {


            e.target.style.transform = 'rotate(' + e.absoluteRotation + 'deg)';
            mw.top().app.liveEdit.handles.hide();
            mw.app.liveEdit.pause();
            mw.top().app.freeDraggableElementManager.setLayoutHeight(container);

            mvb.rotateInfo.show(`${Math.round(e.absoluteRotation)}&deg;`);
        });

        mw.top().app.canvas.on('resize', () => mvb.updateRect());


        mw.top()._freeContainers.push({
            container,
            instance: mvb,
            element
        })



};
