<script type="text/javascript">
    static_url = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>";
</script>
<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/font.php" />
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<script type="text/javascript" src="<?  print site_url('api/js'); ?>/index/load_editmode:true"></script>
<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/toolbar.css?<? print rand();?>" />
<!--<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/jquery.plupload.queue.min.js"></script>-->
<!--<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/nicedit.js"></script>-->
<!--<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/aloha-0.9.3/aloha/aloha.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/aloha-0.9.3/aloha/plugins/com.gentics.aloha.plugins.Format/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/aloha-0.9.3/aloha/plugins/com.gentics.aloha.plugins.Table/plugin.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/aloha-0.9.3/aloha/plugins/com.gentics.aloha.plugins.List/plugin.js"></script>-->
<? include('headers_shared.php')  ?>
<script type="text/javascript">
    static_url = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>";
</script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/qtip/jquery.qtip.min.js"></script>
<link href='<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/qtip/jquery.qtip.min.css' rel='stylesheet' type='text/css'>
<script>

$(document).ready(function(){
						 
						   
		mw_load_history_module();				    
			data1 = {}
   data1.module = 'admin/content/module_bar';
   data1.do_not_wrap = '1';
   
   data1.page_id = '<? print intval(PAGE_ID) ?>';
   data1.post_id = '<? print intval(POST_ID) ?>';
   data1.category_id = '<? print intval(CATEGORY_ID) ?>';
   
   
   
   
$("#admin_sidebar").live("hover",function(){

 $("#module_temp_holder_id").val(''); 
  $("#module_temp_holder").hide(); 
 });
   
  
 	  $('#module_bar_resp').load('<? print site_url('api/module') ?>',data1, function() {
  //alert('Load was performed.');
  mw_sidebar_make_sortables()
});
	   
 
 
//   $.ajax({
//  url: "<? print site_url('api/module') ?>",
//   type: "POST",
//      data: data1,
//
//      async:true,
//
//  success: function(resp) {
//
//   $('#module_bar_resp').html(resp);
//  // $('.mw_sidebar_module_group_table').corner();
//
// //$('.mw_sidebar_module_group_table').corner();
// 
// mw_sidebar_make_sortables()
//  
//		//  $dragable_opts2.helper = 'original';
// 
//			
//
//
//
//
//  }
//    }); 
 
	});
 
 
 

	</script>
<script type="text/javascript">

$(document).ready(function(){
						   

						   
						   
						   

//						   
//						   
//						   $('body').mousedown(function(event) {
//    switch (event.which) {
//        case 1:
//           // alert('Left mouse button pressed');
//            break;
//        case 2:
//		
//		var $checkbox = $(this).find('#enable_browse:checkbox');
//       $checkbox.attr('checked', !$checkbox.attr('checked'));
//	   mw.prevent(event);
//          //  alert('Middle mouse button pressed');
//            break;
//        case 3:
//           // alert('Right mouse button pressed');
//            break;
//        default:
//           // alert('You have a strange mouse');
//    }
//});
//						   
						   

 
});


 
</script>
<script>





//window.onload = function(){



/*    $(".module_accordion li").draggable({
			connectToSortable: ".edit",
		    helper: 'clone',
			revert: "invalid",
            cursorAt: { left: -15, top:0 }
		});*/
	
	







    mw.upload = function(elem){

    var uploader = new plupload.Uploader({
		runtimes: 'html5,flash,html4',
		url: "<? print site_url('api/media/upload'); ?>",
		max_file_size: '10mb',
		chunk_size: '1mb',
		unique_names: true,
        id:"instance_"+elem,
        container:elem,



		resize: {width: 320, height: 240, quality: 90},


		filters: [
			{title: "Image files", extensions: "jpg,gif,png,bmp"},
			{title: "Zip files", extensions: "zip"}
		],


		flash_swf_url: '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.flash.swf'
    });

  uploader.bind('FilesAdded', function(up, files) {
    //alert(1)
    uploader.start();
  });

  uploader.bind('init', function(up, files) {

  });



  //  uploader.init();
//

    return uploader;
}


/*

$.ctrl = function(key, callback, args) {
    $(document).keydown(function(event) {
        if(!args) args=[]; // IE barks when args is null
        if(event.keyCode == key.charCodeAt(0) && event.ctrlKey) {
            callback.apply(this, args);

             event.preventDefault();

		   if (event.stopPropagation) {
			    event.stopPropagation();
			  } else {
			    event.cancelBubble = true;
			  }

            return false;
        }

    });
};
*/






