<?

$rand = rand();
//p($params);

if($params['page_id']){
	$for = 'content';
	$id = $params['page_id'];
}

if($params['post_id']){
	$for = 'content';
	$id = $params['post_id'];
}

if($params['category_id']){
	$for = 'category';
	$id = $params['category_id'];
}



if($for == false){
	
	$for = $params['for'];
}

if($id == false){
	
	$id = $params['for_id'];
}
?>


<div id="media_manager<? print $rand ?>"></div>


<script>



var call_media_manager<? print $rand ?> = function(){
	
	
	//alert($id);
	
	 
	 data1 = {}
   data1.module = 'admin/media/media_manager';
    data1.for = '<? print $for ?>';
	data1.for_id = '<? print $id ?>';
	data1.type = 'picture';
 
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

   $('#media_manager<? print $rand ?>').html(resp);

 

  }
    });
	
	
 
	
	
	
	
}



// ******************************** UPLOADER *******************************


$(document).ready(function(){
						   
						   call_media_manager<? print $rand ?>();
   $(document.body).append('<div class="drag_files<? print $rand ?>"></div>');

     

 	$(".drag_files<? print $rand ?>").pluploadQueue({
		// General settings
		runtimes: 'html5,flash,gears,browserplus',
		url: "<? print site_url('api/media/upload_to_library/for:'.$for.'/for_id:'.$id); ?>",
		max_file_size: '100mb',
		chunk_size: '1000mb',
		unique_names: true,


		resize: {width: 320, height: 240, quality: 90},


		filters: [
			{title: "Image files", extensions: "jpg,gif,png,bmp"},
			{title: "Zip files", extensions: "zip"}
		],


		flash_swf_url: '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.flash.swf',
        silverlight_xap_url: '<?php print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.silverlight.xap',
		preinit: {

		},


		init: {
         FilesAdded:function(up, files){

           this.start();

           /*$(".drag_files").css({
              width:0,
              height:0,
              left:-100,
              top:-100
            })  */
         },
         FileUploaded: function(up, file, info) {
			 call_media_manager<? print $rand ?>();
       //   var obj = eval("(" + info.response + ")");
            //$(document.body).append(obj.url);
        /*    var image = new Image();
            image.src = obj.url;
            mw.image.edit.init(image);
            $(".edit .plupload").remove();
            $(".edit_drag_curr").append(image);
             $(".drag_files").css({
              width:0,
              height:0,
              left:-100,
              top:-100
            })*/
         }
		}
	});


});

// ******************************** END UPLOADER *******************************

</script>
  
			
 <div class="drag_files<? print $rand ?> drag_files_here">drag files here</div>