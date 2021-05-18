export const Handles = function (handles) {

    this.handles = handles;
    var scope = this;

    this.hide = function(handle) {
        if(handle && this.handles[handle]) {
            this.handles[handle].hide();
        }
        this.each(function (handle){
            handle.hide()
        });

    };
    this.show = function(handle) {
        if(handle && this.handles[handle]) {
            this.handles[handle].show();
        }
        this.each(function (handle){
            handle.show();
        });

    };

    this.each = function (c) {
        if(!c) return;
        var i;
        for (i in this.handles) {
            c.call(scope, this.handles[i])
        }
    };

};
