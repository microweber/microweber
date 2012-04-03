/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.util.DelayedTask=function(E,D,A){var G=null,F,B;var C=function(){var H=new Date().getTime();if(H-B>=F){clearInterval(G);G=null;E.apply(D,A||[])}};this.delay=function(I,K,J,H){if(G&&I!=F){this.cancel()}F=I;B=new Date().getTime();E=K||E;D=J||D;A=H||A;if(!G){G=setInterval(C,F)}};this.cancel=function(){if(G){clearInterval(G);G=null}}};