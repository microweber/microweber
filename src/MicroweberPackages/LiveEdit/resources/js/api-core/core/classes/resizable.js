export class Resizable  {

    constructor(options = {}) {

        const defaults = {
            element: null,
            document: document,
            maxHeight: null,
            maxWidth: null,
        };

        this.settings = Object.assign({}, defaults, options);
        this.element = this.settings.element;
        this.document = this.settings.document;
        this.element.classList.add('mw-le-resizable');

    };

    x = 0;
    y = 0;
    w = 0;
    h = 0;

    listeners = {}

    #_e = {};

    on(e, f){ this.#_e[e] ? this.#_e[e].push(f) : (this.#_e[e] = [f]) };
    dispatch(e, f){ this.#_e[e] ? this.#_e[e].forEach(c => { c.call(this, f); }) : ''; };

    mouseMoveHandler (e, target) {
        const dx = e.clientX - this.x;
        const dy = e.clientY - this.y;
        let calcH = this.h + dy;
        let calcW;




        if(this.activeHandle === this.handles.left) {
            calcW = this.w - dx;
        } else {
            calcW = this.w + dx;
        }



        if(this.settings.maxWidth) {
            calcW = Math.min(calcW, this.settings.maxWidth)
        }
        if(this.settings.maxHeight) {
            calcH = Math.min(calcH, this.settings.maxHeight)
        }
         this.element.style.width = `${calcW}px`;

         var setHeight = target && (target.$name !== 'right' && target.$name !== 'left');

         if(setHeight) {
            this.element.style.height = `${calcH}px`;
         }


        e.preventDefault();
        this.dispatch('resize', { height: setHeight ? this.element.offsetHeight : (''), width: this.element.offsetWidth });

    }

    mouseUpHandler () {
        for (const l in this.listeners) {
            this.document.removeEventListener(l, this.listeners[l]);
        }
        this.listeners = {};
        this.activeHandle = null;
        this.dispatch('resizeStop');
    };

    mouseDownHandler (e, target) {
        if(e.which !== 1) return;
        this.x = e.clientX;
        this.y = e.clientY;

        const styles = this.document.defaultView.getComputedStyle(this.element);
        this.w = parseInt(styles.width, 10);
        this.h = parseInt(styles.height, 10);



        this.listeners.mousemove = e => this.mouseMoveHandler(e, target)
        this.listeners.mouseup = e => this.mouseUpHandler(e)



        for (const l in this.listeners) {
            this.document.addEventListener(l, this.listeners[l]);
        }
        this.dispatch('resizeStart');

    };

    activeHandle = null;
    handles = {};
    #disabled = false;
    #enabled = true;


    disable() {
        this.#disabled = true;
        this.#enabled = false;
        this.element.classList.add('mw-le-resizable-disabled');
    }


    enable() {
        this.#disabled = false;
        this.#enabled = true;
        this.element.classList.remove('mw-le-resizable-disabled');
    }

    disabled() {
        return this.#disabled
    }

    enabled() {
        return this.#enabled
    }

    build() {
        const nodeT = this.document.createElement('span');
        const nodeR = this.document.createElement('span');
        const nodeB = this.document.createElement('span');
        const nodeL = this.document.createElement('span');

        this.handles.top = nodeT;
        this.handles.right = nodeR;
        this.handles.bottom = nodeB;
        this.handles.left = nodeL;

        nodeT.className = 'mw-le-resizer mw-le-resizer-t';
        nodeR.className = 'mw-le-resizer mw-le-resizer-r';
        nodeL.className = 'mw-le-resizer mw-le-resizer-l';
        nodeB.className = 'mw-le-resizer mw-le-resizer-b';


        nodeT.$name = 'top';
        nodeR.$name = 'right';
        nodeL.$name = 'left';
        nodeB.$name = 'bottom';

        this.element.appendChild(nodeT);
        this.element.appendChild(nodeR);
        this.element.appendChild(nodeB);
        this.element.appendChild(nodeL);
    }

    mount() {
        if(!this.element) { return this; }
        if(this.element.dataset.resizable) { return this; }
        this.element.dataset.resizable = true;
        this.build();
        const resizers = this.element.querySelectorAll('.mw-le-resizer');

        Array.from(resizers).forEach(resizer => {

            resizer.addEventListener('mousedown', e => {
                this.mouseDownHandler(e, resizer)
                this.activeHandle = resizer;
            });
        });
        this.dispatch('ready', { height: this.element.offsetHeight, width: this.element.offsetWidth });
        return this;
    }

    destroy() {
        if(!this.element) { return this; }
        if(!this.element.dataset.resizable) { return this; }
        this.element.dataset.resizable = false;
        const resizers = this.element.querySelectorAll('.mw-le-resizer');
        Array.from(resizers).forEach(resizer => {
            resizer.removeEventListener('mousedown', e => {
                this.mouseDownHandler(e)
            });
        });
        return this;
    }



}

globalThis.Resizable = Resizable
