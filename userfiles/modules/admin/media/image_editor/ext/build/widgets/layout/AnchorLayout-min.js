/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.AnchorLayout=Ext.extend(Ext.layout.ContainerLayout,{monitorResize:true,getAnchorViewSize:function(A,B){return B.dom==document.body?B.getViewSize():B.getStyleSize()},onLayout:function(F,I){Ext.layout.AnchorLayout.superclass.onLayout.call(this,F,I);var O=this.getAnchorViewSize(F,I);var M=O.width,E=O.height;if(M<20||E<20){return }var B,K;if(F.anchorSize){if(typeof F.anchorSize=="number"){B=F.anchorSize}else{B=F.anchorSize.width;K=F.anchorSize.height}}else{B=F.initialConfig.width;K=F.initialConfig.height}var H=F.items.items,G=H.length,D,J,L,C,A;for(D=0;D<G;D++){J=H[D];if(J.anchor){L=J.anchorSpec;if(!L){var N=J.anchor.split(" ");J.anchorSpec=L={right:this.parseAnchor(N[0],J.initialConfig.width,B),bottom:this.parseAnchor(N[1],J.initialConfig.height,K)}}C=L.right?this.adjustWidthAnchor(L.right(M),J):undefined;A=L.bottom?this.adjustHeightAnchor(L.bottom(E),J):undefined;if(C||A){J.setSize(C||undefined,A||undefined)}}}},parseAnchor:function(B,F,A){if(B&&B!="none"){var D;if(/^(r|right|b|bottom)$/i.test(B)){var E=A-F;return function(G){if(G!==D){D=G;return G-E}}}else{if(B.indexOf("%")!=-1){var C=parseFloat(B.replace("%",""))*0.01;return function(G){if(G!==D){D=G;return Math.floor(G*C)}}}else{B=parseInt(B,10);if(!isNaN(B)){return function(G){if(G!==D){D=G;return G+B}}}}}}return false},adjustWidthAnchor:function(B,A){return B},adjustHeightAnchor:function(B,A){return B}});Ext.Container.LAYOUTS["anchor"]=Ext.layout.AnchorLayout;