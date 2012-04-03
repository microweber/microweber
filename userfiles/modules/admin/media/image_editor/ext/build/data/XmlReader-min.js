/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.XmlReader=function(A,B){A=A||{};Ext.data.XmlReader.superclass.constructor.call(this,A,B||A.fields)};Ext.extend(Ext.data.XmlReader,Ext.data.DataReader,{read:function(A){var B=A.responseXML;if(!B){throw {message:"XmlReader.read: XML Document not available"}}return this.readRecords(B)},readRecords:function(T){this.xmlData=T;var N=T.documentElement||T;var I=Ext.DomQuery;var B=this.recordType,L=B.prototype.fields;var D=this.meta.id;var G=0,E=true;if(this.meta.totalRecords){G=I.selectNumber(this.meta.totalRecords,N,0)}if(this.meta.success){var K=I.selectValue(this.meta.success,N,true);E=K!==false&&K!=="false"}var Q=[];var U=I.select(this.meta.record,N);for(var P=0,R=U.length;P<R;P++){var M=U[P];var A={};var J=D?I.selectValue(D,M):undefined;for(var O=0,H=L.length;O<H;O++){var S=L.items[O];var F=I.selectValue(S.mapping||S.name,M,S.defaultValue);F=S.convert(F,M);A[S.name]=F}var C=new B(A,J);C.node=M;Q[Q.length]=C}return{success:E,records:Q,totalRecords:G||Q.length}}});