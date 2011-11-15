/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.DateMenu
 * @extends Ext.menu.Menu
 * A menu containing a {@link Ext.menu.DateItem} component (which provides a date picker).
 * @constructor
 * Creates a new DateMenu
 * @param {Object} config Configuration options
 */
Ext.menu.DateMenu = function(config){
    Ext.menu.DateMenu.superclass.constructor.call(this, config);
    this.plain = true;
    var di = new Ext.menu.DateItem(config);
    this.add(di);
    /**
     * The {@link Ext.DatePicker} instance for this DateMenu
     * @type DatePicker
     */
    this.picker = di.picker;
    /**
     * @event select
     * @param {DatePicker} picker
     * @param {Date} date
     */
    this.relayEvents(di, ["select"]);

    this.on('beforeshow', function(){
        if(this.picker){
            this.picker.hideMonthPicker(true);
        }
    }, this);
};
Ext.extend(Ext.menu.DateMenu, Ext.menu.Menu, {
    cls:'x-date-menu',

    // private
    beforeDestroy : function() {
        this.picker.destroy();
    }
});