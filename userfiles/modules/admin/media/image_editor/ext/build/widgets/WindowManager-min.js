/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.WindowGroup=function(){var F={};var D=[];var E=null;var C=function(I,H){return(!I._lastAccess||I._lastAccess<H._lastAccess)?-1:1};var G=function(){var J=D,H=J.length;if(H>0){J.sort(C);var I=J[0].manager.zseed;for(var K=0;K<H;K++){var L=J[K];if(L&&!L.hidden){L.setZIndex(I+(K*10))}}}A()};var B=function(H){if(H!=E){if(E){E.setActive(false)}E=H;if(H){H.setActive(true)}}};var A=function(){for(var H=D.length-1;H>=0;--H){if(!D[H].hidden){B(D[H]);return }}B(null)};return{zseed:9000,register:function(H){F[H.id]=H;D.push(H);H.on("hide",A)},unregister:function(H){delete F[H.id];H.un("hide",A);D.remove(H)},get:function(H){return typeof H=="object"?H:F[H]},bringToFront:function(H){H=this.get(H);if(H!=E){H._lastAccess=new Date().getTime();G();return true}return false},sendToBack:function(H){H=this.get(H);H._lastAccess=-(new Date().getTime());G();return H},hideAll:function(){for(var H in F){if(F[H]&&typeof F[H]!="function"&&F[H].isVisible()){F[H].hide()}}},getActive:function(){return E},getBy:function(J,I){var K=[];for(var H=D.length-1;H>=0;--H){var L=D[H];if(J.call(I||L,L)!==false){K.push(L)}}return K},each:function(I,H){for(var J in F){if(F[J]&&typeof F[J]!="function"){if(I.call(H||F[J],F[J])===false){return }}}}}};Ext.WindowMgr=new Ext.WindowGroup();