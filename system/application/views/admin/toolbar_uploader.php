<script>







// ******************************** UPLOADER *******************************


$(document).ready(function(){
    $(document.body).append('<div class="drag_files"></div>');

    $(".edit").mousedown(function(){
        $(this).addClass("edit_no_file_drag")
    });
    $(".edit").mouseup(function(){
         $(this).removeClass("edit_no_file_drag")
    });

    $(".edit *").live("dragenter", function(event){
       $(".edit *").removeClass("edit_drag_curr");
       $(this).addClass("edit_drag_curr");
       mw.prevent(event);


       var curr = $(".edit_drag_curr");

            var width = curr.outerWidth();
            var height = curr.outerHeight();
            var left = curr.offset().left;
            var top = curr.offset().top;

          //alert(1)
          if($(".edit_no_file_drag").length==0){
              $(".drag_files").css({
                width:width,
                display:'block',
                height:height,
                left:left,
                top:top,
                opacity:0.5,
                background:'#212D3A'
              })
          }


    });
    $(".edit *").live("dragleave", function(){
      //alert(1)
      setTimeout(function(){
          //$(".drag_files").hide();
      }, 400)

    });

    $(".edit img").each(function(){
       mw.image.edit.init(this)
    });

    $(".edit").live("click",function(){
       $(this).removeClass("no_file_drag");
    });
    $("#mw_toolbar").hide();
    $(".edit").live("mousedown",function(){
      $(this).addClass("no_file_drag");

    });
    $(".edit").live("mouseup",function(){
      $(this).removeClass("no_file_drag");
    });
    $(".edit").live("mouseout",function(){
      $(this).removeClass("no_file_drag");
    });

 	$(".drag_files").pluploadQueue({
		// General settings
		runtimes: 'gears,html5,browserplus',
		url: "<? print site_url('api/media/upload'); ?>",
		max_file_size: '10mb',
		chunk_size: '1mb',
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
          var obj = eval("(" + info.response + ")");
            //$(document.body).append(obj.url);
            var image = new Image();
            image.src = obj.url;
            mw.image.edit.init(image);
            $(".edit .plupload").remove();
            $(".edit_drag_curr").append(image);
             $(".drag_files").css({
              width:0,
              height:0,
              left:-100,
              top:-100
            })
         }
		}
	});


});

// ******************************** END UPLOADER *******************************

</script>

