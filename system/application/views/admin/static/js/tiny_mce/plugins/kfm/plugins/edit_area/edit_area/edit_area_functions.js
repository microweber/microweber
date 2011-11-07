
EditArea.prototype.replace_tab=function(text){return text.replace(/((\n?)([^\t\n]*)\t)/gi,editArea.smartTab);};EditArea.prototype.smartTab=function(){val="                   ";return EditArea.prototype.smartTab.arguments[2]+EditArea.prototype.smartTab.arguments[3]+val.substr(0,editArea.tab_nb_char-(EditArea.prototype.smartTab.arguments[3].length)%editArea.tab_nb_char);};EditArea.prototype.show_waiting_screen=function(){width=this.editor_area.offsetWidth;height=this.editor_area.offsetHeight;if(!(this.isIE&&this.isIE<6))
{width-=2;height-=2;}
this.processing_screen.style.display="block";this.processing_screen.style.width=width+"px";this.processing_screen.style.height=height+"px";this.waiting_screen_displayed=true;};EditArea.prototype.hide_waiting_screen=function(){this.processing_screen.style.display="none";this.waiting_screen_displayed=false;};EditArea.prototype.add_style=function(styles){if(styles.length>0){newcss=document.createElement("style");newcss.type="text/css";newcss.media="all";if(newcss.styleSheet){newcss.styleSheet.cssText=styles;}else{newcss.appendChild(document.createTextNode(styles));}
document.getElementsByTagName("head")[0].appendChild(newcss);}};EditArea.prototype.set_font=function(family,size){var t=this,a=this.textarea,s=this.settings,elem_font,i,elem;var elems=["textarea","content_highlight","cursor_pos","end_bracket","selection_field","selection_field_text","line_number"];if(family&&family!="")
s["font_family"]=family;if(size&&size>0)
s["font_size"]=size;if(t.isOpera&&t.isOpera<9.6)
s['font_family']="monospace";if(elem_font=_$("area_font_size"))
{for(i=0;i<elem_font.length;i++)
{if(elem_font.options[i].value&&elem_font.options[i].value==s["font_size"])
elem_font.options[i].selected=true;}}
if(t.isFirefox)
{var nbTry=3;do{var div1=document.createElement('div'),text1=document.createElement('textarea');var styles={width:'40px',overflow:'scroll',zIndex:50,visibility:'hidden',fontFamily:s["font_family"],fontSize:s["font_size"]+"pt",lineHeight:t.lineHeight+"px",padding:'0',margin:'0',border:'none',whiteSpace:'nowrap'};var diff,changed=false;for(i in styles)
{div1.style[i]=styles[i];text1.style[i]=styles[i];}
text1.wrap='off';text1.setAttribute('wrap','off');t.container.appendChild(div1);t.container.appendChild(text1);div1.innerHTML=text1.value='azertyuiopqsdfghjklm';div1.innerHTML=text1.value=text1.value+'wxcvbn^p*ù$!:;,,';diff=text1.scrollWidth-div1.scrollWidth;if(Math.abs(diff)>=2)
{s["font_size"]++;changed=true;}
t.container.removeChild(div1);t.container.removeChild(text1);nbTry--;}while(changed&&nbTry>0);}
elem=t.test_font_size;elem.style.fontFamily=""+s["font_family"];elem.style.fontSize=s["font_size"]+"pt";elem.innerHTML="0";t.lineHeight=elem.offsetHeight;for(i=0;i<elems.length;i++)
{elem=_$(elems[i]);elem.style.fontFamily=s["font_family"];elem.style.fontSize=s["font_size"]+"pt";elem.style.lineHeight=t.lineHeight+"px";}
t.add_style("pre{font-family:"+s["font_family"]+"}");if((t.isOpera&&t.isOpera<9.6)||t.isIE>=8)
{var parNod=a.parentNode,nxtSib=a.nextSibling,start=a.selectionStart,end=a.selectionEnd;parNod.removeChild(a);parNod.insertBefore(a,nxtSib);t.area_select(start,end-start);}
this.focus();this.update_size();this.check_line_selection();};EditArea.prototype.change_font_size=function(){var size=_$("area_font_size").value;if(size>0)
this.set_font("",size);};EditArea.prototype.open_inline_popup=function(popup_id){this.close_all_inline_popup();var popup=_$(popup_id);var editor=_$("editor");for(var i=0;i<this.inlinePopup.length;i++){if(this.inlinePopup[i]["popup_id"]==popup_id){var icon=_$(this.inlinePopup[i]["icon_id"]);if(icon){this.switchClassSticky(icon,'editAreaButtonSelected',true);break;}}}
popup.style.height="auto";popup.style.overflow="visible";if(document.body.offsetHeight<popup.offsetHeight){popup.style.height=(document.body.offsetHeight-10)+"px";popup.style.overflow="auto";}
if(!popup.positionned){var new_left=editor.offsetWidth/2-popup.offsetWidth/2;var new_top=editor.offsetHeight/2-popup.offsetHeight/2;popup.style.left=new_left+"px";popup.style.top=new_top+"px";popup.positionned=true;}
popup.style.visibility="visible";};EditArea.prototype.close_inline_popup=function(popup_id){var popup=_$(popup_id);for(var i=0;i<this.inlinePopup.length;i++){if(this.inlinePopup[i]["popup_id"]==popup_id){var icon=_$(this.inlinePopup[i]["icon_id"]);if(icon){this.switchClassSticky(icon,'editAreaButtonNormal',false);break;}}}
popup.style.visibility="hidden";};EditArea.prototype.close_all_inline_popup=function(e){for(var i=0;i<this.inlinePopup.length;i++){this.close_inline_popup(this.inlinePopup[i]["popup_id"]);}
this.textarea.focus();};EditArea.prototype.show_help=function(){this.open_inline_popup("edit_area_help");};EditArea.prototype.new_document=function(){this.textarea.value="";this.area_select(0,0);};EditArea.prototype.get_all_toolbar_height=function(){var area=_$("editor");var results=parent.getChildren(area,"div","class","area_toolbar","all","0");var height=0;for(var i=0;i<results.length;i++){height+=results[i].offsetHeight;}
return height;};EditArea.prototype.go_to_line=function(line){if(!line)
{var icon=_$("go_to_line");if(icon!=null){this.restoreClass(icon);this.switchClassSticky(icon,'editAreaButtonSelected',true);}
line=prompt(this.get_translation("go_to_line_prompt"));if(icon!=null)
this.switchClassSticky(icon,'editAreaButtonNormal',false);}
if(line&&line!=null&&line.search(/^[0-9]+$/)!=-1){var start=0;var lines=this.textarea.value.split("\n");if(line>lines.length)
start=this.textarea.value.length;else{for(var i=0;i<Math.min(line-1,lines.length);i++)
start+=lines[i].length+1;}
this.area_select(start,0);}};EditArea.prototype.change_smooth_selection_mode=function(setTo){if(this.do_highlight)
return;if(setTo!=null){if(setTo===false)
this.smooth_selection=true;else
this.smooth_selection=false;}
var icon=_$("change_smooth_selection");this.textarea.focus();if(this.smooth_selection===true){this.switchClassSticky(icon,'editAreaButtonNormal',false);this.smooth_selection=false;this.selection_field.style.display="none";_$("cursor_pos").style.display="none";_$("end_bracket").style.display="none";}else{this.switchClassSticky(icon,'editAreaButtonSelected',false);this.smooth_selection=true;this.selection_field.style.display="block";_$("cursor_pos").style.display="block";_$("end_bracket").style.display="block";}};EditArea.prototype.scroll_to_view=function(show){var zone,lineElem;if(!this.smooth_selection)
return;zone=_$("result");var cursor_pos_top=_$("cursor_pos").cursor_top;if(show=="bottom")
{cursor_pos_top+=this.getLinePosTop(this.last_selection['line_start']+this.last_selection['line_nb']-1);}
var max_height_visible=zone.clientHeight+zone.scrollTop;var miss_top=cursor_pos_top+this.lineHeight-max_height_visible;if(miss_top>0){zone.scrollTop=zone.scrollTop+miss_top;}else if(zone.scrollTop>cursor_pos_top){zone.scrollTop=cursor_pos_top;}
var cursor_pos_left=_$("cursor_pos").cursor_left;var max_width_visible=zone.clientWidth+zone.scrollLeft;var miss_left=cursor_pos_left+10-max_width_visible;if(miss_left>0){zone.scrollLeft=zone.scrollLeft+miss_left+50;}else if(zone.scrollLeft>cursor_pos_left){zone.scrollLeft=cursor_pos_left;}else if(zone.scrollLeft==45){zone.scrollLeft=0;}};EditArea.prototype.check_undo=function(only_once){if(!editAreas[this.id])
return false;if(this.textareaFocused&&editAreas[this.id]["displayed"]==true){var text=this.textarea.value;if(this.previous.length<=1)
this.switchClassSticky(_$("undo"),'editAreaButtonDisabled',true);if(!this.previous[this.previous.length-1]||this.previous[this.previous.length-1]["text"]!=text){this.previous.push({"text":text,"selStart":this.textarea.selectionStart,"selEnd":this.textarea.selectionEnd});if(this.previous.length>this.settings["max_undo"]+1)
this.previous.shift();}
if(this.previous.length>=2)
this.switchClassSticky(_$("undo"),'editAreaButtonNormal',false);}
if(!only_once)
setTimeout("editArea.check_undo()",3000);};EditArea.prototype.undo=function(){if(this.previous.length>0)
{this.getIESelection();this.next.push({"text":this.textarea.value,"selStart":this.textarea.selectionStart,"selEnd":this.textarea.selectionEnd});var prev=this.previous.pop();if(prev["text"]==this.textarea.value&&this.previous.length>0)
prev=this.previous.pop();this.textarea.value=prev["text"];this.last_undo=prev["text"];this.area_select(prev["selStart"],prev["selEnd"]-prev["selStart"]);this.switchClassSticky(_$("redo"),'editAreaButtonNormal',false);this.resync_highlight(true);this.check_file_changes();}};EditArea.prototype.redo=function(){if(this.next.length>0)
{var next=this.next.pop();this.previous.push(next);this.textarea.value=next["text"];this.last_undo=next["text"];this.area_select(next["selStart"],next["selEnd"]-next["selStart"]);this.switchClassSticky(_$("undo"),'editAreaButtonNormal',false);this.resync_highlight(true);this.check_file_changes();}
if(this.next.length==0)
this.switchClassSticky(_$("redo"),'editAreaButtonDisabled',true);};EditArea.prototype.check_redo=function(){if(editArea.next.length==0||editArea.textarea.value!=editArea.last_undo){editArea.next=[];editArea.switchClassSticky(_$("redo"),'editAreaButtonDisabled',true);}
else
{this.switchClassSticky(_$("redo"),'editAreaButtonNormal',false);}};EditArea.prototype.switchClass=function(element,class_name,lock_state){var lockChanged=false;if(typeof(lock_state)!="undefined"&&element!=null){element.classLock=lock_state;lockChanged=true;}
if(element!=null&&(lockChanged||!element.classLock)){element.oldClassName=element.className;element.className=class_name;}};EditArea.prototype.restoreAndSwitchClass=function(element,class_name){if(element!=null&&!element.classLock){this.restoreClass(element);this.switchClass(element,class_name);}};EditArea.prototype.restoreClass=function(element){if(element!=null&&element.oldClassName&&!element.classLock){element.className=element.oldClassName;element.oldClassName=null;}};EditArea.prototype.setClassLock=function(element,lock_state){if(element!=null)
element.classLock=lock_state;};EditArea.prototype.switchClassSticky=function(element,class_name,lock_state){var lockChanged=false;if(typeof(lock_state)!="undefined"&&element!=null){element.classLock=lock_state;lockChanged=true;}
if(element!=null&&(lockChanged||!element.classLock)){element.className=class_name;element.oldClassName=class_name;}};EditArea.prototype.scroll_page=function(params){var dir=params["dir"],shift_pressed=params["shift"];var lines=this.textarea.value.split("\n");var new_pos=0,length=0,char_left=0,line_nb=0,curLine=0;var toScrollAmount=_$("result").clientHeight-30;var nbLineToScroll=0,diff=0;if(dir=="up"){nbLineToScroll=Math.ceil(toScrollAmount/this.lineHeight);for(i=this.last_selection["line_start"];i-diff>this.last_selection["line_start"]-nbLineToScroll;i--)
{if(elem=_$('line_'+i))
{diff+=Math.floor((elem.offsetHeight-1)/this.lineHeight);}}
nbLineToScroll-=diff;if(this.last_selection["selec_direction"]=="up"){for(line_nb=0;line_nb<Math.min(this.last_selection["line_start"]-nbLineToScroll,lines.length);line_nb++){new_pos+=lines[line_nb].length+1;}
char_left=Math.min(lines[Math.min(lines.length-1,line_nb)].length,this.last_selection["curr_pos"]-1);if(shift_pressed)
length=this.last_selection["selectionEnd"]-new_pos-char_left;this.area_select(new_pos+char_left,length);view="top";}else{view="bottom";for(line_nb=0;line_nb<Math.min(this.last_selection["line_start"]+this.last_selection["line_nb"]-1-nbLineToScroll,lines.length);line_nb++){new_pos+=lines[line_nb].length+1;}
char_left=Math.min(lines[Math.min(lines.length-1,line_nb)].length,this.last_selection["curr_pos"]-1);if(shift_pressed){start=Math.min(this.last_selection["selectionStart"],new_pos+char_left);length=Math.max(new_pos+char_left,this.last_selection["selectionStart"])-start;if(new_pos+char_left<this.last_selection["selectionStart"])
view="top";}else
start=new_pos+char_left;this.area_select(start,length);}}
else
{var nbLineToScroll=Math.floor(toScrollAmount/this.lineHeight);for(i=this.last_selection["line_start"];i+diff<this.last_selection["line_start"]+nbLineToScroll;i++)
{if(elem=_$('line_'+i))
{diff+=Math.floor((elem.offsetHeight-1)/this.lineHeight);}}
nbLineToScroll-=diff;if(this.last_selection["selec_direction"]=="down"){view="bottom";for(line_nb=0;line_nb<Math.min(this.last_selection["line_start"]+this.last_selection["line_nb"]-2+nbLineToScroll,lines.length);line_nb++){if(line_nb==this.last_selection["line_start"]-1)
char_left=this.last_selection["selectionStart"]-new_pos;new_pos+=lines[line_nb].length+1;}
if(shift_pressed){length=Math.abs(this.last_selection["selectionStart"]-new_pos);length+=Math.min(lines[Math.min(lines.length-1,line_nb)].length,this.last_selection["curr_pos"]);this.area_select(Math.min(this.last_selection["selectionStart"],new_pos),length);}else{this.area_select(new_pos+char_left,0);}}else{view="top";for(line_nb=0;line_nb<Math.min(this.last_selection["line_start"]+nbLineToScroll-1,lines.length,lines.length);line_nb++){if(line_nb==this.last_selection["line_start"]-1)
char_left=this.last_selection["selectionStart"]-new_pos;new_pos+=lines[line_nb].length+1;}
if(shift_pressed){length=Math.abs(this.last_selection["selectionEnd"]-new_pos-char_left);length+=Math.min(lines[Math.min(lines.length-1,line_nb)].length,this.last_selection["curr_pos"])-char_left-1;this.area_select(Math.min(this.last_selection["selectionEnd"],new_pos+char_left),length);if(new_pos+char_left>this.last_selection["selectionEnd"])
view="bottom";}else{this.area_select(new_pos+char_left,0);}}}
this.check_line_selection();this.scroll_to_view(view);};EditArea.prototype.start_resize=function(e){parent.editAreaLoader.resize["id"]=editArea.id;parent.editAreaLoader.resize["start_x"]=(e)?e.pageX:event.x+document.body.scrollLeft;parent.editAreaLoader.resize["start_y"]=(e)?e.pageY:event.y+document.body.scrollTop;if(editArea.isIE)
{editArea.textarea.focus();editArea.getIESelection();}
parent.editAreaLoader.resize["selectionStart"]=editArea.textarea.selectionStart;parent.editAreaLoader.resize["selectionEnd"]=editArea.textarea.selectionEnd;parent.editAreaLoader.start_resize_area();};EditArea.prototype.toggle_full_screen=function(to){var t=this,p=parent,a=t.textarea,html,frame,selStart,selEnd,old,icon;if(typeof(to)=="undefined")
to=!t.fullscreen['isFull'];old=t.fullscreen['isFull'];t.fullscreen['isFull']=to;icon=_$("fullscreen");selStart=t.textarea.selectionStart;selEnd=t.textarea.selectionEnd;html=p.document.getElementsByTagName("html")[0];frame=p.document.getElementById("frame_"+t.id);if(to&&to!=old)
{t.fullscreen['old_overflow']=p.get_css_property(html,"overflow");t.fullscreen['old_height']=p.get_css_property(html,"height");t.fullscreen['old_width']=p.get_css_property(html,"width");t.fullscreen['old_scrollTop']=html.scrollTop;t.fullscreen['old_scrollLeft']=html.scrollLeft;t.fullscreen['old_zIndex']=p.get_css_property(frame,"z-index");if(t.isOpera){html.style.height="100%";html.style.width="100%";}
html.style.overflow="hidden";html.scrollTop=0;html.scrollLeft=0;frame.style.position="absolute";frame.style.width=html.clientWidth+"px";frame.style.height=html.clientHeight+"px";frame.style.display="block";frame.style.zIndex="999999";frame.style.top="0px";frame.style.left="0px";frame.style.top="-"+p.calculeOffsetTop(frame)+"px";frame.style.left="-"+p.calculeOffsetLeft(frame)+"px";t.switchClassSticky(icon,'editAreaButtonSelected',false);t.fullscreen['allow_resize']=t.resize_allowed;t.allow_resize(false);if(t.isFirefox){p.editAreaLoader.execCommand(t.id,"update_size();");t.area_select(selStart,selEnd-selStart);t.scroll_to_view();t.focus();}else{setTimeout("parent.editAreaLoader.execCommand('"+t.id+"', 'update_size();');editArea.focus();",10);}}
else if(to!=old)
{frame.style.position="static";frame.style.zIndex=t.fullscreen['old_zIndex'];if(t.isOpera)
{html.style.height="auto";html.style.width="auto";html.style.overflow="auto";}
else if(t.isIE&&p!=top)
{html.style.overflow="auto";}
else
{html.style.overflow=t.fullscreen['old_overflow'];}
html.scrollTop=t.fullscreen['old_scrollTop'];html.scrollLeft=t.fullscreen['old_scrollLeft'];p.editAreaLoader.hide(t.id);p.editAreaLoader.show(t.id);t.switchClassSticky(icon,'editAreaButtonNormal',false);if(t.fullscreen['allow_resize'])
t.allow_resize(t.fullscreen['allow_resize']);if(t.isFirefox){t.area_select(selStart,selEnd-selStart);setTimeout("editArea.scroll_to_view();",10);}}};EditArea.prototype.allow_resize=function(allow){var resize=_$("resize_area");if(allow){resize.style.visibility="visible";parent.editAreaLoader.add_event(resize,"mouseup",editArea.start_resize);}else{resize.style.visibility="hidden";parent.editAreaLoader.remove_event(resize,"mouseup",editArea.start_resize);}
this.resize_allowed=allow;};EditArea.prototype.change_syntax=function(new_syntax,is_waiting){if(new_syntax==this.settings['syntax'])
return true;var founded=false;for(var i=0;i<this.syntax_list.length;i++)
{if(this.syntax_list[i]==new_syntax)
founded=true;}
if(founded==true)
{if(!parent.editAreaLoader.load_syntax[new_syntax])
{if(!is_waiting)
parent.editAreaLoader.load_script(parent.editAreaLoader.baseURL+"reg_syntax/"+new_syntax+".js");setTimeout("editArea.change_syntax('"+new_syntax+"', true);",100);this.show_waiting_screen();}
else
{if(!this.allready_used_syntax[new_syntax])
{parent.editAreaLoader.init_syntax_regexp();this.add_style(parent.editAreaLoader.syntax[new_syntax]["styles"]);this.allready_used_syntax[new_syntax]=true;}
var sel=_$("syntax_selection");if(sel&&sel.value!=new_syntax)
{for(var i=0;i<sel.length;i++){if(sel.options[i].value&&sel.options[i].value==new_syntax)
sel.options[i].selected=true;}}
this.settings['syntax']=new_syntax;this.resync_highlight(true);this.hide_waiting_screen();return true;}}
return false;};EditArea.prototype.set_editable=function(is_editable){if(is_editable)
{document.body.className="";this.textarea.readOnly=false;this.is_editable=true;}
else
{document.body.className="non_editable";this.textarea.readOnly=true;this.is_editable=false;}
if(editAreas[this.id]["displayed"]==true)
this.update_size();};EditArea.prototype.toggle_word_wrap=function(){this.set_word_wrap(!this.settings['word_wrap']);};EditArea.prototype.set_word_wrap=function(to){var t=this,a=t.textarea;if(t.isOpera&&t.isOpera<9.8)
{this.settings['word_wrap']=false;t.switchClassSticky(_$("word_wrap"),'editAreaButtonDisabled',true);return false;}
if(to)
{wrap_mode='soft';this.container.className+=' word_wrap';this.container.style.width="";this.content_highlight.style.width="";a.style.width="100%";if(t.isIE&&t.isIE<7)
{a.style.width=(a.offsetWidth-5)+"px";}
t.switchClassSticky(_$("word_wrap"),'editAreaButtonSelected',false);}
else
{wrap_mode='off';this.container.className=this.container.className.replace(/word_wrap/g,'');t.switchClassSticky(_$("word_wrap"),'editAreaButtonNormal',true);}
this.textarea.previous_scrollWidth='';this.textarea.previous_scrollHeight='';a.wrap=wrap_mode;a.setAttribute('wrap',wrap_mode);if(!this.isIE)
{var start=a.selectionStart,end=a.selectionEnd;var parNod=a.parentNode,nxtSib=a.nextSibling;parNod.removeChild(a);parNod.insertBefore(a,nxtSib);this.area_select(start,end-start);}
this.settings['word_wrap']=to;this.focus();this.update_size();this.check_line_selection();};EditArea.prototype.open_file=function(settings){if(settings['id']!="undefined")
{var id=settings['id'];var new_file={};new_file['id']=id;new_file['title']=id;new_file['text']="";new_file['last_selection']="";new_file['last_text_to_highlight']="";new_file['last_hightlighted_text']="";new_file['previous']=[];new_file['next']=[];new_file['last_undo']="";new_file['smooth_selection']=this.settings['smooth_selection'];new_file['do_highlight']=this.settings['start_highlight'];new_file['syntax']=this.settings['syntax'];new_file['scroll_top']=0;new_file['scroll_left']=0;new_file['selection_start']=0;new_file['selection_end']=0;new_file['edited']=false;new_file['font_size']=this.settings["font_size"];new_file['font_family']=this.settings["font_family"];new_file['word_wrap']=this.settings["word_wrap"];new_file['toolbar']={'links':{},'selects':{}};new_file['compare_edited_text']=new_file['text'];this.files[id]=new_file;this.update_file(id,settings);this.files[id]['compare_edited_text']=this.files[id]['text'];var html_id='tab_file_'+encodeURIComponent(id);this.filesIdAssoc[html_id]=id;this.files[id]['html_id']=html_id;if(!_$(this.files[id]['html_id'])&&id!="")
{this.tab_browsing_area.style.display="block";var elem=document.createElement('li');elem.id=this.files[id]['html_id'];var close="<img src=\""+parent.editAreaLoader.baseURL+"images/close.gif\" title=\""+this.get_translation('close_tab','word')+"\" onclick=\"editArea.execCommand('close_file', editArea.filesIdAssoc['"+html_id+"']);return false;\" class=\"hidden\" onmouseover=\"this.className=''\" onmouseout=\"this.className='hidden'\" />";elem.innerHTML="<a onclick=\"javascript:editArea.execCommand('switch_to_file', editArea.filesIdAssoc['"+html_id+"']);\" selec=\"none\"><b><span><strong class=\"edited\">*</strong>"+this.files[id]['title']+close+"</span></b></a>";_$('tab_browsing_list').appendChild(elem);var elem=document.createElement('text');this.update_size();}
if(id!="")
this.execCommand('file_open',this.files[id]);this.switch_to_file(id,true);return true;}
else
return false;};EditArea.prototype.close_file=function(id){if(this.files[id])
{this.save_file(id);if(this.execCommand('file_close',this.files[id])!==false)
{var li=_$(this.files[id]['html_id']);li.parentNode.removeChild(li);if(id==this.curr_file)
{var next_file="";var is_next=false;for(var i in this.files)
{if(is_next)
{next_file=i;break;}
else if(i==id)
is_next=true;else
next_file=i;}
this.switch_to_file(next_file);}
delete(this.files[id]);this.update_size();}}};EditArea.prototype.save_file=function(id){var t=this,save,a_links,a_selects,save_butt,img,i;if(t.files[id])
{var save=t.files[id];save['last_selection']=t.last_selection;save['last_text_to_highlight']=t.last_text_to_highlight;save['last_hightlighted_text']=t.last_hightlighted_text;save['previous']=t.previous;save['next']=t.next;save['last_undo']=t.last_undo;save['smooth_selection']=t.smooth_selection;save['do_highlight']=t.do_highlight;save['syntax']=t.settings['syntax'];save['text']=t.textarea.value;save['scroll_top']=t.result.scrollTop;save['scroll_left']=t.result.scrollLeft;save['selection_start']=t.last_selection["selectionStart"];save['selection_end']=t.last_selection["selectionEnd"];save['font_size']=t.settings["font_size"];save['font_family']=t.settings["font_family"];save['word_wrap']=t.settings["word_wrap"];save['toolbar']={'links':{},'selects':{}};a_links=_$("toolbar_1").getElementsByTagName("a");for(i=0;i<a_links.length;i++)
{if(a_links[i].getAttribute('fileSpecific')=='yes')
{save_butt={};img=a_links[i].getElementsByTagName('img')[0];save_butt['classLock']=img.classLock;save_butt['className']=img.className;save_butt['oldClassName']=img.oldClassName;save['toolbar']['links'][a_links[i].id]=save_butt;}}
a_selects=_$("toolbar_1").getElementsByTagName("select");for(i=0;i<a_selects.length;i++)
{if(a_selects[i].getAttribute('fileSpecific')=='yes')
{save['toolbar']['selects'][a_selects[i].id]=a_selects[i].value;}}
t.files[id]=save;return save;}
return false;};EditArea.prototype.update_file=function(id,new_values){for(var i in new_values)
{this.files[id][i]=new_values[i];}};EditArea.prototype.display_file=function(id){var t=this,a=t.textarea,new_file,a_lis,a_selects,a_links,a_options,i,j;if(id=='')
{a.readOnly=true;t.tab_browsing_area.style.display="none";_$("no_file_selected").style.display="block";t.result.className="empty";if(!t.files[''])
{t.open_file({id:''});}}
else if(typeof(t.files[id])=='undefined')
{return false;}
else
{t.result.className="";a.readOnly=!t.is_editable;_$("no_file_selected").style.display="none";t.tab_browsing_area.style.display="block";}
t.check_redo(true);t.check_undo(true);t.curr_file=id;a_lis=t.tab_browsing_area.getElementsByTagName('li');for(i=0;i<a_lis.length;i++)
{if(a_lis[i].id==t.files[id]['html_id'])
a_lis[i].className='selected';else
a_lis[i].className='';}
new_file=t.files[id];a.value=new_file['text'];t.set_font(new_file['font_family'],new_file['font_size']);t.area_select(new_file['selection_start'],new_file['selection_end']-new_file['selection_start']);t.manage_size(true);t.result.scrollTop=new_file['scroll_top'];t.result.scrollLeft=new_file['scroll_left'];t.previous=new_file['previous'];t.next=new_file['next'];t.last_undo=new_file['last_undo'];t.check_redo(true);t.check_undo(true);t.execCommand("change_highlight",new_file['do_highlight']);t.execCommand("change_syntax",new_file['syntax']);t.execCommand("change_smooth_selection_mode",new_file['smooth_selection']);t.execCommand("set_word_wrap",new_file['word_wrap']);a_links=new_file['toolbar']['links'];for(i in a_links)
{if(img=_$(i).getElementsByTagName('img')[0])
{img.classLock=a_links[i]['classLock'];img.className=a_links[i]['className'];img.oldClassName=a_links[i]['oldClassName'];}}
a_selects=new_file['toolbar']['selects'];for(i in a_selects)
{a_options=_$(i).options;for(j=0;j<a_options.length;j++)
{if(a_options[j].value==a_selects[i])
_$(i).options[j].selected=true;}}};EditArea.prototype.switch_to_file=function(file_to_show,force_refresh){if(file_to_show!=this.curr_file||force_refresh)
{this.save_file(this.curr_file);if(this.curr_file!='')
this.execCommand('file_switch_off',this.files[this.curr_file]);this.display_file(file_to_show);if(file_to_show!='')
this.execCommand('file_switch_on',this.files[file_to_show]);}};EditArea.prototype.get_file=function(id){if(id==this.curr_file)
this.save_file(id);return this.files[id];};EditArea.prototype.get_all_files=function(){tmp_files=this.files;this.save_file(this.curr_file);if(tmp_files[''])
delete(this.files['']);return tmp_files;};EditArea.prototype.check_file_changes=function(){var id=this.curr_file;if(this.files[id]&&this.files[id]['compare_edited_text']!=undefined)
{if(this.files[id]['compare_edited_text'].length==this.textarea.value.length&&this.files[id]['compare_edited_text']==this.textarea.value)
{if(this.files[id]['edited']!=false)
this.set_file_edited_mode(id,false);}
else
{if(this.files[id]['edited']!=true)
this.set_file_edited_mode(id,true);}}};EditArea.prototype.set_file_edited_mode=function(id,to){if(this.files[id]&&_$(this.files[id]['html_id']))
{var link=_$(this.files[id]['html_id']).getElementsByTagName('a')[0];if(to==true)
{link.className='edited';}
else
{link.className='';if(id==this.curr_file)
text=this.textarea.value;else
text=this.files[id]['text'];this.files[id]['compare_edited_text']=text;}
this.files[id]['edited']=to;}};EditArea.prototype.set_show_line_colors=function(new_value){this.show_line_colors=new_value;if(new_value)
this.selection_field.className+=' show_colors';else
this.selection_field.className=this.selection_field.className.replace(/ show_colors/g,'');};