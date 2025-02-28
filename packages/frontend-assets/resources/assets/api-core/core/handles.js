export const Handles = function (handles) {

    this.handles = handles;
    this.dragging = false;
    var scope = this;

    this.get = function (handle) {
        return this.handles[handle];
    }


    this.set = function (handle, target, forced = false, event = null){


         this.get(handle).set(target, forced, event);
    }

    this.hide = function(handle) {
        if(handle && this.handles[handle]) {
            this.handles[handle].hide();
        } else {
            this.each(function (name, h){
                h.hide()
            });
        }
    };

    this.hideAllBut = function(handle) {
        this.each(function (name, h){
            if(name !== handle) {
                h.hide()
            }
        });
    };

    this.show = function(handle) {
        if (handle && this.handles[handle]) {
            this.handles[handle].show();
        } else {
            this.each(function (name, handle){
                handle.show();
            });
        }
    };

    this.each = function (c) {
        if(!c) return;
        var i;
        for (i in this.handles) {
            c.call(scope, i, this.handles[i]);
        }
    };


    this.reposition = function(except = []) {
        this.each((i, handle) => {
            if(except.indexOf(handle) === -1) {
                if(handle.isVisible()) {
                    handle.reposition()
                }
            }
        })
    }

    this.targetIsSelected = function(target, except) {

        if(!target) {
            return;
        }
        target = target.target || target;
        var i;
        for (i in this.handles) {
            if(except && except === this.handles[i]) {
                continue;
            }
            const tg = this.handles[i].getTarget();

             if( tg && tg === target) {
                return true
             }
        }
        return false;
    }

    this.targetIsSelectedAndHandleIsNotHidden = function(target, except) {

        if(!target) {
            return;
        }
        target = target.target || target;
        var i;
        for (i in this.handles) {
            if(except && except === this.handles[i]) {
                continue;
            }
            const tg = this.handles[i].getTarget();

             if( tg && tg === target) {
                return this.handles[i].isVisible();
             }
        }
        return false;
    }
    this.targetIsOrInsideHandle = function(target, except) {
        if (!target) {
            return;
        }
        target = target.target || target;

        if(!target.nodeType || target.nodeType !== 1) { return }


        var i;
        for (i in this.handles) {
            if(except && except === this.handles[i]){
                continue;
            }
            if(this.handles[i].wrapper.get(0) === target || this.handles[i].wrapper.get(0).contains(target)) {
                return true;
            }
        }
        return false;
    }

    this.init = function (){
        this.each(function (name, handle){
            handle.draggable.on('dragStart', ()=>{
                scope.dragging = true;
                scope.hideAllBut(name)

            })
            handle.draggable.on('dragEnd', function (){
                scope.dragging = false;
                handle.show();


                mw.top().app.dispatch('dragEnd', handle.getTarget());
                mw.top().app.registerChange(handle.getTarget());
            });
        })
    }

    this.init();
};
