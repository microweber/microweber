<template>

    <div v-show="displayNodeInfo">

        <div class="well">




            <div class="d-flex align-items-center justify-content-between gap-2">
                <label class="live-edit-label mb-0">Selected element:</label>
                <button
                    type="button"
                    :class="{'btn-dark': displayDomTree, 'btn-outline-dark': !displayDomTree }"
                    class="btn btn-sm "
                    @click="toggleDomTree"
                    v-html="displayNodeInfo"></button>
            </div>
        </div>
    </div>
</template>


<script>

import './dom-tree.css';

export default {
    data() {
        return {
            'nodeTagName': null,
            'displayNodeInfo': null,
            'displayDomTree': null,
            'activeNode': null,
            'domTree': null,
            'isReady': false,
            'currentCanvasDocument': false,
            targetMW: mw.top()
        };
    },

    methods: {

        initDomTree() {
            const targetMW = mw.top();
            if(!targetMW.__controlBoxDomTree) {

                targetMW.__controlBoxDomTree = new targetMW.controlBox({
                    content:``,
                    position:  'left',
                    id: 'mw-live-edit-domtree-box',
                    closeButton: true,
                    title: mw.lang('Layers')
                });

                targetMW.__controlBoxDomTree.on('show', () => {
                    mw.top().doc.documentElement.classList.add('mw-live-edit-sidebar-start')
                });
                targetMW.__controlBoxDomTree.on('hide', () => {
                    mw.top().doc.documentElement.classList.remove('mw-live-edit-sidebar-start')
                });
            }

           const tree = new targetMW.DomTree({
                    element: targetMW.__controlBoxDomTree.boxContent,
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

                        targetMW.app.dispatch('mw.elementStyleEditor.selectNode', node);
                        node.scrollIntoView({behavior: "smooth", block: "start", inline: "start"});

                    }
           });
           tree.select(this.activeNode);

           tree.on('sort', data => {
            const targetParent = data.target.parentNode;
            const li = data.node;
            const ul = li.parentNode;
            Array.from(ul.children).forEach(li => {
            const ref = li._value;
            targetParent.append(ref);
            });

            mw.top().app.liveEdit.handles.reposition()
            mw.top().app.registerChange(targetParent)

           });


            this.domTree = tree


           targetMW.__controlBoxDomTree.show();
        },

        toggleDomTree: function () {
            this.displayDomTree = !this.displayDomTree;
            this.initDomTree();
            if (this.displayDomTree) {
                this.populateDomTree(this.activeNode);
            }
        },
        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {

                this.isReady = false;
                this.displayNodeInfo = false;


                this.activeNode = node;
                this.populateSelectedNode(node);
                this.populateDomTree(node);


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        populateSelectedNode: function (node) {
            this.nodeTagName = node.tagName;
            if (this.domTree) {
                const info = this.domTree.getNodeIconAndTitle(node);
                this.displayNodeInfo = info.icon + info.title;

            } else {
                if (node.id) {
                    this.displayNodeInfo = node.tagName;
                } else {
                    this.displayNodeInfo = node.tagName;
                }
            }


        },

        populateDomTree: function (element, domtree) {

            if (!this.displayDomTree) {
                return;
            }




            this.domTree.select(element)
        }


    },


    mounted() {


        mw.top().app.on('liveEditCanvasLoaded', event => {
            this.initDomTree();
        });
    },

    watch: {

        '$root.selectedElement': {
            handler: function (element) {
                if (element) {
                    this.populateStyleEditor(element);
                }
            },
            deep: true
        },
    }


}
</script>


