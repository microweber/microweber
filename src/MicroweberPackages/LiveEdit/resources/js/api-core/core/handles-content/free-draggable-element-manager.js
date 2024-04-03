import MicroweberBaseClass from "../../services/containers/base-class";
import { DomService } from "../classes/dom";
import { ElementManager } from "../classes/element";
import { ResizableInfo } from "../classes/resizable";
import { movable } from "./free-draggable-adapters";




const adapter = movable;



export class FreeDraggableElementManagerTools extends MicroweberBaseClass {
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

    static getFirstFreeNode(element) {
        if(!element) {
            return;
        }
        while (element) {
            if(element.dataset && element.dataset.mwFreeElement === 'true') {
                return element;
            }
            element = element.parentNode
        }

    }

    static toPercent(node, container){
        return;
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
        return;

        if(!node || node.nodeType !== 1) {
            return
        }

        const el = ElementManager(node);

        const css = getComputedStyle(node);


        el.css({
            left: css.left,
            top: css.top,
            /*width: css.width,
            height: css.height,*/
        })

    }


    static getTargetNode(node) {
        if(!node) {
            return
        }
        if(node.nodeType !== 1) {
            node =  node.parentNode;
        }
        if(!node) {
            return
        }
        return mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['element', 'module'])
    }


    static getElementContainer(node) {
        if(!node) {
            return
        }
        if(node.nodeType !== 1) {
            node =  node.parentNode;
        }
        if(!node) {
            return
        }

        return mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['mw-free-layout-container'])
    }

    static getLayoutContainer(layout) {
        if(!layout || layout.nodeType !== 1) {
            return
        }

        return layout.classList.contains('mw-layout-container') ? layout : layout.querySelector('.mw-layout-container')

    }


    static saveLayoutHeight(layout) {
        if(!layout || layout.nodeType !== 1) {
            return
        }
        const container = layout.classList.contains('mw-layout-container') ? layout : layout.querySelector('.mw-layout-container');
        if(!container ) {
            return
        }
        const css = { };

        if(container.style.height){
            css['height'] = container.style.height;
        }
        if(container.style.minHeight){
            css['min-height'] = container.style.minHeight;
        }
        mw.top().app.cssEditor.style(container, css);
    }

}
export class FreeDraggableElementManager extends FreeDraggableElementManagerTools {
    constructor() {
        super();

        mw.top().on('onLiveEditReady', event => {
            this.init();
        });

        mw.top().app.state.on('undo', () => this.initLayouts());
        mw.top().app.state.on('redo', () => this.initLayouts());


        this.#handleTargetChange();


    }