$(document).ready(function(){
  $(".show_editable").each(function(){
     this.checked=false;
  });


  $(".modules_list").sortable({
   // connectWith:'editblock'
  });



  $(".editblock").each(function (index, domEle) {
      // domEle == this
      $editblock_id = $(domEle).attr("id");
      $(domEle).find(".mw_save").remove();
     // $(domEle).append('<a class="mw_save" href="javascript:save_editblock(\''+$editblock_id+'\');">save</a>');
   /*   if ($(this).is("#stop")) {
        $("span").text("Stopped at div index #" + index);
        return false;
      }*/
  });


 




   

});








nic_save = function(content, id, instance){



 


var nic_obj = {};


var attrs = $("#"+id)[0].attributes;
for(var i=0;i<attrs.length;i++) {
    temp1 = attrs[i].nodeName;
    temp2 = attrs[i].nodeValue;

      if((temp2!=null) && (temp1 != null) && (temp1 != undefined) && (temp2 != undefined)){

        if((new String(temp2).indexOf("function(") == -1)&& (temp2 !="")  && (temp1 != "")){
          nic_obj[temp1] =temp2;
      }
    }

}



//var nic_obj = mw.singleline(nic_obj);



//var nic_obj = '{' + nic_obj + '}';




var obj = {
    attributes:nic_obj,
    html : content
}




/*
$.each(obj.attributes, function(i, val) {
      alert(i+" = "+val)
    });
*/
$(".js_generated").remove();




// window.saving =true;


	$.ajax({
		  type: 'POST',
		  url: "<?php print site_url('api/content/save_field');  ?>",
		  data: obj,
		 
          async:true,
		  complete: function(){
			  window.saving =false;
		  },
		  beforeSend :  function() {
			  window.saving =true;
		 
		  },
		  success: function(data) {
			   window.saving =false;
			   
			   
			   
			   
			  mw.modal.alert("Content Saved");
  if(typeof window.parent.mw_edit_init == 'function'){
     window.parent.mw_edit_init(window.location.href);
  }

		  }
		})




 }
 

 nic_save_all = function(callback, only_preview){
$(".mw_non_sortable").removeClass('mw_non_sortable');
 var master = {}
  // $(".mw_edited").each(function(j){
								   $(".edit").each(function(j){


 //$(this).addClass("mw_edited");

var nic_obj = {};





var attrs = $(this)[0].attributes;
for(var i=0;i<attrs.length;i++) {
    temp1 = attrs[i].nodeName;
    temp2 = attrs[i].nodeValue;

      if((temp2!=null) && (temp1 != null) && (temp1 != undefined) && (temp2 != undefined)){

        if((new String(temp2).indexOf("function(") == -1)&& (temp2 !="")  && (temp1 != "")){
          nic_obj[temp1] =temp2;
      }
    }

}
var content = $(this).html();

var obj = {
    attributes:nic_obj,
    html : content
}
var objX = "field_data_"+j;
master[objX] = obj;




   });
		
		
			//  mw.modal.overlay();
		
	$emp = isEmpty(master);
		if ($emp == true){
		 
			
		} else {
		




   	$.ajax({
		  type: 'POST',
		  url: "<?php print site_url('api/content/save_field/peview:true');  ?>",
		  data: master,
		  datatype: "jsonp",
          async:true,
		  beforeSend :  function() {
			
			  window.saving =true;
			  $( "#ContentSave" ).fadeOut();
		 
		  },
		  success: function(data) {
			  			  
			  if(only_preview  == undefined){
			  	$.ajax({
		  type: 'POST',
		  url: "<?php print site_url('api/content/save_field');  ?>",
		  data: master,
		  datatype: "json",
          async:true,
		  beforeSend :  function() {
			  	  window.mw_sortables_created = false;
 
		  },
		  success: function(data2) {
			  
			  window.saving =false;
  $( "#ContentSave" ).fadeIn();
 //   $( ".module_draggable" ).draggable( "option", "disabled", false );
   window.mw_sortables_created = false;
	  window.mw_drag_started = false;
	  mw_make_editables()
 
		remove_sortables()
 
  init_edits()
  mw_load_history_module()
		  
		   

		  }
		})
			  
			  
			  
			  
			 } 
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			//  mw.modal.alert("Content Saved");
	//		$(".to_here_drop").removeClass("to_here_drop");
  if(typeof window.parent.mw_edit_init == 'function'){
     window.parent.mw_edit_init(window.location.href);
  }
 //  $("#"+data[0].page_element_id).html(data[0].page_element_content);
  //$(this).html(data);
   //$('#module_temp_holder').hide();
 
 
 if(only_preview  == undefined){
 $.each(data, function(i, item) {
						$("#"+data[i].page_element_id).html(data[i].page_element_content);
				//		alert(item.page_element_id+item.page_element_content);
	//$("#"+data[i].page_element_id).html(data[i].page_element_content);
     
});
  
  
  //mw_make_draggables();
 init_edits()
 
 }
 
 callback.call(this);
 
 
 
 
 
 
 
 
 
 
//  $(".mw_edited").removeClass('mw_edited');
 // $( ".module_draggable" ).draggable( "option", "disabled", false );
 
  
  // mw.modal.close();
  

		  }
		})
	
		}
	
	
	

 }



