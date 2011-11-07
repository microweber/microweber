<script type="text/javascript">
  //  FCKEditorPath = '<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/fckeditor/';
    imgurl = '<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>images/';
</script>
<!--<link rel="stylesheet" href="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>admin/style.css" type="text/css" media="all"  />-->
<!--<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tree/source/_lib.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jquery.field.min.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jquery.DOMWindow.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jquery.form.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jquery.fn.handleKeyboardChange.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/modal.js"></script>
<SCRIPT type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/excanvas.js"></SCRIPT>
<SCRIPT type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/visualize.jQuery.js"></SCRIPT>
<SCRIPT type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/functions.js"></SCRIPT>
<SCRIPT type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/js/engine.js"></SCRIPT>
<!-- Individual YUI CSS files -->
<!--<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/yui/2.7.0/build/datatable/assets/skins/sam/datatable.css">-->
<!-- Individual YUI JS files -->
<!--<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jquery.uploadify-v2.1.0/swfobject.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jquery.uploadify-v2.1.0/jquery.uploadify.v2.1.0.js"></script>-->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.7.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.7.0/build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.7.0/build/element/element-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.7.0/build/datatable/datatable-min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/yui/2.7.0/build/uploader/uploader.js"></script>-->

<!--<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/swfupload/swfupload_fp10/swfupload.js"></script>-->

<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/swfupload/handlers.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/swfupload/fileprogress.js"></script>
<!--<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/swfupload/swfupload.speed.js"></script>-->
<!--<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/swfupload/swfupload.cookies.js"></script>-->
<link rel="stylesheet" href="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jqgrid/css/ui.jqgrid.css" type="text/css" media="all"  />
<link rel="stylesheet" href="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jqgrid/css/jquery.searchFilter.css" type="text/css" media="all"  />
<script type="text/javascript">
   var  jqgrid_dir = '<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jqgrid/js/';
</script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/jqgrid/jqgrid_loader.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" type="text/css" />
<script type="text/javascript">
var tb_path = "<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/thickbox/";
</script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/thickbox/thickbox.js"></script>
<link rel="stylesheet" href="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>jquery/thickbox/thickbox.css" type="text/css" media="all"  />
<?php if($load_google_map == true) : ?>
<!--<script src="http://www.google.com/jsapi?key=<?php print $this->core_model->optionsGetByKey ( 'google_maps_api_key' ); ?>" type="text/javascript"></script>-->
<script type="text/javascript">
//google.load("maps", "2");
</script>
<?php endif; ?>
<!-- Load TinyMCE -->
<!--<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>-->
<script type="text/javascript">
function category_editor_setup($id) {




  if($('#' + $id).css("display")=='none'){
      $("textarea.TaxEditor").val($("textarea.TaxEditor").next(".mceEditor").find("iframe").contents().find("body").html());
      $('#'+$id).tinymce().remove();
      $('#'+$id).show();

  }
  else{
    // mce_setup('#'+$id);










  }




 /*  tinyMCE.init({
				script_url : '<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tiny_mce/tiny_mce.js',
      mode : "textareas",
      theme : "advanced",
      plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
      theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
      theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
      theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
      theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_statusbar_location : "bottom",
	  relative_urls : false,
			convert_urls : false,
			remove_script_host : false,
			document_base_url : "<?php print site_url(); ?>",
	  remove_linebreaks : false,
			height : "480",
			//content_css : "http://192.168.0.197/wfl_dev/userfiles/templates/waterforlifeusa/css/ooyes.framework.css?" + new Date().getTime(),
			file_browser_callback : "ajaxfilemanager",

      theme_advanced_resizing : true
   });*/
}
</script>
<script type="text/javascript">



$(document).ready(function(){
	//	mce_setup('textarea.richtext');
	});



function mce_remove() {
      $('textarea.richtext').tinymce('remove');
    }


