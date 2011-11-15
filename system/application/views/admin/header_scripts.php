<script type="text/javascript">
            imgurl="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/";
            jsurl="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/";
         </script>
<link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/style.css?<?  print rand();?>" type="text/css" media="screen"  />
<!--[if IE]><? print '<?import namespace="v" implementation="#default#VML" ?>' ?><![endif]-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>
<script type="text/javascript" src="<?php print site_url('api/js/index/ui:true/no_mw_edit:true')  ?>"></script>
 
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/libs.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/functions.js"></script>
<!--<script id="mw_ready_js" type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/mw.ready.js"></script>-->
<!--<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />-->

 
<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/jquery-ui-1.8.13.custom/css/custom-theme/jquery-ui-1.8.13.custom.css"/>
<!--<link href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/css/jquery.ui.plupload.css" rel="stylesheet" type="text/css"/>-->
 

<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/jquery.ui.nestedSortable.js"></script>



<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
        $(function() {
                $('textarea.richtext').tinymce({
                        // Location of TinyMCE script
                        script_url : '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/tiny_mce/tiny_mce.js',

                        // General options
                        theme : "advanced",
                        plugins : "openKCFinder,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

                        // Theme options
                        theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                        theme_advanced_toolbar_location : "top",
                        theme_advanced_toolbar_align : "left",
                        theme_advanced_statusbar_location : "bottom",
                        theme_advanced_resizing : true,
						
						file_browser_callback: "openKCFinder",

						

                        // Example content CSS (should be your site CSS)
                        content_css : "css/content.css",

                        // Drop lists for link/image/media/template dialogs
                        template_external_list_url : "lists/template_list.js",
                        external_link_list_url : "lists/link_list.js",
                        external_image_list_url : "lists/image_list.js",
                        media_external_list_url : "lists/media_list.js",
relative_urls : false,
                        // Replace values for the template plugin
                        template_replace_values : {
                                username : "Some User",
                                staffid : "991234"
                        }
                });
        });
		
		
		
		
		

 
function ajaxfilemanager(field_name, url, type, win) {
			var ajaxfilemanagerurl = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
			var view = 'detail';
			switch (type) {
				case "image":
				view = 'thumbnail';
					break;
				case "media":
					break;
				case "flash": 
					break;
				case "file":
					break;
				default:
					return false;
			}
            tinyMCE.activeEditor.windowManager.open({
                url: "<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php?view=" + view,
                width: 782,
                height: 440,
                inline : "yes",
                close_previous : "no"
            },{
                window : win,
                input : field_name
            });
            
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

 <script type="text/javascript">
 
 
 

 function openKCFinder(field_name, url, type, win) {
	 
	 
	/* window.KCFinder = {};
    window.KCFinder.callBack = function(url) {
        window.KCFinder = null;
		$('#href').value = url;
		 field_name.value = url;
		 alert(url);
		  alert(field_name);
        // Actions with url parameter here
    };*/
	 
    tinyMCE.activeEditor.windowManager.open({
        file: '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/tiny_mce/plugins/kcfinder/browse.php?opener=tinymce&type=' + type + '&dir=' + type + '/public',
        title: 'File browser',
        width: 700,
        height: 500,
        resizable: "yes",
        inline: true,
        close_previous: "no",
        popup_css: false
    }, {
        window: win,
        input: field_name
    });
    return false;
}
 
 
 
</script>

 
<script type="text/javascript">
 
	
 

</script>
 



<!--

<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/cle/jquery.cleditor.css" />


<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/cle/jquery.cleditor.js"></script>


<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/cle/jquery.cleditor.xhtml.js"></script>


<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/cle/jquery.cleditor.icon.js"></script>


<script type="text/javascript">
      $(document).ready(function() {
        $(".richtext").cleditor({width:        950});
      });
    </script>
    -->
    
    
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


<script type="text/javascript">


function quick_live_save(){
	//window.frames[0].mw_saveALL(true);

	// window.frames['quick_live_edit_frame'].mw_saveALL(true);


 

//window.frames['quick_live_edit_frame'].
 
 	mw.reload_module('admin/posts/edit');
 	mw.reload_module('admin/pages/edit');
	
	 

}

function quick_live_edit($content_id){
	
	post_id = $content_id;
	 $.get('<? print site_url('api/content/get_url');?>/id:'+post_id , function(data) {
		  data123 = data + '/editmode:y';
		  
		  
		  
		  
		  frobj = {};
   frobj.src = data123;
    frobj.title = 'Quick live edit';
   frobj.width = $(window).width()-200;
   frobj.height = $(window).height()-160; 
    frobj.id = 'quick_live_edit_frame'; 
   frobj.onclose = function(){
      //     alert(1);
	  
	  
	 // window.frames['quick_live_edit_frame'].window.location.reload(); 
	  
          }; 
		  
		   // frobj.buttons = '<a href="javascript:quick_live_save()" class="xbtn"><b>Save</b></a>'
		  
		  
		  
   
  
    
 
  mw.modal.iframe(frobj);	
		 
		 
				});
	 
	 
	 
	 
	

	
}
</script>
 




