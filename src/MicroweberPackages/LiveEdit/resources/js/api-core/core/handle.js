import {ObjectService} from "./classes/object.service.js";
import {Draggable} from "./draggable.js";

import {ElementManager} from "./classes/element.js";
import {DomService} from "./classes/dom.js";
import {Resizable} from "./classes/resizable.js";
import { type } from "jquery";

export const Handle = function (options) {

    var defaults = {
        automaticMaxWidth: true,
        setDraggableTarget: function(target) {
            return true;
        }
    };

    var scope = this;

    this.settings = ObjectService.extend({}, defaults, options);

    const _e = {};
    this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };

    var _visible = true;
    var _currentTarget = null;

    this.getTarget = function () {
        return _currentTarget
    }

    this.isVisible = function () {
        return _visible;
    };

    this.show = function () {
        _visible = true;
        this.wrapper.removeClass('mw-handle-item-hidden');
    };
    this.hide = function () {
        _visible = false;
       
   
        this.wrapper.addClass('mw-handle-item-hidden');
        this.wrapper.removeClass('mw-handle-active');
    };

    
    let _content = null;
    this.setContent = function (content) {
        if(_content){
            _content.remove()
        }
        _content = content;
        this.wrapper.append(_content);
    }

    this.getContent = function() {
        return _content;
    }


    this.initDraggable = function () {
 

      this.draggable = new Draggable({
          handle: this.settings.handle,
          element: null,
          helper: true,
          dropIndicator: this.settings.dropIndicator,
          document: this.settings.document,
          target: this.settings.root,
          stateManager: this.settings.stateManager,
          type: this.settings.type

      }, options);

      
        this.draggable.on('dragStart', function () {
          
            scope.wrapper
            .addClass('mw-handle-item-dragging')
            .get(0)
            .ownerDocument.querySelectorAll('.mw-le-handle-menu-button.sub-menu-active')
            .forEach(el => el.classList.remove('sub-menu-active'));

        })
        this.draggable.on('dragEnd', function () {
             
            scope.wrapper.removeClass('mw-handle-item-dragging');
            scope.position(scope.getTarget())
        });

    };

    const getScroll = () => {
        if (this.settings.document.defaultView.pageYOffset !== undefined) {
            return {x: this.settings.document.defaultView.pageXOffset, y: this.settings.document.defaultView.pageYOffset};
        } else {
            let sx, sy, d = this.settings.document,
                r = d.documentElement,
                b = d.body;
            sx = r.scrollLeft || b.scrollLeft || 0;
            sy = r.scrollTop || b.scrollTop || 0;
            return {x: sx, y: sy};
        }
    }

    this.position = function(target) {
        if(!target){
            return
        }
        const off = DomService.offset(target);
        const scroll = getScroll();
        const menu = this.wrapper.get(0).querySelector('.mw-handle-item-menus-holder');


 
 



 

        if(menu) {

            let transform = 0

            if(this.settings.offsetMenuTransform) {
                transform = this.settings.offsetMenuTransform(scroll, off, menu);
            }

            

            

            menu.style.transition = `none`;
            menu.style.transform = transform ? `translateY(${transform}px)` : '';

            if(typeof this.settings.onPosition === 'function') {
                this.settings.onPosition(menu, transform, off)
            }
            
    
            
    
            setTimeout(() => menu.style.transition = ``, 10)
        }


        var height = off.height;
        if(!height) {
            height = target.scrollHeight;
        }

         this.wrapper.css({
            top:  off.top, 
            left:  off.left,
            width: off.width,   
            height
        });
        
    }


    var _draggable;

    this.setDraggable = function(valueOrTarget) {
        if(typeof value === 'boolean') {
            _draggable = value;
            return this;
        }
        if(typeof this.settings.setDraggableTarget === 'function') {
            _draggable = this.settings.setDraggableTarget(valueOrTarget);
        }
        this.wrapper.get(0).dataset.draggable = _draggable;
    }

    this.getDraggable = function() {
        return _draggable;
    }

    this.set = function (target, forced) {
 

        
        if (!target) {
            _currentTarget = null;
            this.setDraggable(false)
            return;
        }

        this.position(target);
        this.setDraggable(target)
        this.show();

        this.draggable.setElement(target);
        if(_currentTarget !== target || forced) {
            _currentTarget = target;
            this.dispatch('targetChange', target);
        }
        setTimeout(() => this.wrapper.addClass('mw-handle-active'), 1);
        return this;
    };


    var _draggablePaused = false;

    this.draggablePaused = function(state) {
        if (typeof state !== 'undefined') {
            _draggablePaused = state;
        }
        return _draggablePaused;
    }

    this.createHandle = function () {
        if (this.settings.handle === 'self') {

            var elementhandle = ElementManager({
                tag: 'div',
                props: {
                    className: 'mw-handle-item-draggable-self',
 
                }
            });

            this.wrapper.append(elementhandle);

            this.settings.document.addEventListener('mousedown', function(){
                const draggablePaused = scope.draggablePaused();

                if(draggablePaused !== true && draggablePaused !== scope.getTarget()) {
                    elementhandle.addClass('active');
                }
            });

            this.settings.document.addEventListener('mouseup', function(){
                elementhandle.removeClass('active');
            });

            this.settings.handle = elementhandle;
            this.handle = elementhandle;

            this.wrapper.append(elementhandle)


            return;

        }

        if (this.settings.handle) {
            if(typeof this.settings.handle === 'string') {
                //this.settings.handle = (this.handle)
            }
            this.handle = this.settings.handle;
        } else {
            this.handle = ElementManager({
                tag: 'div',
                props: {
                    className: 'mw-handle-item-handle',
                    contentEditable: false,
                    draggable: true,
                }
            });
            this.wrapper.append(this.handle);
        }
        
    }

    var _resizableMaxWidth = this.settings.document.defaultView.innerWidth;
    var _resizableMaxHeight = this.settings.document.defaultView.innerHeight;

    this.resizableMaxWidth = function(number) {
        if(typeof number === 'undefined') {
            return _resizableMaxWidth;
        }
        _resizableMaxWidth = number;
    }

    this.resizableMaxHeight = function(number) {
        if(typeof number === 'undefined') {
            return _resizableMaxHeight;
        }
        _resizableMaxHeight = number;
    }

    this.findClosestElementByClass = function(element, maxDistance = 200) {
        let closestElements = [];
        let currentElement = element;
        let distance = 0;
   
      
        while (currentElement !== document) {
          const elements = currentElement.parentNode.querySelectorAll('.element,.module');
          
          if (elements.length > 0) {
            closestElements = [...elements].filter(el => {
              const elDistance = Math.abs(element.getBoundingClientRect().top - el.getBoundingClientRect().top);
              return elDistance <= maxDistance;
            });
            
            if (closestElements.length > 0) {
              break;
            }
          }
      
          currentElement = currentElement.parentNode;
          distance++;
      
          if (distance > maxDistance / 10) {
            // Break out of the loop if we've exceeded the maximum distance threshold
            break;
          }
        }
      
        return closestElements;
      }


      function calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight) {

        var ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);
    
        return { width: srcWidth*ratio, height: srcHeight*ratio };
     }




     var _resizerEnabled = this.settings.resizable === true;
      

     this.resizerEnabled = function(state) {
        if (!this.settings.resizable) {
            return;
        }

        if(typeof state === 'boolean') {
            _resizerEnabled = state;
        }
 
        this.resizer.element.classList[_resizerEnabled ? 'remove' : 'add']('mw-le-resizable-disabled');
         
        return _resizerEnabled

     }

 

    this.resizable = function() {
        if(!this.settings.resizable) {
            return;
        }

        

       this.resizer = new Resizable({
            element: this.wrapper.get(0),
            document: this.settings.document,
        });

        this.resizerEnabled()

        this.resizer.mount();
        this.resizer.on('resize',  data => {
            const target = this.getTarget();
            const prevData = target.$$prevData || data;
            if(this.settings.automaticMaxWidth) {
                target.style.maxWidth = '100%';
            }
            if(target.nodeName === 'IMG') {
                data = calculateAspectRatioFit(target.offsetWidth, target.offsetHeight, data.width, data.height)
                target.style.height = data.height + 'px';
                target.style.width = data.width + 'px';
                
            } else {
                target.style.minHeight = data.height + 'px';
                target.style.width = data.width + 'px';
            }
             
            var isCol = target.classList.contains('mw-col');
            if(isCol) {
                const next = target.nextElementSibling;
                const prev = target.previousElementSibling;
                if(next) {
                    // const nextWidth = parseFloat(next.ownerDocument.defaultView.getComputedStyle(next).width)
                    const nextWidth = next.offsetWidth
                    if(prevData.width > data.width) {
                        next.style.width = (nextWidth + (prevData.width - data.width )) + 'px'
                    } else {
                        next.style.width = (nextWidth - (data.width - prevData.width )) + 'px'
                    }
                } else if(prev) {
                    const prevWidth = prev.offsetWidth
                    if(prevData.width > data.width) {
                        prev.style.width = (prevWidth + (prevData.width - data.width )) + 'px'
                    } else {
                        prev.style.width = (prevWidth - (data.width - prevData.width )) + 'px'
                    }
                }
            }
            this.set(target)

            target.$$prevData = data;
        });
    }

    this.createWrapper = function() {
        this.wrapper = ElementManager({
            tag: 'div',
            props: {
                className: 'mw-handle-item ' + (this.settings.className || 'mw-handle-type-default'),
                id: this.settings.id || ('mw-handle-' + new Date().getTime()),
                contentEditable: false
            }
        });
 

        this.wrapper.on('mousedown', function () {
            this.classList.remove('mw-handle-item-mouse-down')
        });

        ElementManager(document.body).on('mouseup touchend', function () {
            scope.wrapper.removeClass('mw-handle-item-mouse-down')
        });

        this.settings.document.body.appendChild(this.wrapper.get(0));

 
    };

    this.createWrapper();
    this.createHandle();
    this.initDraggable();
    if(this.settings.content) {
        this.setContent(this.settings.content);
    }
    this.hide()
    this.resizable()

    this.settings.document.addEventListener('orientationChange', e => this.position(this.getTarget()))
    this.settings.document.defaultView.addEventListener('resize', e => this.position(this.getTarget()))
    this.settings.document.addEventListener('scroll', e => this.position(this.getTarget()))
};