function removeChapter(elem){
     $(elem).parents(".chapter_editor").remove();
}

 
function newEditor(elem){
    var length = $(".content_chapter").length+1;
    var newHTML = ''
    +'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
      +'<tr>'
        +'<td><label class="lbl">Content ' +  length + '&nbsp;&nbsp;&nbsp;<a onclick="removeChapter(this)" href="javascript:;">Remove chapter</a></label>'
          +'<input style="width:200px" name="custom_field_content_body_' + length + '_title"  type="text" /><div style="height:10px">&nbsp;</div>'
          +'<textarea name="custom_field_content_body_' + length + '" class="content_chapter richtext" rows="10" cols="10" style="width:645px"></textarea>'
        +'</td>'
      +'</tr>'
    +'</table>';

  var div = document.createElement('div');
  div.className = 'chapter_editor';

  div.innerHTML = newHTML;
  document.getElementById(elem).appendChild(div);


  mce_setup('textarea[name="custom_field_content_body_' + length + '"]');

}


function mce_setup(selector){
	
	//
//	 //tinyMCE.execCommand
//
//$(selector).tinymce({
//			// Location of TinyMCE script
//			script_url : '<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tiny_mce/tiny_mce.js',
//
//			// General options
//			theme : "advanced",
//			plugins : "safari,pagebreak,style,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,preelementfix,syntaxhl",
//
//			// Theme options
//			//theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
//			theme_advanced_buttons1 : "faq,fullscreen,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
//			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
//			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,|,ltr,rtl,|,fullscreen,syntaxhl",
//		//	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
//			theme_advanced_toolbar_location : "top",
//			theme_advanced_toolbar_align : "left",
//			theme_advanced_statusbar_location : "bottom",
//			theme_advanced_resizing : true,
//			relative_urls : false,
//			convert_urls : false,
//			remove_script_host : false,
//			document_base_url : "<?php print site_url(); ?>",
//			valid_elements : ""
//+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
//  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
//  +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
//+"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
//  +"|height|hspace|id|name|object|style|title|vspace|width],"
//+"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
//  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
//  +"|shape<circle?default?poly?rect|style|tabindex|title|target],"
//+"base[href|target],"
//+"basefont[color|face|id|size],"
//+"bdo[class|dir<ltr?rtl|id|lang|style|title],"
//+"big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"blockquote[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
//  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
//  +"|onmouseover|onmouseup|style|title],"
//+"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
//+"br[class|clear<all?left?none?right|id|style|title],"
//+"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
//  +"|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
//  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
//  +"|value],"
//+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
//  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
//  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
//  +"|valign<baseline?bottom?middle?top|width],"
//+"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
//  +"|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
//  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
//  +"|valign<baseline?bottom?middle?top|width],"
//+"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
//+"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
//+"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
//+"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
//  +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
//  +"|style|title|target],"
//+"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
//  +"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
//+"frameset[class|cols|id|onload|onunload|rows|style|title],"
//+"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"head[dir<ltr?rtl|lang|profile],"
//+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
//+"html[dir<ltr?rtl|lang|version],"
//+"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
//  +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
//  +"|title|width],"
//+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
//  +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|src|style|title|usemap|vspace|width],"
//+"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
//  +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
//  +"|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
//  +"|readonly<readonly|size|src|style|tabindex|title"
//  +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
//  +"|usemap|value],"
//+"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
//+"kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
//  +"|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
//  +"|onmouseover|onmouseup|style|title],"
//+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
//  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
//  +"|value],"
//+"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
//+"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
//+"noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"noscript[class|dir<ltr?rtl|id|lang|style|title],"
//+"object[align<bottom?left?middle?right?top|archive|border|class|classid"
//  +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
//  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
//  +"|vspace|width],"
//+"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|start|style|title|type],"
//+"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
//  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
//  +"|onmouseover|onmouseup|selected<selected|style|title|value],"
//+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|style|title],"
//+"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
//+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
//  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
//  +"|onmouseover|onmouseup|style|title|width],"
//+"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
//+"samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"script[charset|defer|language|src|type],"
//+"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
//  +"|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
//  +"|tabindex|title],"
//+"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title],"
//+"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"style[dir<ltr?rtl|lang|media|title|type],"
//+"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title],"
//+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
//  +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
//  +"|style|summary|title|width],"
//+"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
//  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
//  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
//  +"|valign<baseline?bottom?middle?top],"
//+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
//  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
//  +"|style|title|valign<baseline?bottom?middle?top|width],"
//+"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
//  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
//  +"|readonly<readonly|rows|style|tabindex|title],"
//+"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
//  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
//  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
//  +"|valign<baseline?bottom?middle?top],"
//+"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
//  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
//  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
//  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
//  +"|style|title|valign<baseline?bottom?middle?top|width],"
//+"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
//  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
//  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
//  +"|valign<baseline?bottom?middle?top],"
//+"title[dir<ltr?rtl|lang],"
//+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
//  +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title|valign<baseline?bottom?middle?top],"
//+"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
//+"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
//  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
//+"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
//  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
//  +"|onmouseup|style|title|type],"
//+"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
//  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
//  +"|title]" ,
//			remove_linebreaks : false,
//			height : "480",
//			
//			<?php $file = ACTIVE_TEMPLATE_DIR.'css/style.css';
//			if(is_file($file) == true) { ?>
//			
//			content_css : "<?php print TEMPLATE_URL ?>css/style.css?" + new Date().getTime(),
//			
//			<?php }		?>
//			file_browser_callback : "ajaxfilemanager",
//
//
//			// Example content CSS (should be your site CSS)
//			//content_css : "css/content.css",
//
//			// Drop lists for link/image/media/template dialogs
//			//template_external_list_url : "lists/template_list.js",
//			//external_link_list_url : "lists/link_list.js",
//			//external_image_list_url : "lists/image_list.js",
//			//media_external_list_url : "lists/media_list.js",
//
//			// Replace values for the template plugin
//			template_replace_values : {
//				username : "Some User",
//				staffid : "991234"
//			}
//		});


}








