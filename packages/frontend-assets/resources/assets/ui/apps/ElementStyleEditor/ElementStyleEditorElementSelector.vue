<template>

    <div v-show="displayNodeInfo">

        <div class="well">

            <div v-show="displayDomTree">
                <div id="domtree" style="margin-block-end: 15px;">


                </div>
            </div>


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
                    closeButton: true
                })
            }

           const tree = new targetMW.DomTree({
                    element: targetMW.__controlBoxDomTree.boxContent,
                    resizable: true,

                    targetDocument: targetMW.__controlBoxDomTree.boxContent.ownerDocument,
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
                        if (node.ownerDocument.defaultView.mw) {
                            node.ownerDocument.defaultView.mw.tools.scrollTo(node, false, 100);
                        }

                    }
           })
           tree.select(this.activeNode)

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

        populateDomTree: function (element) {
            if (!this.displayDomTree) {
                return;
            }

            if (!this.domTree || !this.currentCanvasDocument || this.currentCanvasDocument !== mw.top().app.canvas.getDocument()) {
                this.currentCanvasDocument = mw.top().app.canvas.getDocument();
                this.domTree = new mw.DomTree({
                    element: '#domtree',
                    resizable: true,
                    targetDocument: element.ownerDocument,
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
                        mw.top().app.dispatch('mw.elementStyleEditor.selectNode', node);
                        if (node.ownerDocument.defaultView.mw) {
                            node.ownerDocument.defaultView.mw.tools.scrollTo(node, false, 100);
                        }

                    }
                });
            }


            this.domTree.select(element)
        }


    },


    mounted() {
        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //     this.populateStyleEditor(element)
        // });
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


