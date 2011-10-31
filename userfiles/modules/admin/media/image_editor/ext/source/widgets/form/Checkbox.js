/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.form.Checkbox
 * @extends Ext.form.Field
 * Single checkbox field.  Can be used as a direct replacement for traditional checkbox fields.
 * @constructor
 * Creates a new Checkbox
 * @param {Object} config Configuration options
 */
Ext.form.Checkbox = Ext.extend(Ext.form.Field,  {
    /**
     * @cfg {String} checkedCls The CSS class to use when the control is checked (defaults to 'x-form-check-checked').
     * Note that this class applies to both checkboxes and radio buttons and is added to the control's wrapper element.
     */
    checkedCls: 'x-form-check-checked',
    /**
     * @cfg {String} focusCls The CSS class to use when the control receives input focus (defaults to 'x-form-check-focus').
     * Note that this class applies to both checkboxes and radio buttons and is added to the control's wrapper element.
     */
    focusCls: 'x-form-check-focus',
    /**
     * @cfg {String} overCls The CSS class to use when the control is hovered over (defaults to 'x-form-check-over').
     * Note that this class applies to both checkboxes and radio buttons and is added to the control's wrapper element.
     */
    overCls: 'x-form-check-over',
    /**
     * @cfg {String} mouseDownCls The CSS class to use when the control is being actively clicked (defaults to 'x-form-check-down').
     * Note that this class applies to both checkboxes and radio buttons and is added to the control's wrapper element.
     */
    mouseDownCls: 'x-form-check-down',
    /**
     * @cfg {Number} tabIndex The tabIndex for this field. Note this only applies to fields that are rendered,
     * not those which are built via applyTo (defaults to 0, which allows the browser to manage the tab index).
     */
    tabIndex: 0,
    /**
     * @cfg {Boolean} checked True if the checkbox should render already checked (defaults to false)
     */
    checked: false,
    /**
     * @cfg {String/Object} autoCreate A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "checkbox", autocomplete: "off"}).
     */
    defaultAutoCreate: {tag: 'input', type: 'checkbox', autocomplete: 'off'},
    /**
     * @cfg {String} boxLabel The text that appears beside the checkbox (defaults to '')
     */
    /**
     * @cfg {String} inputValue The value that should go into the generated input element's value attribute
     * (defaults to undefined, with no value attribute)
     */
    /**
     * @cfg {Function} handler A function called when the {@link #checked} value changes (can be used instead of 
     * handling the check event)
     */

    // private
    baseCls: 'x-form-check',

    // private
    initComponent : function(){
        Ext.form.Checkbox.superclass.initComponent.call(this);
        this.addEvents(
            /**
             * @event check
             * Fires when the checkbox is checked or unchecked.
             * @param {Ext.form.Checkbox} this This checkbox
             * @param {Boolean} checked The new checked value
             */
            'check'
        );
    },

    // private
    initEvents : function(){
        Ext.form.Checkbox.superclass.initEvents.call(this);
        this.initCheckEvents();
    },

    // private
    initCheckEvents : function(){
        this.innerWrap.removeAllListeners();
        this.innerWrap.addClassOnOver(this.overCls);
        this.innerWrap.addClassOnClick(this.mouseDownCls);
        this.innerWrap.on('click', this.onClick, this);
        this.innerWrap.on('keyup', this.onKeyUp, this);
    },

    // private
    onRender : function(ct, position){
        Ext.form.Checkbox.superclass.onRender.call(this, ct, position);
        if(this.inputValue !== undefined){
            this.el.dom.value = this.inputValue;
        }
        this.el.addClass('x-hidden');

        this.innerWrap = this.el.wrap({
            tabIndex: this.tabIndex,
            cls: this.baseCls+'-wrap-inner'
        });
        this.wrap = this.innerWrap.wrap({cls: this.baseCls+'-wrap'});

        if(this.boxLabel){
            this.labelEl = this.innerWrap.createChild({
                tag: 'label',
                htmlFor: this.el.id,
                cls: 'x-form-cb-label',
                html: this.boxLabel
            });
        }

        this.imageEl = this.innerWrap.createChild({
            tag: 'img',
            src: Ext.BLANK_IMAGE_URL,
            cls: this.baseCls
        }, this.el);

        if(this.checked){
            this.setValue(true);
        }else{
            this.checked = this.el.dom.checked;
        }
        this.originalValue = this.checked;
    },

    // private
    onDestroy : function(){
        if(this.rendered){
            Ext.destroy(this.imageEl, this.labelEl, this.innerWrap, this.wrap);
        }
        Ext.form.Checkbox.superclass.onDestroy.call(this);
    },

    // private
    onFocus: function(e) {
        Ext.form.Checkbox.superclass.onFocus.call(this, e);
        this.el.addClass(this.focusCls);
    },

    // private
    onBlur: function(e) {
        Ext.form.Checkbox.superclass.onBlur.call(this, e);
        this.el.removeClass(this.focusCls);
    },

    // private
    onResize : function(){
        Ext.form.Checkbox.superclass.onResize.apply(this, arguments);
        if(!this.boxLabel && !this.fieldLabel){
            this.el.alignTo(this.wrap, 'c-c');
        }
    },

    // private
    onKeyUp : function(e){
        if(e.getKey() == Ext.EventObject.SPACE){
            this.onClick(e);
        }
    },

    // private
    onClick : function(e){
        if (!this.disabled && !this.readOnly) {
            this.toggleValue();
        }
        e.stopEvent();
    },

    // private
    onEnable : function(){
        Ext.form.Checkbox.superclass.onEnable.call(this);
        this.initCheckEvents();
    },

    // private
    onDisable : function(){
        Ext.form.Checkbox.superclass.onDisable.call(this);
        this.innerWrap.removeAllListeners();
    },

    toggleValue : function(){
        this.setValue(!this.checked);
    },

    // private
    getResizeEl : function(){
        if(!this.resizeEl){
            this.resizeEl = Ext.isSafari ? this.wrap : (this.wrap.up('.x-form-element', 5) || this.wrap);
        }
        return this.resizeEl;
    },

    // private
    getPositionEl : function(){
        return this.wrap;
    },

    // private
    getActionEl : function(){
        return this.wrap;
    },

    /**
     * Overridden and disabled. The editor element does not support standard valid/invalid marking. @hide
     * @method
     */
    markInvalid : Ext.emptyFn,
    /**
     * Overridden and disabled. The editor element does not support standard valid/invalid marking. @hide
     * @method
     */
    clearInvalid : Ext.emptyFn,

    // private
    initValue : Ext.emptyFn,

    /**
     * Returns the checked state of the checkbox.
     * @return {Boolean} True if checked, else false
     */
    getValue : function(){
        if(this.rendered){
            return this.el.dom.checked;
        }
        return false;
    },

    /**
     * Sets the checked state of the checkbox.
     * @param {Boolean/String} checked True, 'true', '1', or 'on' to check the checkbox, any other value will uncheck it.
     */
    setValue : function(v) {
        var checked = this.checked;
        this.checked = (v === true || v === 'true' || v == '1' || String(v).toLowerCase() == 'on');
        
        if(this.el && this.el.dom){
            this.el.dom.checked = this.checked;
            this.el.dom.defaultChecked = this.checked;
        }
        this.wrap[this.checked? 'addClass' : 'removeClass'](this.checkedCls);
        
        if(checked != this.checked){
            this.fireEvent("check", this, this.checked);
            if(this.handler){
                this.handler.call(this.scope || this, this, this.checked);
            }
        }
    }

    /**
     * @cfg {Mixed} value
     * @hide
     */
    /**
     * @cfg {String} disabledClass
     * @hide
     */
    /**
     * @cfg {String} focusClass
     * @hide
     */
});
Ext.reg('checkbox', Ext.form.Checkbox);
