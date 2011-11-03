define(
['plugintest1/componenttype'],
function( componenttype ) {
    
 
    var component = componenttype.extend({
        doOther: function() {
            return 'didOther';
        }
    });
	
    return new component();
 
});