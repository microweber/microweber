export const Tooltip = (node, content, position) => {
    if(!node || !content) return;
    node = node.isMWElement ? node.get(0) : node;
    node.dataset.tooltip = content;
    node.title = content;
    node.dataset.tooltipposition = position || 'top-center';
};


export const ToolTipController = function (options = {}) {

    const defaults = {
        template: 'default',
        overlay: false
    };

    var scope = this;

    this.settings = Object.assign({}, defaults, options);
    if (this.settings.skin ) {
        this.settings.template = this.settings.skin;
    }

    const create = () => {
        let tpl = scope.settings.template.indexOf('mw-le-tooltip') === 0 ? scope.settings.template : 'mw-le-tooltip  mw-le-tooltip-' + scope.settings.template;
        tpl += ' mw-le-tooltip-widget';
        scope.tooltip = mw.element({
            tag: 'div',
            props: {
                className: tpl,
                id: scope.settings.id || mw.id('mw-le-tooltip-')
            }
        });
        scope.tooltip.get(0)._mwtooltip = scope;
        if ( scope.settings.overlay) {
            scope.overlay = mw.element({
                tag: 'div',
                props: {
                    className: 'mw-le-tooltip-overlay',
                }
            });
            scope.overlay.on('mousedown touchstart', function (){
                scope.remove();
            });
        }
        mw.element('body')
            .append(scope.overlay)
            .append(scope.tooltip);
        scope.content(scope.settings.content);
    };

    var _e = {};

    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };

    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    this.content = function (content) {
        if (typeof content === 'undefined') {
            return scope.tooltip.innerHTML;
        }
        if(typeof content === 'string') {
            scope.tooltip.html(content || '') ;
        } else if(content.nodeType === 1) {
            scope.tooltip.empty().append(content || '') ;
        }

    };

    this.remove = function () {
        this.tooltip.remove();
        if (this.overlay) {
            this.overlay.remove();
        }
        this.dispatch('removed');
    };
    this.show = function () {
        this.tooltip.show();
        if (this.overlay) {
            this.overlay.show();
        }
        this.dispatch('show');
    };
    this.hide = function () {
        this.tooltip.hide();
        if (this.overlay) {
            this.overlay.hide();
        }
        this.dispatch('hide');
    };

    this._position = null;
    this.position = function (position, target) {
        if (target) {
            scope.settings.element = target;
        }
        if(typeof scope.settings.element === 'string') {
            scope.settings.element = document.querySelector(scope.settings.element);
        }
        var el = scope.settings.element;

        if (!el) {
            return false;
        }
        var tooltip = this.tooltip.get(0);
        var w = el.offsetWidth,
            tipwidth =  tooltip.offsetWidth,
            h = el.offsetHeight,
            off = {
                top: el.offsetTop + el.ownerDocument.defaultView.scrollY,
                left: el.offsetLeft + el.ownerDocument.defaultView.scrollX,
            };
        if (off.top === 0 && off.left === 0) {
            off = {
                top: el.parentElement.offsetTop + el.ownerDocument.defaultView.scrollY,
                left: el.parentElement.offsetLeft + el.ownerDocument.defaultView.scrollX,
            };
        }
        off.left = off.left > 0 ? off.left : 0;
        off.top = off.top > 0 ? off.top : 0;

        var leftCenter = off.left - tipwidth / 2 + w / 2;
        leftCenter = leftCenter > 0 ? leftCenter : 0;

         tooltip.style.top = (off.top + h) + 'px';
         tooltip.style.left = (leftCenter) + 'px';

        if ( (tooltip.offsetTop) < 0) {
            tooltip.style.top =  '0px';
        }
        this.show();
    };

    var init = function () {
        create();
        scope.position();
        scope.show();
    };

    init();

};
