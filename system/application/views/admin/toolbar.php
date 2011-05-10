<script type="text/javascript">
    static_url = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>";
</script>
<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/font.php" />
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<link href='http://fonts.googleapis.com/css?family=Molengo' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/toolbar.css?<? print rand();?>" />
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/jquery.plupload.queue.min.js"></script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/nicedit.js"></script>
<script type="text/javascript">
    static_url = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>";
</script>
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/qtip/jquery.qtip.min.js"></script>
<link href='<?php   print( ADMIN_STATIC_FILES_URL);  ?>jquery/qtip/jquery.qtip.min.css' rel='stylesheet' type='text/css'>
<script>

$(document).ready(function(){
						 
						   
						    
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
   
  
 
 
 
   $.ajax({
  url: "<? print site_url('api/module') ?>",
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

   $('#module_bar_resp').html(resp);
  // $('.mw_sidebar_module_group_table').corner();

 //$('.mw_sidebar_module_group_table').corner();
 
 
  
		//  $dragable_opts2.helper = 'original';

  $(".module_draggable").draggable({
								   
			connectToSortable: ".edit, .edit *",
						appendTo: 'body',
containment: 'window',

scroll: true,
		    helper: 'clone',
		 cursor: 'move', 
		 zIndex: 20000,
		   cancel: $('.edit *').find('.module'),
 
			//containment: 'document',
			revert: "invalid",
			
start: function(e,ui){
	
	if(window.saving ===true){
		//alert('Saving.. please wait');
	//return false;	
	}
	  $('.edit *').find('.module').droppable( "option", "disabled", true );
// $(".module").append("aaaaaaaaaaaaaa");
	//$('.edit').find('.module').css('background-color', 'red');
	
	//
	// $('.edit *').find('.module').append('<div class="mw_module_box_place js_remove">Module</div>');
	//$('.edit *').find('.module').hide(); 
	 //$("#module_temp_holder").addClass("mw_module_box_place");
	
	/*
	    $("#module_temp_holder").css({
        	 
        	    'width': 100,
        	    'height': 100  
        	    //'position': 'absolute',
        	    
        	    
        	});
         */
        
	
	
	window.mw_dragging = true;
	//$(".to_here").removeClass("to_here");
	
	
	
	
	
	        var dialog = $('#module_temp_holder');

         
         
         dialog.css({
        
        	    'width': 20,
        	    'height': 20  
        	    //'position': 'absolute',
        	    
        	    
        	});
	
	
	//dialog.show();
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   //$(this).addClass('fade');
  // ui.helper.find('.caption').text("I'm being dragged!");
  },
  stop: function(e,ui){
	  window.mw_dragging = false;
	  
	    $('.edit *').find('.module').droppable( "option", "disabled", false );
	//  $('.edit').find('.module').show();
	
	
//	$(".js_remove").remove();
	//   $('.mw_module_box_place').removeClass('mw_module_box_place');
	  
	  
	  //$(".edit .module").show();
	  
	  
	//  var dialog = $('#module_temp_holder').hide();

   //$(this).removeClass('fade');
   //ui.helper.find('.caption').text("Drag me to my target");
  }
 
  

		});
//$("#module_temp_holder").draggable($dragable_opts2);
$("#module_temp_holder").draggable( "option" , 'helper' , 'original' );



			




  }
    }); 
 
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







$(document).ready(function(){
  $(".show_editable").each(function(){
     this.checked=false;
  });


  $(".modules_list").sortable({
    connectWith:'editblock'
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


$.ctrl('S', function() {

    var content = $(".nicEdit-selected").html();
    var id = $(".nicEdit-selected").attr("id");

    nic_save(content, id);



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
			   $('*[mw_last_hover!=""]').each(function(index) {
			            		
			            		$(this).attr('mw_last_hover', '');
								// $(this).removeAttr("mw_last_hover")
								
										 });
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
 

 nic_save_all = function(callback){

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
			     $('.edit *').find('.module').droppable( "option", "disabled", true );
			  $( ".module_draggable" ).draggable( "option", "disabled", true );
			  window.saving =true;
			  $( "#ContentSave" ).fadeOut();
			  $('*[mw_last_hover!=""]').attr('mw_last_hover', '');
			  $('*[mw_last_hover!=""]').each(function(index) {
			            		
			            		$(this).attr('mw_last_hover', '');
								// $(this).removeAttr("mw_last_hover")
								
										 });
		  },
		  success: function(data) {
			  			  
			  
			  	$.ajax({
		  type: 'POST',
		  url: "<?php print site_url('api/content/save_field');  ?>",
		  data: master,
		  datatype: "jsonp",
          async:true,
		  beforeSend :  function() {
			  
		  },
		  success: function(data2) {
			     $('.edit *').find('.module').droppable( "option", "disabled", false );
			  window.saving =false;
  $( "#ContentSave" ).fadeIn();
    $( ".module_draggable" ).draggable( "option", "disabled", false );
  

		  }
		})
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			  
			//  mw.modal.alert("Content Saved");
			$(".to_here_drop").removeClass("to_here_drop");
  if(typeof window.parent.mw_edit_init == 'function'){
     window.parent.mw_edit_init(window.location.href);
  }
 //  $("#"+data[0].page_element_id).html(data[0].page_element_content);
  //$(this).html(data);
   //$('#module_temp_holder').hide();
  $.each(data, function(i, item) {
						$("#"+data[i].page_element_id).html(data[i].page_element_content);
				//		alert(item.page_element_id+item.page_element_content);
	//$("#"+data[i].page_element_id).html(data[i].page_element_content);
     
});
  
  callback.call(this);
  //mw_make_draggables();
 init_edits()
  $(".mw_edited").removeClass('mw_edited');
  $( ".module_draggable" ).draggable( "option", "disabled", false );
 
  
  // mw.modal.close();
  

		  }
		})
	
		}
	
	
	

 }



$(document).ready(function(){









 
 




// here2


myNicEditor = new nicEditor({

          fullPanel : true,
          iconsPath : "<?php print( ADMIN_STATIC_FILES_URL);  ?>js/nicEditorIcons.gif",
		  uploadURI : "<? print site_url('api/media/nic_upload') ?>",
          onSave:function(content, id, instance){

nic_save(content, id, instance);


          }

});




myNicEditor.setPanel('mw_editbar');

  $(".edit").each(function(){
     var id = mw.id();
     $(this).attr("id", "edit_" + id);

    /*here1*/     myNicEditor.addInstance("edit_" + id);


  });


          $(".edit").attr('contentEditable','false');



});



//}//end onload






</script>
<div class="toobar_container">
  <div class="toobar_container_left"> 
  
  <div style="display:none">Outline Editable Regions;
    <input type="checkbox" class="show_editable" onclick="this.checked==true?mw.outline.init('.edit', '#DCCC01'):mw.outline.remove('.edit')" />
    <br />
    Outline Draggable Regions;
    <input type="checkbox" class="show_editable" onclick="this.checked==true?mw.outline.init('.editblock', '#2667B7'):mw.outline.remove('.editblock')" />
    Toggle Browse
    <input type="checkbox"   id="enable_browse" /></div>
    
    <input type="hidden" id="mw_edit_page_id" value="<?  print(PAGE_ID); ?>" />
  <input type="hidden" id="mw_edit_post_id" value="<?  print(POST_ID); ?>" />
  <input type="hidden" id="mw_edit_category_id" value="<?  print(CATEGORY_ID); ?>" />
      <input type="hidden"  id="mw_module_param_to_copy" value="" />
      <input type="hidden"  id="module_temp_holder_id" value="" />
    <input type="hidden"  id="module_focus_holder_id" value="" />
    
    
    
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
    <input name="mw_make_draggables()" type="button" onClick="mw_make_draggables()" value="mw_make_draggables()" />
    <input name="reloadIframesreloadIframes" type="button" onClick="reloadIframes()" value="reloadIframes" />
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
    <div id="mw_toolbar_nav"> 
    
    <a class="mw_toolbar_nav_icons active"  title="Modules" rel="modules"  href="javascript:mw_sidebar_nav('#mw_sidebar_modules_holder')"> <span> <img    src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_modules.png"  style="float:left" /> </span> </a>
    
    <a   class="mw_toolbar_nav_icons" title="Design" rel="design"  href="javascript:mw_sidebar_nav('#mw_sidebar_design_holder')"> <span> <img     src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_design.png"   style="float:left" /> </span> </a>
    
    <a  class="mw_toolbar_nav_icons"  title="Add new content"  rel="add"    href="javascript:mw_sidebar_nav('#mw_sidebar_add_holder')"> <span> <img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/btn_add.png" style="float:left" /> </span> </a> 
    
    
    
    </div>
    <a href="<? print site_url('admin'); ?>" id="mw_toolbar_nav_go_to_admin"> <span> Switch to Admin </span> </a> </div>
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
<a href="#" onclick='mw.saveALL()' id="ContentSave">Save</a>
<div id="admin_sidebar">
   
  <div id="mw_sidebar_module_edit_holder">
 
  </div>
  
   
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
  <a href="#" onclick="call_edit_module_iframe('<? print site_url('api/module/iframe:'. $iframe_module_params_page) ?>/admin:y', 'aa')">Edit page</a> <br />
  <br />
  <a href="#" onclick="call_edit_module_iframe('<? print site_url('api/module/iframe:'. $iframe_module_params_post) ?>/admin:y', 'aa')">Edit post</a>
  
  <?   //p(CATEGORY_IDS); ?>
  <br />
  <br />
  <br />
  
  
  </div>
   <div id="mw_sidebar_css_editor_holder">
  
   <?  include "toolbar_tag_editor.php" ?>
  </div>
  <div id="mw_sidebar_design_holder">
  
  mw_sidebar_design_holder
  </div>
  
  
   <div id="mw_sidebar_add_holder">
  
  mw_sidebar_add_holder
  </div>
  
</div>
