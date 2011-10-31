/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.data.SimpleStore
 * @extends Ext.data.Store
 * Small helper class to make creating Stores from Array data easier.
 * @cfg {Number} id The array index of the record id. Leave blank to auto generate ids.
 * @cfg {Array} fields An array of field definition objects, or field name strings.
 * @cfg {Array} data The multi-dimensional array of data
 * @constructor
 * @param {Object} config
 */
Ext.data.SimpleStore = function(config){
    Ext.data.SimpleStore.superclass.constructor.call(this, Ext.apply(config, {
        reader: new Ext.data.ArrayReader({
                id: config.id
            },
            Ext.data.Record.create(config.fields)
        )
    }));
};
Ext.extend(Ext.data.SimpleStore, Ext.data.Store, {
    loadData : function(data, append){
        if(this.expandData === true){
            var r = [];
            for(var i = 0, len = data.length; i < len; i++){
                r[r.length] = [data[i]];
            }
            data = r;
        }
        Ext.data.SimpleStore.superclass.loadData.call(this, data, append);
    }
});