function ajaxfilemanager(field_name, url, type, win) {
		//	var ajaxfilemanagerurl = "<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
//			switch (type) {
//				case "image":
//					break;
//				case "media":
//					break;
//				case "flash":
//					break;
//				case "file":
//					break;
//				default:
//					return false;
//			}
//            tinyMCE.activeEditor.windowManager.open({
//                url: "<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php",
//                width: 782,
//                height: 440,
//                inline : "yes",
//				 resizable : "yes",
//                close_previous : "yes"
//            },{
//                window : win,
//                input : field_name
//            });

/*            return false;
			var fileBrowserWindow = new Array();
			fileBrowserWindow["file"] = ajaxfilemanagerurl;
			fileBrowserWindow["title"] = "Ajax File Manager";
			fileBrowserWindow["width"] = "782";
			fileBrowserWindow["height"] = "440";
			fileBrowserWindow["close_previous"] = "no";
			tinyMCE.openWindow(fileBrowserWindow, {
			  window : win,
			  input : field_name,
			  resizable : "yes",
			  inline : "yes",
			  editor_id : tinyMCE.getWindowArg("editor_id")
			});

			return false;*/
		}





























</script>
<!-- /TinyMCE -->
<?php if($load_google_map == true) : ?>
<?php endif; ?>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/jquery.gritter.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/app.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/sortable.js"></script>
<script type="text/javascript">

	$(document).ready(function(){

     //Context
    $.fn.extend({
           contextMenu:function(menu){
               return this.live("contextmenu", function(e){
                  var navtop = e.pageY;
                  var navleft = e.pageX;
                  $("#ooyesTreeContextMenu").html("");

                  //alert($("#bindContextNav").html())
                  $(this).find(".bindContextNav:first").clone(true).appendTo("#ooyesTreeContextMenu");
                  $(menu).css({"top":navtop, "left":navleft});
                  if($("#treeRenamer").length<=0){
                    $(menu).show();
                    $(this).addClass("ContextMenuActive");
                  }
                  var active_item = $(this);
                  $(".context").hover(function(){
                       $(this).addClass("contextHover")
                  }, function(){
                       $(this).removeClass("contextHover")
                  })
                        $(document).bind("click", function(){
                            if($(".contextHover").length<=0){
                    			$(menu).hide();
                                active_item.removeClass("ContextMenuActive");
                                $("#ooyesTreeContextMenu").empty();
                            }
                  	     });
                   return false;
               })
             }
         });
         // End Context



$('#loadingDiv')
    .hide()  // hide it initially
    .ajaxStart(function() {
        $(this).fadeIn();
    })
    .ajaxStop(function() {
        $(this).fadeOut();
    })
;
	})


