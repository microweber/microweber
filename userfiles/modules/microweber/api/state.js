(function (){
    if(mw.State) return;
    var State = function(options){

        var scope = this;
        var defaults = {
            maxItems: 1000
        };
        this.options = $.extend({}, defaults, (options || {}));
        this._state = this.options.state || [];
        this._active = null;
        this._activeIndex = -1;

        this.hasNext = false;
        this.hasPrev = false;

        this.state = function(state){
            if(!state){
                return this._state;
            }
            this._state = state;
            return this;
        };
        var _e = {};
        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


        this.active = function(active){
            if(!active){
                return this._active;
            }
        };

        this.activeIndex = function(activeIndex){
            if(!activeIndex){
                return this._activeIndex;
            }
        };

        this._timeout = null;
        this.timeoutRecord = function(item){
            clearTimeout(this._timeout);
            this._timeout = setTimeout(function(scope, item){
                scope.record(item);
            }, 333, this, item);
        };

        var recentRecordIsEqual = function (item) {
            const curr = scope._state[0];
            if(!curr) return false;
            for (var n in item) {
                if(curr[n] !== item[n]) {
                    return false;
                }
            }
            return true;
        };



        this.record = function(item){
            if(this._activeIndex>-1) {
                var i = 0;
                while ( i <  this._activeIndex) {
                    this._state.shift();
                    i++;
                }
            }
            if (recentRecordIsEqual(item)) {
                return;
            }
            this._state.unshift(item);
            if(this._state.length >= this.options.maxItems) {
                this._state.splice(-1,1);
            }
            this._active = null;
            this._activeIndex = -1;
            this.afterChange(false);
            mw.$(this).trigger('stateRecord', [this.eventData()]);
            this.dispatch('record', [this.eventData()]);
            return this;
        };

        this.actionRecord = function(recordGenFunc, action){
            this.record(recordGenFunc());
            action.call();
            this.record(recordGenFunc());
        };

        this.redo = function(){
            this._activeIndex--;
            this._active = this._state[this._activeIndex];
            this.afterChange('stateRedo');
            this.dispatch('redo');
            return this;
        };

        this.undo = function(){
            if(this._activeIndex === -1) {
                this._activeIndex = 1;
            }
            else{
                this._activeIndex++;
            }
            this._active = this._state[this._activeIndex];
            this.afterChange('stateUndo');
            this.dispatch('undo');
            return this;
        };

        this.hasRecords = function(){
            return !!this._state.length;
        };

        this.eventData = function(){
            return {
                hasPrev: this.hasPrev,
                hasNext: this.hasNext,
                active: this.active(),
                activeIndex: this.activeIndex()
            };
        };
        this.afterChange = function(action){
            this.hasNext = true;
            this.hasPrev = true;

            if(action) {
                if(this._activeIndex >= this._state.length) {
                    this._activeIndex = this._state.length - 1;
                    this._active = this._state[this._activeIndex];
                }
            }

            if(this._activeIndex <= 0) {
                this.hasPrev = false;
            }
            if(this._activeIndex === this._state.length-1 || (this._state.length === 1 && this._state[0].$initial)) {
                this.hasNext = false;
            }

            if(action){

                mw.$(this).trigger(action, [this.eventData()]);
            }
            if(action !== false){
                mw.$(this).trigger('change', [this.eventData()]);
            }
            return this;
        };

        this.reset = function(){
            this._state = this.options.state || [];
            this.afterChange('reset');
            return this;
        };

        this.clear = function(){
            this._state = [];
            this.afterChange('clear');
            return this;
        };


    };
    mw.State = State;
})();

(function(){
    if(mw.liveEditState) return;
    mw.liveEditState = new mw.State();
    mw.liveEditState.record({
         value: null,
         $initial: true
    });
    mw.$liveEditState = mw.$(mw.liveEditState);


    mw.$(document).ready(function(){
        var idata = mw.liveEditState.eventData();


        var edits = document.querySelectorAll('.edit');

        for ( var i = 0; i < edits.length; i++ ) {
            if(!mw.tools.hasParentsWithClass(this, 'edit')) {
                edits[i].addEventListener('beforeinput', function (e) {
                    var sel = getSelection();
                    var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                     if(target) {
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                    }
                });
                edits[i].addEventListener('input', function (e) {
                        var sel = getSelection();
                        var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                        if(!target) return;
                        mw.liveEditState.record({
                            target: target,
                            value: target.innerHTML
                        });
                        this.__initialRecord = false;
                });
            }
        }


        mw.$liveEditState.on('stateUndo stateRedo', function(e, data){

            if(data.active) {
                var target = data.active.target;
                if(typeof target === 'string'){
                    target = document.querySelector(data.active.target);
                }

                if(!data.active || (!target && !data.active.action)) {

                    return;
                }
                if(data.active.action) {
                    data.active.action();
                } else if(document.body.contains(target)) {
                    mw.$(target).html(data.active.value);
                } else{
                    if(target.id) {
                        mw.$(document.getElementById(target.id)).html(data.active.value);
                    }
                }
                if(data.active.prev) {
                    mw.$(data.active.prev).html(data.active.prevValue);
                }
            }
            mw.drag.load_new_modules();

        });

        mw.$('#history_panel_toggle,#history_dd,.mw_editor_undo,.mw_editor_redo').remove();

        mw.element(document.body).on('keydown', function(e) {
            if( e.key )  {
                var key = e.key.toLowerCase();
                if (e.ctrlKey && key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    mw.liveEditState.undo();
                } else if ((e.ctrlKey && key === 'y') || (e.ctrlKey && e.shiftKey && key === 'z')) {
                    e.preventDefault();
                    mw.liveEditState.redo();
                }
            }

        });
    });
})();


