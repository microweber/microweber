(function(mw){

    mw.paddingEditor = function( options ) {

        this._active = null;
        var scope = this;

        this.create = function() {
            this.paddingTop = document.createElement('div');
            this.paddingTop.className = 'mw-padding-ctrl mw-padding-ctrl-top';

            this.paddingBottom = document.createElement('div');
            this.paddingBottom.className = 'mw-padding-ctrl mw-padding-ctrl-bottom';

            document.body.appendChild(this.paddingTop);
            document.body.appendChild(this.paddingBottom);
        };

        this.eventsHandlers = function() {
            mw.on('LayoutOver', function(e, el){
                var $el = $(el);
                var off = $el.offset();
                scope._active = el;
                scope.paddingTop.style.top = off.top + 'px';
                scope.paddingBottom.style.top = (off.top + $el.outerHeight() - 20) + 'px';
            });
        };

        this.init = function() {
           // this.eventsHandlers();
            // this.create();
        };

        this.init();
    };

})(window.mw);
