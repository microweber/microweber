/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.Action
 * <p>An Action is a piece of reusable functionality that can be abstracted out of any particular component so that it
 * can be usefully shared among multiple components.  Actions let you share handlers, configuration options and UI
 * updates across any components that support the Action interface (primarily {@link Ext.Toolbar}, {@link Ext.Button}
 * and {@link Ext.menu.Menu} components).</p>
 * <p>Aside from supporting the config object interface, any component that needs to use Actions must also support
 * the following method list, as these will be called as needed by the Action class: setText(string), setIconCls(string),
 * setDisabled(boolean), setVisible(boolean) and setHandler(function).</p>
 * Example usage:<br>
 * <pre><code>
// Define the shared action.  Each component below will have the same
// display text and icon, and will display the same message on click.
var action = new Ext.Action({
    text: 'Do something',
    handler: function(){
        Ext.Msg.alert('Click', 'You did something.');
    },
    iconCls: 'do-something'
});

var panel = new Ext.Panel({
    title: 'Actions',
    width:500,
    height:300,
    tbar: [
        // Add the action directly to a toolbar as a menu button
        action, {
            text: 'Action Menu',
            // Add the action to a menu as a text item
            menu: [action]
        }
    ],
    items: [
        // Add the action to the panel body as a standard button
        new Ext.Button(action)
    ],
    renderTo: Ext.getBody()
});

// Change the text for all components using the action
action.setText('Something else');
</code></pre>
 * @constructor
 * @param {Object} config The configuration options
 */
Ext.Action = function(config){
    this.initialConfig = config;
    this.items = [];
}

Ext.Action.prototype = {
    /**
     * @cfg {String} text The text to set for all components using this action (defaults to '').
     */
    /**
     * @cfg {String} iconCls The icon CSS class for all components using this action (defaults to '').
     * The class should supply a background image that will be used as the icon image.
     */
    /**
     * @cfg {Boolean} disabled True to disable all components using this action, false to enable them (defaults to false).
     */
    /**
     * @cfg {Boolean} hidden True to hide all components using this action, false to show them (defaults to false).
     */
    /**
     * @cfg {Function} handler The function that will be invoked by each component tied to this action
     * when the component's primary event is triggered (defaults to undefined).
     */
    /**
     * @cfg {Object} scope The scope in which the {@link #handler} function will execute.
     */

    // private
    isAction : true,

    /**
     * Sets the text to be displayed by all components using this action.
     * @param {String} text The text to display
     */
    setText : function(text){
        this.initialConfig.text = text;
        this.callEach('setText', [text]);
    },

    /**
     * Gets the text currently displayed by all components using this action.
     */
    getText : function(){
        return this.initialConfig.text;
    },

    /**
     * Sets the icon CSS class for all components using this action.  The class should supply
     * a background image that will be used as the icon image.
     * @param {String} cls The CSS class supplying the icon image
     */
    setIconClass : function(cls){
        this.initialConfig.iconCls = cls;
        this.callEach('setIconClass', [cls]);
    },

    /**
     * Gets the icon CSS class currently used by all components using this action.
     */
    getIconClass : function(){
        return this.initialConfig.iconCls;
    },

    /**
     * Sets the disabled state of all components using this action.  Shortcut method
     * for {@link #enable} and {@link #disable}.
     * @param {Boolean} disabled True to disable the component, false to enable it
     */
    setDisabled : function(v){
        this.initialConfig.disabled = v;
        this.callEach('setDisabled', [v]);
    },

    /**
     * Enables all components using this action.
     */
    enable : function(){
        this.setDisabled(false);
    },

    /**
     * Disables all components using this action.
     */
    disable : function(){
        this.setDisabled(true);
    },

    /**
     * Returns true if the components using this action are currently disabled, else returns false.  Read-only.
     * @property
     */
    isDisabled : function(){
        return this.initialConfig.disabled;
    },

    /**
     * Sets the hidden state of all components using this action.  Shortcut method
     * for {@link #hide} and {@link #show}.
     * @param {Boolean} hidden True to hide the component, false to show it
     */
    setHidden : function(v){
        this.initialConfig.hidden = v;
        this.callEach('setVisible', [!v]);
    },

    /**
     * Shows all components using this action.
     */
    show : function(){
        this.setHidden(false);
    },

    /**
     * Hides all components using this action.
     */
    hide : function(){
        this.setHidden(true);
    },

    /**
     * Returns true if the components using this action are currently hidden, else returns false.  Read-only.
     * @property
     */
    isHidden : function(){
        return this.initialConfig.hidden;
    },

    /**
     * Sets the function that will be called by each component using this action when its primary event is triggered.
     * @param {Function} fn The function that will be invoked by the action's components.  The function
     * will be called with no arguments.
     * @param {Object} scope The scope in which the function will execute
     */
    setHandler : function(fn, scope){
        this.initialConfig.handler = fn;
        this.initialConfig.scope = scope;
        this.callEach('setHandler', [fn, scope]);
    },

    /**
     * Executes the specified function once for each component currently tied to this action.  The function passed
     * in should accept a single argument that will be an object that supports the basic Action config/method interface.
     * @param {Function} fn The function to execute for each component
     * @param {Object} scope The scope in which the function will execute
     */
    each : function(fn, scope){
        Ext.each(this.items, fn, scope);
    },

    // private
    callEach : function(fnName, args){
        var cs = this.items;
        for(var i = 0, len = cs.length; i < len; i++){
            cs[i][fnName].apply(cs[i], args);
        }
    },

    // private
    addComponent : function(comp){
        this.items.push(comp);
        comp.on('destroy', this.removeComponent, this);
    },

    // private
    removeComponent : function(comp){
        this.items.remove(comp);
    },

    /**
     * Executes this action manually using the default handler specified in the original config object.  Any arguments
     * passed to this function will be passed on to the handler function.
     * @param {Mixed} arg1 (optional) Variable number of arguments passed to the handler function 
     * @param {Mixed} arg2 (optional)
     * @param {Mixed} etc... (optional)
     */
    execute : function(){
        this.initialConfig.handler.apply(this.initialConfig.scope || window, arguments);
    }
};