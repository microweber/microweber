/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.data.MemoryProxy
 * @extends Ext.data.DataProxy
 * An implementation of Ext.data.DataProxy that simply passes the data specified in its constructor
 * to the Reader when its load method is called.
 * @constructor
 * @param {Object} data The data object which the Reader uses to construct a block of Ext.data.Records.
 */
Ext.data.MemoryProxy = function(data){
    Ext.data.MemoryProxy.superclass.constructor.call(this);
    this.data = data;
};

Ext.extend(Ext.data.MemoryProxy, Ext.data.DataProxy, {
    /**
     * @event loadexception
     * Fires if an exception occurs in the Proxy during data loading. Note that this event is also relayed 
     * through {@link Ext.data.Store}, so you can listen for it directly on any Store instance.
     * @param {Object} this
     * @param {Object} arg The callback's arg object passed to the {@link #load} function
     * @param {Object} null This parameter does not apply and will always be null for MemoryProxy
     * @param {Error} e The JavaScript Error object caught if the configured Reader could not read the data
     */
    
    /**
     * Load data from the requested source (in this case an in-memory
     * data object passed to the constructor), read the data object into
     * a block of Ext.data.Records using the passed Ext.data.DataReader implementation, and
     * process that block using the passed callback.
     * @param {Object} params This parameter is not used by the MemoryProxy class.
     * @param {Ext.data.DataReader) reader The Reader object which converts the data
     * object into a block of Ext.data.Records.
     * @param {Function} callback The function into which to pass the block of Ext.data.records.
     * The function must be passed <ul>
     * <li>The Record block object</li>
     * <li>The "arg" argument from the load function</li>
     * <li>A boolean success indicator</li>
     * </ul>
     * @param {Object} scope The scope in which to call the callback
     * @param {Object} arg An optional argument which is passed to the callback as its second parameter.
     */
    load : function(params, reader, callback, scope, arg){
        params = params || {};
        var result;
        try {
            result = reader.readRecords(this.data);
        }catch(e){
            this.fireEvent("loadexception", this, arg, null, e);
            callback.call(scope, null, arg, false);
            return;
        }
        callback.call(scope, result, arg, true);
    },
    
    // private
    update : function(params, records){
        
    }
});