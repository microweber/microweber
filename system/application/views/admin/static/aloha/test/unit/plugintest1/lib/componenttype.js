define(
[], // no dependency
function() {
    "use strict";
 
    var componenttype = Class.extend({
        doSome: function() {
            return 'didSome';
        }
    });
 
    return componenttype;
 
});