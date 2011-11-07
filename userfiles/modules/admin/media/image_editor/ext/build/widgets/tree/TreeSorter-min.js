/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.tree.TreeSorter=function(B,C){Ext.apply(this,C);B.on("beforechildrenrendered",this.doSort,this);B.on("append",this.updateSort,this);B.on("insert",this.updateSort,this);B.on("textchange",this.updateSortParent,this);var E=this.dir&&this.dir.toLowerCase()=="desc";var F=this.property||"text";var G=this.sortType;var A=this.folderSort;var D=this.caseSensitive===true;var H=this.leafAttr||"leaf";this.sortFn=function(J,I){if(A){if(J.attributes[H]&&!I.attributes[H]){return 1}if(!J.attributes[H]&&I.attributes[H]){return -1}}var L=G?G(J):(D?J.attributes[F]:J.attributes[F].toUpperCase());var K=G?G(I):(D?I.attributes[F]:I.attributes[F].toUpperCase());if(L<K){return E?+1:-1}else{if(L>K){return E?-1:+1}else{return 0}}}};Ext.tree.TreeSorter.prototype={doSort:function(A){A.sort(this.sortFn)},compareNodes:function(B,A){return(B.text.toUpperCase()>A.text.toUpperCase()?1:-1)},updateSort:function(A,B){if(B.childrenRendered){this.doSort.defer(1,this,[B])}},updateSortParent:function(A){var B=A.parentNode;if(B&&B.childrenRendered){this.doSort.defer(1,this,[B])}}};