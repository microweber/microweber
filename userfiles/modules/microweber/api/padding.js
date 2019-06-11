(function(mw){

    mw.paddingEditor = function( options ) {

        this._pageY = -1;
        this._active = null;
        this._paddingTopDown = false;
        this._paddingBottomDown = false;
        var scope = this;

        this.create = function() {
            this.paddingTop = document.createElement('div');
            this.paddingTop.className = 'mw-padding-ctrl mw-padding-ctrl-top';

            this.paddingBottom = document.createElement('div');
            this.paddingBottom.className = 'mw-padding-ctrl mw-padding-ctrl-bottom';

            document.body.appendChild(this.paddingTop);
            document.body.appendChild(this.paddingBottom);
        };




        this.handleMouseMove = function() {
            $(this.paddingTop).on('mousedown touchstart', function(){
                scope._paddingTopDown = true;
            });
            $(this.paddingBottom).on('mousedown touchstart', function(){
                scope._paddingBottomDown = true;
            });
            $(document.body).on('mouseup touchend', function(){
                scope._paddingTopDown = false
                scope._paddingBottomDown = false
                scope._working = false;
            });
            $(document.body).on('mousemove', function(e){
                var isDown = e.pageY < scope._pageY;
                var inc = 10;
                if(scope._paddingTopDown){
                    scope._working = true;
                    var curr = scope._active._currPaddingTop || (parseFloat($(scope._active).css('paddingTop')));
                    if(isDown){
                        scope._active.style.paddingTop = (curr <= 0 ? 0 : curr-inc) + 'px';
                    } else {
                        scope._active.style.paddingTop = (curr + inc) + 'px';
                    }
                    scope._active._currPaddingTop = parseFloat(scope._active.style.paddingTop);
                    scope.position(scope._active);
                } else if(scope._paddingBottomDown){
                    scope._working = true;
                    var curr = scope._active._currPaddingBottom || (parseFloat($(scope._active).css('paddingBottom')));
                    if(isDown){
                        scope._active.style.paddingBottom = (curr <= 0 ? 0 : curr-inc) + 'px';
                    } else {
                        scope._active.style.paddingBottom = (curr + inc) + 'px';
                    }
                    scope._active._currPaddingBottom = parseFloat(scope._active.style.paddingBottom);
                    scope.position(scope._active);
                }

                scope._pageY = e.pageY;
            });
        };

        this.position = function(targetIsLayout) {
            var $el = $(targetIsLayout);
            var off = $el.offset();
            scope._active = targetIsLayout;
            scope.paddingTop.style.top = off.top + 'px';
            scope.paddingBottom.style.top = (off.top + $el.outerHeight() - 20) + 'px';
        };

        this.eventsHandlers = function() {

            mw.on('moduleOver ModuleClick', function(e, el){
                if(!scope._working){
                    var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(el, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
                    if(targetIsLayout){
                        scope.position(targetIsLayout);
                    }
                }


            });
        };

        this.init = function() {
            this.create();
            this.eventsHandlers();
            this.handleMouseMove();

        };

        this.init();
    };

})(window.mw);
