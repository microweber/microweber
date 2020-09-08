(function(){

    var Element = function(options, root){

        this.nodes = [];
        this.root = root || document;
        this._asElement = false;

        if(options.nodeName && options.nodeType) {
            this.nodes.push(options);
            this.node = (options);
            options = {};
            this._asElement = true;
        } else if(typeof options === 'string') {
            this.nodes = Array.prototype.slice.call(this.root.querySelectorAll());
            options = {};
            this._asElement = true;
        }

        options = options || {};


        var defaults = {
            tag: 'div',
            props: {}
        };
        var scope = this;

        this.settings = $.extend({}, defaults, options);

        this.document =  (this.root.body ? this.root : this.root.ownerDocument);

        this.toggle = function () {
            this.css('display', this.css('display') === 'none' ? 'block' : 'none');
        };



        this.get = function(selector, scope){
            this.nodes = (scope || document).querySelectorAll(selector);
        };

        this.each = function(cb){
            if(this.nodes) {
                for (var i = 0; i < this.nodes.length; i++) {
                    cb.call(this.nodes[i], i);
                }
            } else if(this.node) {
                cb.call(this.node, 0);
            }
        };

        this.create = function(){
            this.node = this.document.createElement(this.settings.tag);
            this.$node = $(this.node);
        };

        this._specialProps = function(dt, val){
            if(dt === 'tooltip') {
                this.node.dataset[dt] = val;
                return true;
            }
        };

        this.setProps = function(){
            for(var i in this.settings.props) {
                if (i === 'dataset') {
                    for(var dt in this.settings.props[i]) {
                        this.node.dataset[dt] = this.settings.props[i][dt];
                    }
                } else if (i === 'style') {
                    for(var st in this.settings.props[i]) {
                        var stval = this.settings.props[i][st];
                        this.node.style[st] = stval;
                    }
                } else {
                    var val = this.settings.props[i];
                    if(!this._specialProps(i, val)) {
                        this.node[i] = val;
                    }
                }
            }
        };

        this.__ = {
            cssNumber: [
                'animationIterationCount',
                'columnCount',
                'fillOpacity',
                'flexGrow',
                'flexShrink',
                'fontWeight',
                'gridArea',
                'gridColumn',
                'gridColumnEnd',
                'gridColumnStart',
                'gridRow',
                'gridRowEnd',
                'gridRowStart',
                'lineHeight',
                'opacity',
                'order',
                'orphans',
                'widows',
                'zIndex',
                'zoom'
            ]
        };

        this._normalizeCSSValue = function (prop, val) {
            if(typeof val === 'number') {
                if(this.__.cssNumber.indexOf(prop) === -1) {
                    val = val + 'px';
                }
            }
            return val;
        };

        this.css = function(css, val){
            if(typeof css === 'string') {
                if(typeof val !== 'undefined'){
                    this.node.style[css] = this._normalizeCSSValue(css, val);
                } else {
                    return this.document.defaultView.getComputedStyle(this.node)[css];
                }
            }
            if(typeof css === 'object') {
                for (var i in css) {
                    this.node.style[i] = this._normalizeCSSValue(i, css[i]);
                }
            }
        };

        this.prop = function(prop, val){
            if(this.node[prop] !== val){
                this.node[prop] = val;
                this.trigger('propChange', [prop, val, Element]);
            }
        };

        this.hide = function () {
            this.node.style.display = 'none';
        };
        this.show = function () {
            this.node.style.display = '';
        };

        this.addClass = function (cls) {
            this.node.classList.add(cls.trim());
        };

        this.toggleClass = function (cls) {
            this.node.classList.toggle(cls.trim());
        };

        this.removeClass = function (cls) {
            this.node.classList.remove(cls.trim());
        };

        this.html = function (val) {
            if(typeof val === 'undefined') {
                return this.node.innerHTML;
            }
            this.node.innerHTML = val;
        };
        this.text = function (val, clean) {
            if(typeof val === 'undefined') {
                return this.node.textContent;
            }
            if(typeof clean === 'undefined') {
                clean = true;
            }
            if (clean) {
                val = this.document.createRange().createContextualFragment(val).textContent;
            }
            this.node.innerHTML = val;
        };

        this.parent = function () {
            return this.node.parentNode;
        };
        this.append = function (el) {
            if(el) {
                return this.node.append( el.node ? el.node : el );
            }
        };

        this.prepend = function (el) {
            return this.$node.prepend( el.node ? el.node : el );
        };
        this._disabled = false;

        Object.defineProperty(this, "disabled", {
            get : function () { return this._disabled; },
            set : function (value) {
                this._disabled = value;
                this.node.disabled = this._disabled;
                this.node.dataset.disabled = this._disabled;
            }
        });

        this.trigger = function(event, data){
            data = data || {};
            scope.node.dispatchEvent(new CustomEvent(event, {
                detail: data,
                cancelable: true,
                bubbles: true
            }));
            return this;
        };

        this.on = function(events, cb){
            events = events.trim().split(' ');
            events.forEach(function (ev) {
                scope.node.addEventListener(ev, function(e) {
                    cb.call(scope, e, e.detail, this);
                }, false);
            });
            return this;
        };
        this.init = function(){
            if(this._asElement) return;
            this.create();
            this.setProps();
         };
        this.init();
    };
    mw.element = function(options){
        return new Element(options);
    };
})();
