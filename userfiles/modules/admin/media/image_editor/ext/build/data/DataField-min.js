/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.Field=function(D){if(typeof D=="string"){D={name:D}}Ext.apply(this,D);if(!this.type){this.type="auto"}var C=Ext.data.SortTypes;if(typeof this.sortType=="string"){this.sortType=C[this.sortType]}if(!this.sortType){switch(this.type){case"string":this.sortType=C.asUCString;break;case"date":this.sortType=C.asDate;break;default:this.sortType=C.none}}var E=/[\$,%]/g;if(!this.convert){var B,A=this.dateFormat;switch(this.type){case"":case"auto":case undefined:B=function(F){return F};break;case"string":B=function(F){return(F===undefined||F===null)?"":String(F)};break;case"int":B=function(F){return F!==undefined&&F!==null&&F!==""?parseInt(String(F).replace(E,""),10):""};break;case"float":B=function(F){return F!==undefined&&F!==null&&F!==""?parseFloat(String(F).replace(E,""),10):""};break;case"bool":case"boolean":B=function(F){return F===true||F==="true"||F==1};break;case"date":B=function(G){if(!G){return""}if(Ext.isDate(G)){return G}if(A){if(A=="timestamp"){return new Date(G*1000)}if(A=="time"){return new Date(parseInt(G,10))}return Date.parseDate(G,A)}var F=Date.parse(G);return F?new Date(F):null};break}this.convert=B}};Ext.data.Field.prototype={dateFormat:null,defaultValue:"",mapping:null,sortType:null,sortDir:"ASC"};