$('#wrap').hide(); //Hide Content area when someone visit the page
$(document).ready(function(){
$('#loadingDiv').hide(); //hide loading are when page is ready for display
$('#wrap').show(); //Show the content area after page is ready
});




</script>
<link rel="stylesheet" type="text/css" href="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tree/source/tree_component.css" />
<link rel="stylesheet" type="text/css" href="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tree/source/themes/apple/style.css" />
<script type="text/javascript" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>js/tree/source/tree_component.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){



$(".ooyes_ul_tree_container li").contextMenu("#ooyesTreeContextMenu");
$(".ooyes_ul_pages_tree_container li").contextMenu("#ooyesTreeContextMenu");

recreate_js_category_trees();
recreate_js_pages_trees();
delete_category_pre_set_dialog();



          $(".treestate").live("click", function(e){
          if(e.button==0){
            if($(this).parent().hasClass("collapse")){
                  $(this).parent().find("ul:first").slideUp("fast");
                   $(this).removeClass("Activectrl");
                   $(this).parent().removeClass("collapse");
             }
             else{
                  $(this).parent().find("ul:first").slideDown('fast');
                  $(this).addClass('Activectrl');
                  $(this).parent().addClass('collapse');
             }
          }
          else if(e.button==2){return false}
          else{
                if($(this).parent().hasClass("collapse")){
                      $(this).parent().find("ul:first").slideUp('fast');
                       $(this).removeClass('Activectrl');
                       $(this).parent().removeClass('collapse');
                 }
                 else{
                      $(this).parent().find("ul:first").slideDown('fast');
                      $(this).addClass('Activectrl');
                      $(this).parent().addClass('collapse');
                 }
          }

        });


    })

	function recreate_js_category_trees_pre(obj, html){
	$(obj).html(html);
	}

	function recreate_js_category_trees(){
	   $(".ooyes_ul_tree_container").each(function(the_tree_index){
	   //tb_remove() ;
		$params =  $(this).attr("treeparams"); 
		//alert($params);
		$the_container = $(this);
		//$(this).html('loading...');
        $(this).attr("treeid", "tree_" + the_tree_index);
		//some icons
		$add_page_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/page_add.png' />";
		$add_page_url = "<?php print site_url('admin/content/posts_edit/id:0/category:'); ?>";




		$add_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/folder__plus.png' />";
		$edit_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/folder__pencil.png' />";
		$del_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/folder__minus.png' />";

		$up_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/arrow_090_small.png' />";
		$down_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/arrow_270_small.png' />";

		$.post("<?php print site_url('ajax_helpers/taxonomy_categories_load_category_js_tree') ?>", { treeparams: $params, rand: Math.random() },
		  function(html){
		    var data = html;
                 var trees=document.getElementsByTagName('div');
                 the_tree_index=the_tree_index;

                 for(var tree=0; tree<trees.length; tree++){
                   var tattribute = trees[tree].getAttribute("treeid");
					   if(tattribute == "tree_" + the_tree_index){
						  var curr = tree;
						  document.getElementsByTagName('div')[curr].innerHTML = data;
                           $(".ooyes_ul_tree_container").addClass("LoadedUiTree")




                          $(".ooyes_ul_tree_container").eq(the_tree_index).find("a").each(function(){

							var tree_name = $(this).attr("name");
						   	$(this).after("<div class='bindContextNav'><a href='"+ $add_page_url + tree_name + "'>" + $add_page_icon + "Add post here</a> <a href='javascript:edit_category_dialog(0," + tree_name + ")'>" + $add_cat_icon + "Add sub-category</a>  <a href='javascript:edit_category_dialog(" + tree_name + ")'>" + $edit_cat_icon + "Edit category</a> <a href='javascript:delete_category_ajax(" + tree_name + ")'>" + $del_cat_icon + "Delete category</a><hr> <a href='javascript:move_category_ajax_up(" + tree_name + ")'>" + $up_cat_icon + "Move up</a>			<a href='javascript:move_category_ajax_down(" + tree_name + ")'>" + $down_cat_icon + "Move down</a>		</div>");

                        /* var li = $(".ooyes_ul_tree_container li");

                          for(var i=0; i<li.length; i++){
                              if(li[i].getElementsByTagName('li').length>0){
                                  li[i].className = li[i].className + ' liparent collapse';
                              }
                              else{
                                 li[i].className = 'lichild';
                              }
                          } */
                          /*li.each(function(){
                            if($(this).find("li").length>0){
                               $(this).addClass("liparent collapse");
                            }
                            else{
                               $(this).addClass("lichild");
                            }
                          }); */

                        //$(".ooyes_ul_tree_container li:has(li)").addClass("liparent collapse");
						//$(".ooyes_ul_tree_container li").not(":has(li)").addClass("lichild");

        $(".liparent").each(function(){
          if($(this).find(".treestate").length<=0){
             $(this).append("<span class='treestate Activectrl'></span>");
          }
        })
        });





        $(".context").bind("contextmenu", function(){
          return false;
        });
       //  $(".ooyes_ul_tree_container li:has(li)").addClass("liparent collapse");
                   }
                 }


                  $(".ooyes_ul_tree_container li:has(li)").addClass("liparent collapse");
				  $(".ooyes_ul_tree_container li").not(":has(li)").addClass("lichild");



		  });

      });



	}

        tree = new Object;
        tree.hideall = function(id){
             $(id).find(".Activectrl").click();
        }
        tree.showall = function(id){
            $(id).find(".treestate").not(".Activectrl").click();
        }


















		function recreate_js_pages_trees(){
	   $(".ooyes_ul_pages_tree_container").each(function(the_tree_index){
	   //tb_remove() ;
		$params =  $(this).attr("treeparams");
		//alert($params);
		$the_container = $(this);
		//$(this).html('loading...');
        $(this).attr("treeid", "pages_tree_" + the_tree_index);
		//some icons

		$edit_page_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/page_edit.png' />";
		$edit_page_url = "<?php print site_url('admin/content/pages_edit/id:') ; ?>";

		$add_subpage_page_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/page_copy.png' />";
		$add_subpage_page_url = "<?php print site_url('admin/content/pages_edit/id:0/content_parent:') ; ?>";



		$add_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/folder__plus.png' />";
		$edit_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/folder__pencil.png' />";
		$del_cat_icon = "<img border='0' src='<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>icons/folder__minus.png' />";



		$.post("<?php print site_url('ajax_helpers/pages_load_category_js_tree') ?>", { treeparams: $params, rand: Math.random() },
		  function(html){
		    var data = html;
                 var trees=document.getElementsByTagName('div');
                 the_tree_index=the_tree_index;
                 for(var tree=0; tree<trees.length; tree++){
                   var tattribute = trees[tree].getAttribute("treeid");
					   if(tattribute == "pages_tree_" + the_tree_index){
						  var curr = tree;
						  document.getElementsByTagName('div')[curr].innerHTML = data;
                          $(".ooyes_ul_pages_tree_container").addClass("LoadedUiTree")
                          $(".ooyes_ul_pages_tree_container").eq(the_tree_index).find("a").each(function(){
							var tree_name = $(this).attr("name");
							$(this).after("<div class='bindContextNav'>			<a href='"+$edit_page_url+tree_name+"'>" + $edit_page_icon + "Edit this page</a>	<a href='"+$add_subpage_page_url+tree_name+"'>" + $add_subpage_page_icon + "Add subpage</a>							</div>");

						  $(".ooyes_ul_pages_tree_container li:has(li)").addClass("liparent collapse");
                          $(".ooyes_ul_pages_tree_container li").not(":has(li)").addClass("lichild");
        $(".liparent").each(function(){
          if($(this).find(".treestate").length<=0){
             $(this).append("<span class='treestate Activectrl'></span>");
          }
          // alert($(this).html())
        })
        });

        $(".context").bind("contextmenu", function(){
          return false;
        });
       //  $(".ooyes_ul_tree_container li:has(li)").addClass("liparent collapse");
                   }
                 }
		  });

      });
	  //  mce_remove() ;
	}//
