/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.ux.ValidationStatus
 * A {@link Ext.StatusBar} plugin that provides automatic error notification when the
 * associated form contains validation errors.
 * @extends Ext.Component
 * @constructor
 * Creates a new ValiationStatus plugin
 * @param {Object} config A config object
 */
Ext.ux.ValidationStatus = Ext.extend(Ext.Component, {
    
    errorIconCls : 'x-status-error',
    
    errorListCls : 'x-status-error-list',
    
    validIconCls : 'x-status-valid',
    
    showText : 'The form has errors (click for details...)',
    
    hideText : 'Click again to hide the error list',
    
    submitText : 'Saving...',
    
    // private
    init : function(sb){
        sb.on('render', function(){
            this.statusBar = sb;
            this.monitor = true;
            this.errors = new Ext.util.MixedCollection();
            this.listAlign = (sb.statusAlign=='right' ? 'br-tr?' : 'bl-tl?');
            
            if(this.form){
                this.form = Ext.getCmp(this.form).getForm();
                this.startMonitoring();
                
                // Have to give the status bar time to render since it happens in afterRender
                (function(){
                    sb.statusEl.on('click', this.onStatusClick, this, {buffer:200});
                }).defer(200, this);
                
                this.form.on('beforeaction', function(f, action){
                    if(action.type == 'submit'){
                        // Ignore monitoring while submitting otherwise the field validation
                        // events cause the status message to reset too early
                        this.monitor = false;
                    }
                }, this);
                var startMonitor = function(){
                    this.monitor = true;
                }
                this.form.on('actioncomplete', startMonitor, this);
                this.form.on('actionfailed', startMonitor, this);
            }
        }, this, {single:true});
    },
    
    // private
    startMonitoring : function(){
        this.form.items.each(function(f){
            f.on('invalid', this.onFieldValidation, this);
            f.on('valid', this.onFieldValidation, this);
        }, this);
    },
    
    // private
    stopMonitoring : function(){
        this.form.items.each(function(f){
            f.un('invalid', this.onFieldValidation, this);
            f.un('valid', this.onFieldValidation, this);
        }, this);
    },
    
    // private
    onDestroy : function(){
        this.stopMonitoring();
        this.statusBar.statusEl.un('click', this.onStatusClick, this);
        Ext.ux.ValidationStatus.superclass.onDestroy.call(this);
    },
    
    // private
    onFieldValidation : function(f, msg){
        if(!this.monitor){
            return false;
        }
        if(msg){
            this.errors.add(f.id, {field:f, msg:msg});
        }else{
            this.errors.removeKey(f.id);
        }
        this.updateErrorList();
        if(this.errors.getCount() > 0){
            if(this.statusBar.getText() != this.showText){
                this.statusBar.setStatus({text:this.showText, iconCls:this.errorIconCls});
            }
        }else{
            this.statusBar.clearStatus().setIcon(this.validIconCls);
        }
    },
    
    // private
    updateErrorList : function(){
        if(this.errors.getCount() > 0){
	        var msg = '<ul>';
	        this.errors.each(function(err){
	            msg += ('<li id="x-err-'+ err.field.id +'"><a href="#">' + err.msg + '</a></li>');
	        }, this);
	        this.getMsgEl().update(msg+'</ul>');
        }else{
            this.getMsgEl().update('');
        }
    },
    
    // private
    getMsgEl : function(){
        if(!this.msgEl){
            this.msgEl = Ext.DomHelper.append(Ext.getBody(), {
                cls: this.errorListCls+' x-hide-offsets'
            }, true);
            
            this.msgEl.on('click', function(e){
                var t = e.getTarget('li', 10, true);
                if(t){
                    Ext.getCmp(t.id.split('x-err-')[1]).focus();
                    this.hideErrors();
                }
            }, this, {stopEvent:true}); // prevent anchor click navigation
        }
        return this.msgEl;
    },
    
    // private
    showErrors : function(){
        this.updateErrorList();
        this.getMsgEl().alignTo(this.statusBar.getEl(), this.listAlign).slideIn('b', {duration:.3, easing:'easeOut'});
        this.statusBar.setText(this.hideText);
        this.form.getEl().on('click', this.hideErrors, this, {single:true}); // hide if the user clicks directly into the form
    },
    
    // private
    hideErrors : function(){
        var el = this.getMsgEl();
        if(el.isVisible()){
	        el.slideOut('b', {duration:.2, easing:'easeIn'});
	        this.statusBar.setText(this.showText);
        }
        this.form.getEl().un('click', this.hideErrors, this);
    },
    
    // private
    onStatusClick : function(){
        if(this.getMsgEl().isVisible()){
            this.hideErrors();
        }else if(this.errors.getCount() > 0){
            this.showErrors();
        }
    }
});