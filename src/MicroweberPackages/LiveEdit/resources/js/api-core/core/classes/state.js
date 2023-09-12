
export const State = function(options){

    var scope = this;
    var defaults = {
        maxItems: 1000
    };
    this.options = Object.assign({}, defaults, (options || {}));
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
    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]); return this; };
    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : '';  return this; };


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
       // this.afterChange('stateUndo');
       // this.dispatch('undo');
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

             this.dispatch(action, [this.eventData()]);
        }
        if(action !== false){
            this.dispatch('change', [this.eventData()]);
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
