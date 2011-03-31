<script type="text/javascript">
            imgurl="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/";
            jsurl="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/";
         </script>
<link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/style.css" type="text/css" media="screen"  />
<!--[if IE]><? print '<?import namespace="v" implementation="#default#VML" ?>' ?><![endif]-->

<script type="text/javascript" src="<?php print site_url('api/js/index/ui:true/no_mw_edit:true')  ?>"></script>
 
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/libs.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/functions.js"></script>
<!--<script id="mw_ready_js" type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/mw.ready.js"></script>-->
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/jquery.plupload.queue.min.js"></script>

<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>


<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery.ui.nestedSortable.js"></script>


<?
/*


<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.js"></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.autogrow.js" type="text/javascript" ></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.ajaxupload.js" type="text/javascript" ></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.masked.js" type="text/javascript" ></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.time.js" type="text/javascript" ></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.timepicker.js" type="text/javascript" ></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.datepicker.js" type="text/javascript" ></script>
<script src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/jquery.jeditable.charcounter.js" type="text/javascript" ></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.timepicker.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.autogrow.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.charcounter.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/date.js"></script>
<!--
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.bgigframe.js"></script>
-->
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.dimensions.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.datePicker.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery-jeditable/js/jquery.ajaxfileupload.js"></script>

*/
?>




<script type="text/javascript">
$(window).load(function(){
 /*
	  $(".richtext").each(function(){
		 var id = mw.id();
		 $(this).attr("id", "edit_" + id);
		// myNicEditor.addInstance("edit_" + id);
		new nicEditor({fullPanel : true, iconsPath : "<?php print( ADMIN_STATIC_FILES_URL);  ?>js/nicEditorIcons.gif",
			  uploadURI : "{SITE_URL}/api/media/nic_upload"}).panelInstance("edit_" + id);
	  });
*/
	  
});





</script>


