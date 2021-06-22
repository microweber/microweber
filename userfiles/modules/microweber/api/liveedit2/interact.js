
import {ObjectService} from "./object.service";
import {DomService} from "./dom";
import {CreateElement} from "./element";

export const DropIndicator = function (options) {

    options = options || {};

    var defaults = {
        template: 'default'
    };

    this.settings = ObjectService.extend({}, defaults, options);

    this._indicator = null;

    var _e = {};
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    this.hide = function () {
        this._indicator.addClass('mw-drop-indicator-hidden');
    };

    this.show = function () {
        this._indicator.removeClass('mw-drop-indicator-hidden');
    };

    var positions = [
        'before-top', 'prepend-top',
        'after-bottom', 'append-bottom'
    ];


    const positionsPrefix = 'mw-drop-indicator-position-';

    var positionsClasses = positions.map(function (cls){ return positionsPrefix + cls });

    var currentPositionClass = null; // do not set if same to prevent animation stop

    this.position = function (rect, position) {
        if(currentPositionClass !== position) {
            this._indicator.removeClass(positionsClasses);
            currentPositionClass = position
        }

        if(!rect || !position) return;
            if(rect.nodeType === 1) {
                rect = DomService.offset(rect);
            }
        this._indicator.addClass(positionsPrefix + position);
        this._indicator.css({
            height: rect.height,
            left: rect.left,
            top: rect.top,
            width: rect.width,
        });
        this.show();
    };

    this.make = function () {
        this._indicator = CreateElement();
        this._indicator.html('<div class="mw-drop-indicator-block"><div class="mw-drop-indicator-pin"></div></div>');
        this._indicator.addClass('mw-drop-indicator mw-drop-indicator-template-' + this.settings.template);
        this.hide();
        this.settings.document.body.appendChild(this._indicator.get(0));
    };

    this.init = function (){
        this.make();
    };

    this.init();

};
