
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

    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

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

        var imgAsBg = DomService.firstParentOrCurrentWithClass(node, 'img-as-background');
        if(imgAsBg) {
            // node = imgAsBg;
        }

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

        if(handleNodeWin.__$$dragoverCoords) {
            pageX = handleNodeWin.__$$dragoverCoords.pageX;
            pageY = handleNodeWin.__$$dragoverCoords.pageY;
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
           // scope._helper.style.top = pageY + 'px';
           scope._helper.style.left = ( pageX -  scope.$data._calcMinus.x) + 'px';
            // scope._helper.style.maxWidth = (scope.settings.document.defaultView.innerWidth - e.pageX - 40) + 'px';
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
         ElementManager(this.settings.target).on('dragleave', function (e) {
            if(scope.dropIndicator) scope.dropIndicator.hide();
         })
         ElementManager(this.settings.target).on('dragover', function (e) {
             scope.target = null;
             scope.action = null;
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
             }
        }).on('drop', function (e) {
            if (scope.isDragging) {
                e.preventDefault();
                if (scope.target && scope.action) {
                    ElementManager(scope.target)[scope.action](scope.element);
                }
                if(scope.dropIndicator) scope.dropIndicator.hide();
                scope.dispatch('drop', {element: scope.element, event: e});
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
                scope.dispatch('dragStart',{target: scope.target, element: scope.element, event: e});

                scope.isDragging = true;
                if (!scope.element.id) {
                    scope.element.id = ('mw-element-' + new Date().getTime());
                }
                 scope.element.classList.add('mw-element-is-dragged');
                e.dataTransfer.setData("text", scope.element.id);
                e.dataTransfer.effectAllowed = "move";


                let img = new Image();
                img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

                e.dataTransfer.setDragImage(img, 1, 1);

                scope.helper('create', e);
            })
            .on('drag', function (e) {

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
