/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.air.FileProvider
 * @extends Ext.state.Provider
 * 
 * An Ext state provider implementation for Adobe AIR that stores state in the application 
 * storage directory.
 * 
 * @constructor
 * @param {Object} config
 */
Ext.air.FileProvider = function(config){
    Ext.air.FileProvider.superclass.constructor.call(this);
	
	this.defaultState = {
		mainWindow : {
			width:780,
			height:580,
			x:10,
			y:10
		}
	};
	
    Ext.apply(this, config);
    this.state = this.readState();
	
	var provider = this;
	air.NativeApplication.nativeApplication.addEventListener('exiting', function(){
		provider.saveState();
	});
};

Ext.extend(Ext.air.FileProvider, Ext.state.Provider, {
	/**
	 * @cfg {String} file
	 * The file name to use for the state file in the application storage directory
	 */
	file: 'extstate.data',
	
	/**
	 * @cfg {Object} defaultState
	 * The default state if no state file can be found
	 */
	// private
    readState : function(){
		var stateFile = air.File.applicationStorageDirectory.resolvePath(this.file);
		if(!stateFile.exists){
			return this.defaultState || {};
		}
		
		var stream = new air.FileStream();
		stream.open(stateFile, air.FileMode.READ);
		
		var stateData = stream.readObject();
		stream.close();
		
		return stateData || this.defaultState || {};
    },

    // private
    saveState : function(name, value){
        var stateFile = air.File.applicationStorageDirectory.resolvePath(this.file);
		var stream = new air.FileStream();
		stream.open(stateFile, air.FileMode.WRITE);
		stream.writeObject(this.state);
		stream.close();
    }
});