    #handleTargetChange(){
        mw.top().app.liveEdit.handles.get('element').on('targetChange', node => {

            if(!mw.top()._freeContainers){
                return
            }

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

                }

            }


        })
        mw.top().app.liveEdit.handles.get('module').on('targetChange', node => {
            if(!mw.top()._freeContainers){
                return
            }
            mw.top()._freeContainers.forEach(obj => {
                obj.instance.selfElement.style.display = 'none';
            })
            const layout = mw.top().app.freeDraggableElementTools.getElementContainer(node)
            if(layout ) {
                let rec = mw.top()._freeContainers.find(obj => obj.container === layout);
                if(rec){
                    rec.instance.setState({
                        target: node
                      }, (e,b) => {

                      });
                    rec.instance.selfElement.style.display = 'block';
                }

            }
        })
    }

    #adapter  = adapter;

    initLayout(node) {
        const _freNodeController = document.createElement('div');
        _freNodeController.classList.add('mw-free-node-controller');
        _freNodeController.style.position = 'absolute';
        _freNodeController.style.zIndex = '10';
        node.appendChild(_freNodeController);

        console.log('initLayout', node);


        const sync = (css = {}) => {
            for (const key in css) {
                _freNodeController.target.style[key] = css[key];
            }
        }


        this.#adapter.call(this, _freNodeController, node, this);
        _freNodeController.style.display = 'none';



        const resizer = new Resizable({
            element: node,
            document: node.ownerDocument,
            direction: 'vertical',
            heightProp: height => {
                node.style.minHeight = Math.max(height, node.__autoLayoutHeight ? node.__autoLayoutHeight : 100 ) + 'px';
            },
        });

        resizer.on('ready', () => {
            if(!resizer.handles){
                return;
            }
            resizer.handles.top.style.display = 'none';
            resizer.handles.right.style.display = 'none';

            resizer.handles.left.style.display = 'none';
        });

        resizer.mount();



        ElementManager(resizer.handles.bottom ).css({
            'width': '35px',
            'height': '35px',
            'background': '#0078ff',
            'right': '10%',
            'bottom': '25px',
            'left': 'auto',
            'padding': '8px',
            'border-radius': '5px',
        }).html(`
        <svg height="19px" width="19px" version="1.1"   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 349.455 349.455" xml:space="preserve">
            <path style="fill:#ffffff;" d="M248.263,240.135c-1.407-1.407-3.314-2.197-5.304-2.197c-1.989,0-3.896,0.79-5.304,2.197
                l-45.429,45.429l0.001-221.673l45.428,45.429c1.407,1.407,3.314,2.197,5.304,2.197c1.989,0,3.896-0.79,5.304-2.197l14.143-14.143
                c1.406-1.406,2.196-3.314,2.196-5.303c0-1.989-0.79-3.897-2.196-5.303L180.032,2.197C178.625,0.79,176.717,0,174.728,0
                c-1.989,0-3.896,0.79-5.304,2.197L87.049,84.573c-1.406,1.407-2.196,3.314-2.196,5.303c0,1.989,0.79,3.897,2.197,5.304
                l14.143,14.142c1.464,1.464,3.384,2.196,5.303,2.196c1.919,0,3.839-0.732,5.304-2.197l45.429-45.43l-0.001,221.673l-45.428-45.429
                c-1.407-1.407-3.314-2.197-5.304-2.197c-1.989,0-3.896,0.79-5.304,2.197l-14.143,14.143c-1.406,1.406-2.196,3.314-2.196,5.303
                c0,1.989,0.79,3.897,2.196,5.303l82.374,82.374c1.465,1.464,3.385,2.197,5.304,2.197c1.919,0,3.839-0.733,5.304-2.197l82.375-82.375
                c1.406-1.406,2.196-3.314,2.196-5.303c0-1.989-0.79-3.897-2.196-5.303L248.263,240.135z"/>
        </svg>
        `)



        resizer.on('resizeStart', () => {


            mw.top().app.canvas.getDocument().body.classList.add('mw-live--layout-resizing');;
            mw.top().app.liveEdit.handles.hide();
            mw.app.liveEdit.pause();
        });



        resizer.on('resizeStop', () => {



            FreeDraggableElementManager.saveLayoutHeight(node);

            mw.top().app.canvas.getDocument().body.classList.remove('mw-live--layout-resizing');;

            mw.app.liveEdit.play();
        })


    }

    initLayouts() {

        if(!mw.top()._freeContainers) {

            mw.top()._freeContainers = [];
        }

        let layouts = mw.top().app.canvas.getDocument().querySelectorAll('.mw-free-layout-container');


        mw.top()._freeContainers = mw.top()._freeContainers.filter(instance => !!instance.container.__mvb);

        let i = 0, l = layouts.length;
        for ( ; i < l; i++) {
            const containerMovable = mw.top()._freeContainers.find(instance => instance.container === layouts[i] );
            console.log(containerMovable, this)
            if(!containerMovable) {
                this.initLayout(layouts[i])
            }
        }
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
            if(node.className.indexOf('moveable-') !== -1) {
                return;
            }


                const el = ElementManager(node);
                const off = ElementManager(node).offset();

                if(((off.offsetTop - containerOff.offsetTop) + off.height) > containerheight) {
                    containerheight = (off.offsetTop - containerOff.offsetTop) + off.height;
                }


        });

        container.style.height = containerheight + 'px';
        container.__autoLayoutHeight = containerheight ;

    }

    makeFreeDraggableElement(element, container = null) {
        this.freeElement(element, container);
        mw.app.dispatch('liveEditRefreshHandlesPosition');
    }

    freeElement(element, container ) {

        if(!container) {
            container = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['mw-layout-container'])
        }
        if(!container) {
            return
        }

        element.dataset.mwFreeElement = true;

        const css = getComputedStyle(element);
        if (css.position === 'static') {
            element.style.position = 'absolute';
            element.style.top = container.offsetHeight/2 - element.offsetHeight/2 + 'px';
            element.style.left = container.offsetWidth/2 - element.offsetWidth/2 + 'px';
        }



        element.dataset.mwFreeElement = true;
    }

    static destroyFreeDraggableElement(element) {

        delete element.dataset.mwFreeElement;


    }



    init() {
        mw.top().app.canvas.getDocument().querySelectorAll('[data-mw-free-element="true"]').forEach(node => this.freeElement(node));
        this.initLayouts();
        mw.top().app.on('moduleInserted', () => {

            setTimeout( () => {
                this.initLayouts();
            }, 300);
        })
    }

}

