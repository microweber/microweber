export const Handles = function (handles) {

    this.handles = handles;
    var scope = this;

    this.get = function (handle) {
        return this.handles[handle];
    }

    this.set = function (handle, target){
        this.get(handle).set(target)
    }

    this.hide = function(handle) {
        if(handle && this.handles[handle]) {
            this.handles[handle].hide();
        } else {
            this.each(function (handle){
                handle.hide()
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
};
