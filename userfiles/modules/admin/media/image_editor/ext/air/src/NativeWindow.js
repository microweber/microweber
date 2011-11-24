/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.air.NativeWindow
 * @extends Ext.air.NativeObservable
 * 
 * Wraps the AIR NativeWindow class to give an Ext friendly API. <br/><br/>This class also adds 
 * automatic state management (position and size) for the window (by id) and it can be used 
 * for easily creating "minimize to system tray" for the main window in your application.<br/><br/>
 * 
 * Note: Many of the config options for this class can only be applied to NEW windows. Passing 
 * in an existing instance of a window along with those config options will have no effect.
 * 
 * @constructor
 * @param {Object} config
 */
Ext.air.NativeWindow = function(config){
	Ext.apply(this, config);
	
	/**
	 * @type String
	 */
	this.id = this.id || Ext.uniqueId();
	
	this.addEvents(
		/**
		 * @event close
		 * @param {Object} e The air event object
		 */
		'close', 
		/**
		 * @event closing
		 * @param {Object} e The air event object
		 */
		'closing',
		/**
		 * @event move
		 * @param {Object} e The air event object
		 */
		'move',
		/**
		 * @event moving
		 * @param {Object} e The air event object
		 */
		'moving',
		/**
		 * @event resize
		 * @param {Object} e The air event object
		 */
		'resize',
		/**
		 * @event resizing
		 * @param {Object} e The air event object
		 */
		'resizing',
		/**
		 * @event displayStateChange
		 * @param {Object} e The air event object
		 */
		'displayStateChange',
		/**
		 * @event displayStateChanging
		 * @param {Object} e The air event object
		 */
		'displayStateChanging'
	);
	
	Ext.air.NativeWindow.superclass.constructor.call(this);
	
	if(!this.instance){
		var options = new air.NativeWindowInitOptions();
		options.systemChrome = this.chrome;
		options.type = this.type;
		options.resizable = this.resizable;
		options.minimizable = this.minimizable;
		options.maximizable = this.maximizable;
		options.transparent = this.transparent;
		
		this.loader = window.runtime.flash.html.HTMLLoader.createRootWindow(false, options, false);
		this.loader.load(new air.URLRequest(this.file));
	
		this.instance = this.loader.window.nativeWindow;
	}else{
		this.loader = this.instance.stage.getChildAt(0);
	}
	
	var provider = Ext.state.Manager;
	var b = air.Screen.mainScreen.visibleBounds;
	
	var state = provider.get(this.id) || {};
	provider.set(this.id, state);
		
	var win = this.instance;
	
	var width = Math.max(state.width || this.width, 100);
	var height = Math.max(state.height || this.height, 100);
	
	var centerX = b.x + ((b.width/2)-(width/2));
	var centerY = b.y + ((b.height/2)-(height/2));
	
	var x = !Ext.isEmpty(state.x, false) ? state.x : (!Ext.isEmpty(this.x, false) ? this.x : centerX);
	var y = !Ext.isEmpty(state.y, false) ? state.y : (!Ext.isEmpty(this.y, false) ? this.y : centerY);
	
	win.width = width;
	win.height = height;
	win.x = x;
	win.y = y;
	
	win.addEventListener('move', function(){
		if(win.displayState != air.NativeWindowDisplayState.MINIMIZED && win.width > 100 && win.height > 100) {
			state.x = win.x;
			state.y = win.y;
		}
	});	
	win.addEventListener('resize', function(){
		if (win.displayState != air.NativeWindowDisplayState.MINIMIZED && win.width > 100 && win.height > 100) {
			state.width = win.width;
			state.height = win.height;
		}
	});
	
	Ext.air.NativeWindowManager.register(this);
	this.on('close', this.unregister, this);
	
	/**
	 * @cfg {Boolean} minimizeToTray 
	 * True to enable minimizing to the system tray. Note: this should only be applied
	 * to the primary window in your application. A trayIcon is required.
	 */
	if(this.minimizeToTray){
		this.initMinimizeToTray(this.trayIcon, this.trayMenu);
	}
	
};

