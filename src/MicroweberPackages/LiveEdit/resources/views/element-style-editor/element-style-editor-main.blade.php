
@include('admin::layouts.partials.loads-user-custom-fonts')


<script>
    mw.lib.require('jseldom');
    mw.require('domtree.js');
</script>
<script>
    selectNodeInElementStyleEditorApp = function (ActiveNode) {
        if (ActiveNode) {

            mw.top().app.dispatch('mw.elementStyleEditor.selectNode', ActiveNode);
        }
    }


    var ActiveSelector = false;
    var ActiveNode = false;
    var OverlayNode = false;
    mw.app = mw.top().app;
    var targetWindow = mw.top().app.canvas.getWindow();
    if (targetWindow) {
        var targetMw = targetWindow.mw;

    }
    var specialCases = function (property, value) {
        if (!property) return;
        if (property.includes('col-')) {
            scColumns(property, value)
            return true;
        } else if (OverlayNode && property === 'overlay-color') {
            OverlayNode.style.backgroundColor = value;
            mw.top().app.registerChange(OverlayNode);
            return true;
        } else if (OverlayNode && property === 'overlay-blend-mode') {
            OverlayNode.style.mixBlendMode = value;
            mw.top().app.registerChange(OverlayNode);
            return true;
        }

    }

    $(document).ready(function () {

        ActiveNode = mw.top().app.liveEdit.getSelectedNode();
        if(ActiveNode){
            selectNodeInElementStyleEditorApp(ActiveNode);
        }


        window.document.addEventListener('refreshSelectedElement', function (e) {

            ActiveNode = mw.top().app.liveEdit.getSelectedNode();
            if(ActiveNode){
                selectNodeInElementStyleEditorApp(ActiveNode);
            }

        });

        mw.top().app.on('cssEditorSelectElementBySelector', function (selector) {
            var canvasDocument = mw.top().app.canvas.getDocument();

            if (selector) {
                ActiveNode = canvasDocument.querySelector(selector);
                if (!ActiveNode) {

                    var newEl =    $.jseldom(selector);

                    var holder = canvasDocument.querySelector('#mw-non-existing-temp-element-holder');
                    if(!holder){
                        holder = canvasDocument.createElement('div');
                        holder.id = 'mw-non-existing-temp-element-holder';
                        holder.style.display = 'none';
                        canvasDocument.body.append(holder);
                    }
                    if(newEl) {
                        holder = canvasDocument.getElementById('mw-non-existing-temp-element-holder');
                        holder.append(newEl[0]);
                    }
                    ActiveNode = canvasDocument.querySelector(selector);

                }
                ActiveSelector = selector;
                selectNodeInElementStyleEditorApp(ActiveNode);

            }
        });
    });


    mw.top().app.on('mw.elementStyleEditor.addClassToNode', function (data) {
        // (data.class, data.node);
        if (data.node) {
            data.node.classList.add(data.class);
            mw.top().app.registerChange(data.node);
        }
    });

    mw.top().app.on('mw.elementStyleEditor.removeClassFromNode', function (data) {
        if (data.node) {
            data.node.classList.remove(data.class);
            mw.top().app.registerChange(data.node);
        }
    });



    mw.top().app.on('mw.elementStyleEditor.applyCssPropertyToNode', function (data) {
       output(data.prop, data.val, data.node);
    });


    var output = function (property, value, ActiveNode) {


//console.log('CSSoutput', property, value, ActiveNode)


        var mwTarget = targetMw;
        if (ActiveNode) {

            if (!specialCases(property, value)) {
                let prop = property.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
                // if(prop.indexOf('webkit') === 0) {
                //     prop = `-${prop}`;
                // }
                if(ActiveSelector){
                    mw.top().app.cssEditor.setPropertyForSelector(ActiveSelector, prop, value)
                }

                if(ActiveNode.style && ActiveNode.style[prop]) {
                    ActiveNode.style[prop] = '';
                }
                mw.top().app.cssEditor.temp(ActiveNode, prop, value)
            }
            mw.top().app.registerChange(ActiveNode);

            if(mw.top().app.liveEdit) {
                mw.top().app.liveEdit.handles.get('interactionHandle').hide()

                mw.top().app.liveEdit.handles.reposition();
            }
        }

    };

</script>

<style>
    body {
        background: #fff !important;
    }
</style>

<script src="{{ asset('vendor/microweber-packages/frontend-assets/build/element-style-editor-app.js') }}"></script>


<div id="mw-element-style-editor-app">
    Loading ...
</div>
