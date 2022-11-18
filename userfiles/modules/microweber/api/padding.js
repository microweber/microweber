mw.require('tempcss.js');

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


            // this.paddingBottom.style.visibility = 'hidden';
            // this.paddingTop.style.visibility = 'hidden';

            document.body.appendChild(this.paddingTop);
            document.body.appendChild(this.paddingBottom);
        };


        this.record = function() {
            if(!scope._active){
                return;
            }

            var rec_scope = scope._active;
            if(rec_scope.parentNode){
                rec_scope = rec_scope.parentNode;
            }
        //    var root = mw.tools.firstParentOrCurrentWithAnyOfClasses(scope._active.parentNode, ['edit', 'element', 'module']);
            var root = mw.tools.firstParentOrCurrentWithAnyOfClasses(rec_scope, ['edit', 'element', 'module']);
            mw.liveEditState.record({
                target:root,
                value: root.innerHTML
            });
        };



        if(scope && scope._active){

        }
        this.handleMouseMove = function() {
            mw.$(this.paddingTop).on('mousedown touchstart', function(){
                scope._paddingTopDown = true;
                mw.liveEditSelectMode = 'none';
                mw.$('html').addClass('padding-control-start');
            });
            mw.$(this.paddingBottom).on('mousedown touchstart', function(){
                scope._paddingBottomDown = true;
                mw.liveEditSelectMode = 'none';
                mw.$('html').addClass('padding-control-start');
                scope.record();
            });
            mw.$(document.body).on('mouseup touchend', function(){
                if(scope._paddingTopDown || scope._paddingBottomDown) {
                    scope.record();
                }
                mw.liveEditSelectMode = 'element';

                scope._paddingTopDown = false;
                scope._paddingBottomDown = false;
                scope._working = false;
                mw.$(scope._info).removeClass('active');
                scope.activeMark(false);
                mw.liveEditSelector.active(true);
                mw.$('html').removeClass('padding-control-start');
            });
            mw.event.windowLeave(function (e) {
                if(scope._paddingTopDown || scope._paddingBottomDown) {
                    scope.record();
                }
                mw.liveEditSelectMode = 'element';
                scope._paddingTopDown = false;
                scope._paddingBottomDown = false;
                scope._working = false;
                mw.$(scope._info).removeClass('active');
                mw.liveEditSelector.active(true);
                mw.$('html').removeClass('padding-control-start');
            });
            mw.$(document).on('mousemove touchmove', function(e){
                if(scope._active){
                    var isDown = e.pageY < scope._pageY;
                    var inc = isDown ? scope._pageY - e.pageY : e.pageY - scope._pageY;
                    var curr;
                    if(scope && scope._active && scope._paddingTopDown){
                        scope._working = true;
                        curr = scope._active._currPaddingTop || (parseFloat($(scope._active).css('paddingTop')));

                        if(isDown){
                            scope._active.style.paddingTop = (curr <= 0 ? 0 : curr-inc) + 'px';
                        } else {
                            scope._active.style.paddingTop = (curr + inc) + 'px';
                        }
                        scope._active._currPaddingTop = parseFloat(scope._active.style.paddingTop);
                        scope.position(scope._active);
                        scope.info();
                        scope._active.setAttribute('staticdesign', true);
                        scope.activeMark(true);
                        mw.wysiwyg.change(scope._active);
                        mw.liveEditSelector.pause();
                        mw.trigger('PaddingControl', scope._active);
                    } else if(scope && scope._active && scope._paddingBottomDown){
                        scope._working = true;
                        curr = scope._active._currPaddingBottom || (parseFloat($(scope._active).css('paddingBottom')));
                        if(isDown){
                            scope._active.style.paddingBottom = (curr <= 0 ? 0 : curr-inc) + 'px';
                        } else {
                            scope._active.style.paddingBottom = (curr + inc) + 'px';
                        }
                        scope._active._currPaddingBottom = parseFloat(scope._active.style.paddingBottom);
                        scope.position(scope._active);
                        scope.info();
                        scope._active.setAttribute('staticdesign', true);
                        scope.activeMark(true);
                        mw.wysiwyg.change(scope._active);
                        mw.liveEditSelector.pause();
                        mw.trigger('PaddingControl', scope._active);
                    }
                    scope._pageY = e.pageY;
                    scope._activePadding = curr;

                }

                if (scope._active && mw.liveedit.data.get('move', 'hasLayout')) {
                    scope.show();
                    scope.position();
                } else {
                    scope.hide();
                }
            });
        };
        this.show = function(){
            scope.paddingTop.style.display = 'block';
            scope.paddingBottom.style.display = 'block';
        };

        this.hide = function(){
            scope.paddingTop.style.display = 'none';
            scope.paddingBottom.style.display = 'none';
        };

        this.position = function(targetIsLayout) {
            var $el = mw.$(targetIsLayout);
            var off = $el.offset();
            scope._active = targetIsLayout;
            scope.paddingTop.style.top = off.top + 'px';
            scope.paddingBottom.style.top = (off.top + $el.outerHeight() - this.settings.height) + 'px';
        };

        this.selectors = [
            '.mw-padding-gui-element',
            '.mw-padding-control-element',
        ];

        this.prepareSelectors = function(){
            /* var i = 0;
            for( ; i < this.selectors.length; i++){
                if(this.selectors[i].indexOf('[id') === -1){
                    this.selectors[i] += '[id]';
                }
            } */
        };

        this.addSelector = function(selector){
            this.selectors.push(selector);
            this.prepareSelectors();
        };

        this.eventsHandlers = function() {
            // mw.on('moduleOver ModuleClick', function(e, el){
            $(document).on('mousemove touchmove', function(e){
                var el = e.target;
                if(!scope._working){
                    var targetIsLayout = mw.tools.firstMatchesOnNodeOrParent(el, scope.selectors);

                    if(targetIsLayout){
                        if(mw.tools.hasClass(targetIsLayout, 'module')){
                            var child = mw.$(targetIsLayout).children(scope.selectors.join(','))[0];
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
            this.prepareSelectors();
            this.hide();
        };

        this.activeMark = function (state) {
            if(typeof state === 'undefined') {
                state = false;
            }
            if(!this._activeMark) {
                this._activeMark = document.createElement('div');
                this._activeMark.className = 'mw-padding-control-active-mark';
                document.body.appendChild(this._activeMark);
            }
            if (state) {
                mw.tools.addClass(this._activeMark, 'active');
                var active = scope._paddingTopDown ? scope.paddingTop : scope.paddingBottom;
                var off = scope._active.getBoundingClientRect();
                this._activeMark.style.left = off.left + 'px';
                this._activeMark.style.width = off.width + 'px';
                this._activeMark.style.height = scope._activePadding + 'px';
                if (scope._paddingTopDown) {
                    this._activeMark.style.top = (off.top + scrollY) + 'px';
                } else {
                    this._activeMark.style.top = ((off.top + scrollY + mw.$(scope._active).outerHeight()) - parseFloat(scope._active.style.paddingBottom)) + 'px';
                }
            } else {
                mw.tools.removeClass(this._activeMark, 'active');
            }
        };

        this.generateCSS = function(obj, media){
            if(!obj || !scope._active) return;

            media = (media || 'all').trim();
            var selector = mw.tools.generateSelectorForNode(scope._active);
            var objCss = '{';
            for (var i in obj) {
                objCss += (i + ':' + obj[i] + ';');
            }
            objCss += '}';
            var css = '@media ' + media  + ' {' + selector + objCss + '}';
            return css;
        };

        this.info = function() {
            if(!this._info){
                this._info = document.createElement('div');
                this._info.className = 'mw-padding-control-info';
                document.body.appendChild(this._info);
            }
            mw.$(this._info).addClass('active');
            var off;
            if (scope._paddingTopDown) {
                off = mw.$(scope.paddingTop).offset();
                this._info.style.top = (off.top + scope.settings.height) + 'px';
                this._info.innerHTML = scope._active.style.paddingTop;
            } else if (scope._paddingBottomDown) {
                off = mw.$(scope.paddingBottom).offset();
                this._info.style.top = (off.top - scope.settings.height - 30) + 'px';
                this._info.innerHTML = scope._active.style.paddingBottom;
            }
            this._info.style.left = (off.left + (scope._active.clientWidth/2)) + 'px';
        };
        this.init();
    };

})(window.mw);
