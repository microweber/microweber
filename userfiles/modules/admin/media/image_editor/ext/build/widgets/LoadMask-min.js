/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.LoadMask=function(C,B){this.el=Ext.get(C);Ext.apply(this,B);if(this.store){this.store.on("beforeload",this.onBeforeLoad,this);this.store.on("load",this.onLoad,this);this.store.on("loadexception",this.onLoad,this);this.removeMask=Ext.value(this.removeMask,false)}else{var A=this.el.getUpdater();A.showLoadIndicator=false;A.on("beforeupdate",this.onBeforeLoad,this);A.on("update",this.onLoad,this);A.on("failure",this.onLoad,this);this.removeMask=Ext.value(this.removeMask,true)}};Ext.LoadMask.prototype={msg:"Loading...",msgCls:"x-mask-loading",disabled:false,disable:function(){this.disabled=true},enable:function(){this.disabled=false},onLoad:function(){this.el.unmask(this.removeMask)},onBeforeLoad:function(){if(!this.disabled){this.el.mask(this.msg,this.msgCls)}},show:function(){this.onBeforeLoad()},hide:function(){this.onLoad()},destroy:function(){if(this.store){this.store.un("beforeload",this.onBeforeLoad,this);this.store.un("load",this.onLoad,this);this.store.un("loadexception",this.onLoad,this)}else{var A=this.el.getUpdater();A.un("beforeupdate",this.onBeforeLoad,this);A.un("update",this.onLoad,this);A.un("failure",this.onLoad,this)}}};