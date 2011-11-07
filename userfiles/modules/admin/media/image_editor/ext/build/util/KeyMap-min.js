/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.KeyMap=function(C,B,A){this.el=Ext.get(C);this.eventName=A||"keydown";this.bindings=[];if(B){this.addBinding(B)}this.enable()};Ext.KeyMap.prototype={stopEvent:false,addBinding:function(D){if(Ext.isArray(D)){for(var F=0,H=D.length;F<H;F++){this.addBinding(D[F])}return }var N=D.key,C=D.shift,A=D.ctrl,G=D.alt,J=D.fn||D.handler,M=D.scope;if(D.stopEvent){this.stopEvent=D.stopEvent}if(typeof N=="string"){var K=[];var I=N.toUpperCase();for(var E=0,H=I.length;E<H;E++){K.push(I.charCodeAt(E))}N=K}var B=Ext.isArray(N);var L=function(R){if((!C||R.shiftKey)&&(!A||R.ctrlKey)&&(!G||R.altKey)){var P=R.getKey();if(B){for(var Q=0,O=N.length;Q<O;Q++){if(N[Q]==P){if(this.stopEvent){R.stopEvent()}J.call(M||window,P,R);return }}}else{if(P==N){if(this.stopEvent){R.stopEvent()}J.call(M||window,P,R)}}}};this.bindings.push(L)},on:function(B,D,C){var G,A,E,F;if(typeof B=="object"&&!Ext.isArray(B)){G=B.key;A=B.shift;E=B.ctrl;F=B.alt}else{G=B}this.addBinding({key:G,shift:A,ctrl:E,alt:F,fn:D,scope:C})},handleKeyDown:function(D){if(this.enabled){var B=this.bindings;for(var C=0,A=B.length;C<A;C++){B[C].call(this,D)}}},isEnabled:function(){return this.enabled},enable:function(){if(!this.enabled){this.el.on(this.eventName,this.handleKeyDown,this);this.enabled=true}},disable:function(){if(this.enabled){this.el.removeListener(this.eventName,this.handleKeyDown,this);this.enabled=false}}};