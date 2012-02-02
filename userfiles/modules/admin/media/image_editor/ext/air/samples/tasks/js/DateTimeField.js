/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.namespace('Ext.ux.form');

/**
  * Ext.ux.form.DateTime Extension Class for Ext 2.x Library
  *
  * @author    Ing. Jozef Sakalos
  * @copyright (c) 2008, Ing. Jozef Sakalos
  * @version $Id: Ext.ux.form.DateTime.js 645 2008-01-27 21:53:01Z jozo $
  *
  * @class Ext.ux.form.DateTime
  * @extends Ext.form.Field
  * 
  * @history
  * 2008-1-31 Jack Slocum
  * Updated for reformatting and code edits
  */
Ext.ux.form.DateTime = Ext.extend(Ext.form.Field, {
	defaultAutoCreate: {
		tag: 'input',
		type: 'hidden'
	},
	dateWidth: 135,
	timeWidth: 100,
	dtSeparator: ' ',
	hiddenFormat: 'Y-m-d H:i:s',
	otherToNow: true,
	timePosition: 'right',
	
	initComponent: function(){
		// call parent initComponent
		Ext.ux.form.DateTime.superclass.initComponent.call(this);
		
		// create DateField
		var dateConfig = Ext.apply({}, {
			id: this.id + '-date',
			format: this.dateFormat,
			width: this.dateWidth,
			listeners: {
				blur: {
					scope: this,
					fn: this.onBlur
				},
				focus: {
					scope: this,
					fn: this.onFocus
				}
			}
		}, this.dateConfig);
		this.df = new Ext.form.DateField(dateConfig);
		delete (this.dateFormat);
		
		// create TimeField
		var timeConfig = Ext.apply({}, {
			id: this.id + '-time',
			format: this.timeFormat,
			width: this.timeWidth,
			listeners: {
				blur: {
					scope: this,
					fn: this.onBlur
				},
				focus: {
					scope: this,
					fn: this.onFocus
				}
			}
		}, this.timeConfig);
		this.tf = new Ext.form.TimeField(timeConfig);
		delete (this.timeFormat);
		
		// relay events
		this.relayEvents(this.df, ['focus', 'specialkey', 'invalid', 'valid']);
		this.relayEvents(this.tf, ['focus', 'specialkey', 'invalid', 'valid']);
		
	},
	onRender: function(ct, position){
		if (this.isRendered) {
			return;
		}
		
		// render underlying field
		Ext.ux.form.DateTime.superclass.onRender.call(this, ct, position);
		
		// render DateField and TimeField
		// create bounding table
		if ('below' === this.timePosition) {
			var t = Ext.DomHelper.append(ct, {
				tag: 'table',
				style: 'border-collapse:collapse',
				children: [{
					tag: 'tr',
					children: [{
						tag: 'td',
						style: 'padding-bottom:1px',
						cls: 'ux-datetime-date'
					}]
				}, {
					tag: 'tr',
					children: [{
						tag: 'td',
						cls: 'ux-datetime-time'
					}]
				}]
			}, true);
		}
		else {
			var t = Ext.DomHelper.append(ct, {
				tag: 'table',
				style: 'border-collapse:collapse',
				children: [{
					tag: 'tr',
					children: [{
						tag: 'td',
						style: 'padding-right:4px',
						cls: 'ux-datetime-date'
					}, {
						tag: 'td',
						cls: 'ux-datetime-time'
					}]
				}]
			}, true);
		}
		
		this.tableEl = t;
		this.wrap = t.wrap({
			cls: 'x-form-field-wrap'
		});
		this.wrap.on("mousedown", this.onMouseDown, this, {
			delay: 10
		});
		
		// render DateField & TimeField
		this.df.render(t.child('td.ux-datetime-date'));
		this.tf.render(t.child('td.ux-datetime-time'));
		
		if (Ext.isIE && Ext.isStrict) {
			t.select('input').applyStyles({
				top: 0
			});
		}
		
		this.on('specialkey', this.onSpecialKey, this);
		
		this.df.el.swallowEvent(['keydown', 'keypress']);
		this.tf.el.swallowEvent(['keydown', 'keypress']);
		
		// create errorIcon for side invalid
		if ('side' === this.msgTarget) {
			var elp = this.el.findParent('.x-form-element', 10, true);
			this.errorIcon = elp.createChild({
				cls: 'x-form-invalid-icon'
			});
			
			this.df.errorIcon = this.errorIcon;
			this.tf.errorIcon = this.errorIcon;
		}
		
		this.isRendered = true;
		
	},
	getPositionEl: function(){
		return this.wrap;
	},
	getResizeEl: function(){
		return this.wrap;
	},
	
	adjustSize: Ext.BoxComponent.prototype.adjustSize,
	
	alignErrorIcon: function(){
		this.errorIcon.alignTo(this.wrap, 'tl-tr', [2, 0]);
	},
	
	onSpecialKey: function(t, e){
		if (e.getKey() == e.TAB) {
			if (t === this.df && !e.shiftKey) {
				e.stopEvent();
				this.tf.focus();
			}
			if (t === this.tf && e.shiftKey) {
				e.stopEvent();
				this.df.focus();
			}
		}
	},
	
	setSize: function(w, h){
		if (!w) {
			return;
		}
		if ('below' == this.timePosition) {
			this.df.setSize(w, h);
			this.tf.setSize(w, h)
			if (Ext.isIE) {
				this.df.el.up('td').setWidth(w);
				this.tf.el.up('td').setWidth(w);
			}
		}
		else {
			this.df.setSize(w - this.timeWidth - 4, h);
			this.tf.setSize(this.timeWidth, h);
			
			if (Ext.isIE) {
				this.df.el.up('td').setWidth(w - this.timeWidth - 4);
				this.tf.el.up('td').setWidth(this.timeWidth);
			}
		}
		
	},
	
	setValue: function(val){
		if (!val) {
			this.setDate('');
			this.setTime('');
			this.updateValue();
			return;
		}
		// clear cross frame AIR nonsense
		val = new Date(val.getTime());
		var da, time;
		if (Ext.isDate(val)) {
			this.setDate(val);
			this.setTime(val);
			this.dateValue = new Date(val);
		}
		else {
			da = val.split(this.dtSeparator);
			this.setDate(da[0]);
			if (da[1]) {
				this.setTime(da[1]);
			}
		}
		this.updateValue();
	},
	
	getValue: function(){
		// create new instance of date
		return this.dateValue ? new Date(this.dateValue) : '';
	},
	
	onMouseDown: function(e){
		// just to prevent blur event when clicked in the middle of fields
		this.wrapClick = 'td' === e.target.nodeName.toLowerCase();
	},
	
	onFocus: function(){
		if (!this.hasFocus) {
			this.hasFocus = true;
			this.startValue = this.getValue();
			this.fireEvent("focus", this);
		}
	},
	
	onBlur: function(f){
		// called by both DateField and TimeField blur events
		
		// revert focus to previous field if clicked in between
		if (this.wrapClick) {
			f.focus();
			this.wrapClick = false;
		}
		
		// update underlying value
		if (f === this.df) {
			this.updateDate();
		}
		else {
			this.updateTime();
		}
		this.updateHidden();
		
		// fire events later
		(function(){
			if (!this.df.hasFocus && !this.tf.hasFocus) {
				var v = this.getValue();
				if (String(v) !== String(this.startValue)) {
					this.fireEvent("change", this, v, this.startValue);
				}
				this.hasFocus = false;
				this.fireEvent('blur', this);
			}
		}).defer(100, this);
		
	},
	updateDate: function(){
	
		var d = this.df.getValue();
		if (d) {
			if (!Ext.isDate(this.dateValue)) {
				this.initDateValue();
				if (!this.tf.getValue()) {
					this.setTime(this.dateValue);
				}
			}
			this.dateValue.setFullYear(d.getFullYear());
			this.dateValue.setMonth(d.getMonth());
			this.dateValue.setDate(d.getDate());
		}
		else {
			this.dateValue = '';
			this.setTime('');
		}
	},
	updateTime: function(){
		var t = this.tf.getValue();
		if (t && !Ext.isDate(t)) {
			t = Date.parseDate(t, this.tf.format);
		}
		if (t && !this.df.getValue()) {
			this.initDateValue();
			this.setDate(this.dateValue);
		}
		if (Ext.isDate(this.dateValue)) {
			if (t) {
				this.dateValue.setHours(t.getHours());
				this.dateValue.setMinutes(t.getMinutes());
				this.dateValue.setSeconds(t.getSeconds());
			}
			else {
				this.dateValue.setHours(0);
				this.dateValue.setMinutes(0);
				this.dateValue.setSeconds(0);
			}
		}
	},
	initDateValue: function(){
		this.dateValue = this.otherToNow ? new Date() : new Date(1970, 0, 1, 0, 0, 0);
	},
	updateHidden: function(){
		if (this.isRendered) {
			var value = Ext.isDate(this.dateValue) ? this.dateValue.format(this.hiddenFormat) : '';
			this.el.dom.value = value;
		}
	},
	updateValue: function(){
	
		this.updateDate();
		this.updateTime();
		this.updateHidden();
		
		return;
		
	},
	setDate: function(date){
		this.df.setValue(date);
	},
	setTime: function(date){
		this.tf.setValue(date);
	},
	isValid: function(){
		return this.df.isValid() && this.tf.isValid();
	},
	validate: function(){
		return this.df.validate() && this.tf.validate();
	},
	focus: function(){
		this.df.focus();
	},
	
	onDisable : function(){
		if(this.rendered){
			this.df.disable();
			this.tf.disable();
		}
	},
	
	onEnable : function(){
		if(this.rendered){
			this.df.enable();
			this.tf.enable();
		}
	}
});

// register xtype
Ext.reg('xdatetime', Ext.ux.form.DateTime);