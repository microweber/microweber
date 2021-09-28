
import {ObjectService} from "../classes/object.service";
import {DomService} from '../classes/dom';
import {ElementManager} from "../classes/element";

export const DropIndicator = function (options) {

    options = options || {};

    const defaults = {
        template: 'default'
    };

    let positionCache = { }

    this.settings = ObjectService.extend({}, defaults, options);

    this._indicator = null;

    const _e = {};
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    this.visible = false;

    this.hide = function () {
        if(this.visible) {
            this._indicator.addClass('mw-drop-indicator-hidden');
            this.visible = false;
            positionCache = {}
        }
    };

    this.show = function () {
        console.log(this.visible)
        if(!this.visible) {

            this._indicator.removeClass('mw-drop-indicator-hidden');
        }
        this.visible = true;

    };

    const positions = [
        'before-top', 'prepend-top',
        'after-bottom', 'append-bottom'
    ];


    const positionsPrefix = 'mw-drop-indicator-position-';

    const positionsClasses = positions.map(function (cls){ return positionsPrefix + cls });

    let currentPositionClass = null; // do not set if same to prevent animation stop


    let _positionTime = null;

    this.position = function (target, position) {
        if(!target || !position) return;

        if(positionCache.target === target && positionCache.position === position) {
            return;
        }

        positionCache.target = target;
        positionCache.position = position

        if(currentPositionClass !== position) {
            this._indicator.removeClass(positionsClasses);
            currentPositionClass = position;
            this._indicator.addClass(positionsPrefix + position);
        }

        var rect = DomService.offset(target);

        this._indicator.css({
            height: rect.height,
            left: rect.left,
            top: rect.top,
            width: rect.width
        });
        this.show();


    };

    this.make = function () {
        this._indicator = ElementManager();
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
