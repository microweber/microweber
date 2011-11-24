/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.FitLayout=Ext.extend(Ext.layout.ContainerLayout,{monitorResize:true,onLayout:function(A,B){Ext.layout.FitLayout.superclass.onLayout.call(this,A,B);if(!this.container.collapsed){this.setItemSize(this.activeItem||A.items.itemAt(0),B.getStyleSize())}},setItemSize:function(B,A){if(B&&A.height>0){B.setSize(A)}}});Ext.Container.LAYOUTS["fit"]=Ext.layout.FitLayout;