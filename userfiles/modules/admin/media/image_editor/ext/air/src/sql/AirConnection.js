/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

 Ext.sql.AirConnection = Ext.extend(Ext.sql.Connection, {
	// abstract methods
    open : function(db){
        this.conn = new air.SQLConnection();
		var file = air.File.applicationDirectory.resolvePath(db);
		this.conn.open(file);
        this.openState = true;
		this.fireEvent('open', this);
    },

	close : function(){
        this.conn.close();
        this.fireEvent('close', this);
    },

	createStatement : function(type){
		var stmt = new air.SQLStatement();
		stmt.sqlConnection = this.conn;
		return stmt;
	},

    exec : function(sql){
        var stmt = this.createStatement('exec');
		stmt.text = sql;
		stmt.execute();
    },

	execBy : function(sql, args){
		var stmt = this.createStatement('exec');
		stmt.text = sql;
		this.addParams(stmt, args);
		stmt.execute();
	},

	query : function(sql){
		var stmt = this.createStatement('query');
		stmt.text = sql;
		stmt.execute(this.maxResults);
		return this.readResults(stmt.getResult());
	},

	queryBy : function(sql, args){
		var stmt = this.createStatement('query');
		stmt.text = sql;
		this.addParams(stmt, args);
		stmt.execute(this.maxResults);
		return this.readResults(stmt.getResult());
	},

    addParams : function(stmt, args){
		if(!args){ return; }
		for(var key in args){
			if(args.hasOwnProperty(key)){
				if(!isNaN(key)){
					var v = args[key];
					if(Ext.isDate(v)){
						v = v.format(Ext.sql.Proxy.DATE_FORMAT);
					}
					stmt.parameters[parseInt(key)] = v;
				}else{
					stmt.parameters[':' + key] = args[key];
				}
			}
		}
		return stmt;
	},

    readResults : function(rs){
        var r = [];
        if(rs && rs.data){
		    var len = rs.data.length;
	        for(var i = 0; i < len; i++) {
	            r[r.length] = rs.data[i];
	        }
        }
        return r;
    }
});