
var EditArea_test={init:function(){editArea.load_css(this.baseURL+"css/test.css");editArea.load_script(this.baseURL+"test2.js");},get_control_html:function(ctrl_name){switch(ctrl_name){case"test_but":return parent.editAreaLoader.get_button_html('test_but','test.gif','test_cmd',false,this.baseURL);case"test_select":html="<select id='test_select' onchange='javascript:editArea.execCommand(\"test_select_change\")' fileSpecific='no'>"
+"   <option value='-1'>{$test_select}</option>"
+"   <option value='h1'>h1</option>"
+"   <option value='h2'>h2</option>"
+"   <option value='h3'>h3</option>"
+"   <option value='h4'>h4</option>"
+"   <option value='h5'>h5</option>"
+"   <option value='h6'>h6</option>"
+"  </select>";return html;}
return false;},onload:function(){alert("test load");},onkeydown:function(e){var str=String.fromCharCode(e.keyCode);if(str.toLowerCase()=="f"){return true;}
return false;},execCommand:function(cmd,param){switch(cmd){case"test_select_change":var val=document.getElementById("test_select").value;if(val!=-1)
parent.editAreaLoader.insertTags(editArea.id,"<"+val+">","</"+val+">");document.getElementById("test_select").options[0].selected=true;return false;case"test_cmd":alert("user clicked on test_cmd");return false;}
return true;},_someInternalFunction:function(a,b){return a+b;}};editArea.add_plugin("test",EditArea_test);