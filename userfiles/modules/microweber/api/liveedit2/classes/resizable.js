export class Resizable  {

    constructor(options = {}) {

        const defaults = {
            element: null,
            document: document,
        };

        this.settings = Object.assign({}, defaults, options);
        this.element = this.settings.element;
        this.mount();

    };

     x = 0;
     y = 0;
     w = 0;
     h = 0;

    mouseMoveHandler (e) {
        const dx = e.clientX - x;
        const dy = e.clientY - y;
        this.element.style.width = `${this.w + dx}px`;
        this.element.style.height = `${this.h + dy}px`;
    }

    mouseUpHandler () {

        this.document.removeEventListener('mousemove', this.mouseMoveHandler);
        this.document.removeEventListener('mouseup', this.mouseUpHandler);
    };

    mouseDownHandler (e) {

        this.x = e.clientX;
        this.y = e.clientY;

        const styles = this.document.defaultView.getComputedStyle(this.element);
        this.w = parseInt(styles.width, 10);
        this.h = parseInt(styles.height, 10);


        this.document.addEventListener('mousemove', this.mouseMoveHandler);
        this.document.addEventListener('mouseup', this.mouseUpHandler);
    };

    build() {
        const nodeR = document.createElement('span');
        const nodeB = document.createElement('span');
        nodeR.className = 'mw-le-resizer mw-le-resizer-r';
        nodeB.className = 'mw-le-resizer mw-le-resizer-b';
        this.element.appendChild(nodeR);
        this.element.appendChild(nodeB);
    }

    mount() {
        if(!this.element) { return; }
        this.build();
        const resizers = ele.querySelectorAll('.mw-le-resizable');

        [].forEach.call(resizers, resizer => {
            resizer.addEventListener('mousedown', this.mouseDownHandler);
        });
    }

}
