define(
[], // no dependency
function() {
    
 
    var componenttype = Class.extend({
        doSome: function() {
            return 'didSome';
        }
    });
 
    return componenttype;
 
});