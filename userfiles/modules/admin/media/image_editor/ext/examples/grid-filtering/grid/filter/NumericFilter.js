/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.filter.NumericFilter = Ext.extend(Ext.grid.filter.Filter, {
	init: function() {
		this.menu = new Ext.menu.RangeMenu();
		
		this.menu.on("update", this.fireUpdate, this);
	},
	
	fireUpdate: function() {
		this.setActive(this.isActivatable());
		this.fireEvent("update", this);
	},
	
	isActivatable: function() {
		var value = this.menu.getValue();
		return value.eq !== undefined || value.gt !== undefined || value.lt !== undefined;
	},
	
	setValue: function(value) {
		this.menu.setValue(value);
	},
	
	getValue: function() {
		return this.menu.getValue();
	},
	
	serialize: function() {
		var args = [];
		var values = this.menu.getValue();
		for(var key in values) {
			args.push({type: 'numeric', comparison: key, value: values[key]});
    }
		this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record) {
		var val = record.get(this.dataIndex),
			values = this.menu.getValue();
			
		if(values.eq != undefined && val != values.eq) {
			return false;
    }
		if(values.lt != undefined && val >= values.lt) {
			return false;
    }
		if(values.gt != undefined && val <= values.gt) {
			return false;
    }
		return true;
	}
});