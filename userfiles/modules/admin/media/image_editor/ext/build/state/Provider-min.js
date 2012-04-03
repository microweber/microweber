/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.state.Provider=function(){this.addEvents("statechange");this.state={};Ext.state.Provider.superclass.constructor.call(this)};Ext.extend(Ext.state.Provider,Ext.util.Observable,{get:function(B,A){return typeof this.state[B]=="undefined"?A:this.state[B]},clear:function(A){delete this.state[A];this.fireEvent("statechange",this,A,null)},set:function(A,B){this.state[A]=B;this.fireEvent("statechange",this,A,B)},decodeValue:function(A){var J=/^(a|n|d|b|s|o)\:(.*)$/;var C=J.exec(unescape(A));if(!C||!C[1]){return }var F=C[1];var H=C[2];switch(F){case"n":return parseFloat(H);case"d":return new Date(Date.parse(H));case"b":return(H=="1");case"a":var G=[];var I=H.split("^");for(var B=0,D=I.length;B<D;B++){G.push(this.decodeValue(I[B]))}return G;case"o":var G={};var I=H.split("^");for(var B=0,D=I.length;B<D;B++){var E=I[B].split("=");G[E[0]]=this.decodeValue(E[1])}return G;default:return H}},encodeValue:function(C){var B;if(typeof C=="number"){B="n:"+C}else{if(typeof C=="boolean"){B="b:"+(C?"1":"0")}else{if(Ext.isDate(C)){B="d:"+C.toGMTString()}else{if(Ext.isArray(C)){var F="";for(var E=0,A=C.length;E<A;E++){F+=this.encodeValue(C[E]);if(E!=A-1){F+="^"}}B="a:"+F}else{if(typeof C=="object"){var F="";for(var D in C){if(typeof C[D]!="function"&&C[D]!==undefined){F+=D+"="+this.encodeValue(C[D])+"^"}}B="o:"+F.substring(0,F.length-1)}else{B="s:"+C}}}}}return escape(B)}});