
var EditArea_autocompletion={init:function(){if(editArea.settings["autocompletion"])
this.enabled=true;else
this.enabled=false;this.current_word=false;this.shown=false;this.selectIndex=-1;this.forceDisplay=false;this.isInMiddleWord=false;this.autoSelectIfOneResult=false;this.delayBeforeDisplay=100;this.checkDelayTimer=false;this.curr_syntax_str='';this.file_syntax_datas={};},onload:function(){if(this.enabled)
{var icon=document.getElementById("autocompletion");if(icon)
editArea.switchClassSticky(icon,'editAreaButtonSelected',true);}
this.container=document.createElement('div');this.container.id="auto_completion_area";editArea.container.insertBefore(this.container,editArea.container.firstChild);parent.editAreaLoader.add_event(document,"click",function(){editArea.plugins['autocompletion']._hide();});parent.editAreaLoader.add_event(editArea.textarea,"blur",function(){editArea.plugins['autocompletion']._hide();});},onkeydown:function(e){if(!this.enabled)
return true;if(EA_keys[e.keyCode])
letter=EA_keys[e.keyCode];else
letter=String.fromCharCode(e.keyCode);if(this._isShown())
{if(letter=="Esc")
{this._hide();return false;}
else if(letter=="Entrer")
{var as=this.container.getElementsByTagName('A');if(this.selectIndex>=0&&this.selectIndex<as.length)
{as[this.selectIndex].onmousedown();return false}
else
{this._hide();return true;}}
else if(letter=="Tab"||letter=="Down")
{this._selectNext();return false;}
else if(letter=="Up")
{this._selectBefore();return false;}}
else
{}
if(letter=="Space"&&CtrlPressed(e))
{this.forceDisplay=true;this.autoSelectIfOneResult=true;this._checkLetter();return false;}
setTimeout("editArea.plugins['autocompletion']._checkDelayAndCursorBeforeDisplay();",editArea.check_line_selection_timer+5);this.checkDelayTimer=false;return true;},execCommand:function(cmd,param){switch(cmd){case'toggle_autocompletion':var icon=document.getElementById("autocompletion");if(!this.enabled)
{if(icon!=null){editArea.restoreClass(icon);editArea.switchClassSticky(icon,'editAreaButtonSelected',true);}
this.enabled=true;}
else
{this.enabled=false;if(icon!=null)
editArea.switchClassSticky(icon,'editAreaButtonNormal',false);}
return true;}
return true;},_checkDelayAndCursorBeforeDisplay:function()
{this.checkDelayTimer=setTimeout("if(editArea.textarea.selectionStart == "+editArea.textarea.selectionStart+") EditArea_autocompletion._checkLetter();",this.delayBeforeDisplay-editArea.check_line_selection_timer-5);},_hide:function(){this.container.style.display="none";this.selectIndex=-1;this.shown=false;this.forceDisplay=false;this.autoSelectIfOneResult=false;},_show:function(){if(!this._isShown())
{this.container.style.display="block";this.selectIndex=-1;this.shown=true;}},_isShown:function(){return this.shown;},_isInMiddleWord:function(new_value){if(typeof(new_value)=="undefined")
return this.isInMiddleWord;else
this.isInMiddleWord=new_value;},_selectNext:function()
{var as=this.container.getElementsByTagName('A');for(var i=0;i<as.length;i++)
{if(as[i].className)
as[i].className=as[i].className.replace(/ focus/g,'');}
this.selectIndex++;this.selectIndex=(this.selectIndex>=as.length||this.selectIndex<0)?0:this.selectIndex;as[this.selectIndex].className+=" focus";},_selectBefore:function()
{var as=this.container.getElementsByTagName('A');for(var i=0;i<as.length;i++)
{if(as[i].className)
as[i].className=as[i].className.replace(/ focus/g,'');}
this.selectIndex--;this.selectIndex=(this.selectIndex>=as.length||this.selectIndex<0)?as.length-1:this.selectIndex;as[this.selectIndex].className+=" focus";},_select:function(content)
{cursor_forced_position=content.indexOf('{@}');content=content.replace(/{@}/g,'');editArea.getIESelection();var start_index=Math.max(0,editArea.textarea.selectionEnd-content.length);line_string=editArea.textarea.value.substring(start_index,editArea.textarea.selectionEnd+1);limit=line_string.length-1;nbMatch=0;for(i=0;i<limit;i++)
{if(line_string.substring(limit-i-1,limit)==content.substring(0,i+1))
nbMatch=i+1;}
if(nbMatch>0)
parent.editAreaLoader.setSelectionRange(editArea.id,editArea.textarea.selectionStart-nbMatch,editArea.textarea.selectionEnd);parent.editAreaLoader.setSelectedText(editArea.id,content);range=parent.editAreaLoader.getSelectionRange(editArea.id);if(cursor_forced_position!=-1)
new_pos=range["end"]-(content.length-cursor_forced_position);else
new_pos=range["end"];parent.editAreaLoader.setSelectionRange(editArea.id,new_pos,new_pos);this._hide();},_parseSyntaxAutoCompletionDatas:function(){for(var lang in parent.editAreaLoader.load_syntax)
{if(!parent.editAreaLoader.syntax[lang]['autocompletion'])
{parent.editAreaLoader.syntax[lang]['autocompletion']={};if(parent.editAreaLoader.load_syntax[lang]['AUTO_COMPLETION'])
{for(var i in parent.editAreaLoader.load_syntax[lang]['AUTO_COMPLETION'])
{datas=parent.editAreaLoader.load_syntax[lang]['AUTO_COMPLETION'][i];tmp={};if(datas["CASE_SENSITIVE"]!="undefined"&&datas["CASE_SENSITIVE"]==false)
tmp["modifiers"]="i";else
tmp["modifiers"]="";tmp["prefix_separator"]=datas["REGEXP"]["prefix_separator"];tmp["match_prefix_separator"]=new RegExp(datas["REGEXP"]["prefix_separator"]+"$",tmp["modifiers"]);tmp["match_word"]=new RegExp("(?:"+datas["REGEXP"]["before_word"]+")("+datas["REGEXP"]["possible_words_letters"]+")$",tmp["modifiers"]);tmp["match_next_letter"]=new RegExp("^("+datas["REGEXP"]["letter_after_word_must_match"]+")$",tmp["modifiers"]);tmp["keywords"]={};for(var prefix in datas["KEYWORDS"])
{tmp["keywords"][prefix]={prefix:prefix,prefix_name:prefix,prefix_reg:new RegExp("(?:"+parent.editAreaLoader.get_escaped_regexp(prefix)+")(?:"+tmp["prefix_separator"]+")$",tmp["modifiers"]),datas:[]};for(var j=0;j<datas["KEYWORDS"][prefix].length;j++)
{tmp["keywords"][prefix]['datas'][j]={is_typing:datas["KEYWORDS"][prefix][j][0],replace_with:datas["KEYWORDS"][prefix][j][1]?datas["KEYWORDS"][prefix][j][1].replace('§',datas["KEYWORDS"][prefix][j][0]):'',comment:datas["KEYWORDS"][prefix][j][2]?datas["KEYWORDS"][prefix][j][2]:''};if(tmp["keywords"][prefix]['datas'][j]['replace_with'].length==0)
tmp["keywords"][prefix]['datas'][j]['replace_with']=tmp["keywords"][prefix]['datas'][j]['is_typing'];if(tmp["keywords"][prefix]['datas'][j]['comment'].length==0)
tmp["keywords"][prefix]['datas'][j]['comment']=tmp["keywords"][prefix]['datas'][j]['replace_with'].replace(/{@}/g,'');}}
tmp["max_text_length"]=datas["MAX_TEXT_LENGTH"];parent.editAreaLoader.syntax[lang]['autocompletion'][i]=tmp;}}}}},_checkLetter:function(){if(this.curr_syntax_str!=editArea.settings['syntax'])
{if(!parent.editAreaLoader.syntax[editArea.settings['syntax']]['autocompletion'])
this._parseSyntaxAutoCompletionDatas();this.curr_syntax=parent.editAreaLoader.syntax[editArea.settings['syntax']]['autocompletion'];this.curr_syntax_str=editArea.settings['syntax'];}
if(editArea.is_editable)
{time=new Date;t1=time.getTime();editArea.getIESelection();this.selectIndex=-1;start=editArea.textarea.selectionStart;var str=editArea.textarea.value;var results=[];for(var i in this.curr_syntax)
{var last_chars=str.substring(Math.max(0,start-this.curr_syntax[i]["max_text_length"]),start);var matchNextletter=str.substring(start,start+1).match(this.curr_syntax[i]["match_next_letter"]);if(matchNextletter||this.forceDisplay)
{var match_prefix_separator=last_chars.match(this.curr_syntax[i]["match_prefix_separator"]);var match_word=last_chars.match(this.curr_syntax[i]["match_word"]);if(match_word)
{var begin_word=match_word[1];var match_curr_word=new RegExp("^"+parent.editAreaLoader.get_escaped_regexp(begin_word),this.curr_syntax[i]["modifiers"]);for(var prefix in this.curr_syntax[i]["keywords"])
{for(var j=0;j<this.curr_syntax[i]["keywords"][prefix]['datas'].length;j++)
{if(this.curr_syntax[i]["keywords"][prefix]['datas'][j]['is_typing'].match(match_curr_word))
{hasMatch=false;var before=last_chars.substr(0,last_chars.length-begin_word.length);if(!match_prefix_separator&&this.curr_syntax[i]["keywords"][prefix]['prefix'].length==0)
{if(!before.match(this.curr_syntax[i]["keywords"][prefix]['prefix_reg']))
hasMatch=true;}
else if(this.curr_syntax[i]["keywords"][prefix]['prefix'].length>0)
{if(before.match(this.curr_syntax[i]["keywords"][prefix]['prefix_reg']))
hasMatch=true;}
if(hasMatch)
results[results.length]=[this.curr_syntax[i]["keywords"][prefix],this.curr_syntax[i]["keywords"][prefix]['datas'][j]];}}}}
else if(this.forceDisplay||match_prefix_separator)
{for(var prefix in this.curr_syntax[i]["keywords"])
{for(var j=0;j<this.curr_syntax[i]["keywords"][prefix]['datas'].length;j++)
{hasMatch=false;if(!match_prefix_separator&&this.curr_syntax[i]["keywords"][prefix]['prefix'].length==0)
{hasMatch=true;}
else if(match_prefix_separator&&this.curr_syntax[i]["keywords"][prefix]['prefix'].length>0)
{var before=last_chars;if(before.match(this.curr_syntax[i]["keywords"][prefix]['prefix_reg']))
hasMatch=true;}
if(hasMatch)
results[results.length]=[this.curr_syntax[i]["keywords"][prefix],this.curr_syntax[i]["keywords"][prefix]['datas'][j]];}}}}}
if(results.length==1&&this.autoSelectIfOneResult)
{this._select(results[0][1]['replace_with']);}
else if(results.length==0)
{this._hide();}
else
{var lines=[];for(var i=0;i<results.length;i++)
{var line="<li><a href=\"#\" class=\"entry\" onmousedown=\"EditArea_autocompletion._select('"+results[i][1]['replace_with'].replace(new RegExp('"',"g"),"&quot;")+"');return false;\">"+results[i][1]['comment'];if(results[i][0]['prefix_name'].length>0)
line+='<span class="prefix">'+results[i][0]['prefix_name']+'</span>';line+='</a></li>';lines[lines.length]=line;}
this.container.innerHTML='<ul>'+lines.sort().join('')+'</ul>';var cursor=_$("cursor_pos");this.container.style.top=(cursor.cursor_top+editArea.lineHeight)+"px";this.container.style.left=(cursor.cursor_left+8)+"px";this._show();}
this.autoSelectIfOneResult=false;time=new Date;t2=time.getTime();}}};editArea.settings['plugins'][editArea.settings['plugins'].length]='autocompletion';editArea.add_plugin('autocompletion',EditArea_autocompletion);