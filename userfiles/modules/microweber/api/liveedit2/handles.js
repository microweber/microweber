export const Handles = function (handles) {

    this.handles = handles;
    this.dragging = false;
    var scope = this;

    this.get = function (handle) {
        return this.handles[handle];
    }

    this.set = function (handle, target){
        this.get(handle).set(target)
    }

    this.hideAllExceptCurrent = function (e) {
        var target = e.target ? e.target : e;
        this.each(function (h){
            var el = h.wrapper.get(0);
            if(target !== el && !el.contains(target)) {
                h.hide()
            }
        });
    }

    this.hide = function(handle) {
        if(handle && this.handles[handle]) {
            this.handles[handle].hide();
        } else {
            this.each(function (h){
                h.hide()
            });
        }

    };
    this.show = function(handle) {
        if (handle && this.handles[handle]) {
            this.handles[handle].show();
        } else {
            this.each(function (handle){
                handle.show();
            });
        }
    };

    this.each = function (c) {
        if(!c) return;
        var i;
        for (i in this.handles) {
            c.call(scope, this.handles[i]);
        }
    };

    this.init = function (){
        this.each(function (handle){
            handle.draggable.on('dragStart', function (){
                scope.dragging = true;
                //handle.hide()
            })
            handle.draggable.on('dragEnd', function (){
                scope.dragging = false;
                handle.show()
            })
        })
    }

    this.init();
};