Ext.extend(Ext.air.NativeWindow, Ext.air.NativeObservable, {
	
	/**
	 * @cfg {air.NativeWindow} instance 
	 * The native window instance to wrap. If undefined, a new window will be created.
	 */
	
	/**
	 * @cfg {String} trayIcon 
	 * The icon to display when minimized in the system tray
	 */
	/**
	 * @cfg {NativeMenu} trayMenu 
	 * Menu to display when the tray icon is right clicked
	 */
	/**
	 * @cfg {String} trayTip 
	 * Tooltip for the tray icon
	 */	
	
	/**
	 * @cfg {String} chrome 
	 * The native window chrome (defaults to 'standard', can also be 'none').
	 */
	chrome: 'standard', // can also be none
	/**
	 * @cfg {String} type 
	 * The native window type - normal, utility or lightweight. (defaults to normal)
	 */
	type: 'normal',	// can be normal, utility or lightweight
	/**
	 * @cfg {Number} width
	 */
	width:600,
	/**
	 * @cfg {Number} height 
	 */
	height:400,
	/**
	 * @cfg {Boolean} resizable 
	 */
	resizable: true,
	/**
	 * @cfg {Boolean} minimizable 
	 */
	minimizable: true,
	/**
	 * @cfg {Boolean} maximizable 
	 */
	maximizable: true,
	/**
	 * @cfg {Boolean} transparent
	 */
	transparent: false,
	
	/**
	 * Returns the air.NativeWindow instance
	 * @return air.NativeWindow
	 */
	getNative : function(){
		return this.instance;
	},
	
	/**
	 * Returns the x/y coordinates for centering the windw on the screen
	 * @return {x: Number, y: Number}
	 */
	getCenterXY : function(){
		var b = air.Screen.mainScreen.visibleBounds;
		return {
			x: b.x + ((b.width/2)-(this.width/2)),
			y: b.y + ((b.height/2)-(this.height/2))
		};
	},
	
	/**
	 * Shows the window
	 */
	show :function(){
		if(this.trayed){
			Ext.air.SystemTray.hideIcon();
			this.trayed = false;
		}
		this.instance.visible = true;
	},
	
	/**
	 * Shows and activates the window
	 */
	activate : function(){
		this.show();
		this.instance.activate();
	},
	
	/**
	 * Hides the window
	 */
	hide :function(){
		this.instance.visible = false;
	},
	
	/**
	 * Closes the window
	 */
	close : function(){
		this.instance.close();	
	},
	
	/**
	 * Returns true if this window is minimized
	 * @return Boolean
	 */
	isMinimized :function(){
		return this.instance.displayState == air.NativeWindowDisplayState.MINIMIZED;
	},
	
	/**
	 * Returns true if this window is maximized
	 * @return Boolean
	 */
	isMaximized :function(){
		return this.instance.displayState == air.NativeWindowDisplayState.MAXIMIZED;
	},
	
	/**
	 * Moves the window to the passed xy and y coordinates
	 * @param {Number} x
	 * @param {Number} y
	 */
	moveTo : function(x, y){
		this.x = this.instance.x = x;
		this.y = this.instance.y = y;	
	},
	
	/**
	 * @param {Number} width
	 * @param {Number} height
	 */
	resize : function(width, height){
		this.width = this.instance.width = width;
		this.height = this.instance.height = height;	
	},
	
	unregister : function(){
		Ext.air.NativeWindowManager.unregister(this);
	},
	
	initMinimizeToTray : function(icon, menu){
		var tray = Ext.air.SystemTray;
		
		tray.setIcon(icon, this.trayTip);
		this.on('displayStateChanging', function(e){
			if(e.afterDisplayState == 'minimized'){
				e.preventDefault();
				this.hide();
				tray.showIcon();
				this.trayed = true;
			}
		}, this);
		
		tray.on('click', function(){
			this.activate();
		}, this);
		
		if(menu){
			tray.setMenu(menu);
		}
	}
});

/**
 * Returns the first opened window in your application
 * @return air.NativeWindow
 * @static
 */
Ext.air.NativeWindow.getRootWindow = function(){
	return air.NativeApplication.nativeApplication.openedWindows[0];
};

/**
 * Returns the javascript "window" object of the first opened window in your application
 * @return Window
 * @static
 */
Ext.air.NativeWindow.getRootHtmlWindow = function(){
	return Ext.air.NativeWindow.getRootWindow().stage.getChildAt(0).window;
};

/**
 * @class Ext.air.NativeWindowGroup
 * 
 * A collection of NativeWindows.
 */
Ext.air.NativeWindowGroup = function(){
    var list = {};

    return {
		/**
		 * @param {Object} win
		 */
        register : function(win){
            list[win.id] = win;
        },

        /**
		 * @param {Object} win
		 */
        unregister : function(win){
            delete list[win.id];
        },

        /**
		 * @param {String} id
		 */
        get : function(id){
            return list[id];
        },

        /**
		 * Closes all windows
		 */
        closeAll : function(){
            for(var id in list){
                if(list.hasOwnProperty(id)){
                    list[id].close();
                }
            }
        },

        /**
         * Executes the specified function once for every window in the group, passing each
         * window as the only parameter. Returning false from the function will stop the iteration.
         * @param {Function} fn The function to execute for each item
         * @param {Object} scope (optional) The scope in which to execute the function
         */
        each : function(fn, scope){
            for(var id in list){
                if(list.hasOwnProperty(id)){
                    if(fn.call(scope || list[id], list[id]) === false){
                        return;
                    }
                }
            }
        }
    };
};

/**
 * @class Ext.air.NativeWindowManager
 * @extends Ext.air.NativeWindowGroup
 * 
 * Collection of all NativeWindows created.
 * 
 * @singleton
 */
Ext.air.NativeWindowManager = new Ext.air.NativeWindowGroup();