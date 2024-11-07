import MicroweberBaseClass from "../../../../containers/base-class.js";

class LiveEditDOMTree extends MicroweberBaseClass {
    constructor(options = {}) {

        super();

        this.config(options);
        this.init();
    }

    tree = null;
    box = null;

    config(options) {
        const defaults = {
            target: mw.top()
        }

        this.settings = Object.assign({}, defaults, options);
    }

    buildBox() {
        this.box = new this.settings.target.controlBox({
            content:``,
            position:  'left',
            id: 'mw-live-edit-domtree-box',
            closeButton: true,
            title: mw.lang('Layers')
        });

        this.box.on('show', () => {
            mw.top().doc.documentElement.classList.add('mw-live-edit-sidebar-start')
            this.dispatch("show", this)
        });
        this.box.on('hide', () => {
            mw.top().doc.documentElement.classList.remove('mw-live-edit-sidebar-start')

            this.dispatch("hide", this)
        });

    }
    show() {
        this.box.show();
        this.dispatch("show", this)
    }
    hide() {
        this.box.hide();
        this.dispatch("hide", this)
    }
    buildTree() {
        this.tree = new this.settings.target.DomTree({
            element: this.box.boxContent,
            resizable: false,
            sortable: true,

            targetDocument: mw.app.canvas.getDocument(),

            canSelect: function (node, li) {
                if (node.id) {
                  return true;
                }

                var isInaccessible = mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(node);
                if (isInaccessible) {
                    return false;
                }


                return true;
            },
            onHover: function (e, target, node, element) {

            },
            onSelect: (e, target, node, element) => {
                this.settings.target.app.dispatch('mw.elementStyleEditor.selectNode', node);
                if (node.ownerDocument.defaultView.mw) {
                    node.ownerDocument.defaultView.mw.tools.scrollTo(node, false, 100);
                }

            }
        });
    }
    init() {
        this.buildBox();
        this.buildTree();
        // this.show();
        mw.top().app.on('mw.elementStyleEditor.selectNode', target => {
            console.log(target)
        });
        mw.top().app.canvas.on('liveEditCanvasLoaded', event => {
            this.buildTree();
        });
    }
};
;(async function () {
    const states = ['complete', 'interactive'];
    let config = {};
    mw.top().app.domTree = await new Promise(function (resolve, reject) {
        if(states.indexOf(mw.top().win.document.readyState) !== -1 ) {
            resolve(new LiveEditDOMTree(config));
        } else {
            addEventListener('load', () => {
                resolve(new LiveEditDOMTree(config));
            })
        }
    });

    mw.top().app.dispatch("DOMTreeReady", mw.top().app.domTree);



})();

