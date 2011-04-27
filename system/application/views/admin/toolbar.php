<script type="text/javascript">
    static_url = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>";
</script>
<script>

$(document).ready(function(){
			 data1 = {}
   data1.module = 'admin/content/module_bar';
   data1.do_not_wrap = '1';
   
   data1.page_id = '<? print intval(PAGE_ID) ?>';
   data1.post_id = '<? print intval(POST_ID) ?>';
   data1.category_id = '<? print intval(CATEGORY_ID) ?>';
   
   
   
   
   
  
 
 
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

   $('#module_bar_resp').html(resp);

  $(".module_draggable").draggable({
			connectToSortable: ".edit",
		    helper: 'clone',
			revert: "invalid"
			//create: function(event, ui) {alert(':))')}
			
			//,            cursorAt: { left: -15, top:0 }
		});

  }
    });
 
	});
 
 
 

	</script>

<div id="mw_toolbar">
   
   

   
 
   
  <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
  <link rel="stylesheet" type="text/css" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/toolbar.css" />
  <script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.full.min.js"></script>
  <script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/jquery.plupload.queue.min.js"></script>
  <script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/nicedit.js"></script>
  <script type="text/javascript">
    static_url = "<?php   print( ADMIN_STATIC_FILES_URL);  ?>";
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
  <div id="drag_mw_toolbar"></div>
  <div id="mw_toolbar_content">
  <div id="mw_editbar"></div>
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


	$.ajax({
		  type: 'POST',
		  url: "<?php print site_url('api/content/save_field');  ?>",
		  data: obj,
          async:true,
		  success: function(data) {
			  mw.modal.alert("Content Saved");
  if(typeof window.parent.mw_edit_init == 'function'){
     window.parent.mw_edit_init(window.location.href);
  }

		  }
		})




 }

 nic_save_all = function(callback){

 var master = {}
   $(".mw_edited").each(function(j){


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
		  url: "<?php print site_url('api/content/save_field');  ?>",
		  data: master,
          async:false,
		  success: function(data) {
			//  mw.modal.alert("Content Saved");
  if(typeof window.parent.mw_edit_init == 'function'){
     window.parent.mw_edit_init(window.location.href);
  }
   $(".mw_edited").html(data);
  //$(this).html(data);
  callback.call(this)
 
  $(".mw_edited").removeClass('mw_edited');
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


<div style="width: 300px;height: 400px;position: fixed;right:10px;bottom: 10px;background: #EFECEC">
    <div id="mw_block_history"></div>
    Outline Editable Regions;
    <input type="checkbox" class="show_editable" onclick="this.checked==true?mw.outline.init('.edit', '#DCCC01'):mw.outline.remove('.edit')" />
    <br />
    Outline Draggable Regions;
    <input type="checkbox" class="show_editable" onclick="this.checked==true?mw.outline.init('.editblock', '#2667B7'):mw.outline.remove('.editblock')" />
    <p><strong>Toggle Browse</strong> </p>
    <input type="checkbox" checked="checked" id="enable_browse" />

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
           
         
           <a href="#" onclick="call_edit_module_iframe('<? print site_url('api/module/iframe:'. $iframe_module_params_page) ?>/admin:y', 'aa')">Edit page</a>
           <br />
<br />

             <a href="#" onclick="call_edit_module_iframe('<? print site_url('api/module/iframe:'. $iframe_module_params_post) ?>/admin:y', 'aa')">Edit post</a>
           
 
 
 <? // p(CATEGORY_ID); ?>
  <?   //p(CATEGORY_IDS); ?>
 

</div>


