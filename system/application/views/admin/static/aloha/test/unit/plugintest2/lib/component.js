define(
['plugintest1/componenttype'],
function( componenttype ) {
    "use strict";
 
    var component = componenttype.extend({
        doOther: function() {
            return 'didOther';
        }
    });
	
    return new component();
 
});