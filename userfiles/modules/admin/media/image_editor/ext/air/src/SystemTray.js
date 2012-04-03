/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.air.SystemTray = function(){
	var app = air.NativeApplication.nativeApplication;
	var icon, isWindows = false, bitmaps;
	
	// windows
	if(air.NativeApplication.supportsSystemTrayIcon) {
        icon = app.icon;
		isWindows = true;
    }
    
	// mac
    if(air.NativeApplication.supportsDockIcon) {
		icon = app.icon;
    }
	
	return {
		
		setIcon : function(icon, tooltip, initWithIcon){
			if(!icon){ // not supported OS
				return;
			}
			var loader = new air.Loader();
			loader.contentLoaderInfo.addEventListener(air.Event.COMPLETE, function(e){
				bitmaps = new runtime.Array(e.target.content.bitmapData);
				if (initWithIcon) {
					icon.bitmaps = bitmaps;
				}
			});
        	loader.load(new air.URLRequest(icon));
			if(tooltip && air.NativeApplication.supportsSystemTrayIcon) {
				app.icon.tooltip = tooltip;
			}
		},
		
		bounce : function(priority){
			icon.bounce(priority);
		},
		
		on : function(eventName, fn, scope){
			icon.addEventListener(eventName, function(){
				fn.apply(scope || this, arguments);
			});
		},
		
		hideIcon : function(){
			if(!icon){ // not supported OS
				return;
			}
			icon.bitmaps = [];
		},
		
		showIcon : function(){
			if(!icon){ // not supported OS
				return;
			}
			icon.bitmaps = bitmaps;
		},
		
		setMenu: function(actions, _parentMenu){
			if(!icon){ // not supported OS
				return;
			}
			var menu = new air.NativeMenu();
			
			for (var i = 0, len = actions.length; i < len; i++) {
				var a = actions[i];
				if(a == '-'){
					menu.addItem(new air.NativeMenuItem("", true));
				}else{
					var item = menu.addItem(Ext.air.MenuItem(a));
					if(a.menu || (a.initialConfig && a.initialConfig.menu)){
						item.submenu = Ext.air.SystemTray.setMenu(a.menu || a.initialConfig.menu, menu);
					}
				}
				
				if(!_parentMenu){
					icon.menu = menu;
				}
			}
			
			return menu;
		}
	};	
}();