</script>
<script type="text/javascript">

function 	clear_cache_admin_link(){
$.post("<?php print site_url ('main/clearcache'); ?>", { name: "John", time: "2pm" },
  function(data){
    //alert("Data Loaded: " + data);
	$('#clear_cache_admin_link').fadeOut().fadeIn();

  });

  }


</script>
<script type="text/javascript">
    $(document).ready(function() {
  //  $(".tablesorter").tablesorter();

});
</script>
<script type="text/javascript">
function deleteContentItem($id, $remove_element){

		var answer = confirm("Are you sure?")
	if (answer){
	$("#"+$remove_element).addClass("light_red_background");
		$.post("<?php print site_url('admin/content/content_delete/id:')  ?>"+$id, function(data){
						  //alert("Data Loaded: " + data);

						  if( $remove_element != false){
							  $("#"+$remove_element).fadeOut();
						  }



						});
	}
	else{
		return false;
	}


	}

	</script>
<div style="display:none;">
  <!--dialogs and ajax boxes are here-->
  <div id="deleteContentItem_dialog" title="Are you sure?" class="flora">
    <p>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
  </div>
  <div id="deleteTaxonomyItem_dialog" title="Are you sure?" class="flora">
    <p>These items will be permanently deleted and cannot be recovered, also all the children items will inherit the parent of the deleted item. Are you sure?</p>
  </div>