$(document).ready(function(){

window.mw_sortables_created = false;
init_edits()
	setup()





 
 




// here2

//
//myNicEditor = new nicEditor({
//
//          fullPanel : true,
//          iconsPath : "<?php print( ADMIN_STATIC_FILES_URL);  ?>js/nicEditorIcons.gif",
//		  uploadURI : "<? print site_url('api/media/nic_upload') ?>",
//          onSave:function(content, id, instance){
//
//nic_save(content, id, instance);
//
//
//          }
//
//});
//
//
//
//
//myNicEditor.setPanel('mw_editbar');
//
//   $(".edit").each(function(){
//   //  var id = mw.id();
//      var id = $(this).attr("id");
//
//      myNicEditor.addInstance(id);
//
//
//  });


        //  $(".edit").attr('contentEditable','false');



});



//}//end onload

function mw_load_history_module(){
	
	
	data1 = {}
   data1.module = 'admin/mics/edit_block_history';
   
   
   data1.page_id = '<? print intval(PAGE_ID) ?>';
   data1.post_id = '<? print intval(POST_ID) ?>';
   data1.category_id = '<? print intval(CATEGORY_ID) ?>';
   data1.for_url = document.location.href;
   
   
    $('#history_module_resp').load('<? print site_url('api/module') ?>',data1);
//   $.ajax({
//  url: "<? print site_url('api/module') ?>",
//   type: "POST",
//      data: data1,
//
//      async:true,
//
//  success: function(resp) {
//
//   $('#history_module_resp').html(resp);
// 
//
//
//
//  }
//    }); 
	
	
}




</script>
<div class="toobar_container">
  <div class="toobar_container_left">
    <div style="display:none">Outline Editable Regions;
      <input type="checkbox" class="show_editable" onclick="this.checked==true?mw.outline.init('.edit', '#DCCC01'):mw.outline.remove('.edit')" />
      <br />
      Outline Draggable Regions;
      <input type="checkbox" class="show_editable" onclick="this.checked==true?mw.outline.init('.editblock', '#2667B7'):mw.outline.remove('.editblock')" />
      Toggle Browse
      <input type="checkbox"   id="enable_browse" />
      <input type="text" id="mw_edit_page_id" value="<?  print(PAGE_ID); ?>" />
      <input type="text" id="mw_edit_post_id" value="<?  print(POST_ID); ?>" />
      <input type="text" id="mw_edit_category_id" value="<?  print(CATEGORY_ID); ?>" />
      <input type="text"  id="mw_module_param_to_copy" value="" />
      <input type="text"  id="module_temp_holder_id" value="" />
      <input type="text"  id="module_focus_holder_id" value="" />
    </div>
    <a href="<? print site_url('admin'); ?>" class="mw_toolbar_nav_go_to_admin"> <span> Switch to Admin </span> </a>
    <div id="buttons"> </div>
    <div id="sortable-delete" style="display:none;" class="edit">del <br />
      <br />
    </div>
    <div class="module_draggable"  id="module_temp_holder">
      <textarea rows="1"  style="display: none;" id="module_temp_holder_value">test</textarea>
    </div>
    <script>
    
    reloadIframes = function()
{
    var allFrames = document.getElementsByTagName("iframe");
    for (var i = 0; i < allFrames.length; i++)
    {
        var f = allFrames[i];
        f.contentDocument.location = f.src;
    }
}
    </script>
    <!--  <input name="mw_load_history_module()" type="button" onClick="mw_load_history_module()" value="mw_load_history_module()" />-->
    <!--<input name="reloadIframesreloadIframes" type="button" onClick="reloadIframes()" value="reloadIframes" />-->
  </div>
  <div class="toobar_container_right">
    <div id="mw_toolbar_pre_nav"> <span id="mw_toolbar_pre_nav_title">Live edit</span> <a href="#">
      <?  if(($_COOKIE['mw_live_edit_state'] == 'on')or($_COOKIE['mw_live_edit_state'] == false) ){
		
	$mw_live_edit_state_class = 'mw_live_edit_on_off_state_on';
	} else {
		
		$mw_live_edit_state_class = 'mw_live_edit_on_off_state_off';
	}
	
	
	?>
      <img class="mw_live_edit_on_off <? print $mw_live_edit_state_class; ?>"  src="#" title="live edit"  /> </a>
      <script>
