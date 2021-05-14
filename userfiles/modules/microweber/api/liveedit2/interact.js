/* jshint esversion: 6 */
/* globals: mw */

export const InteractionService = function (settings, rootNode) {
    this.settings = settings;

    rootNode = rootNode || document.body;

    var doc = rootNode.ownerDocument;

    var _e = {};
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


    var handleMove = function (e) {
        var tartet = e.target;
    };

    this.init = function () {
        rootNode.addEventListener("mousemove", function (event){
            handleMove(event);
        });
        rootNode.addEventListener("touchmove", function (event){
            handleMove(event);
        });
    };

    this.init();

};

var DropIndicator = function (options) {

    options = options || {};

    var defaults = {
        template: 'default'
    };

    this.settings = mw.object.extend({}, defaults, options);

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
        'before-top', 'before-right', 'before-bottom', 'before-left',
        'inside-top', 'inside-right', 'inside-bottom', 'inside-left'
    ];

    var positionsClasses = positions.map(function (cls){ return 'mw-drop-indicator-position-' + cls });

    this.position = function (rect, position) {
        this._indicator.removeClass(positionsClasses);
        if(!rect || !position) return;
            if(rect.nodeType === 1) {
            rect = rect.getBoundingClientRect();
        }
        this._indicator.addClass('mw-drop-indicator-position-' + position);
        this._indicator.css({
            height: rect.height,
            left: rect.left,
            top: rect.top,
            width: rect.width,
        });
        this.show();
    };

    this.make = function () {
        this._indicator = mw.element();
        this._indicator.html('<div class="mw-drop-indicator-block"><div class="mw-drop-indicator-pin"></div></div>');
        this._indicator.addClass('mw-drop-indicator mw-drop-indicator-template-' + this.settings.template);
        this.hide();
        document.body.appendChild(this._indicator.get(0));
    };

    this.init = function (){
        this.make();
    };

    this.init();

};
