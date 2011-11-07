<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="<?php echo css_browser_selector() ?>" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<? $url = url_param('url');
$url  = base64_decode($url );
$url = $url.'/editmode:y';
?>
<?  include('header_scripts.php'); ?>
<meta charset=utf-8>
<!--<script src=<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/lib/codemirror.js></script>
<script src=<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/mode/xml/xml.js></script>
<script src=<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/mode/javascript/javascript.js></script>
<script src=<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/mode/css/css.js></script>
<script src=<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/mode/htmlmixed/htmlmixed.js></script>
<link rel=stylesheet href=<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/lib/codemirror.css>
<link rel=stylesheet href=<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/theme/default.css>-->
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/js/codemirror.js" type="text/javascript"></script>
<style type=text/css>
#code {
	float: left;
	width: 50%;
	height:430px;
	border: 1px solid black;
	background-color:#ececec;
}
.tb_ed {
	width: 100%;
	background-color:#999;
	height:45px;
	position:fixed;
	top:0px;
	left:0px;
	background: url("<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/jquery-ui-1.8.13.custom/css/custom-theme/mw_images/grad1.png") repeat-x scroll 50% 50% #E6E6E6;
	border: 1px solid #BBBBBB;
	color: #212121;
	font-weight: normal;
}
.tb_ed2 {
	width: 100%;
	height:430px;
	position:fixed;
	top:30px;
	left:0px;
}
.CodeMirror-scroll {
	height:450px;
	overflow: scroll;
	position: relative;
	display:block;
	width: 390px;	
	word-wrap:break-word;
}

iframe {
	width: 100%;
	float: left;
	height: 430px;
	border: 1px solid black;
	border-left: 0px;
}
</style>
<script>
 

$(document).ready(function(){
						   
 
  
	get_code()				   
						   

 
});

function update_parent_code(){
	updatePreview()
	parent.mw_apply_code_from_editor()
	
}
 
 
var delay;

setInterval ( "mw_check_if_updated_code()", 1000 );

function mw_check_if_updated_code ( )
{
 if(parent.window.html_editor_code_updated == true){
	parent.window.html_editor_code_updated = false; 
	//alert(1);
	 get_code()
	
 }
}





function get_code(){

$v = parent.document.getElementById('mw_css_editor_element_id').value;
 
  $("#html_ed_el_id").html($v );
$all_attr = {};
					$all_attr.file=$v+'.php';
				 
					 url1= '{SITE_URL}api/content/html_editor_get_cache_file';
					 $.post(url1,$all_attr,function(data) {
					 
					  $("#html_ed_code").empty();
 

 
 $("#html_ed_code").val(data);
 
 
 
      // Initialize CodeMirror editor with a nice html5 canvas demo.
    /*  var editor = CodeMirror.fromTextArea(document.getElementById('html_ed_code'), {
        mode: 'text/html',
		value:$v,
        textWrapping: true,
		tabMode: 'indent',
        onChange: function() {
          clearTimeout(delay);
          delay = setTimeout(updatePreview, 300);
        }
      });*/
	  
	  
	   var textarea = document.getElementById('html_ed_code');
  editor = new CodeMirror.fromTextArea((textarea), {
    height: "450px",
    width: "100%",
    content: data,
    parserfile: [ "parsexml.js", "parsecss.js",  "tokenizejavascript.js","parsejavascript.js",  "parsehtmlmixed.js" ],
    stylesheet: "<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/css/xmlcolors.css",
    path: "<?php   print( ADMIN_STATIC_FILES_URL);  ?>codemirror/js/",
    autoMatchParens: true,
	   onChange: function() {
          clearTimeout(delay);
          delay = setTimeout(updatePreview, 300);
        },
    initCallback: function(editor){ }
  });
  
  
  
 
 

 window.editor = editor;
		 updatePreview();			 
					 
					 }); 
					 
					 
					 
					 

 
 
 
}
</script>
<body>
<div class="tb_ed">
  <input class="sbm left" type="button" value="Apply changes" onclick="update_parent_code()" >
   <small class="grey" id="html_ed_el_id"></small>
</div>
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tb_ed2">
  <tr valign="top">
    <td width="50%">
    <div>
    <textarea id="html_ed_code" name="html_ed_code" wrap="virtual">
 
</textarea></div></td>
    <td  width="50%"><iframe id="preview"></iframe></td>
  </tr>
</table>
<script>
     
      
      function updatePreview() {
        var preview = document.getElementById('preview').contentDocument;
        preview.open();
     //   preview.write(window.editor.getCode());
		
		//parent.document.getElementById('mw_edit_code_holder').value = (window.editor.getCode());
		
		
        preview.close();
      }
      setTimeout(updatePreview, 300);
    </script>getCode
</body>
</html>
