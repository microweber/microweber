Ext.namespace('Ext.ux');
var scriptRegister = [];
Ext.ux.OnDemandLoad = function(){

    loadComponent = function(component, callback){

        if( !scriptRegister[component] ) {
        	scriptRegister[component] = { loaded: false };
        }
        var fileType = component.substring(component.lastIndexOf('.'));
        var head = document.getElementsByTagName("head")[0];
        var done = false;
        if (fileType === ".js" ) {
        	if( !scriptRegister[component].loaded ) {
        		var fileRef = document.createElement('script');
        		fileRef.setAttribute("type", "text/javascript");
        		fileRef.setAttribute("src", component);
        		fileRef.onload = fileRef.onreadystatechange = function(){
        			if (!done) {
        				scriptRegister[component].loaded = true;
        				
        				done = true;
        				if(typeof callback == "function"){
        					callback();
        				};
        				head.removeChild(fileRef);
        			}
        		};
        	} else {
        		if(typeof callback == "function"){
					callback();
				};
        	}
        } else if (fileType === ".css" && !scriptRegister[component].loaded) {
            var fileRef = document.createElement("link");
            fileRef.setAttribute("type", "text/css");
            fileRef.setAttribute("rel", "stylesheet");
            fileRef.setAttribute("href", component);
            scriptRegister[component].loaded = true;
        }
        if (typeof fileRef != "undefined") {
            head.appendChild(fileRef);
        }
    };

    return {
        load: function(component, callback){
    		loadComponent(component, callback);
        }
    };
}();