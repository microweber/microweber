/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

// Asbtract base class for Connection classes
Ext.sql.Connection = function(config){
	Ext.apply(this, config);
	Ext.sql.Connection.superclass.constructor.call(this);

	this.addEvents({
		open : true,
		close: true
	});
};

Ext.extend(Ext.sql.Connection, Ext.util.Observable, {
	maxResults: 10000,
	openState : false,

    // abstract methods
    open : function(file){
	},

	close : function(){
	},

    exec : function(sql){
	},

	execBy : function(sql, args){
	},

	query : function(sql){
	},

	queryBy : function(sql, args){
	},

    // protected/inherited method
    isOpen : function(){
		return this.openState;
	},

	getTable : function(name, keyName){
		return new Ext.sql.Table(this, name, keyName);
	},

	createTable : function(o){
		var tableName = o.name;
		var keyName = o.key;
		var fs = o.fields;
		if(!Ext.isArray(fs)){ // Ext fields collection
			fs = fs.items;
		}
		var buf = [];
		for(var i = 0, len = fs.length; i < len; i++){
			var f = fs[i], s = f.name;
			switch(f.type){
	            case "int":
	            case "bool":
	            case "boolean":
	                s += ' INTEGER';
	                break;
	            case "float":
	                s += ' REAL';
	                break;
	            default:
	            	s += ' TEXT';
	        }
	        if(f.allowNull === false || f.name == keyName){
	        	s += ' NOT NULL';
	        }
	        if(f.name == keyName){
	        	s += ' PRIMARY KEY';
	        }
	        if(f.unique === true){
	        	s += ' UNIQUE';
	        }

	        buf[buf.length] = s;
	    }
	    var sql = ['CREATE TABLE IF NOT EXISTS ', tableName, ' (', buf.join(','), ')'].join('');
        this.exec(sql);
	}
});


Ext.sql.Connection.getInstance = function(db, config){
    if(Ext.isAir){ // air
        return new Ext.sql.AirConnection(config);
    } else { // gears
        return new Ext.sql.GearsConnection(config);
    }
};