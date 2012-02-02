/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.HttpProxy=function(A){Ext.data.HttpProxy.superclass.constructor.call(this);this.conn=A;this.useAjax=!A||!A.events};Ext.extend(Ext.data.HttpProxy,Ext.data.DataProxy,{getConnection:function(){return this.useAjax?Ext.Ajax:this.conn},load:function(E,B,F,C,A){if(this.fireEvent("beforeload",this,E)!==false){var D={params:E||{},request:{callback:F,scope:C,arg:A},reader:B,callback:this.loadResponse,scope:this};if(this.useAjax){Ext.applyIf(D,this.conn);if(this.activeRequest){Ext.Ajax.abort(this.activeRequest)}this.activeRequest=Ext.Ajax.request(D)}else{this.conn.request(D)}}else{F.call(C||this,null,A,false)}},loadResponse:function(E,D,B){delete this.activeRequest;if(!D){this.fireEvent("loadexception",this,E,B);E.request.callback.call(E.request.scope,null,E.request.arg,false);return }var A;try{A=E.reader.read(B)}catch(C){this.fireEvent("loadexception",this,E,B,C);E.request.callback.call(E.request.scope,null,E.request.arg,false);return }this.fireEvent("load",this,E,E.request.arg);E.request.callback.call(E.request.scope,A,E.request.arg,true)},update:function(A){},updateResponse:function(A){}});