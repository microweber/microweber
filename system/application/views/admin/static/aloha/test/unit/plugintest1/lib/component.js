define(
['./componenttype'], // dependency in the same path
function( componenttype ) {
    "use strict";
 
    var component = componenttype.extend({
        doOther: function() {
            return 'didOther';
        }
    });
    return new component();
 
});