$(document).ready(function(){
			   
			   
			   
   // We'll target all AREA elements with alt tags (Don't target the map element!!!)
   $('.mw_toolbar_nav_icons').qtip(
   { 
      content: {
         attr: 'title' // Use the ALT attribute of the area map for the content
      }, 
	   position: {
         my: 'top left',
         target: 'mouse',
         viewport: $(window), // Keep it on-screen at all times if possible
         adjust: {
            x: 5,  y: 5
         }
      },
      hide: {
         fixed: true // Helps to prevent the tooltip from hiding ocassionally when tracking!
      },
      style: {
         classes: 'ui-tooltip-tipsy ui-tooltip-shadow'
      }
   });
 
			   
			   
			   
			  mw_live_edit_state =  $.cookie("mw_live_edit_state")
			   if(mw_live_edit_state == 'on'){
				$('.mw_live_edit_on_off').addClass('mw_live_edit_on_off_state_on');
				$('.mw_live_edit_on_off').removeClass('mw_live_edit_on_off_state_off');
				$('.mw_live_edit_on_off').attr({src:"<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_on.png"});
				//mw_live_edit_on_off_switch(true);
				 $('#admin_sidebar').show('slide', { direction: 'right' }, 300)

			   }
			   
			    if(mw_live_edit_state == 'off' || mw_live_edit_state == undefined){
				$('.mw_live_edit_on_off').removeClass('mw_live_edit_on_off_state_on');
				$('.mw_live_edit_on_off').addClass('mw_live_edit_on_off_state_off');
				$('.mw_live_edit_on_off').attr({src:"<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_off.png"});
				//mw_live_edit_on_off_switch(true);
				 $('#admin_sidebar').hide('slide', { direction: 'right' }, 300)
				   }
			//   alert(mw_live_edit_state);
			   
$('.mw_live_edit_on_off').click(
								function () {
   mw_live_edit_on_off_switch(false);
								}
);
//mw_live_edit_on_off_switch();


 

});

function mw_live_edit_on_off_switch($no_cookie){
	
  
                if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_on')) {
            $('.mw_live_edit_on_off').removeClass('mw_live_edit_on_off_state_on').addClass('mw_live_edit_on_off_state_off');
			if($no_cookie == false){
			$.cookie("mw_live_edit_state", "off", { expires: 7 });
			}
			$('.mw_live_edit_on_off').attr({src:"<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_off.png"});
			  $('#admin_sidebar').hide('slide', { direction: 'right' }, 300)
        } else if ($('.mw_live_edit_on_off').hasClass('mw_live_edit_on_off_state_off')) {
			
			if($no_cookie == false){
			$.cookie("mw_live_edit_state", "on", { expires: 7 });
			}
			 $('#admin_sidebar').show('slide', { direction: 'right' }, 300)
            $('.mw_live_edit_on_off').removeClass('mw_live_edit_on_off_state_off');
			 $('.mw_live_edit_on_off').addClass('mw_live_edit_on_off_state_on');
			$('.mw_live_edit_on_off').attr({src:"<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_on.png"});
        } else {
			if($no_cookie == false){
			$.cookie("mw_live_edit_state", "off", { expires: 7 });
			}
			 $('#admin_sidebar').hide('slide', { direction: 'right' }, 300)
             $('.mw_live_edit_on_off').removeClass('mw_live_edit_on_off_state_off').addClass('mw_live_edit_on_off_state_on');
			$('.mw_live_edit_on_off').attr({src:"<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_on.png"});
        }
 
 



}

//}//end onload




function mw_sidebar_nav($selector){
	
	
	
	
	$('#mw_sidebar_modules_holder').hide();
	$('#mw_sidebar_module_edit_holder').hide();
	$('#mw_sidebar_design_holder').hide();
	$('#mw_sidebar_add_holder').hide();
	
	$('#mw_sidebar_css_editor_holder').hide();
	
	
	
	
	$($selector).show();
	
	
	
	
}



