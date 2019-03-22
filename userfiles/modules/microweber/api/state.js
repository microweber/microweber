mw.State = function(options){
    this.options = options || {};
    this._state = this.options.state || [];
    this._active = null;
    this._activeIndex = -1;

    this.hasNext = false;
    this.hasPrev = false;

    this.state = function(state){
        if(!state){
            return this._state;
        }
    }

    this.active = function(active){
        if(!active){
            return this._active;
        }
    }

    this.activeIndex = function(activeIndex){
        if(!activeIndex){
            return this._activeIndex;
        }
    }

    this.record = function(item){
        if(this._activeIndex) {
            var i = 0;
            while ( i <= this._activeIndex) {
                this._state.shift();
                i++;
            }
        }
        this._state.unshift(item);
        this._active = null;
        this._activeIndex = -1;
        this.afterChange(false)
        return this;
    }

    this.redo = function(){
        this._activeIndex--;
        this._active = this._state[this._activeIndex];
        this.afterChange('stateRedo')
        return this;
    }

    this.undo = function(){
        this._activeIndex++;
        this._active = this._state[this._activeIndex];
        this.afterChange('stateUndo')
        return this;
    }

    this.eventData = function(){
        return {
            hasPrev: this.hasPrev,
            hasNext: this.hasNext,
            active: this.active(),
            activeIndex: this.activeIndex(),
        }
    }
    this.afterChange = function(action){

        this.hasNext = true;
        this.hasPrev = true;
        if(this._activeIndex <= -1) {
             this.hasPrev = false;
        }
        if(this._activeIndex === this._state.length-1) {
             this.hasNext = false;
        }

        if(action){
            $(this).trigger(action, [this.eventData()])
        }
        if(action !== false){
           $(this).trigger('change', [this.eventData()])
        }

        return this;
    }

    this.reset = function(){
        this._state = this.options.state || [];
        this.afterChange('reset');
        return this;
    }
    this.clear = function(){
        this._state = [];
        this.afterChange('clear');
        return this;
    }
}


$(document).ready(function(){
    mw.liveEditState = new mw.State();
    $(mw.liveEditState).on('stateUndo stateRedo', function(e, data){
        $(data.active.target).html(data.active.value);
        mw.drag.load_new_modules()
    })
})