<template>

    <div v-show="displayNodeInfo">
<div id="domtree">


</div>
        <span class="badge badge-outline text-blue">{{nodeTagName}}</span>


    </div>
</template>


<script>

import './dom-tree.css';

export default {
    data() {
        return {
            'nodeTagName': null,
            'displayNodeInfo': null,
            'activeNode': null,
            'domTree': null,
            'isReady': false,
        };
    },

    methods: {


        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {

                this.isReady = false;
                this.displayNodeInfo = false;
                this.domTree = false;
                this.activeNode = node;
                this.populateSelectedNode(node);
                this.populateDomTree(node);

                this.isReady = true;
            }
        },

        populateSelectedNode: function (node) {
            this.nodeTagName = node.tagName;
            if(node.id){
                this.displayNodeInfo = '#' + node.id;
            } else {
                this.displayNodeInfo = node.tagName;
            }

        },

        populateDomTree: function (element){


            this.domTree = new mw.DomTree({
                element: '#domtree',
                resizable: true,
               // compactTreeView: true,
              //  targetDocument: targetMw.win.document,
                targetDocument: element.ownerDocument,
                canSelect: function (node, li) {
                     var can = mw.top().app.liveEdit.canBeElement(node)
                     var isInaccessible =  mw.top().app.liveEdit.liveEditHelpers.targetIsInacesibleModule(node);
                     if(isInaccessible){
                         return false;
                     }
                    //   return can;
                    // var cant = (!mw.tools.isEditable(node) && !node.classList.contains('edit') && !node.id);
                    // return !cant;
                    // return mw.tools.isEditable(node) || node.classList.contains('edit');

                    return true;
                },
                onHover: function (e, target, node, element) {

                },
                onSelect:  (e, target, node, element) => {
                    mw.top().app.dispatch('mw.elementStyleEditor.selectNode', node);
                    if(node.ownerDocument.defaultView.mw) {
                        node.ownerDocument.defaultView.mw.tools.scrollTo(node, false, 100);
                    }

                }
            });



            this.domTree.select(element)
        }


    },





    mounted() {
        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
            this.populateStyleEditor(element)
        });
    },



}
</script>


