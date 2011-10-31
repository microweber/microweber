
EditArea.prototype.change_highlight=function(change_to){if(this.settings["syntax"].length==0&&change_to==false){this.switchClassSticky(_$("highlight"),'editAreaButtonDisabled',true);this.switchClassSticky(_$("reset_highlight"),'editAreaButtonDisabled',true);return false;}
if(this.do_highlight==change_to)
return false;this.getIESelection();var pos_start=this.textarea.selectionStart;var pos_end=this.textarea.selectionEnd;if(this.do_highlight===true||change_to==false)
this.disable_highlight();else
this.enable_highlight();this.textarea.focus();this.textarea.selectionStart=pos_start;this.textarea.selectionEnd=pos_end;this.setIESelection();};EditArea.prototype.disable_highlight=function(displayOnly){var t=this,a=t.textarea,new_Obj,old_class,new_class;t.selection_field.innerHTML="";t.selection_field_text.innerHTML="";t.content_highlight.style.visibility="hidden";new_Obj=t.content_highlight.cloneNode(false);new_Obj.innerHTML="";t.content_highlight.parentNode.insertBefore(new_Obj,t.content_highlight);t.content_highlight.parentNode.removeChild(t.content_highlight);t.content_highlight=new_Obj;old_class=parent.getAttribute(a,"class");if(old_class){new_class=old_class.replace("hidden","");parent.setAttribute(a,"class",new_class);}
a.style.backgroundColor="transparent";t.switchClassSticky(_$("highlight"),'editAreaButtonNormal',true);t.switchClassSticky(_$("reset_highlight"),'editAreaButtonDisabled',true);t.do_highlight=false;t.switchClassSticky(_$("change_smooth_selection"),'editAreaButtonSelected',true);if(typeof(t.smooth_selection_before_highlight)!="undefined"&&t.smooth_selection_before_highlight===false){t.change_smooth_selection_mode(false);}};EditArea.prototype.enable_highlight=function(){var t=this,a=t.textarea,new_class;t.show_waiting_screen();t.content_highlight.style.visibility="visible";new_class=parent.getAttribute(a,"class")+" hidden";parent.setAttribute(a,"class",new_class);if(t.isIE)
a.style.backgroundColor="#FFFFFF";t.switchClassSticky(_$("highlight"),'editAreaButtonSelected',false);t.switchClassSticky(_$("reset_highlight"),'editAreaButtonNormal',false);t.smooth_selection_before_highlight=t.smooth_selection;if(!t.smooth_selection)
t.change_smooth_selection_mode(true);t.switchClassSticky(_$("change_smooth_selection"),'editAreaButtonDisabled',true);t.do_highlight=true;t.resync_highlight();t.hide_waiting_screen();};EditArea.prototype.maj_highlight=function(infos){var debug_opti="",tps_start=new Date().getTime(),tps_middle_opti=new Date().getTime();var t=this,hightlighted_text,updated_highlight;var textToHighlight=infos["full_text"],doSyntaxOpti=false,doHtmlOpti=false,stay_begin="",stay_end="",trace_new,trace_last;if(t.last_text_to_highlight==infos["full_text"]&&t.resync_highlight!==true)
return;if(t.reload_highlight===true){t.reload_highlight=false;}else if(textToHighlight.length==0){textToHighlight="\n ";}else{changes=t.checkTextEvolution(t.last_text_to_highlight,textToHighlight);trace_new=t.get_syntax_trace(changes.newTextLine).replace(/\r/g,'');trace_last=t.get_syntax_trace(changes.lastTextLine).replace(/\r/g,'');doSyntaxOpti=(trace_new==trace_last);if(!doSyntaxOpti&&trace_new=="\n"+trace_last&&/^[ \t\s]*\n[ \t\s]*$/.test(changes.newText.replace(/\r/g,''))&&changes.lastText=="")
{doSyntaxOpti=true;}
if(doSyntaxOpti){tps_middle_opti=new Date().getTime();stay_begin=t.last_hightlighted_text.split("\n").slice(0,changes.lineStart).join("\n");if(changes.lineStart>0)
stay_begin+="\n";stay_end=t.last_hightlighted_text.split("\n").slice(changes.lineLastEnd+1).join("\n");if(stay_end.length>0)
stay_end="\n"+stay_end;if(stay_begin.split('<span').length!=stay_begin.split('</span').length||stay_end.split('<span').length!=stay_end.split('</span').length)
{doSyntaxOpti=false;stay_end='';stay_begin='';}
else
{if(stay_begin.length==0&&changes.posLastEnd==-1)
changes.newTextLine+="\n";textToHighlight=changes.newTextLine;}}
if(t.settings["debug"]){var ch=changes;debug_opti=(doSyntaxOpti?"Optimisation":"No optimisation")
+" start: "+ch.posStart+"("+ch.lineStart+")"
+" end_new: "+ch.posNewEnd+"("+ch.lineNewEnd+")"
+" end_last: "+ch.posLastEnd+"("+ch.lineLastEnd+")"
+"\nchanged_text: "+ch.newText+" => trace: "+trace_new
+"\nchanged_last_text: "+ch.lastText+" => trace: "+trace_last
+"\nchanged_line: "+ch.newTextLine
+"\nlast_changed_line: "+ch.lastTextLine
+"\nstay_begin: "+stay_begin.slice(-100)
+"\nstay_end: "+stay_end.substr(0,100);+"\n";}}
tps_end_opti=new Date().getTime();updated_highlight=t.colorize_text(textToHighlight);tpsAfterReg=new Date().getTime();doSyntaxOpti=doHtmlOpti=false;if(doSyntaxOpti)
{try
{var replacedBloc,i,nbStart='',nbEnd='',newHtml,lengthOld,lengthNew;replacedBloc=t.last_hightlighted_text.substring(stay_begin.length,t.last_hightlighted_text.length-stay_end.length);lengthOld=replacedBloc.length;lengthNew=updated_highlight.length;for(i=0;i<lengthOld&&i<lengthNew&&replacedBloc.charAt(i)==updated_highlight.charAt(i);i++)
{}
nbStart=i;for(i=0;i+nbStart<lengthOld&&i+nbStart<lengthNew&&replacedBloc.charAt(lengthOld-i-1)==updated_highlight.charAt(lengthNew-i-1);i++)
{}
nbEnd=i;lastHtml=replacedBloc.substring(nbStart,lengthOld-nbEnd);newHtml=updated_highlight.substring(nbStart,lengthNew-nbEnd);if(newHtml.indexOf('<span')==-1&&newHtml.indexOf('</span')==-1&&lastHtml.indexOf('<span')==-1&&lastHtml.indexOf('</span')==-1)
{var beginStr,nbOpendedSpan,nbClosedSpan,nbUnchangedChars,span,textNode;doHtmlOpti=true;beginStr=t.last_hightlighted_text.substr(0,stay_begin.length+nbStart);newHtml=newHtml.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');nbOpendedSpan=beginStr.split('<span').length-1;nbClosedSpan=beginStr.split('</span').length-1;span=t.content_highlight.getElementsByTagName('span')[nbOpendedSpan];parentSpan=span;maxStartOffset=maxEndOffset=0;if(nbOpendedSpan==nbClosedSpan)
{while(parentSpan.parentNode!=t.content_highlight&&parentSpan.parentNode.tagName!='PRE')
{parentSpan=parentSpan.parentNode;}}
else
{maxStartOffset=maxEndOffset=beginStr.length+1;nbClosed=beginStr.substr(Math.max(0,beginStr.lastIndexOf('<span',maxStartOffset-1))).split('</span').length-1;while(nbClosed>0)
{nbClosed--;parentSpan=parentSpan.parentNode;}
while(parentSpan.parentNode!=t.content_highlight&&parentSpan.parentNode.tagName!='PRE'&&(tmpMaxStartOffset=Math.max(0,beginStr.lastIndexOf('<span',maxStartOffset-1)))<(tmpMaxEndOffset=Math.max(0,beginStr.lastIndexOf('</span',maxEndOffset-1))))
{maxStartOffset=tmpMaxStartOffset;maxEndOffset=tmpMaxEndOffset;}}
if(parentSpan.parentNode==t.content_highlight||parentSpan.parentNode.tagName=='PRE')
{maxStartOffset=Math.max(0,beginStr.indexOf('<span'));}
if(maxStartOffset==beginStr.length)
{nbSubSpanBefore=0;}
else
{lastEndPos=Math.max(0,beginStr.lastIndexOf('>',maxStartOffset));nbSubSpanBefore=beginStr.substr(lastEndPos).split('<span').length-1;}
if(nbSubSpanBefore==0)
{textNode=parentSpan.firstChild;}
else
{lastSubSpan=parentSpan.getElementsByTagName('span')[nbSubSpanBefore-1];while(lastSubSpan.parentNode!=parentSpan)
{lastSubSpan=lastSubSpan.parentNode;}
if(lastSubSpan.nextSibling==null||lastSubSpan.nextSibling.nodeType!=3)
{textNode=document.createTextNode('');lastSubSpan.parentNode.insertBefore(textNode,lastSubSpan.nextSibling);}
else
{textNode=lastSubSpan.nextSibling;}}
if((lastIndex=beginStr.lastIndexOf('>'))==-1)
{nbUnchangedChars=beginStr.length;}
else
{nbUnchangedChars=beginStr.substr(lastIndex+1).replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&').length;}
if(t.isIE)
{nbUnchangedChars-=(beginStr.substr(beginStr.length-nbUnchangedChars).split("\n").length-1);textNode.replaceData(nbUnchangedChars,lastHtml.replace(/\n/g,'').length,newHtml.replace(/\n/g,''));}
else
{textNode.replaceData(nbUnchangedChars,lastHtml.length,newHtml);}}}
catch(e)
{doHtmlOpti=false;}}
tpsAfterOpti2=new Date().getTime();hightlighted_text=stay_begin+updated_highlight+stay_end;if(!doHtmlOpti)
{var new_Obj=t.content_highlight.cloneNode(false);if((t.isIE&&t.isIE<8)||(t.isOpera&&t.isOpera<9.6))
new_Obj.innerHTML="<pre><span class='"+t.settings["syntax"]+"'>"+hightlighted_text+"</span></pre>";else
new_Obj.innerHTML="<span class='"+t.settings["syntax"]+"'>"+hightlighted_text+"</span>";t.content_highlight.parentNode.replaceChild(new_Obj,t.content_highlight);t.content_highlight=new_Obj;}
t.last_text_to_highlight=infos["full_text"];t.last_hightlighted_text=hightlighted_text;tps3=new Date().getTime();if(t.settings["debug"]){t.debug.value="Tps optimisation "+(tps_end_opti-tps_start)
+" | tps reg exp: "+(tpsAfterReg-tps_end_opti)
+" | tps opti HTML : "+(tpsAfterOpti2-tpsAfterReg)+' '+(doHtmlOpti?'yes':'no')
+" | tps update highlight content: "+(tps3-tpsAfterOpti2)
+" | tpsTotal: "+(tps3-tps_start)
+"("+tps3+")\n"+debug_opti;}};EditArea.prototype.resync_highlight=function(reload_now){this.reload_highlight=true;this.last_text_to_highlight="";this.focus();if(reload_now)
this.check_line_selection(false);};