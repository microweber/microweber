
function getAttribute(elm,aName){var aValue,taName,i;try{aValue=elm.getAttribute(aName);}catch(exept){}
if(!aValue){for(i=0;i<elm.attributes.length;i++){taName=elm.attributes[i].name.toLowerCase();if(taName==aName){aValue=elm.attributes[i].value;return aValue;}}}
return aValue;};function setAttribute(elm,attr,val){if(attr=="class"){elm.setAttribute("className",val);elm.setAttribute("class",val);}else{elm.setAttribute(attr,val);}};function getChildren(elem,elem_type,elem_attribute,elem_attribute_match,option,depth)
{if(!option)
var option="single";if(!depth)
var depth=-1;if(elem){var children=elem.childNodes;var result=null;var results=[];for(var x=0;x<children.length;x++){strTagName=new String(children[x].tagName);children_class="?";if(strTagName!="undefined"){child_attribute=getAttribute(children[x],elem_attribute);if((strTagName.toLowerCase()==elem_type.toLowerCase()||elem_type=="")&&(elem_attribute==""||child_attribute==elem_attribute_match)){if(option=="all"){results.push(children[x]);}else{return children[x];}}
if(depth!=0){result=getChildren(children[x],elem_type,elem_attribute,elem_attribute_match,option,depth-1);if(option=="all"){if(result.length>0){results=results.concat(result);}}else if(result!=null){return result;}}}}
if(option=="all")
return results;}
return null;};function isChildOf(elem,parent){if(elem){if(elem==parent)
return true;while(elem.parentNode!='undefined'){return isChildOf(elem.parentNode,parent);}}
return false;};function getMouseX(e){if(e!=null&&typeof(e.pageX)!="undefined"){return e.pageX;}else{return(e!=null?e.x:event.x)+document.documentElement.scrollLeft;}};function getMouseY(e){if(e!=null&&typeof(e.pageY)!="undefined"){return e.pageY;}else{return(e!=null?e.y:event.y)+document.documentElement.scrollTop;}};function calculeOffsetLeft(r){return calculeOffset(r,"offsetLeft")};function calculeOffsetTop(r){return calculeOffset(r,"offsetTop")};function calculeOffset(element,attr){var offset=0;while(element){offset+=element[attr];element=element.offsetParent}
return offset;};function get_css_property(elem,prop)
{if(document.defaultView)
{return document.defaultView.getComputedStyle(elem,null).getPropertyValue(prop);}
else if(elem.currentStyle)
{var prop=prop.replace(/-\D/gi,function(sMatch)
{return sMatch.charAt(sMatch.length-1).toUpperCase();});return elem.currentStyle[prop];}
else return null;}
var _mCE;function start_move_element(e,id,frame){var elem_id=(e.target||e.srcElement).id;if(id)
elem_id=id;if(!frame)
frame=window;if(frame.event)
e=frame.event;_mCE=frame.document.getElementById(elem_id);_mCE.frame=frame;frame.document.onmousemove=move_element;frame.document.onmouseup=end_move_element;mouse_x=getMouseX(e);mouse_y=getMouseY(e);_mCE.start_pos_x=mouse_x-(_mCE.style.left.replace("px","")||calculeOffsetLeft(_mCE));_mCE.start_pos_y=mouse_y-(_mCE.style.top.replace("px","")||calculeOffsetTop(_mCE));return false;};function end_move_element(e){_mCE.frame.document.onmousemove="";_mCE.frame.document.onmouseup="";_mCE=null;};function move_element(e){var newTop,newLeft,maxLeft;if(_mCE.frame&&_mCE.frame.event)
e=_mCE.frame.event;newTop=getMouseY(e)-_mCE.start_pos_y;newLeft=getMouseX(e)-_mCE.start_pos_x;maxLeft=_mCE.frame.document.body.offsetWidth-_mCE.offsetWidth;max_top=_mCE.frame.document.body.offsetHeight-_mCE.offsetHeight;newTop=Math.min(Math.max(0,newTop),max_top);newLeft=Math.min(Math.max(0,newLeft),maxLeft);_mCE.style.top=newTop+"px";_mCE.style.left=newLeft+"px";return false;};var nav=editAreaLoader.nav;function getSelectionRange(textarea){return{"start":textarea.selectionStart,"end":textarea.selectionEnd};};function setSelectionRange(t,start,end){t.focus();start=Math.max(0,Math.min(t.value.length,start));end=Math.max(start,Math.min(t.value.length,end));if(nav.isOpera&&nav.isOpera<9.6){t.selectionEnd=1;t.selectionStart=0;t.selectionEnd=1;t.selectionStart=0;}
t.selectionStart=start;t.selectionEnd=end;if(nav.isIE)
set_IE_selection(t);};function get_IE_selection(t){var d=document,div,range,stored_range,elem,scrollTop,relative_top,line_start,line_nb,range_start,range_end,tab;if(t&&t.focused)
{if(!t.ea_line_height)
{div=d.createElement("div");div.style.fontFamily=get_css_property(t,"font-family");div.style.fontSize=get_css_property(t,"font-size");div.style.visibility="hidden";div.innerHTML="0";d.body.appendChild(div);t.ea_line_height=div.offsetHeight;d.body.removeChild(div);}
range=d.selection.createRange();try
{stored_range=range.duplicate();stored_range.moveToElementText(t);stored_range.setEndPoint('EndToEnd',range);if(stored_range.parentElement()==t){elem=t;scrollTop=0;while(elem.parentNode){scrollTop+=elem.scrollTop;elem=elem.parentNode;}
relative_top=range.offsetTop-calculeOffsetTop(t)+scrollTop;line_start=Math.round((relative_top/t.ea_line_height)+1);line_nb=Math.round(range.boundingHeight/t.ea_line_height);range_start=stored_range.text.length-range.text.length;tab=t.value.substr(0,range_start).split("\n");range_start+=(line_start-tab.length)*2;t.selectionStart=range_start;range_end=t.selectionStart+range.text.length;tab=t.value.substr(0,range_start+range.text.length).split("\n");range_end+=(line_start+line_nb-1-tab.length)*2;t.selectionEnd=range_end;}}
catch(e){}}
if(t&&t.id)
{setTimeout("get_IE_selection(document.getElementById('"+t.id+"'));",50);}};function IE_textarea_focus(){event.srcElement.focused=true;}
function IE_textarea_blur(){event.srcElement.focused=false;}
function set_IE_selection(t){var nbLineStart,nbLineStart,nbLineEnd,range;if(!window.closed){nbLineStart=t.value.substr(0,t.selectionStart).split("\n").length-1;nbLineEnd=t.value.substr(0,t.selectionEnd).split("\n").length-1;try
{range=document.selection.createRange();range.moveToElementText(t);range.setEndPoint('EndToStart',range);range.moveStart('character',t.selectionStart-nbLineStart);range.moveEnd('character',t.selectionEnd-nbLineEnd-(t.selectionStart-nbLineStart));range.select();}
catch(e){}}};editAreaLoader.waiting_loading["elements_functions.js"]="loaded";