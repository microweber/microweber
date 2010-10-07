<? if($display_posts != 'yes'): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
                <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tiny_mce/tiny_mce_popup.js"></script>
                <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tiny_mce/utils/mctabs.js"></script>
                <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tiny_mce/utils/form_utils.js"></script>
                <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tiny_mce/utils/validate.js"></script>
                <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tiny_mce/utils/editable_selects.js"></script>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Insert</title>
                <script type="text/javascript">
 
window.onload=function(){
 var ed = tinyMCEPopup.editor, t = this, f = document.forms[0];


n = ed.selection.getNode();


if ($(n).hasClass('mwFormControll') || $(n).parents(".mwFormControll").length>0){
				
//if($(n).find("microweber").length>0 || $(n).parents('.mwFormControll').find('microweber').length>0){
	
	//var mw_html = $(n).parents('.mwFormControll').find('microweber').html();
	var mw_html = $(n).parents('.mwFormControll').html();
//	var mw_html = $(n).parents('.mwFormControll').html();
	
	
	$.post("<? print $controller_url; ?>", { data:mw_html},
   function(data){
     ///alert("Data Loaded: " + data);
	 $('#body').html(data);
   });
	

				
				//}
				
				
}

	//ed.execCommand('mceInsertContent', false, 'asdasdasdasdasdasdasdasd', {skip_undo : 1});
			//ed.dom.setAttribs('__mce_tmp', args);
		//ed.dom.setAttrib('__mce_tmp', 'id', '');
			//ed.undoManager.add();

		//tinyMCEPopup.close();
}
 

function mw_content_edit_generate_and_insert_tinymce_content_code($content_id){
	 var ed = tinyMCEPopup.editor;
	n = ed.selection.getNode();

	//$(n).parents('.mwFormControll').html();
	serialized_params = '{"type":"content","to_table":"table_content","to_table_id":"'+$content_id+'","to_table_field":"content_body"}';
	$.post("<? print site_url('api/template/microweberTagsGenerate'); ?>", { params:serialized_params},
   function(data){
     //alert("Data Loaded: " + data);
	// $('#body').html(data);
	$(n).parents('.mwFormControll').html(data);

	 
$(n).html(data);


$(n).removeClass("noform");
$(n).removeClass("no-form");
$(n).find("microweber").hide();
$(n).addClass("mceNonEditable");



	//$(n).parents('.mwFormControll').children().css('background-color', 'red');
	//ed.execCommand('mceRepaint');
	$win1 = tinyMCEPopup.getWin()
	$win1.user_content_post_get_related_layout_styles();
	tinyMCEPopup.close();
	//window.user_content_post_get_related_layout_styles();
	
   });
	//user_content_post_get_related_layout_styles();
	 //window.top.user_content_post_get_related_layout_styles();
	
	
	
}






                </script>
                </head>

                <body id="body">
<? endif; ?>
<? if($display_posts == 'yes'): ?>
<br />
<?php include ACTIVE_TEMPLATE_DIR. "articles_search_bar.php" ?>
<br />
<br />
<? if(!empty($posts_data['posts'])): ?>
<? foreach ($posts_data['posts'] as $the_post): ?>
<? $show_edit_and_delete_buttons = true; ?>
<?php include ACTIVE_TEMPLATE_DIR."users/articles_list_single_post_item_tinymce.php" ?>
<? endforeach; ?>
<?php include ACTIVE_TEMPLATE_DIR."articles_paging.php" ?>
<? else : ?>
<div class="post">
                  <p> There are no posts here. Try again later. </p>
                </div>
<? endif; ?>
<? endif; ?>
<? if($display_posts != 'yes'): ?>
</body>
</html>
<? endif; ?>