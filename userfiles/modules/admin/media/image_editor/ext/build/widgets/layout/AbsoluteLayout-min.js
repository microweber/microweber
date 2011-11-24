/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.AbsoluteLayout=Ext.extend(Ext.layout.AnchorLayout,{extraCls:"x-abs-layout-item",isForm:false,setContainer:function(A){Ext.layout.AbsoluteLayout.superclass.setContainer.call(this,A);if(A.isXType("form")){this.isForm=true}},onLayout:function(A,B){if(this.isForm){A.body.position()}else{B.position()}Ext.layout.AbsoluteLayout.superclass.onLayout.call(this,A,B)},getAnchorViewSize:function(A,B){return this.isForm?A.body.getStyleSize():Ext.layout.AbsoluteLayout.superclass.getAnchorViewSize.call(this,A,B)},isValidParent:function(B,A){return this.isForm?true:Ext.layout.AbsoluteLayout.superclass.isValidParent.call(this,B,A)},adjustWidthAnchor:function(B,A){return B?B-A.getPosition(true)[0]:B},adjustHeightAnchor:function(B,A){return B?B-A.getPosition(true)[1]:B}});Ext.Container.LAYOUTS["absolute"]=Ext.layout.AbsoluteLayout;