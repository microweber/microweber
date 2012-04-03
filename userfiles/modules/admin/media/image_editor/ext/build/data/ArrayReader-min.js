/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.ArrayReader=Ext.extend(Ext.data.JsonReader,{readRecords:function(C){var B=this.meta?this.meta.id:null;var G=this.recordType,K=G.prototype.fields;var E=[];var M=C;for(var I=0;I<M.length;I++){var D=M[I];var O={};var A=((B||B===0)&&D[B]!==undefined&&D[B]!==""?D[B]:null);for(var H=0,P=K.length;H<P;H++){var L=K.items[H];var F=L.mapping!==undefined&&L.mapping!==null?L.mapping:H;var N=D[F]!==undefined?D[F]:L.defaultValue;N=L.convert(N,D);O[L.name]=N}var J=new G(O,A);J.json=D;E[E.length]=J}return{records:E,totalRecords:E.length}}});