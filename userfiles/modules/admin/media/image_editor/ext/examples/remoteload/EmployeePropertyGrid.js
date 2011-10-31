/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ns('App');

App.EmployeePropertyGrid = Ext.extend(Ext.grid.PropertyGrid, {
	load: function(config) {
		Ext.apply(config, {
			url: this.url,
			success: this.onLoad,
			scope: this
		});		
		Ext.Ajax.request(config);
	},
	onLoad: function(response, opts) {
		var json = Ext.decode(response.responseText);
		this.setSource(json);		
	}
});
Ext.reg('employeepropertygrid', App.EmployeePropertyGrid);
