import BaseComponent from "../../containers/base-class";
import liveEditHelpers from "../../../core/live-edit-helpers.service";

export class LiveEditSpacer extends BaseComponent {
    constructor() {
        super();

        var LiveEditSpacerInstance = this;
        mw.app.canvas.on('onLiveEditReady', (data) => {

            LiveEditSpacerInstance.init();
        });

        mw.app.state.on('undo', function (data) {
            LiveEditSpacerInstance.removeAllSpacers();
            // var undoTarget = mw.app.state.active();
            // if (undoTarget.target) {
            //     // is in spacer module
            //     var isInsideSpacerModule = liveEditHelpers.targetGetFirstModuleOfType(undoTarget.target, 'spacer');
            //     if (isInsideSpacerModule) {
            //         if (undoTarget.previousOption) {
            //          //   mw.top().options.saveOption(undoTarget.previousOption);
            //         }
            //         // mw.reload_module(isInsideSpacerModule, function () {
            //         //     initResizables();
            //         // });
            //     }
            // }
            //
            setTimeout(function () {
                LiveEditSpacerInstance.init();
            }, 300);
        });

        mw.app.state.on('redo', function (data) {
            LiveEditSpacerInstance.removeAllSpacers();
            // var undoTarget = mw.app.state.active();
            // if (undoTarget && undoTarget.target) {
            //     // is in spacer module
            //     var isInsideSpacerModule = liveEditHelpers.targetGetFirstModuleOfType(undoTarget.target, 'spacer');
            //     if (isInsideSpacerModule) {
            //
            //
            //         if (undoTarget.previousOption) {
            //         //    mw.top().options.saveOption(undoTarget.previousOption);
            //         }
            //
            //
            //       // mw.reload_module(isInsideSpacerModule, function () {
            //         //     setTimeout(function () {
            //         //         initResizables();
            //         //     }, 178);
            //         // });
            //     }
            // }
            setTimeout(function () {
                LiveEditSpacerInstance.init();
            }, 300);
        });
        mw.on.moduleReload(function () {
            LiveEditSpacerInstance.init();
        });

        if(mw.top().app.canvas.getWindow() && mw.top().app.canvas.getWindow().mw) {
            mw.top().app.canvas.getWindow().mw.on.moduleReload(function () {
                LiveEditSpacerInstance.init();
            });
        }
        mw.top().app.on('moduleInserted', function () {
            //LiveEditSpacerInstance.init();
            setTimeout(function () {
                LiveEditSpacerInstance.init();
            }, 300);
        })
        mw.top().app.on('layoutCloned', function () {
            setTimeout(function () {
                LiveEditSpacerInstance.init();
            }, 300);
        })
        addEventListener('load', function () {
  //          LiveEditSpacerInstance.init();

            setTimeout(function () {
                LiveEditSpacerInstance.init();
            }, 300);
        });

        mw.app.canvas.on('liveEditCanvasLoaded', event =>{
            setTimeout(function () {
                LiveEditSpacerInstance.init();
            }, 300);
        });
        mw.top().app.on('onModuleReloaded', moduleId => {

            setTimeout(function () {
                LiveEditSpacerInstance.init();
            }, 300);
        });



        mw.app.canvas.on('canvasDocumentClick',event => {
            //is in resizer
            if(event.target && event.target.classList && event.target.classList.contains('mw-resizer')){
                return;
            }
            this.removeAllSpacers();

        });

        //this.init();
    }

    init() {

        const body = mw.app.canvas.getDocument().body;
        Array.from(body.querySelectorAll('.mw-le-spacer:not([data-resizable])')).forEach(node => {
            this.makeSpacerNode(node);
                // node.addEventListener('mouseover', () => {
                //
                //     console.log('mouseover')
                //     this.makeSpacerNode(node);
                // });
                //
                // node.removeEventListener('mouseout', () => {
                //     this.removeSpacerNode(node);
                // });

        });



    }


