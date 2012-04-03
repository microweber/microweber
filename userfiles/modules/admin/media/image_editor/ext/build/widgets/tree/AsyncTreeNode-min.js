/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.tree.AsyncTreeNode=function(A){this.loaded=A&&A.loaded===true;this.loading=false;Ext.tree.AsyncTreeNode.superclass.constructor.apply(this,arguments);this.addEvents("beforeload","load")};Ext.extend(Ext.tree.AsyncTreeNode,Ext.tree.TreeNode,{expand:function(B,D,F){if(this.loading){var E;var C=function(){if(!this.loading){clearInterval(E);this.expand(B,D,F)}}.createDelegate(this);E=setInterval(C,200);return }if(!this.loaded){if(this.fireEvent("beforeload",this)===false){return }this.loading=true;this.ui.beforeLoad(this);var A=this.loader||this.attributes.loader||this.getOwnerTree().getLoader();if(A){A.load(this,this.loadComplete.createDelegate(this,[B,D,F]));return }}Ext.tree.AsyncTreeNode.superclass.expand.call(this,B,D,F)},isLoading:function(){return this.loading},loadComplete:function(A,B,C){this.loading=false;this.loaded=true;this.ui.afterLoad(this);this.fireEvent("load",this);this.expand(A,B,C)},isLoaded:function(){return this.loaded},hasChildNodes:function(){if(!this.isLeaf()&&!this.loaded){return true}else{return Ext.tree.AsyncTreeNode.superclass.hasChildNodes.call(this)}},reload:function(A){this.collapse(false,false);while(this.firstChild){this.removeChild(this.firstChild)}this.childrenRendered=false;this.loaded=false;if(this.isHiddenRoot()){this.expanded=false}this.expand(false,false,A)}});Ext.tree.TreePanel.nodeTypes.async=Ext.tree.AsyncTreeNode;