/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.DateItem
 * @extends Ext.menu.Adapter
 * A menu item that wraps the {@link Ext.DatePicker} component.
 * @constructor
 * Creates a new DateItem
 * @param {Object} config Configuration options
 */
Ext.menu.DateItem = function(config){
    Ext.menu.DateItem.superclass.constructor.call(this, new Ext.DatePicker(config), config);
    /** The Ext.DatePicker object @type Ext.DatePicker */
    this.picker = this.component;
    this.addEvents('select');
    
    this.picker.on("render", function(picker){
        picker.getEl().swallowEvent("click");
        picker.container.addClass("x-menu-date-item");
    });

    this.picker.on("select", this.onSelect, this);
};

Ext.extend(Ext.menu.DateItem, Ext.menu.Adapter, {
    // private
    onSelect : function(picker, date){
        this.fireEvent("select", this, date, picker);
        Ext.menu.DateItem.superclass.handleClick.call(this);
    }
});