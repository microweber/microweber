/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.ColorItem
 * @extends Ext.menu.Adapter
 * A menu item that wraps the {@link Ext.ColorPalette} component.
 * @constructor
 * Creates a new ColorItem
 * @param {Object} config Configuration options
 */
Ext.menu.ColorItem = function(config){
    Ext.menu.ColorItem.superclass.constructor.call(this, new Ext.ColorPalette(config), config);
    /** The Ext.ColorPalette object @type Ext.ColorPalette */
    this.palette = this.component;
    this.relayEvents(this.palette, ["select"]);
    if(this.selectHandler){
        this.on('select', this.selectHandler, this.scope);
    }
};
Ext.extend(Ext.menu.ColorItem, Ext.menu.Adapter);