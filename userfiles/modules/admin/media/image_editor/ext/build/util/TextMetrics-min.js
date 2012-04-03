/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.util.TextMetrics=function(){var A;return{measure:function(B,C,D){if(!A){A=Ext.util.TextMetrics.Instance(B,D)}A.bind(B);A.setFixedWidth(D||"auto");return A.getSize(C)},createInstance:function(B,C){return Ext.util.TextMetrics.Instance(B,C)}}}();Ext.util.TextMetrics.Instance=function(B,D){var C=new Ext.Element(document.createElement("div"));document.body.appendChild(C.dom);C.position("absolute");C.setLeftTop(-1000,-1000);C.hide();if(D){C.setWidth(D)}var A={getSize:function(F){C.update(F);var E=C.getSize();C.update("");return E},bind:function(E){C.setStyle(Ext.fly(E).getStyles("font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"))},setFixedWidth:function(E){C.setWidth(E)},getWidth:function(E){C.dom.style.width="auto";return this.getSize(E).width},getHeight:function(E){return this.getSize(E).height}};A.bind(B);return A};Ext.Element.measureText=Ext.util.TextMetrics.measure;