/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.CheckItem
 * @extends Ext.menu.Item
 * Adds a menu item that contains a checkbox by default, but can also be part of a radio group.
 * @constructor
 * Creates a new CheckItem
 * @param {Object} config Configuration options
 */
Ext.menu.CheckItem = function(config){
    Ext.menu.CheckItem.superclass.constructor.call(this, config);
    this.addEvents(
        /**
         * @event beforecheckchange
         * Fires before the checked value is set, providing an opportunity to cancel if needed
         * @param {Ext.menu.CheckItem} this
         * @param {Boolean} checked The new checked value that will be set
         */
        "beforecheckchange" ,
        /**
         * @event checkchange
         * Fires after the checked value has been set
         * @param {Ext.menu.CheckItem} this
         * @param {Boolean} checked The checked value that was set
         */
        "checkchange"
    );
    /**
     * A function that handles the checkchange event.  The function is undefined by default, but if an implementation
     * is provided, it will be called automatically when the checkchange event fires.
     * @param {Ext.menu.CheckItem} this
     * @param {Boolean} checked The checked value that was set
     * @method checkHandler
     */
    if(this.checkHandler){
        this.on('checkchange', this.checkHandler, this.scope);
    }
    Ext.menu.MenuMgr.registerCheckable(this);
};
Ext.extend(Ext.menu.CheckItem, Ext.menu.Item, {
    /**
     * @cfg {String} group
     * All check items with the same group name will automatically be grouped into a single-select
     * radio button group (defaults to '')
     */
    /**
     * @cfg {String} itemCls The default CSS class to use for check items (defaults to "x-menu-item x-menu-check-item")
     */
    itemCls : "x-menu-item x-menu-check-item",
    /**
     * @cfg {String} groupClass The default CSS class to use for radio group check items (defaults to "x-menu-group-item")
     */
    groupClass : "x-menu-group-item",

    /**
     * @cfg {Boolean} checked True to initialize this checkbox as checked (defaults to false).  Note that
     * if this checkbox is part of a radio group (group = true) only the last item in the group that is
     * initialized with checked = true will be rendered as checked.
     */
    checked: false,

    // private
    ctype: "Ext.menu.CheckItem",

    // private
    onRender : function(c){
        Ext.menu.CheckItem.superclass.onRender.apply(this, arguments);
        if(this.group){
            this.el.addClass(this.groupClass);
        }
        if(this.checked){
            this.checked = false;
            this.setChecked(true, true);
        }
    },

    // private
    destroy : function(){
        Ext.menu.MenuMgr.unregisterCheckable(this);
        Ext.menu.CheckItem.superclass.destroy.apply(this, arguments);
    },

    /**
     * Set the checked state of this item
     * @param {Boolean} checked The new checked value
     * @param {Boolean} suppressEvent (optional) True to prevent the checkchange event from firing (defaults to false)
     */
    setChecked : function(state, suppressEvent){
        if(this.checked != state && this.fireEvent("beforecheckchange", this, state) !== false){
            if(this.container){
                this.container[state ? "addClass" : "removeClass"]("x-menu-item-checked");
            }
            this.checked = state;
            if(suppressEvent !== true){
                this.fireEvent("checkchange", this, state);
            }
        }
    },

    // private
    handleClick : function(e){
       if(!this.disabled && !(this.checked && this.group)){// disable unselect on radio item
           this.setChecked(!this.checked);
       }
       Ext.menu.CheckItem.superclass.handleClick.apply(this, arguments);
    }
});