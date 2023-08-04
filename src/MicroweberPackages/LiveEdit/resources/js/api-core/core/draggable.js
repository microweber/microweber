
import {ObjectService} from './classes/object.service.js';
import {DroppableElementAnalyzerService} from "./analizer.js";
import {DropPosition} from "./drop-position.js";
import {ElementManager} from "./classes/element.js";

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

    this.helper = function (e, event) {
        
        if(!this._helper) {
            this._helper = ElementManager().get(0);
            this._helper.className = 'mw-draggable-helper';
           
            this.settings.document.body.appendChild(this._helper);
        }
        if (e === 'create') {
            var off = ElementManager( scope.element ).offset()
            this.$data._calcMinus = {
                y: (event.pageY - off.offsetTop),
                x: (event.pageX - off.offsetLeft),
            }
            this._helper.style.top = e.pageY + 'px';
            this._helper.style.left = e.pageX + 'px';
            this._helper.style.width = scope.element.offsetWidth + 'px';
            this._helper.style.height = scope.element.offsetHeight + 'px';
            this.settings.document.documentElement.classList.add('le-dragging')
            this._helper.style.display = 'block';
        } else if(e === 'remove' && this._helper) {
            this._helper.style.display = 'none';
            this.settings.document.documentElement.classList.remove('le-dragging')
        } else if(this.settings.helper && e) {
            
            this._helper.style.top = (e.pageY -  this.$data._calcMinus.y) + 'px';
            this._helper.style.left = (e.pageX -  this.$data._calcMinus.x) + 'px';
            // this._helper.style.maxWidth = (scope.settings.document.defaultView.innerWidth - e.pageX - 40) + 'px';
            this.settings.document.documentElement.classList.add('le-dragging')
        }

        if(this._helper.$$element !== scope.element) {
            this._helper.$$element = scope.element;
            this._helper.innerHTML =  scope.element.outerHTML;
        }
         
        
        return this._helper;
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


        var el = this.handle.get(0);
        var handleEl = this.handle.get(0);


 
         
         
        if(el && !handleEl.$handleInit && handleEl.parentElement) {
 
            handleEl.$handleInit = true;
            this.handle.attr('draggable', 'true')
            this.handle
            .on('dragstart', function (e) {
                
                e.dataTransfer.setData('text/html', 'This text <strong>may</strong> be dragged');
                scope.isDragging = true;
                if (!scope.element.id) {
                    scope.element.id = ('mw-element-' + new Date().getTime());
                }
                 scope.element.classList.add('mw-element-is-dragged');
                e.dataTransfer.setData("text", scope.element.id);
                e.dataTransfer.effectAllowed = "move";

                scope.helper('create', e);
                scope.dispatch('dragStart',{element: scope.element, event: e});
            })
            .on('drag', function (e) {
                var scrlStp = 90;
                var step = 5;
                if (e.clientY < scrlStp) {
                    scroll(-step)
                }
                if (e.clientY > (innerHeight - (scrlStp + ( this._helper ? this._helper.offsetHeight + 10 : 0)))) {
                    scroll(step)
                }
                e.dataTransfer.dropEffect = "copy";
                scope.dispatch('drag',{element: scope.element, event: e});
                scope.helper(e)

            })
            .on('dragend', function (e) {
                scope.isDragging = false;
                scope.element.classList.remove('mw-element-is-dragged');
                scope.helper('remove');
                scope.dispatch('dragEnd',{element: scope.element, event: e});
                stop = true;
            });
        }
    }


    this.init();

 
};
