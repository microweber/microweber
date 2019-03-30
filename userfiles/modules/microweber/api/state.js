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
        this._state = state;
        return this;
    };


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

    this.record = function(item){
        if(this._activeIndex>-1) {
            var i = 0;
            while ( i <  this._activeIndex) {
                this._state.shift();
                i++;
            }
        }

        this._state.unshift(item);
        this._active = null;
        this._activeIndex = -1;
        this.afterChange(false);
        $(this).trigger('stateRecord', [this.eventData()]);
        return this;
    };

    this.redo = function(){
        this._activeIndex--;
        this._active = this._state[this._activeIndex];
        this.afterChange('stateRedo');
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
        return this;
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

            $(this).trigger(action, [this.eventData()]);
        }
        if(action !== false){
           $(this).trigger('change', [this.eventData()]);
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

(function(){
    mw.liveEditState = new mw.State();
    mw.liveEditState.record({
         value: null,
         $initial: true
    });
    mw.$liveEditState = $(mw.liveEditState);

    var ui = $('<div class="mw-ui-btn-nav"></div>'),
        undo = mwd.createElement('button'),
        redo = mwd.createElement('button');
    undo.className = 'mw-ui-btn mw-ui-btn-medium';
    undo.innerHTML = '<span class="mw-icon-reply"></span>';
    redo.className = 'mw-ui-btn mw-ui-btn-medium';
    redo.innerHTML = '<span class="mw-icon-forward"></span>';

    undo.onclick = function(){
        mw.liveEditState.undo();
    };
    redo.onclick = function(){
        mw.liveEditState.redo();
    };

    ui.append(undo);
    ui.append(redo);

    $(document).ready(function(){
        var idata = mw.liveEditState.eventData();
        undo.disabled = !idata.hasNext;
        redo.disabled = !idata.hasPrev;

        mw.$liveEditState.on('stateRecord', function(e, data){
            undo.disabled = !data.hasNext;
            redo.disabled = !data.hasPrev;
        });
        mw.$liveEditState.on('stateUndo stateRedo', function(e, data){
            if(!data.active || !data.active.target) {
                undo.disabled = !data.hasNext;
                redo.disabled = !data.hasPrev;
                return;
            }
            if(document.body.contains(data.active.target)) {
                $(data.active.target).html(data.active.value);
            } else{
                if(data.active.target.id) {
                    $(document.getElementById(data.active.target.id)).html(data.active.value);
                }
            }
            if(data.active.prev) {
                $(data.active.prev).html(data.active.prevValue);
            }
            mw.drag.load_new_modules();
            undo.disabled = !data.hasNext;
            redo.disabled = !data.hasPrev;
        });

        $('.wysiwyg-cell-undo-redo').empty().append(ui);
    });

})();


