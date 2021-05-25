import {ObjectService} from "./object.service";
import {Draggable} from "./draggable";

export const Handle = function (options) {

    var defaults = {

    };


    var scope = this;

    this.settings = ObjectService.extend({}, defaults, options);

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

    this.initDraggable = function () {
      this.draggable = new Draggable({
          handle: this.handle,
          element: null
      });
    };

    this.set = function (target) {
        if (!target) {
            _currentTarget = null;
            return;
        }
        var off = target.getBoundingClientRect();
        this.wrapper.css({
            top: off.top,
            left: off.left,
            width: off.width,
            height: off.height,
        });
        this.show();
        this.draggable.setElement(target);
        _currentTarget = target;
    };

    this.createHandle = function () {
        this.handle = mw.element({
            tag: 'div',
            props: {
                className: 'mw-defaults mw-handle-item-handle',
                contentEditable: false
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
        mw.$(document).on('mouseup', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
        });
        document.body.appendChild(this.wrapper.get(0));
    };

    this.createWrapper();
    this.initDraggable();
    this.createHandle();



};