</div>
<script type="text/javascript">


function edit_category_dialog($id, $parent){
		//  mce_remove() ;
$('#edit_categories_ajax_form_saved_status').hide();
$.post("<?php print site_url('admin/content/taxonomy_categories_edit_by_ajax/')  ?>" , { id: $id, parent_id: $parent },
  function(data){

   // alert("Data Loaded: " + data);
   $('.the_edit_categories_ajax_container').html(data);
 // mce_setup('.category_richtext');
   $(".the_edit_categories_ajax_container_accordion").accordion({
			fillSpace: true,
			autoHeight: true,
			collapsible: true,
			minHeight: 200,
			icons: {
    			header: "ui-icon-circle-arrow-e",
   				headerSelected: "ui-icon-circle-arrow-s"
			}
		});
    $.openDOMWindow({
	height:600,
width:800,
        //loader:1,
       // loaderImagePath:'animationProcessing.gif',
        //loaderHeight:16,
        //loaderWidth:17,




        windowSourceID:'.the_edit_categories_ajax_container'
    });


	//mce_setup();
  

  });
}

function edit_category_do_ajax_save($and_close){



		var category_form_options = {
		  //  target:        '#output2',   // target element(s) to be updated with server response
		   // beforeSubmit:  showRequest,  // pre-submit callback
		    url:       '<?php print site_url("admin/content/taxonomy_categories_edit_by_ajax/")  ?>',       // override for form's 'action' attribute
        	type:      'post',        // 'get' or 'post', override for form's 'method' attribute
			success:  function(data) {
			   // $('#htmlExampleTarget').fadeIn('slow');
			   //alert(html);
			  // $('#edit_categories_ajax_form_saved_status').show();
			  if(data == 'new'){

			 	 $.closeDOMWindow({
						windowSourceID:'.the_edit_categories_ajax_container'
					});
					mce_remove()


			  } else {
						$('#edit_categories_ajax_form_saved_status').html('saved');
						$('#edit_categories_ajax_form_saved_status').html(data);
						$('#edit_categories_ajax_form_saved_status').fadeIn('slow');
						$('#edit_categories_ajax_form_saved_status').fadeOut('slow');
			  }




			   recreate_js_category_trees();
			}
		};


            if($("textarea.TaxEditor").next(".mceEditor").length>0){

               $("textarea.TaxEditor").val($("textarea.TaxEditor").next(".mceEditor").find("iframe").contents().find("body").html());
               $("textarea.TaxEditor").next(".mceEditor").remove();
            }

			$('#edit_categories_ajax_form').ajaxSubmit(category_form_options);
			if($and_close == true){
				$.closeDOMWindow({
						windowSourceID:'.the_edit_categories_ajax_container'
					});
					mce_remove()
			}

			$('#edit_categories_ajax_form').submit(function() {
        return false;
    });
			return false;
}

