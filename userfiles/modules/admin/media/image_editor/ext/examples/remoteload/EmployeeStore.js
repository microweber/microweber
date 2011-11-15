/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ns('App');

App.EmployeeStore = function(config) {
	var config = config || {};
	config.fields = ['employeeId','firstName','lastName','title','department','telephone','office'];
	config.id = 'employeeId';
	config.root = 'employees'
	App.EmployeeStore.superclass.constructor.call(this, config);
};
Ext.extend(App.EmployeeStore, Ext.data.JsonStore);