
function kfm_addToSelection(id){id=parseInt(id);if(!id||selectedFiles.indexOf(id)!=-1)return;selectedFiles.push(id);document.getElementById('kfm_file_icon_'+id).className+=' selected';kfm_selectionCheck();}
function kfm_isFileSelected(filename){return kfm_inArray(filename,selectedFiles);}
function kfm_removeFromSelection(id){if(!id)return;var i;for(i=0;i<selectedFiles.length;++i){if(selectedFiles[i]==id){$j('#kfm_file_icon_'+id).removeClass('selected');kfm_selectionCheck();return selectedFiles.splice(i,1);}}}
function kfm_selectAll(){kfm_selectNone();var a,b=document.getElementById('documents_body').fileids;for(a=0;a<b.length;++a)kfm_addToSelection(b[a]);}
function kfm_selectInvert(){var a,b=document.getElementById('documents_body').fileids;for(a=0;a<b.length;++a)if(kfm_isFileSelected(b[a]))kfm_removeFromSelection(b[a]);else kfm_addToSelection(b[a]);}
function kfm_selectNone(){if(kfm_lastClicked){$j('#kfm_file_icon_'+kfm_lastClicked).removeClass('last_clicked');}
for(var i=selectedFiles.length;i>-1;--i)kfm_removeFromSelection(selectedFiles[i]);kfm_lastClicked=0;kfm_selectionCheck();}
function kfm_selectionCheck(){if(selectedFiles.length==1){var el=$j('#kfm_file_details_panel div.kfm_panel_body')[0];if(el)el.innerHTML='loading';kfm_run_delayed('file_details','if(selectedFiles.length==1)kfm_showFileDetails(selectedFiles[0]);');}
else kfm_run_delayed('file_details','if(!selectedFiles.length)kfm_showFileDetails();');}
function kfm_selection_drag(e){if(!window.dragType||window.dragType!=2||!window.drag_wrapper)return;clearSelections();var p1={x:e.pageX,y:e.pageY},p2=window.drag_wrapper.orig;var x1=p1.x>p2.x?p2.x:p1.x;var x2=p2.x>p1.x?p2.x:p1.x;var y1=p1.y>p2.y?p2.y:p1.y;var y2=p2.y>p1.y?p2.y:p1.y;window.drag_wrapper.style.display='block';window.drag_wrapper.style.left=x1+'px';window.drag_wrapper.style.top=y1+'px'
window.drag_wrapper.style.width=(x2-x1)+'px';window.drag_wrapper.style.height=(y2-y1)+'px';window.drag_wrapper.style.zIndex=4;}
llStubs.push('kfm_selection_dragFinish');function kfm_selection_dragStart(e){if(window.dragType)return;if(!kfm_vars.use_templates&&window.mouseAt.x>document.getElementById('kfm_right_column').scrollWidth+document.getElementById('kfm_left_column').scrollWidth-15)return;window.dragType=2;$j.event.add(document,'mouseup',kfm_selection_dragFinish);window.drag_wrapper=document.createElement('div');window.drag_wrapper.id='kfm_selection_drag_wrapper';window.drag_wrapper.style.display='none';window.drag_wrapper.orig=window.mouseAt;kfm.addEl(document.body,window.drag_wrapper);$j.event.add(document,'mousemove',kfm_selection_drag);}
function kfm_shiftFileSelectionLR(dir){if(selectedFiles.length>1)return;var na=document.getElementById('documents_body').fileids,a=0,ns=na.length;if(selectedFiles.length){for(;a<ns;++a)if(na[a]==selectedFiles[0])break;if(dir>0){if(a==ns-1)a=-1}
else if(!a)a=ns;}
else a=dir>0?-1:ns;kfm_selectSingleFile(na[a+dir]);}
function kfm_shiftFileSelectionUD(dir){if(selectedFiles.length>1)return;var na=document.getElementById('documents_body').fileids,a=0,ns=na.length,icons_per_line=0,topOffset=document.getElementById('kfm_file_icon_'+na[0]).offsetTop;if(selectedFiles.length){if(topOffset==document.getElementById('kfm_file_icon_'+na[ns-1]).offsetTop)return;for(;document.getElementById('kfm_file_icon_'+na[icons_per_line]).offsetTop==topOffset;++icons_per_line);for(;a<ns;++a)if(na[a]==selectedFiles[0])break;a+=icons_per_line*dir;if(a>=ns)a=ns-1;if(a<0)a=0;}
else a=dir>0?0:ns-1;kfm_selectSingleFile(na[a]);}
function kfm_toggleSelectedFile(e){var row;if(e.type=="contextmenu"||e.button==2)return;e.stopPropagation();kfm_closeContextMenu();if(window.dragAddedFileToSelection){window.dragAddedFileToSelection=false;return;}
var el=e.target;while(el.tagName!='DIV')el=el.parentNode;var id=el.file_id;if(kfm_listview){row=el;while(row.nodeName!='TR')row=row.parentNode;rowInd=row.rowIndex;}
if(kfm_lastClicked){var el=document.getElementById('kfm_file_icon_'+kfm_lastClicked);if(el)$j(el).removeClass('last_clicked');else kfm_lastClicked=0;}
if(kfm_lastClicked&&e.shift){var e=kfm_lastClicked;if(kfm_listview){row=el;while(row.nodeName!='TR')row=row.parentNode;smalRow=Math.min(row.rowIndex,rowInd);bigRow=Math.max(row.rowIndex,rowInd);$j('#kfm_files_listview_table tbody tr:lt('+bigRow+')').each(function(){if(this.rowIndex>=smalRow)kfm_addToSelection(this.fileid);});}else{clearSelections(e);kfm_selectNone();var a=document.getElementById('documents_body').fileids,b,c,d;for(b=0;b<a.length;++b){if(a[b]==e)c=b;if(a[b]==id)d=parseInt(b);}
if(c>d){b=c;c=d;d=b;}
for(;c<=d;++c)kfm_addToSelection(a[c]);}}
else{if(kfm_isFileSelected(id)){if(!e.control)kfm_selectNone();else kfm_removeFromSelection(id);}
else{if(!e.control&&!e.meta)kfm_selectNone();kfm_addToSelection(id);}}
kfm_lastClicked=id;document.getElementById('kfm_file_icon_'+id).className+=' last_clicked';}
function kfm_selectSingleFile(id){kfm_selectNone();kfm_addToSelection(id);var panel=document.getElementById('kfm_right_column'),el=document.getElementById('kfm_file_icon_'+id);var offset=panel.scrollTop,panelHeight=panel.offsetHeight,elTop=getOffset(el,'Top'),elHeight=el.offsetHeight;if(elTop+elHeight-offset>panelHeight)panel.scrollTop=elTop-panelHeight+elHeight;else if(elTop<offset)panel.scrollTop=elTop;}