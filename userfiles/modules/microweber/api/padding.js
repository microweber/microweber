(function(mw){

    mw.paddingEditor = function( options ) {

        options = options || {};

        var defaults = {
            height: 10
        };

        this.settings = $.extend({}, defaults, options);

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

            this.paddingTop.style.height = this.settings.height + 'px';
            this.paddingBottom.style.height = this.settings.height + 'px';

            document.body.appendChild(this.paddingTop);
            document.body.appendChild(this.paddingBottom);
        };




        this.handleMouseMove = function() {
            $(this.paddingTop).on('mousedown touchstart', function(){
                scope._paddingTopDown = true;
                $('html').addClass('paddding-control-start');
            });
            $(this.paddingBottom).on('mousedown touchstart', function(){
                scope._paddingBottomDown = true;
                $('html').addClass('paddding-control-start');
            });
            $(document.body).on('mouseup touchend', function(){
                scope._paddingTopDown = false;
                scope._paddingBottomDown = false;
                scope._working = false;
                $(scope._info).removeClass('active');
                mw.liveEditSelector.active(true);
                $('html').removeClass('paddding-control-start');
            });
            $(document.body).on('mousemove', function(e){
                var isDown = e.pageY < scope._pageY;
                var inc = 5;
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
                    scope.info();
                    mw.liveEditSelector.pause();
                    mw.trigger('PaddingControl', scope._active);
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
                    scope.info();
                    mw.liveEditSelector.pause()
                    mw.trigger('PaddingControl', scope._active);
                }

                scope._pageY = e.pageY;
            });
        };

        this.position = function(targetIsLayout) {
            var $el = $(targetIsLayout);
            var off = $el.offset();
            scope._active = targetIsLayout;
            scope.paddingTop.style.top = off.top + 'px';
            scope.paddingBottom.style.top = (off.top + $el.outerHeight() - this.settings.height) + 'px';

        };

        this.selectors = [
            '[class*="bg--"]',
            '[data-module-name="layouts"]',
            '[data-type="layouts"]',
            '.imagebg'
        ];

        this.addSelector = function(selector){
            this.selectors.push(selector);
        };

        this.eventsHandlers = function() {
            mw.on('moduleOver ModuleClick', function(e, el, mel){
                if(!scope._working){
                    var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(el, scope.selectors);
                    if(targetIsLayout){
                        if(mw.tools.hasClass(targetIsLayout, 'module')){
                            var child = $(targetIsLayout).children(scope.selectors.join(','))[0];
                            targetIsLayout = child || targetIsLayout;
                        }
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

        this.info = function() {
            if(!this._info){
                this._info = document.createElement('div');
                this._info.className = 'mw-padding-control-info';
                document.body.appendChild(this._info);
            }
            $(this._info).addClass('active');
            var off;
            if (scope._paddingTopDown) {
                off = $(scope.paddingTop).offset();
                this._info.style.top = (off.top + scope.settings.height) + 'px';
                this._info.innerHTML = scope._active.style.paddingTop;
            } else if (scope._paddingBottomDown) {
                off = $(scope.paddingBottom).offset();
                this._info.style.top = (off.top - scope.settings.height - 30) + 'px';
                this._info.innerHTML = scope._active.style.paddingBottom;
            }
        };

        this.init();
    };

})(window.mw);
