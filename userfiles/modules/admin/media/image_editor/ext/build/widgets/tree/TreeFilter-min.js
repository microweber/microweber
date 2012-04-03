/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.tree.TreeFilter=function(A,B){this.tree=A;this.filtered={};Ext.apply(this,B)};Ext.tree.TreeFilter.prototype={clearBlank:false,reverse:false,autoClear:false,remove:false,filter:function(D,A,B){A=A||"text";var C;if(typeof D=="string"){var E=D.length;if(E==0&&this.clearBlank){this.clear();return }D=D.toLowerCase();C=function(F){return F.attributes[A].substr(0,E).toLowerCase()==D}}else{if(D.exec){C=function(F){return D.test(F.attributes[A])}}else{throw"Illegal filter type, must be string or regex"}}this.filterBy(C,null,B)},filterBy:function(D,C,B){B=B||this.tree.root;if(this.autoClear){this.clear()}var A=this.filtered,H=this.reverse;var E=function(J){if(J==B){return true}if(A[J.id]){return false}var I=D.call(C||J,J);if(!I||H){A[J.id]=J;J.ui.hide();return false}return true};B.cascade(E);if(this.remove){for(var G in A){if(typeof G!="function"){var F=A[G];if(F&&F.parentNode){F.parentNode.removeChild(F)}}}}},clear:function(){var B=this.tree;var A=this.filtered;for(var D in A){if(typeof D!="function"){var C=A[D];if(C){C.ui.show()}}}this.filtered={}}};