</script>
    </div>
    <div id="mw_toolbar_nav"> <a class="mw_toolbar_nav_icons active"  title="Modules" rel="modules"  href="javascript:mw_sidebar_nav('#mw_sidebar_modules_holder')"> <span> <img    src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_modules.png"  style="float:left" /> </span> </a> <a   class="mw_toolbar_nav_icons" title="Design" rel="design"  href="javascript:mw_sidebar_nav('#mw_sidebar_design_holder')"> <span> <img     src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_design.png"   style="float:left" /> </span> </a> <a  class="mw_toolbar_nav_icons"  title="Add new content"  rel="add"    href="javascript:mw_sidebar_nav('#mw_sidebar_add_holder')"> <span> <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_add.png" style="float:left" /> </span> </a> </div>
    <a href="adasd" class="mw_toolbar_nav_go_to_admin"> <span> Style element </span> </a>
    <div id="mw_sidebar_styler"> </div>
  </div>
</div>
<div id="mw_toolbar">
  <div id="drag_mw_toolbar"></div>
  <div id="mw_toolbar_content">
    <div id="mw_editbar"></div>
    <? // include "toolbar_uploader.php" ?>
    <? /*
       	<div style="width: 250px;" >
			<div id="dropzone" style="background-color: aqua; width: 100%; height: 200px;" ></div>
			<div id="dropzone-info" style="width: 500px;" ></div>
		</div>
       	*/ ?>
  </div>
</div>
<div id="edit_table" style="display: none">
  <input type="text" onkeyup="alert(cellID)" />
</div>
<div class="mw_bottom_nav"> <a href="#" class="mw_bottom_btn" onclick='mw.saveALL()' id="ContentSave">Save</a>
  <div id="history_module_resp"></div>
</div>
<div id="admin_sidebar">
  <div id="mw_sidebar_module_edit_holder"> </div>
  <div id="mw_sidebar_modules_holder">
    <div class="search_container">
      <table width="250" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><form class="mw_sidebar_searchform">
              <div class="mw_sidebar_searchform_input_holder">
                <input class="mw_sidebar_searchform_searchfield" type="text" value="Search..." onfocus="if (this.value == 'Search modules...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search modules...';}" />
              </div>
            </form></td>
          <td align="right"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/help.png" hspace="5" /></td>
        </tr>
      </table>
    </div>
    <div class="mw_admin_sidebar_text" > <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/drag.png" hspace="5" style="float:left" /> Drag and drop the modules in your website <a target="_blank" href="http://microweber.com">(see how)</a> </div>
    <br />
    <div id="mw_block_history"></div>
    <div class="modules_list"> </div>
    <div id="module_bar_resp"></div>
    <? 
           $iframe_module_params = array();
           $iframe_module_params['module'] = 'admin/pages/edit';
           $iframe_module_params['id'] = PAGE_ID;
 
	 
           $iframe_module_params_page = base64_encode(serialize($iframe_module_params));
		   
		   
		      $iframe_module_params = array();
           $iframe_module_params['module'] = 'admin/posts/edit';
           $iframe_module_params['id'] = intval(POST_ID);
 
	 
           $iframe_module_params_post = base64_encode(serialize($iframe_module_params));
           
           
           
           
           ?>
    <!--  <a href="#" onclick="call_edit_module_iframe('<? print site_url('api/module/iframe:'. $iframe_module_params_page) ?>/admin:y', 'aa')">Edit page</a> <br />
  <br />
  <a href="#" onclick="call_edit_module_iframe('<? print site_url('api/module/iframe:'. $iframe_module_params_post) ?>/admin:y', 'aa')">Edit post</a>-->
    <?   //p(CATEGORY_IDS); ?>
  </div>
  <div id="mw_sidebar_css_editor_holder">
    <?  include "toolbar_tag_editor.php" ?>
  </div>
  <div id="mw_sidebar_design_holder">
    <?  include "toolbar_design_editor.php" ?>
  </div>
  <div id="mw_sidebar_add_holder"> mw_sidebar_add_holder </div>
  <div id="mw_sidebar_html_element_holder">
    <input  id="style_mw_id" disabled="disabled"  value="" style="display:none;" />
    <input  id="style_mw_tag" onclick="mw_html_tag_editor()" value=""  />
  </div>
</div>

<? $iframe = page_link(url_param('page_id'));  ?>
<? print $iframe ?>
<iframe src="<? print $iframe ?>" height="1500" width="1200"></iframe>
