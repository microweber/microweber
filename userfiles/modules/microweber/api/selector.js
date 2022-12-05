mw.css = function(element, css){
    for(var i in css){
        element.style[i] = typeof css[i] === 'number' ? css[i] + 'px' : css[i];
    }
}
mw.Selector = function(options) {

    options = options || {};

    var defaults = {
        autoSelect: true,
        document: document,
        toggleSelect: false, // second click unselects element
        strict: false // only match elements that have id
    };

    this.options = $.extend({}, defaults, options);
    this.document = this.options.document;


    this.buildSelector = function(){
        var stop = this.document.createElement('div');
        var sright = this.document.createElement('div');
        var sbottom = this.document.createElement('div');
        var sleft = this.document.createElement('div');

        stop.className = 'mw-selector mw-selector-type-active mw-selector-top';
        sright.className = 'mw-selector mw-selector-type-active mw-selector-right';
        sbottom.className = 'mw-selector mw-selector-type-active mw-selector-bottom';
        sleft.className = 'mw-selector mw-selector-type-active mw-selector-left';

        this.document.body.appendChild(stop);
        this.document.body.appendChild(sright);
        this.document.body.appendChild(sbottom);
        this.document.body.appendChild(sleft);

        this.selectors.push({
            top:stop,
            right:sright,
            bottom:sbottom,
            left:sleft,
            active:false
        });
    };
    this.getFirstNonActiveSelector = function(){
        var i = 0;
        for( ; i <  this.selectors.length; i++){
            if(!this.selectors[i].active){
                return this.selectors[i]
            }
        }
        this.buildSelector();
        return this.selectors[this.selectors.length-1];
    };
    this.deactivateAll = function(){
         var i = 0;
        for( ; i <  this.selectors.length; i++){
            this.selectors[i].active = false;
        }
    };


    this.pause = function(){
        this.active(false);
        this.hideAll();
    };
    this.hideAll = function(){
        var i = 0;
        for( ; i <  this.selectors.length; i++){
            this.hideItem(this.selectors[i]);
        }
        this.hideItem(this.interactors)
    };

    this.hideItem = function(item){

        item.active = false;
        for (var x in item){
            if(!item[x]) continue;
            item[x].style.visibility = 'hidden';
        }
    };
    this.showItem = function(item){
        for (var x in item) {
            if(typeof item[x] === 'boolean' || !item[x].className || item[x].className.indexOf('mw-selector') === -1) continue;
            item[x].style.visibility = 'visible';
        }
    };

    this.buildInteractor = function(){
        var stop = this.document.createElement('div');
        var sright = this.document.createElement('div');
        var sbottom = this.document.createElement('div');
        var sleft = this.document.createElement('div');

        stop.className = 'mw-selector mw-interactor mw-selector-top';
        sright.className = 'mw-selector mw-interactor mw-selector-right';
        sbottom.className = 'mw-selector mw-interactor mw-selector-bottom';
        sleft.className = 'mw-selector mw-interactor mw-selector-left';

        this.document.body.appendChild(stop);
        this.document.body.appendChild(sright);
        this.document.body.appendChild(sbottom);
        this.document.body.appendChild(sleft);

        this.interactors = {
            top:stop,
            right:sright,
            bottom:sbottom,
            left:sleft
        };
    };
    this.isSelected = function(e){
        var target = e.target?e.target:e;
        return this.selected.indexOf(target) !== -1;
    };

    this.unsetItem = function(e){
        var target = e.target?e.target:e;
        for(var i = 0;i<this.selectors.length;i++){
            if(this.selectors[i].active === target){
                this.hideItem(this.selectors[i]);
                break;
            }
        }
        this.selected.splice(this.selected.indexOf(target), 1);
    };

    this.positionSelected = function(){
        for(var i = 0;i<this.selectors.length;i++){
            this.position(this.selectors[i], this.selectors[i].active)
        }
    };
    this.position = function(item, target){
        var off = mw.$(target).offset();
        mw.css(item.top, {
            top:off.top,
            left:off.left,
            width:target.offsetWidth
        });
        mw.css(item.right, {
            top:off.top,
            left:off.left+target.offsetWidth,
            height:target.offsetHeight
        });
        mw.css(item.bottom, {
            top:off.top+target.offsetHeight,
            left:off.left,
            width:target.offsetWidth
        });
        mw.css(item.left, {
            top:off.top,
            left:off.left,
            height:target.offsetHeight
        });
    };


    var _e = {};

    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };

    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

    this.setItem = function(e, item, select, extend){
        if(!e || !this.active()) return;
        var target = e.target && !e.nodeType ? e.target : e;
        if (this.options.strict) {
            target = mw.tools.first(target, ['[id]', '.edit']);
        }
        var validateTarget = !mw.tools.firstMatchesOnNodeOrParent(target, ['.mw-control-box', '.mw-defaults']);
        if(!target || !validateTarget) return;
        if($(target).hasClass('mw-select-skip')){
            return this.setItem(target.parentNode, item, select, extend);
        }
        if(select){
            if(this.options.toggleSelect && this.isSelected(target)){
                this.unsetItem(target);
                return false;
            }
            else{
                if(extend){
                    this.selected.push(target);
                }
                else{
                    this.selected = [target];
                }
                mw.$(this).trigger('select', [this.selected]);
                this.dispatch('select', this.selected)
            }
        }


        this.position(item, target);

        item.active = target;

        this.showItem(item);
    };

    this.select = function(e, target){

        if(!e && !target) return;
        if(!e.nodeType){
            target = target || e.target;
        } else{
            target = e;
        }

        if(!mw.tools.isEditable(target) && !target.classList.contains('edit') && !target.id) {
            // if parent is inside module but module has editable parent
            target = mw.tools.firstParentWithClass(target, 'edit');
            if(!target) return;
        }

        if(e.ctrlKey){
            this.setItem(target, this.getFirstNonActiveSelector(), true, true);
        }
        else{
            this.hideAll();
            this.setItem(target, this.selectors[0], true, false);
        }

    };

    this.deselect = function(e, target){
        e.preventDefault();
        target = target || e.target;

        this.unsetItem(target);

    };

    this.init = function(){
        this.buildSelector();
        this.buildInteractor();
        var scope = this;
        mw.$(this.root).on("click", function(e){
            if(scope.options.autoSelect && scope.active()){

                scope.select(e);
            }
        });

        mw.$(this.root).on( "mousemove touchmove touchend", function(e){
            if(scope.options.autoSelect && scope.active()){
                scope.setItem(e, scope.interactors);
            }
        });
        mw.$(this.root).on( 'scroll', function(){
            scope.positionSelected();
        });
        mw.$(window).on('resize orientationchange', function(){
            scope.positionSelected();
        });
    };

    this._active = false;
    this.active = function (state) {
        if(typeof state === 'undefined') {
            return this._active;
        }
        if(this._active !== state) {
            this._active = state;
            mw.$(this).trigger('stateChange', [state]);
            this.dispatch('stateChange', state);
        }
    };
    this.selected = [];
    this.selectors = [];
    this.root = options.root;
    this.init();
};
