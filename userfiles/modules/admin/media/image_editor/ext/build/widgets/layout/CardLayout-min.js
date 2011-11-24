/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.CardLayout=Ext.extend(Ext.layout.FitLayout,{deferredRender:false,renderHidden:true,setActiveItem:function(A){A=this.container.getComponent(A);if(this.activeItem!=A){if(this.activeItem){this.activeItem.hide()}this.activeItem=A;A.show();this.layout()}},renderAll:function(A,B){if(this.deferredRender){this.renderItem(this.activeItem,undefined,B)}else{Ext.layout.CardLayout.superclass.renderAll.call(this,A,B)}}});Ext.Container.LAYOUTS["card"]=Ext.layout.CardLayout;