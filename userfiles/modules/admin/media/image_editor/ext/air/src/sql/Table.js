/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.sql.Table = function(conn, name, keyName){
	this.conn = conn;
	this.name = name;
	this.keyName = keyName;
};

Ext.sql.Table.prototype = {
	update : function(o){
		var clause = this.keyName + " = ?";
		return this.updateBy(o, clause, [o[this.keyName]]);
	},

	updateBy : function(o, clause, args){
		var sql = "UPDATE " + this.name + " set ";
		var fs = [], a = [];
		for(var key in o){
			if(o.hasOwnProperty(key)){
				fs[fs.length] = key + ' = ?';
				a[a.length] = o[key];
			}
		}
		for(var key in args){
			if(args.hasOwnProperty(key)){
				a[a.length] = args[key];
			}
		}
		sql = [sql, fs.join(','), ' WHERE ', clause].join('');
		return this.conn.execBy(sql, a);
	},

	insert : function(o){
		var sql = "INSERT into " + this.name + " ";
		var fs = [], vs = [], a = [];
		for(var key in o){
			if(o.hasOwnProperty(key)){
				fs[fs.length] = key;
				vs[vs.length] = '?';
				a[a.length] = o[key];
			}
		}
		sql = [sql, '(', fs.join(','), ') VALUES (', vs.join(','), ')'].join('');
        return this.conn.execBy(sql, a);
    },

	lookup : function(id){
		return this.selectBy('where ' + this.keyName + " = ?", [id])[0] || null;
	},

	exists : function(id){
		return !!this.lookup(id);
	},

	save : function(o){
		if(this.exists(o[this.keyName])){
            this.update(o);
        }else{
            this.insert(o);
        }
	},

	select : function(clause){
		return this.selectBy(clause, null);
	},

	selectBy : function(clause, args){
		var sql = "select * from " + this.name;
		if(clause){
			sql += ' ' + clause;
		}
		args = args || {};
		return this.conn.queryBy(sql, args);
	},

	remove : function(clause){
		this.deleteBy(clause, null);
	},

	removeBy : function(clause, args){
		var sql = "delete from " + this.name;
		if(clause){
			sql += ' where ' + clause;
		}
		args = args || {};
		this.conn.execBy(sql, args);
	}
};