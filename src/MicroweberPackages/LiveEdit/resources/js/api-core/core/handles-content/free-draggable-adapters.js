
import { ElementManager } from "../classes/element";
import { ResizableInfo } from "../classes/resizable";

export const movable = function(element, container) {



    const Mvb = mw.top().app.canvas.getWindow().Moveable;

    if(!mw.top()._freeContainers) {

        mw.top()._freeContainers = [];
    }

    const containerMovable = mw.top()._freeContainers.find(instance => instance.container === container);

    if(!containerMovable) {

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









         mvb.selfElement.classList.add('no-element');

         mvb.info = new ResizableInfo({
            element: mvb.selfElement.querySelector('.moveable-s')
         })

      ;



        const beforeChange = (e) => {
            container.querySelectorAll('[data-mw-free-element]').forEach(node => {
                // if(node !== e.target){
                    mw.top().app.freeDraggableElementTools.toPixel(node);
                // }
             });
             if(element.nodeName === 'IMG' && getComputedStyle(element).objectFit === 'fill'){
                mw.top().app.cssEditor.style(element, {
                    'object-fit': 'contain'
                });
             }
        }
        const afterChanged = (e) => {


            container.querySelectorAll('[data-mw-free-element]').forEach(node => {
                mw.top().app.freeDraggableElementTools.toPercent(node);
                mw.top().app.cssEditor.style(node, mw.top().app.freeDraggableElementTools.getStyle(node));
                node.removeAttribute('style')
            });

            mw.top().app.freeDraggableElementManager.saveLayoutHeight(container);

            mvb.info.hide()

            mw.app.registerChangedState(container);
            mw.app.dispatch('liveEditRefreshHandlesPosition');
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





            e.target.style.transform =  e.transform




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
    }



    mw.top().app.liveEdit.handles.get('element').on('targetChange', node => {

        mw.top()._freeContainers.forEach(obj => {

            obj.instance.selfElement.style.display = 'none';
        });

        const layout = mw.top().app.freeDraggableElementTools.getElementContainer(node)
        if(layout ) {
            let rec = mw.top()._freeContainers.find(obj => obj.container === layout);

            if(rec){
                rec.instance.setState({
                    target: node
                  }, (e,b) => {

                  });

                rec.instance.selfElement.style.display = 'block';
                /*const off = ElementManager(node).offset();
                console.log(node)
                console.log(off)
                console.log(rec.instance)

                ElementManager(rec.element).css({
                    top: off.offsetTop + 'px',
                    left: off.offsetLeft + 'px',
                    width: off.width + 'px',
                    height: off.height + 'px',
                    display: 'block'
                })*/
            }

        }


    })
    mw.top().app.liveEdit.handles.get('module').on('targetChange', node => {
        mw.top()._freeContainers.forEach(obj => {
            obj.instance.selfElement.style.display = 'none';
        })
        const layout = mw.top().app.freeDraggableElementTools.getElementContainer(node)
        if(layout ) {
            let rec = mw.top()._freeContainers.some(obj => obj.container === layout);
            if(rec){
                rec.instance.selfElement.style.display = 'block';
            }

        }
    })

    if(!element || element.__mw_movable) {
        return;
    }



};
