<style type="text/css">
.mwFormControll {
background-color:green;
display:block;
height:100px;
width:100px;	
}
</style>


<script type="text/javascript">


/*************************************************/





/*************************************************/





$(document).ready(function(){
	//	
user_content_post_get_layouts_list();
	
	});

window.onload = function () {
   content_body_editor_init();
   
   	$style_css =  $('#content_layout_style').val();
	$layout =  $('#content_layout_name').val();
	
	var $style_css = String($style_css);
	var $layout = String($layout);
	
	
	if($layout == ''){	
		 window.location.hash='#choose-layout';
	} else {
		user_content_post_get_layout_styles($layout)
		if($style_css != ''){	
		user_content_post_swith_layout_style($style_css);
		} else {
		window.location.hash='#choose-theme';	
		}
		
	}
};

 $(document).ready(function(){
    	user_content_post_get_layouts_list();
  });
 
  

function removePostCat(id){
    $("#" + id).uncheck();
}



function user_content_post_switch_layout($name){

	
	$.post("<? print site_url('api/template/layoutGetHTML') ?>", { name: $name },
   function(data){
		  //$('#content_body').html(data);
		   var ed = tinyMCE.get('content_body');
		   ed.setContent(data);
		    $('#content_layout_name').val($name);
		   user_content_post_get_layout_styles($name);
		   window.location.hash='#choose-theme';
 
	});

}

function user_content_post_get_layouts_list(){
	
	$content_subtype = $('#content_subtype').val();
	$name = $('#content_layout_name').val();
	$.post("<? print site_url('api/template/layoutsList') ?>", { layout: $name, type: $content_subtype },
   function(data){
		  $('#layouts-list').html(data);
	});

}
    
function user_content_post_get_layout_styles($name){
	
	active_style =  $('#content_layout_style').val();
	
	$.post("<? print site_url('api/template/stylesList') ?>", { layout: $name, active_style: active_style },
   function(data){
		$('#layout-styles-list').html(data);
	});
}


function user_content_post_swith_layout_style($style_css){
	$name = $('#content_layout_name').val();




	$.post("<? print site_url('api/template/styleGetCSSPaths') ?>", { layout: $name, style: $style_css },
   function(data){
	    $('#content_layout_style').val($style_css);
	//microweberTagsGenerate
	   //tinyMCE.execCommand('mceRemoveControl',true,'content_body');
	   $('#editor_styles_css_list_layout').val(data);
	   //content_body_editor_init(data);
	   user_content_post_get_related_layout_styles();
	   window.location.hash='#edit-template';
		//$('#layout-styles-list').html(data);


        var themeFolder = $style_css;
        var themeFolder = themeFolder.replace(".css", "");
        var editor_area_content = $("#content_body").val();

       if(editor_area_content != undefined){
           var new_content = editor_area_content.replace("{STYLEURL}", themeFolder);
           $("#content_body").val(new_content);
       }





	});
}
function user_content_post_get_related_layout_styles(){
	 var ed = tinyMCE.get('content_body');

     if(ed != undefined){

	// alert(ed);
	// var n = (ed.getContent());
	 $('#editor_styles_css_list_inner_elements').val('');
	
	
	microweberTagsGenerate_options = '{"get_only_stylesheets_as_csv":"true"}';
			var n = (ed.getContainer());
			var n = $(n).find(".mceIframeContainer iframe").contents();
					if ($(n).find('.mwFormControll').length>0 || $(n).parents(".mwFormControll").length>0){
										if($(n).find("microweber").length>0 || $(n).parents('.mwFormControll').find('microweber').length>0){
										$(n).find('microweber').each(function(){
											var current_html = $(this).html();
											var parent = $(this).parents(".mwFormControll:first");			
											//tinyMCE.activeEditor.setProgressState(true);
											/* $.post(site_url+ "api/template/microweberTagsGenerate", { params: current_html },function(data){
											//parent.html(data);
											alert(data);
											//tinyMCE.activeEditor.setProgressState(false);
											}); */
											
											
											jQuery.ajax({
											 type: 	"POST",
											 data: "params="+current_html+"&options="+microweberTagsGenerate_options,
											 url:    site_url+ "api/template/microweberTagsGenerate" ,
											 success: function(result) {
														  //alert(result);
														  $temp = $('#editor_styles_css_list_inner_elements').val();
														  $('#editor_styles_css_list_inner_elements').val($temp +result );
														
														  
													  },
											 async:   false
										});     
											
											
											
					});
				}}
			//	alert($('#editor_styles_css_list_inner_elements').val());
			
			  //$temp = $('#editor_styles_css_list_inner_elements').val();
														  //alert(  $temp );
			
			
				tinyMCE.execCommand('mceRemoveControl',true,'content_body');
				content_body_editor_init();


    }

}

</script>
<textarea id="editor_styles_css_list_inner_elements"  style="display:none;"></textarea>
<textarea id="editor_styles_css_list_layout" style="display:none;"></textarea>
















