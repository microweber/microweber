/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ns('App');

App.EmployeeGrid = Ext.extend(Ext.grid.GridPanel, {
	initComponent: function() {
		this.columns = [
			{dataIndex: 'lastName', header: 'Name', renderer: this.renderName},
			{dataIndex: 'department', header: 'Department'},
			{dataIndex: 'title', header: 'Title'},
			{dataIndex: 'telephone', header: 'Telephone'},
			{dataIndex: 'office', header: 'Office'}
		];
		this.viewConfig = {
			forceFit: true
		};
		App.EmployeeGrid.superclass.initComponent.call(this);
		this.getSelectionModel().on('rowselect', this.onRowSelect, this, {buffer: 300});
		this.store.load();
	},
	renderName: function(val, md, record) {
		return String.format('{0}, {1}', val, record.get('firstName'));
	},
	onRowSelect: function(sm, idx, r) {
		Ext.getCmp('employeeDetails').load(r.id);
	}
});
Ext.reg('employeegrid', App.EmployeeGrid);
