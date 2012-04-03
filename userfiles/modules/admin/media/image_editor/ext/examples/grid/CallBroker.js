/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.CallBroker = function(config){
    if(!config.reader){
        this.reader = new Ext.data.JsonReader({}, [
            'id', 'type', 'call', 'args'
        ]);
    }
    Ext.CallBroker.superclass.constructor.call(this, config);
};

Ext.extend(Ext.CallBroker, Ext.data.Store, {
    loadRecords : function(o, options, success){
        Ext.CallBroker.superclass.loadRecords.apply(this, arguments);
        if(o && success){
            this.data.each(this.delegateCall, this);
        }
    },

    delegateCall : function(c){
        var o = this[c.type](c.data);
        o[c.call][c.args instanceof Array ? 'apply' : 'call'](o, c.args);
    },

    store : function(c){
        return Ext.StoreMgr.lookup(c.id);
    },

    component : function(c){
        return Ext.getCmp(c.id);
    },

    element : function(c){
        return Ext.get(c.id);
    },

    object : function(c){
        return new Function('return '+c.id)();
    }
});