function delete_category_pre_set_dialog(){


$("#the_delete_category_dialog_container").dialog({
			bgiframe: true,
			resizable: false,
			autoOpen: false ,
			height:140,
			modal: true,
			overlay: {
				backgroundColor: '#000',
				opacity: 0.5
			},
			buttons: {
				'Delete all items in recycle bin': function() {
					//$(this).dialog('close');
					//return true;
					$to_del =  $("#the_delete_category_dialog_container_cat_id_to_delete").html();
					// alert($to_del);
					$.post("<?php print site_url('admin/content/taxonomy_categories_delete_by_ajax/')  ?>" , { id: $to_del },
					  function(data){
					// alert(data);
					  recreate_js_category_trees();
					  });


					 $("#the_delete_category_dialog_container_cat_id_to_delete").html('');
					 $(this).dialog('close');
				},
				Cancel: function() {
				//return false;
					//$(this).dialog('close');
					$("#the_delete_category_dialog_container_cat_id_to_delete").html('');
					$(this).dialog('close');
				}
			}
		});
}

function delete_category_ajax($id){
 $("#the_delete_category_dialog_container_cat_id_to_delete").html($id);
 $("#the_delete_category_dialog_container").dialog('open');
//alert($confirm);
}



function move_category_ajax_up($id){


  $.post("<?php print site_url('admin/content/taxonomy_categories_move_ajax/direction:up/id:')  ?>"+$id , { id: $id },
					  function(data){
					// alert(data);
					$.closeDOMWindow({
						windowSourceID:'.the_edit_categories_ajax_container'
					});
					  recreate_js_category_trees();
					  });
}

function move_category_ajax_down($id){
   $.post("<?php print site_url('admin/content/taxonomy_categories_move_ajax/direction:down/id:')  ?>" +$id, { id: $id },
					  function(data){
					// alert(data);
					$.closeDOMWindow({
						windowSourceID:'.the_edit_categories_ajax_container'
					});
					  recreate_js_category_trees();
					  });
}

</script>
<script type="text/javascript">
    $(document).ready(function(){
       $("a.treeActivation").click(function(){
          if($(this).hasClass("active")){

          }
          else{
            $("a.treeActivation").removeClass("active");
             $(".treeContent:visible").slideUp('fast');
             $(this).parent().find(".treeContent:first").slideDown('fast');
             $(this).addClass("active");
          }
          return false;
       });


    });
</script>

<div id="create_new_content_choose_category_conpatiner" style="display:none"> Please select category to add content there.
  <?php $link = site_url('admin/content/posts_edit/id:0/category:'). '{id}';
	  $link = "<a {active_code} href=$link name='{id}'>{taxonomy_value}</a>";
	 $tree_params = array();
	 $tree_params['content_parent'] = 0;
	 $tree_params['link'] = $link;
	 $tree_params['actve_ids'] = $content_selected_categories;
	 $tree_params['active_code'] = " class='active'  ";
	 $tree_params_string = $this->core_model->securityEncryptArray($tree_params);
	 ?>
  <div class="ooyes_ul_tree_container" treeparams='<?php print $tree_params_string;  ?>'></div>
  <?php $tree_params_string = false;  ?>
</div>
