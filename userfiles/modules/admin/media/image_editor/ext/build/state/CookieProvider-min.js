/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.state.CookieProvider=function(A){Ext.state.CookieProvider.superclass.constructor.call(this);this.path="/";this.expires=new Date(new Date().getTime()+(1000*60*60*24*7));this.domain=null;this.secure=false;Ext.apply(this,A);this.state=this.readCookies()};Ext.extend(Ext.state.CookieProvider,Ext.state.Provider,{set:function(A,B){if(typeof B=="undefined"||B===null){this.clear(A);return }this.setCookie(A,B);Ext.state.CookieProvider.superclass.set.call(this,A,B)},clear:function(A){this.clearCookie(A);Ext.state.CookieProvider.superclass.clear.call(this,A)},readCookies:function(){var C={};var F=document.cookie+";";var B=/\s?(.*?)=(.*?);/g;var E;while((E=B.exec(F))!=null){var A=E[1];var D=E[2];if(A&&A.substring(0,3)=="ys-"){C[A.substr(3)]=this.decodeValue(D)}}return C},setCookie:function(A,B){document.cookie="ys-"+A+"="+this.encodeValue(B)+((this.expires==null)?"":("; expires="+this.expires.toGMTString()))+((this.path==null)?"":("; path="+this.path))+((this.domain==null)?"":("; domain="+this.domain))+((this.secure==true)?"; secure":"")},clearCookie:function(A){document.cookie="ys-"+A+"=null; expires=Thu, 01-Jan-70 00:00:01 GMT"+((this.path==null)?"":("; path="+this.path))+((this.domain==null)?"":("; domain="+this.domain))+((this.secure==true)?"; secure":"")}});