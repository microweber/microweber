/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.Registry=function(){var D={};var B={};var A=0;var C=function(F,E){if(typeof F=="string"){return F}var G=F.id;if(!G&&E!==false){G="extdd-"+(++A);F.id=G}return G};return{register:function(H,I){I=I||{};if(typeof H=="string"){H=document.getElementById(H)}I.ddel=H;D[C(H)]=I;if(I.isHandle!==false){B[I.ddel.id]=I}if(I.handles){var G=I.handles;for(var F=0,E=G.length;F<E;F++){B[C(G[F])]=I}}},unregister:function(H){var J=C(H,false);var I=D[J];if(I){delete D[J];if(I.handles){var G=I.handles;for(var F=0,E=G.length;F<E;F++){delete B[C(G[F],false)]}}}},getHandle:function(E){if(typeof E!="string"){E=E.id}return B[E]},getHandleFromEvent:function(F){var E=Ext.lib.Event.getTarget(F);return E?B[E.id]:null},getTarget:function(E){if(typeof E!="string"){E=E.id}return D[E]},getTargetFromEvent:function(F){var E=Ext.lib.Event.getTarget(F);return E?D[E.id]||B[E.id]:null}}}();