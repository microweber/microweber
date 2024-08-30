(function (){
    if(mw.State) return;
    var State = function(options){

        var scope = this;
        var defaults = {
            maxItems: 1000,
            hooks: {}
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
            return this
        };

        var recentRecordIsEqual = function (item) {
            const curr = scope._state[0];
            if(!curr || curr.$initial) return false;
            for (var n in item) {
                if(curr[n] !== item[n]) {
                    return false;
                }
            }
            return true;
        };


        var _paused = false;

        this.paused = function (state){
            if(typeof state === 'undefined') {
                return _paused;
            }
            _paused = state;
            return this;
        };

        this.pause = function () {
            this.paused(true);
            return this;
        };
        this.unpause = function () {
            this.paused(false);
            return this;
        };

        this.record = function(item){

            if(this.paused()) {
                return this;
            }
            if(this.options.interceptors && typeof this.options.interceptors.beforeRecord === 'function'){
                if(!this.options.interceptors.beforeRecord.call(this, item, this.state())){
                    return this;
                }
            }
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

        this.eventData = function(action){
            return {
                hasPrev: this.hasPrev,
                hasNext: this.hasNext,
                active: this.active(),
                activeIndex: this.activeIndex(),
                action
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
                this.dispatch(action, this.eventData());
            }
            if(action !== false){
                mw.$(this).trigger('change', [this.eventData(action)]);
                this.dispatch('change', this.eventData(action));
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
    mw.liveEditState = new mw.State({
        interceptors: {
            beforeRecord: function(item, state){
                if(item.target && item.target.nodeName === 'BODY') {
                    return false;
                }
                const exists = state.find(function (obj) {
                    return  obj.target === item.target && obj.value === item.value;
                });

                return !exists;
            }
        }

    });
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
                edits[i].addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        var sel = getSelection();
                        var target = mw.wysiwyg.validateCommonAncestorContainer(sel.focusNode);
                        var parent = mw.tools.firstParentOrCurrentWithClass(target, 'edit');

                        mw.liveEditState.record({
                            target: parent,
                            value: parent.innerHTML
                        });
                        mw.liveEditState.pause();
                        setTimeout(function (){
                            mw.liveEditState.unpause();
                            mw.liveEditState.record({
                                target: parent,
                                value: parent.innerHTML
                            });
                        }, 10);
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


