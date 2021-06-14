import {ObjectService} from "./object.service";
import {Draggable} from "./draggable";
import {DropIndicator} from "./interact";
import {DomService} from "./dom";

export const Handle = function (options) {

    var defaults = {

    };


    var scope = this;

    this.settings = ObjectService.extend({}, defaults, options);

    const _e = {};
    this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };

    var _visible = true;
    var _currentTarget = null;

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
    };
    let _content = null;
    this.setContent = function (content) {
        if(_content){
            _content.remove()
        }
        _content = content;
        this.wrapper.append(_content);
    }


    this.initDraggable = function () {
      this.draggable = new Draggable({
          handle: this.handle,
          element: null,
          helper: true,
          dropIndicator: this.settings.dropIndicator
      }, options);
        this.draggable.on('dragStart', function () {
            scope.wrapper.addClass('mw-handle-item-dragging');
        })
        this.draggable.on('dragEnd', function () {
            scope.wrapper.removeClass('mw-handle-item-dragging');
        })
    };

    this.set = function (target) {
        if (!target) {
            _currentTarget = null;
            return;
        }
        var off = DomService.offset(target);
         this.wrapper.css({
            top: off.top,
            left: off.left,
            width: off.width,
            height: off.height,
        });
        this.show();
        this.draggable.setElement(target);
        if(_currentTarget !== target) {
            _currentTarget = target;
            this.dispatch('targetChange', target);
        }

    };

    this.createHandle = function () {
        this.handle = mw.element({
            tag: 'div',
            props: {
                className: 'mw-defaults mw-handle-item-handle',
                contentEditable: false,
                draggable: true,
                innerHTML: this.settings.title
            }
        });
        this.wrapper.append(this.handle);
    }

    this.createWrapper = function() {
        this.wrapper = mw.element({
            tag: 'div',
            props: {
                className: 'mw-defaults mw-handle-item ' + (this.settings.className || 'mw-handle-type-default'),
                contentEditable: false,
                id: this.settings.id || ('mw-handle-' + new Date().getTime())
            }
        });

        this.wrapper.on('mousedown', function () {
            mw.tools.addClass(this, 'mw-handle-item-mouse-down');
        });
        mw.element(document.body).on('mouseup touchend', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
        });
        document.body.appendChild(this.wrapper.get(0));
    };

    this.createWrapper();
    this.createHandle();
    this.initDraggable();
    if(this.settings.content) {
        this.setContent(this.settings.content)
    }




};
