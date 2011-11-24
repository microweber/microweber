/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.KeyNav=function(B,A){this.el=Ext.get(B);Ext.apply(this,A);if(!this.disabled){this.disabled=true;this.enable()}};Ext.KeyNav.prototype={disabled:false,defaultEventAction:"stopEvent",forceKeyDown:false,prepareEvent:function(C){var A=C.getKey();var B=this.keyToHandler[A];if(Ext.isSafari2&&B&&A>=37&&A<=40){C.stopEvent()}},relay:function(C){var A=C.getKey();var B=this.keyToHandler[A];if(B&&this[B]){if(this.doRelay(C,this[B],B)!==true){C[this.defaultEventAction]()}}},doRelay:function(C,B,A){return B.call(this.scope||this,C)},enter:false,left:false,right:false,up:false,down:false,tab:false,esc:false,pageUp:false,pageDown:false,del:false,home:false,end:false,keyToHandler:{37:"left",39:"right",38:"up",40:"down",33:"pageUp",34:"pageDown",46:"del",36:"home",35:"end",13:"enter",27:"esc",9:"tab"},enable:function(){if(this.disabled){if(this.forceKeyDown||Ext.isIE||Ext.isSafari3||Ext.isAir){this.el.on("keydown",this.relay,this)}else{this.el.on("keydown",this.prepareEvent,this);this.el.on("keypress",this.relay,this)}this.disabled=false}},disable:function(){if(!this.disabled){if(this.forceKeyDown||Ext.isIE||Ext.isSafari3||Ext.isAir){this.el.un("keydown",this.relay)}else{this.el.un("keydown",this.prepareEvent);this.el.un("keypress",this.relay)}this.disabled=true}}};