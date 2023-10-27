
import {ObjectService} from './classes/object.service.js';
import {DroppableElementAnalyzerService} from "./analizer.js";
import {DropPosition} from "./drop-position.js";
import {ElementManager} from "./classes/element.js";
import { DomService } from './classes/dom.js';

export const Draggable = function (options, rootSettings) {
    var defaults = {
        handle: null,
        element: null,
        document: document,
        helper: true
    };

    var scope = this;

    var _e = {};

    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]); return this; };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; return this; };

    var stop = true;

    var scroll = function (step) {
        scope.settings.document.body.style.scrollBehavior = 'smooth';
        scope.settings.document.defaultView.scrollTo(0,scope.settings.document.defaultView.scrollY + step);
        scope.settings.document.body.style.scrollBehavior = '';
    }

    this.config = function () {
        this.settings = ObjectService.extend({}, defaults, options);
        if(!this.settings.target) {
            this.settings.target = this.settings.document.body;
        }

        this.setElement(this.settings.element);
        this.dropIndicator = this.settings.dropIndicator;
    };

    this.setElement = function (node) {





        this.element = ElementManager(node)/*.prop('draggable', true)*/.get(0);

        this.handleInit()
    };

    this.setTargets = function (targets) {
        this.targets = ElementManager(targets);
    };

    this.addTarget = function (target) {
        this.targets.push(target);
    };

    this.init = function () {
        this.config();
        this.draggable();
    };

    this.$data = {}

    this.getDragCoords = function(event) {
        let {pageX, pageY} = event;
        const handleNodeWin = event.target.ownerDocument.defaultView;

        // todo: pageY in firefox is wrong
        if(handleNodeWin.__$$dragoverCoords) {
            //pageX = handleNodeWin.__$$dragoverCoords.pageX;
            //pageY = handleNodeWin.__$$dragoverCoords.pageY;
        }



        return {
            pageX, pageY
        }
    }

    this.helper = function (e, event) {

        if(!scope._helper) {
            scope._helper = ElementManager().get(0);
            scope._helper.className = 'mw-draggable-helper';

            scope.settings.document.body.appendChild(scope._helper);
        }
        if (e === 'create') {
            var off = ElementManager( scope.element ).offset();

            let {pageX, pageY} = this.getDragCoords(event);

            scope.$data._calcMinus = {
                y: (pageY - off.offsetTop),
                x: (pageX - off.offsetLeft),
            }
            scope._helper.style.top = pageY + 'px';
            scope._helper.style.left = pageX + 'px';
            scope._helper.style.width = scope.element.offsetWidth + 'px';
            scope._helper.style.height = scope.element.offsetHeight + 'px';
            scope.settings.document.documentElement.classList.add('le-dragging')
            scope._helper.style.display = 'block';
        } else if(e === 'remove' && scope._helper) {
            setTimeout(() => {
                scope.settings.document.querySelectorAll('.mw-draggable-helper').forEach(e => e.innerHTML = ``);
                scope._helper.$$element = null;
            })


            scope.settings.document.documentElement.classList.remove('le-dragging');

        } else if(scope.settings.helper && e) {

            let {pageX, pageY} = this.getDragCoords(e);

            scope._helper.style.top = (pageY -  scope.$data._calcMinus.y) + 'px';

           scope._helper.style.left = ( pageX -  scope.$data._calcMinus.x) + 'px';

           // todo: firefox returns wrong pageX pageY

           const isFireforx = navigator.userAgent.match(/firefox|fxios/i);
           if(isFireforx) {
            scope._helper.style.display = 'none';
           }

            scope.settings.document.documentElement.classList.add('le-dragging')
        }

        if(scope._helper.$$element !== scope.element) {
            scope._helper.$$element = scope.element;
            scope._helper.innerHTML =  scope.element.outerHTML;
        }


        return scope._helper;
    };

    this.isDragging = false;
    this.dropableService = new DroppableElementAnalyzerService(rootSettings);

    this.dropPosition = DropPosition;

    this.draggable = function () {




        const getFile = (e) => {
            let file;
            if (e.dataTransfer.items) {
                file = [...e.dataTransfer.items].find((item, i) => {
                    if (item.kind === "file") {
                        return true;
                    }
                });

            } else {
              file = [...e.dataTransfer.files][0];
            }
            return file;
        }



         ElementManager(this.settings.target).on('dragleave', function (e) {
            if(scope.dropIndicator) scope.dropIndicator.hide();
         });
         const dtarget = ElementManager(this.settings.target).get(0);

         if(dtarget.__draggableReady) {
            return;
         }
         dtarget.__draggableReady = true;




         dtarget.addEventListener('dragover', function (e) {
             scope.target = null;
             scope.action = null;
             const file = getFile(e);
             e.preventDefault();


             let canDrag = true;

             if(!canDrag) {
                return;
             }



             if(e.target !== scope.element || !scope.element.contains(e.target)) {
                 var targetAction = scope.dropableService.getTarget(e.target, scope.element);


                  if (targetAction && targetAction !== scope.element) {
                     const pos = scope.dropPosition(e, targetAction);

                      if(pos) {
                         scope.target = targetAction.target;
                         scope.action = pos.action;
                         if(scope.dropIndicator) scope.dropIndicator.position(scope.target, pos.action + '-' + pos.position)

                     } else {
                        if(scope.dropIndicator) scope.dropIndicator.hide();

                     }
                 } else {
                    if(scope.dropIndicator) scope.dropIndicator.hide();
                 }
                 if (scope.isDragging) {
                     scope.dispatch('dragOver', {element: scope.element, event: e});
                     e.preventDefault();
                 }
             } else {

                if(scope.dropIndicator) scope.dropIndicator.hide();

             }
        });
        dtarget.addEventListener('drop', function (e) {


            const handleFileDrag = (file, e) => {




                if(!scope.target || !scope.action) {
                    return;
                }


                file = file.getAsFile();

                const images = ['image/apng','image/avif','image/gif','image/jpeg','image/png', 'image/svg+xml','image/webp'];
                const videos = ['video/webm', 'video/ogg', 'application/ogg', 'video/mp4'];

                const isImage = images.indexOf(file.type) !== -1;
                const isVideo = videos.indexOf(file.type) !== -1;

                const canUpload = false// isImage || isVideo;



                if(!canUpload) {
                    return;
                }

                mw.spinner({element: document.body, decorate: true});

                mw.uploader().uploadFile(file, function(res){

                    var edit = DomService.firstParentWithAnyOfClasses(scope.target, ['edit'])

                    mw.app.state.record({
                        target: edit,
                        value: edit.innerHTML
                    });
                    if(isVideo) {
                        var id = mw.id('dropped-video');


                        // ElementManager(scope.target)[scope.action](`<div id="${id}" class="module module-video" data-type="video" autoplay="true" url="${res.src}" data-url="${res.src}">`);
                        // ElementManager(scope.target)[scope.action](`<video id="${id}" class="module module-video" autoplay="true" controls playsinline style="width:100%" data-type="video" src="${res.src}" url="${res.src}" data-url="${res.src}">`);
                        ElementManager(scope.target)[scope.action](`<video id="${id}" class="element" autoplay="false" controls playsinline style="width:100%" data-type="video" src="${res.src}" url="${res.src}" data-url="${res.src}">`);
                        setTimeout(() => {
                             // mw.reload_module_everywhere('#' + id);
                        }, 100)
                        mw.app.registerChange(edit);

                    } else if(isImage) {
                        ElementManager(scope.target)[scope.action](`<img src="${res.src}" alt="${res.name || ''}">`);
                        mw.app.registerChange(edit);
                    }

                    var edit = DomService.firstParentWithAnyOfClasses(scope.target, ['edit'])

                    mw.app.state.record({
                        target: edit,
                        value: edit.innerHTML
                    });
                    mw.spinner({element: document.body, decorate: true}).remove();
                })
            }


            if (scope.isDragging || !scope.isDragging) {

                e.preventDefault();
                const file = getFile(e);

                if(file) {
                    handleFileDrag(file, e);
                    e.stopPropagation();
                    e.preventDefault();
                } else if ((mw.top()._dragTarget || scope.target) && scope.action) {

                    let _hndl;
                    if(scope.settings.handle == ".mw-handle-drag-button-module") {
                        _hndl = 'module';
                    } else {
                        _hndl = 'element';
                    }


                    scope.dispatch('beforeDrop', {element: scope.element, event: e});



                      // ElementManager(scope.target)[scope.action](scope.element);
                    // todo




                         ElementManager(scope.target)[scope.action](mw.top()._dragTarget);




                    e.stopPropagation();
                    e.preventDefault();
                }
                if(scope.dropIndicator) scope.dropIndicator.hide();
                // scope.dispatch('drop', {element: scope.element, event: e});
                scope.dispatch('drop', {element: mw.top()._dragTarget, event: e});
            } else {

                const file = getFile(e);

                if(file) {
                    e.stopPropagation();
                    e.preventDefault();

                    handleFileDrag(file, e)
                }
            }

            if(scope.dropIndicator) scope.dropIndicator.hide();
        });


        this.handleInit()

    };

    this.handleInit = function() {


        if(!this.settings.handle) {
            this.settings.handle = this.settings.element;
        }



        this.handle = ElementManager(this.settings.handle, this.settings.document);
        this.handle.attr('draggable', 'true');



        var handleEl = this.handle.get(0);







        if(handleEl && !handleEl.$handleInit && handleEl.parentElement) {

            function _dragHandle(e){




                var scrlStp = 90;
                var step = 5;
                if (e.clientY < scrlStp) {
                    scroll(-step)
                }
                if (e.clientY > (innerHeight - (scrlStp + ( scope._helper ? scope._helper.offsetHeight + 10 : 0)))) {
                    scroll(step)
                }
                e.dataTransfer.dropEffect = "none";
                scope.dispatch('drag',{target: scope.target, element: scope.element, event: e});
                scope.helper(e)

            }

            var handleNode = this.handle.get(0);

            if(!handleNode || !handleNode.ownerDocument){
             return;
            }

            var handleNodeWin = handleNode.ownerDocument.defaultView;

            if(!handleNodeWin.__$$dragoverCoords) { // firefox returns wrong pageY on drag
                handleNodeWin.__$$dragoverCoords = {}
            }

            handleNode.ownerDocument.addEventListener('dragover',  (e) => {

                    handleNodeWin.__$$dragoverCoords.pageY = e.pageY
                    handleNodeWin.__$$dragoverCoords.pageX = e.pageX

            })

            handleNode.addEventListener('drag',  (e) => {
                _dragHandle.call(this, e);

            })

            handleEl.$handleInit = true;
            this.handle.attr('draggable', 'true')
            this.handle
            .on('dragstart', function (e) {

                if (!scope.element.id) {
                    scope.element.id = ('mw-element-' + new Date().getTime());
                }
                 scope.element.classList.add('mw-element-is-dragged');
                e.dataTransfer.setData("text", scope.element.id);

                scope.dispatch('dragStart',{target: scope.target, element: scope.element, event: e});

                scope.isDragging = true;

                e.dataTransfer.effectAllowed = "move";




                let img = new Image();
                img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

                e.dataTransfer.setDragImage(img, 1, 1);

                scope.helper('create', e);
            })
            .on('drag', function (e) {
                scope.isDragging = true;
            })
            .on('dragend', function (e) {
                scope.element.classList.remove('mw-element-is-dragged');

                scope.dispatch('beforeDragEnd',{target: scope.target, element: scope.element, event: e});


                scope.isDragging = false;


                scope.helper('remove');
                scope.dispatch('dragEnd',{target: scope.target, element: scope.element, event: e});
                stop = true;
            });
        }
    }


    this.init();


};
