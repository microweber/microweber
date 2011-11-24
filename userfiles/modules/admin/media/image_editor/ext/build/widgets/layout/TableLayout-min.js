/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.TableLayout=Ext.extend(Ext.layout.ContainerLayout,{monitorResize:false,setContainer:function(A){Ext.layout.TableLayout.superclass.setContainer.call(this,A);this.currentRow=0;this.currentColumn=0;this.cells=[]},onLayout:function(C,E){var D=C.items.items,A=D.length,F,B;if(!this.table){E.addClass("x-table-layout-ct");this.table=E.createChild({tag:"table",cls:"x-table-layout",cellspacing:0,cn:{tag:"tbody"}},null,true);this.renderAll(C,E)}},getRow:function(A){var B=this.table.tBodies[0].childNodes[A];if(!B){B=document.createElement("tr");this.table.tBodies[0].appendChild(B)}return B},getNextCell:function(H){var A=this.getNextNonSpan(this.currentColumn,this.currentRow);var E=this.currentColumn=A[0],D=this.currentRow=A[1];for(var G=D;G<D+(H.rowspan||1);G++){if(!this.cells[G]){this.cells[G]=[]}for(var C=E;C<E+(H.colspan||1);C++){this.cells[G][C]=true}}var F=document.createElement("td");if(H.cellId){F.id=H.cellId}var B="x-table-layout-cell";if(H.cellCls){B+=" "+H.cellCls}F.className=B;if(H.colspan){F.colSpan=H.colspan}if(H.rowspan){F.rowSpan=H.rowspan}this.getRow(D).appendChild(F);return F},getNextNonSpan:function(A,C){var B=this.columns;while((B&&A>=B)||(this.cells[C]&&this.cells[C][A])){if(B&&A>=B){C++;A=0}else{A++}}return[A,C]},renderItem:function(C,A,B){if(C&&!C.rendered){C.render(this.getNextCell(C))}},isValidParent:function(B,A){return true}});Ext.Container.LAYOUTS["table"]=Ext.layout.TableLayout;