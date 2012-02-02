/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.DropTarget=function(B,A){this.el=Ext.get(B);Ext.apply(this,A);if(this.containerScroll){Ext.dd.ScrollManager.register(this.el)}Ext.dd.DropTarget.superclass.constructor.call(this,this.el.dom,this.ddGroup||this.group,{isTarget:true})};Ext.extend(Ext.dd.DropTarget,Ext.dd.DDTarget,{dropAllowed:"x-dd-drop-ok",dropNotAllowed:"x-dd-drop-nodrop",isTarget:true,isNotifyTarget:true,notifyEnter:function(A,C,B){if(this.overClass){this.el.addClass(this.overClass)}return this.dropAllowed},notifyOver:function(A,C,B){return this.dropAllowed},notifyOut:function(A,C,B){if(this.overClass){this.el.removeClass(this.overClass)}},notifyDrop:function(A,C,B){return false}});