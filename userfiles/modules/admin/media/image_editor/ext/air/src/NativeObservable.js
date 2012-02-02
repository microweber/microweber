/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.air.NativeObservable
 * @extends Ext.util.Observable
 * 
 * Adds ability for Ext Observable functionality to proxy events for native (AIR) object wrappers
 * 
 * @constructor
 */

Ext.air.NativeObservable = Ext.extend(Ext.util.Observable, {
	addListener : function(name){
		this.proxiedEvents = this.proxiedEvents || {};
		if(!this.proxiedEvents[name]){
			var instance = this;
			var f = function(){
				var args = Array.prototype.slice.call(arguments, 0);
				args.unshift(name);
				instance.fireEvent.apply(instance, args);
			};
			this.proxiedEvents[name] = f;
			this.getNative().addEventListener(name, f);
		}
		Ext.air.NativeObservable.superclass.addListener.apply(this, arguments);
	}
});

Ext.air.NativeObservable.prototype.on = Ext.air.NativeObservable.prototype.addListener;