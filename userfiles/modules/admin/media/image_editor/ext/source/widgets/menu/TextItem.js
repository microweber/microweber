/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.TextItem
 * @extends Ext.menu.BaseItem
 * Adds a static text string to a menu, usually used as either a heading or group separator.
 * @constructor
 * Creates a new TextItem
 * @param {Object/String} config If config is a string, it is used as the text to display, otherwise it
 * is applied as a config object (and should contain a <tt>text</tt> property).
 */
Ext.menu.TextItem = function(cfg){
    if(typeof cfg == 'string'){
        cfg = {text: cfg}
    }
    Ext.menu.TextItem.superclass.constructor.call(this, cfg);
};

Ext.extend(Ext.menu.TextItem, Ext.menu.BaseItem, {
    /**
     * @cfg {String} text The text to display for this item (defaults to '')
     */
    /**
     * @cfg {Boolean} hideOnClick True to hide the containing menu after this item is clicked (defaults to false)
     */
    hideOnClick : false,
    /**
     * @cfg {String} itemCls The default CSS class to use for text items (defaults to "x-menu-text")
     */
    itemCls : "x-menu-text",

    // private
    onRender : function(){
        var s = document.createElement("span");
        s.className = this.itemCls;
        s.innerHTML = this.text;
        this.el = s;
        Ext.menu.TextItem.superclass.onRender.apply(this, arguments);
    }
});