    makeSpacerNode(node) {

        if(node.classList.contains('mw-le-spacer-is-ready')){
            return;
        }
        if(node.getAttribute('data-resizable-spacer')){
            return;
        }
        node.setAttribute('data-resizable-spacer', 'true');
        node.classList.add('mw-le-spacer', 'noedit', 'nodrop', 'mw-le-spacer-is-ready');
        node.innerHTML = '';
        var nodeInfo = document.createElement('span');
        node.append(nodeInfo);
        nodeInfo.className = 'mw-le-spacer-info';

        var nodeInfoContent = document.createElement('span');
        nodeInfo.append(nodeInfoContent);
        nodeInfoContent.className = 'mw-le-spacer-info-content';
        node._$resizer = null;

        node._$resizer = new Resizable({
            element: node,
            document: node.ownerDocument,
            direction: 'vertical',
            maxHeight: 220
        });


        node._$resizer.on('resize', data => {
            nodeInfoContent.textContent = data.height + 'px';
            node.classList.add('mw-le-spacer-resizing');
            node.ownerDocument.body.classList.add('mw--resizing');
            mw.top().app.liveEdit.pause();
        });

        ;(nodeInfoContent => {
            node._$resizer.on('ready', data => {
                nodeInfoContent.textContent = data.height + 'px';
            });
        })(nodeInfoContent);


        node._$resizer.on('resizeStop', data => {
            // is in spacer module
            node.ownerDocument.body.classList.remove('mw--resizing');
            this.saveHeight(node);
            //remove all spacers
        });

        node._$resizer.mount();
     }
    removeAllSpacers() {
        const body = mw.app.canvas.getDocument().body;
        Array.from(body.querySelectorAll('.mw-le-spacer[data-resizable-spacer]')).forEach(node => {
            this.removeSpacerNode(node);
        });
    }

    removeSpacerNode(node) {
        node.removeAttribute('data-resizable-spacer');
        node.removeAttribute('data-resizable');
        node.classList.remove(  'mw-le-spacer-is-ready');

         if(node._$resizer){
            node._$resizer.destroy();
            node._$resizer = null;
            delete node._$resizer;
        }
    }


    saveHeight(node) {

            // is in spacer module
            var nodeForOffsetHeight = false;
            var isInsideSpacerModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'spacer');
            if (isInsideSpacerModule) {
                if (isInsideSpacerModule) {
                    nodeForOffsetHeight = isInsideSpacerModule;
                    nodeForOffsetHeight.style.height = '';
                }
            }


            node.classList.remove('mw-le-spacer-resizing');
            if (!isInsideSpacerModule) {
                mw.app.registerUndoState(node);

                mw.top().app.cssEditor.temp(node, 'height', node.offsetHeight + 'px');
                node.style.height = '';

            } else {
                var htmlforUndoClean = isInsideSpacerModule.innerHTML;
                var div = document.createElement('div');
                div.innerHTML = htmlforUndoClean;
                var allResizables = div.querySelectorAll('[data-resizable]');

                //remove attrs data-resizable from all
                for (var i = 0; i < allResizables.length; i++) {
                    allResizables[i].removeAttribute('data-resizable');
                }

                //get back new html
                var htmlforUndo = div.innerHTML;
                var options = {
                    group: isInsideSpacerModule.id,
                    key: 'height',
                    module: 'spacer',
                    value: node.offsetHeight + 'px'
                };

                mw.app.state.record({
                    target: isInsideSpacerModule,
                    //previousOption: options,
                    value: htmlforUndo
                });
                isInsideSpacerModule.setAttribute('height', node.offsetHeight + 'px');
                //isInsideSpacerModule.setAttribute('data-mw-temp-options-save', JSON.stringify(options));
                mw.top().options.tempOption(isInsideSpacerModule,options);

             //   mw.top().options.saveOption(options);

            }
            mw.top().app.registerChange(node);
            mw.top().app.liveEdit.play()
            node.ownerDocument.body.classList.remove('mw--resizing');


    }
}

export default LiveEditSpacer;
