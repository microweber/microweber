(function(){
    var Element = function(options){

        options = options || {};

        var defaults = {
            tag: 'div',
            props: {},
            document: document,
            register: null
        };
        var scope = this;

        this.settings = $.extend({}, defaults, options);

        this.document = this.settings.document || document;

        this.register = function(){
            if(this.settings.register) {
                var reg = this.settings.register;
                reg.push(this);
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
                } else {
                    var val = this.settings.props[i];
                    if(!this._specialProps(i, val)) {
                        this.node[i] = val;
                    }
                }
            }
        };

        this.prop = function(prop, val){
            if(this.node[prop] !== val){
                this.node[prop] = val;
                this.$node.trigger('propChange', [prop, val, Element]);
            }
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
            if(!val) {
                return this.innerHTML;
            }
            this.node.innerHTML = val;
        };

        this.append = function (el) {
            if(el) {
                return this.$node.append( el.node ? el.node : el );

            }
        };

        this.prepend = function (el) {
            return this.$node.prepend( el.node ? el.node : el );
        };

        this.on = function(events, cb){
            events = events.trim().split(' ');
            events.forEach(function (ev) {
                scope.node.addEventListener(ev, function(e) {
                    cb.call(scope, e, this);
                }, false);
            });
        };
        this.init = function(){
            this.create();
            this.setProps();
            this.register();
        };
        this.init();
    };
    mw.element = function(options){
        return new Element(options);
    };
})();
