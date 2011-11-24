/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.Accordion=Ext.extend(Ext.layout.FitLayout,{fill:true,autoWidth:true,titleCollapse:true,hideCollapseTool:false,collapseFirst:false,animate:false,sequence:false,activeOnTop:false,renderItem:function(A){if(this.animate===false){A.animCollapse=false}A.collapsible=true;if(this.autoWidth){A.autoWidth=true}if(this.titleCollapse){A.titleCollapse=true}if(this.hideCollapseTool){A.hideCollapseTool=true}if(this.collapseFirst!==undefined){A.collapseFirst=this.collapseFirst}if(!this.activeItem&&!A.collapsed){this.activeItem=A}else{if(this.activeItem){A.collapsed=true}}Ext.layout.Accordion.superclass.renderItem.apply(this,arguments);A.header.addClass("x-accordion-hd");A.on("beforeexpand",this.beforeExpand,this)},beforeExpand:function(C,B){var A=this.activeItem;if(A){if(this.sequence){delete this.activeItem;if(!A.collapsed){A.collapse({callback:function(){C.expand(B||true)},scope:this});return false}}else{A.collapse(this.animate)}}this.activeItem=C;if(this.activeOnTop){C.el.dom.parentNode.insertBefore(C.el.dom,C.el.dom.parentNode.firstChild)}this.layout()},setItemSize:function(F,E){if(this.fill&&F){var B=this.container.items.items;var D=0;for(var C=0,A=B.length;C<A;C++){var G=B[C];if(G!=F){D+=(G.getSize().height-G.bwrap.getHeight())}}E.height-=D;F.setSize(E)}}});Ext.Container.LAYOUTS["accordion"]=Ext.layout.Accordion;