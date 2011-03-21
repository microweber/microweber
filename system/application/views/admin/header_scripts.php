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
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/nicedit.js"></script>
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
	  $(".richtext").each(function(){
		 var id = mw.id();
		 $(this).attr("id", "edit_" + id);
		// myNicEditor.addInstance("edit_" + id);
		new nicEditor({fullPanel : true, iconsPath : "<?php print( ADMIN_STATIC_FILES_URL);  ?>js/nicEditorIcons.gif",
			  uploadURI : "{SITE_URL}/api/media/nic_upload"}).panelInstance("edit_" + id);
	  });
	  
});





</script>


<script type="text/javascript" charset="utf-8"> 
 
</script>  