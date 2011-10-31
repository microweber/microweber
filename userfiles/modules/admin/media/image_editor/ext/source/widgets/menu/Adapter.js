/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.Adapter
 * @extends Ext.menu.BaseItem
 * A base utility class that adapts a non-menu component so that it can be wrapped by a menu item and added to a menu.
 * It provides basic rendering, activation management and enable/disable logic required to work in menus.
 * @constructor
 * Creates a new Adapter
 * @param {Ext.Component} component The component being adapted to render into a menu
 * @param {Object} config Configuration options
 */
Ext.menu.Adapter = function(component, config){
    Ext.menu.Adapter.superclass.constructor.call(this, config);
    this.component = component;
};
Ext.extend(Ext.menu.Adapter, Ext.menu.BaseItem, {
    // private
    canActivate : true,

    // private
    onRender : function(container, position){
        this.component.render(container);
        this.el = this.component.getEl();
    },

    // private
    activate : function(){
        if(this.disabled){
            return false;
        }
        this.component.focus();
        this.fireEvent("activate", this);
        return true;
    },

    // private
    deactivate : function(){
        this.fireEvent("deactivate", this);
    },

    // private
    disable : function(){
        this.component.disable();
        Ext.menu.Adapter.superclass.disable.call(this);
    },

    // private
    enable : function(){
        this.component.enable();
        Ext.menu.Adapter.superclass.enable.call(this);
    }
});