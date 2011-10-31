
var EditArea_charmap={init:function(){this.default_language="Arrows";},get_control_html:function(ctrl_name){switch(ctrl_name){case"charmap":return parent.editAreaLoader.get_button_html('charmap_but','charmap.gif','charmap_press',false,this.baseURL);}
return false;},onload:function(){if(editArea.settings["charmap_default"]&&editArea.settings["charmap_default"].length>0)
this.default_language=editArea.settings["charmap_default"];},onkeydown:function(e){},execCommand:function(cmd,param){switch(cmd){case"charmap_press":win=window.open(this.baseURL+"popup.html","charmap","width=500,height=270,scrollbars=yes,resizable=yes");win.focus();return false;}
return true;}};editArea.add_plugin("charmap",EditArea_charmap);