/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.BaseItem=function(A){Ext.menu.BaseItem.superclass.constructor.call(this,A);this.addEvents("click","activate","deactivate");if(this.handler){this.on("click",this.handler,this.scope)}};Ext.extend(Ext.menu.BaseItem,Ext.Component,{canActivate:false,activeClass:"x-menu-item-active",hideOnClick:true,hideDelay:100,ctype:"Ext.menu.BaseItem",actionMode:"container",render:function(A,B){this.parentMenu=B;Ext.menu.BaseItem.superclass.render.call(this,A);this.container.menuItemId=this.id},onRender:function(B,A){this.el=Ext.get(this.el);B.dom.appendChild(this.el.dom)},setHandler:function(B,A){if(this.handler){this.un("click",this.handler,this.scope)}this.on("click",this.handler=B,this.scope=A)},onClick:function(A){if(!this.disabled&&this.fireEvent("click",this,A)!==false&&this.parentMenu.fireEvent("itemclick",this,A)!==false){this.handleClick(A)}else{A.stopEvent()}},activate:function(){if(this.disabled){return false}var A=this.container;A.addClass(this.activeClass);this.region=A.getRegion().adjust(2,2,-2,-2);this.fireEvent("activate",this);return true},deactivate:function(){this.container.removeClass(this.activeClass);this.fireEvent("deactivate",this)},shouldDeactivate:function(A){return !this.region||!this.region.contains(A.getPoint())},handleClick:function(A){if(this.hideOnClick){this.parentMenu.hide.defer(this.hideDelay,this.parentMenu,[true])}},expandMenu:function(A){},hideMenu:function(){}});