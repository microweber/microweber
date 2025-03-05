import MicroweberBaseClass from "../../../../containers/base-class.js";

class LiveEditDOMTree extends MicroweberBaseClass {
    constructor(options = {}) {

        super();

        this.config(options);
        this.init();
    }

    tree = null;
    box = null;

    visible = false;

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
            this.dispatch("show", this)
        });
        this.box.on('hide', () => {

            this.dispatch("hide", this)
        });

    }
    show() {
        this.box.show();
        this.visible = true;
        this.dispatch("show", this)
    }
    hide() {
        this.box.hide();
        this.visible = false;
        this.dispatch("hide", this)
    }
    buildTree() {
        let shouldScroll = true;
        this.tree = new this.settings.target.DomTree({
            element: this.box.boxContent,
            resizable: false,
            sortable: false,

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
                if(shouldScroll) {
                    node.scrollIntoView({behavior: "smooth", block: "center", inline: "center"});
                    if(node.classList.contains('element')) {
                        mw.app.liveEdit.handles.set('element', node)
                    } else if(node.classList.contains('module')) {
                        mw.app.liveEdit.handles.set('module', node)
                    }

                }


            }
        });

        mw.app.state.on('change', (data) => {



            if(data.active && data.active.target) {
                if( data.active.target === '$multistate') {
                    data.active.value.forEach(obj => {


                        this.tree.refresh(obj.target);
                        this.tree.select(obj.target);
                    })
                } else if(data.active.target.nodeName) {

                    this.tree.refresh(data.active.target);
                    this.tree.select(data.active.target);
                }
            }
        });

        let _activeSortStartData = null,
            _activeSortStartStateData = null;

        this.tree.on("sortStart", data => {
            _activeSortStartData = {...data};
            const startEdit = mw.tools.firstParentOrCurrentWithAnyOfClasses(_activeSortStartData.target, ['edit']);
            if(startEdit) {
                _activeSortStartStateData = {
                    target:  startEdit,
                    value: startEdit.innerHTML,
                };
            } else {
                _activeSortStartStateData = null
            }


        })
        this.tree.on("sort", data => {
            if(data.targetPrev) {
                data.targetPrev.after(data.target)
            } else if(data.targetNext) {
                data.targetNext.before(data.target)
            } else if(data.targetParent) {
                data.targetParent.appendChild(data.target)
            }
            mw.top().app.liveEdit.handles.hide();



            if(_activeSortStartData) {

                const endEdit = mw.tools.firstParentOrCurrentWithAnyOfClasses(data.target, ['edit']);
                if( endEdit && _activeSortStartStateData) {
                    mw.app.state.record({
                        target: "$multistate",
                        value: [

                            _activeSortStartStateData,
                            {
                                target:  endEdit,
                                value: endEdit.innerHTML,
                            }
                        ]
                    });
                }


            }




        })

        const handleTargetChange = target => {

            const targetParent = mw.top().tools.firstParentOrCurrentWithAnyOfClasses(target, ['element', 'edit', 'module']);


            if(targetParent) {
                this.tree.refresh(targetParent);
                this.tree.select(targetParent);
            }
        }

        mw.top().app.on('dragEnd', target => {
            handleTargetChange(target.parentElement)
        });

        mw.top().app.on('moduleInserted', data => {

            handleTargetChange(data.target.parentElement)

        });

        const liveEditHandleTargetChange = target => {
            shouldScroll = false;
            this.tree.select(target);
            shouldScroll = true;
        }

        mw.top().app.liveEdit.handles.get('module').on('targetChange', target => {
            liveEditHandleTargetChange(target)
        })
        mw.top().app.liveEdit.handles.get('element').on('targetChange', target => {
            liveEditHandleTargetChange(target)
        });


    }
    init() {
        this.buildBox();
        this.buildTree();

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

