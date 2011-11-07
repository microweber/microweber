/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.ColumnLayout=Ext.extend(Ext.layout.ContainerLayout,{monitorResize:true,extraCls:"x-column",scrollOffset:0,isValidParent:function(B,A){return B.getEl().dom.parentNode==this.innerCt.dom},onLayout:function(C,F){var D=C.items.items,E=D.length,G,A;if(!this.innerCt){F.addClass("x-column-layout-ct");this.innerCt=F.createChild({cls:"x-column-inner"});this.innerCt.createChild({cls:"x-clear"})}this.renderAll(C,this.innerCt);var J=Ext.isIE&&F.dom!=Ext.getBody().dom?F.getStyleSize():F.getViewSize();if(J.width<1&&J.height<1){return }var H=J.width-F.getPadding("lr")-this.scrollOffset,B=J.height-F.getPadding("tb"),I=H;this.innerCt.setWidth(H);for(A=0;A<E;A++){G=D[A];if(!G.columnWidth){I-=(G.getSize().width+G.getEl().getMargins("lr"))}}I=I<0?0:I;for(A=0;A<E;A++){G=D[A];if(G.columnWidth){G.setSize(Math.floor(G.columnWidth*I)-G.getEl().getMargins("lr"))}}}});Ext.Container.LAYOUTS["column"]=Ext.layout.ColumnLayout;