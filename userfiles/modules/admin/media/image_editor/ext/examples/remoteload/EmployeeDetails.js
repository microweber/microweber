/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ns('App');

App.EmployeeDetails = Ext.extend(Ext.Panel, {
	startingText: 'Please select an employee.',
	initComponent: function() {
		this.tpl = Ext.XTemplate.from('employeeDetailTpl');
		this.html = this.startingText;
		App.EmployeeDetails.superclass.initComponent.call(this);
	},
	load: function(config) {
		var config = config || {};
		Ext.apply(config, {
			url: this.url,
			success: this.onLoad,
			failure: this.onFailure,
			scope: this
		});
		this.getEl().mask('Loading...');
		Ext.Ajax.request(config);
	},
	onLoad: function(response, opts) {
		var json = Ext.decode(response.responseText);
		this.tpl.overwrite(this.body, json);
		this.getEl().unmask();
	},
	onFailure: function() {
		this.getEl().unmask();
		Ext.Msg.alert('Fail', 'Request failed.');
	}
});
Ext.reg('employeedetails', App.EmployeeDetails);
