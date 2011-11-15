/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
	new App.EmployeeStore({
		storeId: 'employeeStore',
		url: 'loadStore.php'
	});
	Ext.ux.ComponentLoader.load({
		url: 'sampleApp.php',
		params: {
			testing: 'Testing params'
		}
	});
});
