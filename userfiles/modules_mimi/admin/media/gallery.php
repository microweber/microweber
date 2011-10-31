<? //p($params); ?>
<? if($params['quick_edit'] and $params['module_id']) :   ?>
<div class="mw_iframe_sub_header" >
  <table width="95%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><? print $config['description']; ?>
        <!--<a target="_blank" href="http://microweber.com">(see how)</a>--></td>
      <td><a target="_blank" href="<? print $config['help_link']; ?>"><img  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/toolbar/help.png" hspace="5" /></a></td>
    </tr>
  </table>
</div>
<? endif; ?>
<? //  p($params); ?>
<?   //   p($config); ?>
<?
$rand = rand();
    $call_media_manager =  $rand;
?>
<script type="text/javascript">
//// Convert divs to queue widgets when the DOM is ready
//$(document).ready(function(){
//	$("#uploader").plupload({
//		// General settings
//		runtimes : 'flash,silverlight,browserplus,html5, html4',
//		url : 'upload.php',
//		max_file_size : '10mb',
//		chunk_size : '1mb',
//		unique_names : true,
//
//		// Resize images on clientside if we can
//		resize : {width : 320, height : 240, quality : 90},
//
//		// Specify what files to browse for
//		filters : [
//			{title : "Image files", extensions : "jpg,gif,png"},
//			{title : "Zip files", extensions : "zip"}
//		],
//
//		// Flash settings
//		flash_swf_url : '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.flash.swf',
//
//		// Silverlight settings
//		silverlight_xap_url : '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.silverlight.xap'
//	});
//
//	// Client side form validation
//	$('form').submit(function(e) {
//		var uploader = $('#uploader').pluploadQueue();
//
//		// Validate number of uploaded files
//		if (uploader.total.uploaded == 0) {
//			// Files in queue upload them first
//			if (uploader.files.length > 0) {
//				// When all files are uploaded submit form
//				uploader.bind('UploadProgress', function() {
//					if (uploader.total.uploaded == uploader.files.length)
//						$('form').submit();
//				});
//
//				uploader.start();
//			} else
//				alert('You must at least upload one file.');
//
//			e.preventDefault();
//		}
//	});
//});
</script>
<div class="formitem">
  <div style="display:none"> <a href="javascript:set_media_type_dropdown<? print $rand ?>('picture')" class="btn">picture</a> <a href="javascript:set_media_type_dropdown<? print $rand ?>('video')" class="btn">video</a>
    <select name="media_type" id="media_type<? print $rand ?>" onchange="call_media_manager<? print $rand ?>()">
      <option value="picture">picture</option>
      <option value="video">video</option>
    </select>
  </div>
</div>
<div id="image_embed_media">
  <? if($params['quick_edit']) { $qe_class = 'quick_edit' ;} else { $qe_class = false ;}  ?>
  <div id="admin_pl<? print $rand ?>" class="drag_files<? print $rand ?> drag_files_here <? print $qe_class ?>">drag files here</div>
  <div  class="drag_files_here_more<? print $rand ?>" style="display:none;"> <small> <a href="javascript:make_the_uploader<? print $rand ?>()"><strong>Click here add more files</strong></a> </small> </div>
  <div id="media_manager<? print $rand ?>" class="<? print $qe_class ?>">media_manager</div>
</div>
<?
 

 //p($params);

if(intval($params['page_id'])  > 0){
	$for = 'content'; 
	$id = $params['page_id'];
}



if(intval($params['post_id']) > 0){
	$for = 'content';
	$id = $params['post_id'];
}

if(intval($params['category_id']) > 0){
	//$for = 'category';
	//$id = $params['category_id'];
}

if(intval($params['content_id'])  > 0){
	$for = 'content'; 
	$id = $params['content_id'];
}
 
if($for == false){
	
	$for = $params['for'];
}

if($params['for_id']!= false){
	
	$id = $params['for_id'];
}
 
 if(intval($params['for_id'])  > 0){
	$for = 'content'; 
	$id = $params['for_id'];
}
 
 
 
if($for == false){
	
	$for =  'content';
}

if($id == false){
	
	$id = url_param('page_id');
} 
 
 if($module_id == false){
	 if($params['module_id'] != false){
	
	$module_id = $params['module_id'];
	 }
}






?>
 

<input type="hidden" name="queue_id" value="<? print $rand ?>" />
<script type="text/javascript">



function call_media_manager<? print $call_media_manager; ?>() {

	
	//alert($id);
		 data1 = {}
   data1.module = 'admin/media/media_manager';
    data1.for_what = '<? print $for ?>';
	data1.module_id = '<? print $module_id ?>';
	data1.quick_edit = '<? print $params['quick_edit']; ?>';
	data1.for_id = '<? print $id ?>';
	data1.queue_id = '<? print $rand ?>';
	//data1.type = 'picture';
 data1.type =  $("#media_type<? print $rand ?>").val();
 
 
 
 
	
	$('#media_manager<? print $rand ?>').load('<? print site_url('api/module') ?>',data1);

	 

	 
//	 data1 = {}
//   data1.module = 'admin/media/media_manager';
//    data1.for_what = '<? print $for ?>';
//	data1.module_id = '<? print $module_id ?>';
//	data1.quick_edit = '<? print $params['quick_edit']; ?>';
//	data1.for_id = '<? print $id ?>';
//	data1.queue_id = '<? print $rand ?>';
//	//data1.type = 'picture';
// data1.type =  $("#media_type<? print $rand ?>").val();
//   $.ajax({
//  url: '<? print site_url('api/module') ?>/rand:'+Math.random(),
//   type: "POST", 
//      data: data1,
//	  cache: false,
//	 dataType: "html",
//global: false,
//  
////dataType: 'script',
//      async:false,
//
//  success: function(resp) {
//
//// alert(resp);
//  
// 
//  // $('#toobar_container').html(resp);
//   
//   
//  //  $('#admin_sidebar').html(resp);
//  
////  elems = resp 
////  if (window.console != undefined) {
////	console.log('elems '+elems);
////}
////  
////  elems.filter("script").appendTo("head"); //now do whatever with the scripts 
////  console.log('elems '+elems.filter("script"));
////  elems.filter(":not(script)").appendTo('#media_manager<? print $rand ?>'); //e.g.
////  
////  
////  
//	
//	//alert(resp)
//	//$('#media_manager<? print $rand ?>').html(resp);
//	
//	//$('#mw_toolbar').html(resp);
//	
//	//innerHTML 
//	
//	var d = resp.getElementsByTagName("script")
//var t = d.length
//for (var x=0;x<t;x++){
//var newScript = document.createElement('script');
//newScript.type = "text/javascript";
//newScript.text = d[x].text;
//document.getElementById('head').appendChild (newScript);
//
//}
//
//
//
//	
//	$elem = gEBI('media_manager<? print $rand ?>');
//	$elem.innerHTML =resp; 
//	//alert($('#media_manager<? print $rand ?>').html())
//	
//    $('#media_manager<? print $rand ?>').show();
// 
// 
// 
// 
//	asdfgg();
//
// 
// //mw.ready = function(elem, callback) 
// 
//  //  $('#admin_sidebar').append(resp);
//
//   $("#image_embed_media").hide();
//   $("#video_embed_media").hide();
//
//   if($("#media_type<? print $rand ?>").val()=='video'){
//       $("#video_embed_media").show();
//   }
//   else{
//      $("#image_embed_media").show();
//   }
//
// //mw.reload_module('media/gallery');
//
//  }
//    });
//	
//	
// 
	
	
	
	
}

function set_media_type_dropdown<? print $rand ?>($newval){
	
 
	
	
	
$("#media_type<? print $rand ?>").val($newval);
 
call_media_manager<? print $rand ?>()
}

// ******************************** UPLOADER *******************************

function upload_by_embed<? print $rand ?>(){
	
 
	
	
	
	embed_code =  $("#embed_code").val();
	screenshot_url =  $("#screenshot_url").val();
	original_link =  $("#original_link").val();
	media_type =  $("#media_type<? print $rand ?>").val();
	
	media_name =  $("#media_name").val();
	media_description =  $("#media_description").val();
	
	
	
	
	$.post("<? print site_url('api/media/upload_to_library/'); ?>", { 'for':'<? print $for?>',  'for_id':'<? print $id?>',  'queue_id':'<? print $rand?>',media_name:media_name, media_description:media_description, embed_code: embed_code, media_type:media_type, screenshot_url: screenshot_url, original_link: original_link }  ,
   function(data) {
     //alert("Data Loaded: " + data);
	  call_media_manager<? print $rand ?>();
	  
   });
	
	
	
 
 

}


 




//
//window.onload = function () {
//
//
//}
$(document).ready(function(){
						//  alert(1);
						  
						  make_the_uploader<? print $rand ?>()
						  
			 call_media_manager<? print $rand ?>();
  // $(document.body).append('<div class="drag_files<? print $rand ?>"></div>');

    // 

 	
 

});


function make_the_uploader<? print $rand ?>(){
	
	
	$(".drag_files_here_more<? print $rand ?>").show();
	
	
	
$(".drag_files<? print $rand ?>").pluploadQueue({
		// General settings
		runtimes: 'html5,flash,gears,browserplus,html4',
		url: "<? print site_url('api/media/upload_to_library/for:'.$for.'/for_id:'.$id.'/queue_id:'.$rand.'/module_id:'.$module_id); ?>",
		//max_file_size: '100mb',
		chunk_size: '1000000000mb',
		unique_names: false,
		browse_button : 'admin_pl<? print $rand ?>',


		//resize: {width: 320, height: 240, quality: 90},


		filters: [
			{title: "Image files", extensions: "jpg,gif,png,bmp"}
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
			 
			   $(".drag_files_here_more<? print $rand ?>").show();
   call_media_manager<? print $rand ?>();
   
   
 
   
   setTimeout("mw.reload_module('media/gallery')",1000)
			 
			 
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



//$('div.plupload').css('z-index','99999');

	
}

// ******************************** END UPLOADER *******************************

</script>
<? if($params['element_id'] and $params['module_id']) :   ?>









<script type="text/javascript">


 
  $(function() {
  vg = $('#mw_gallery_skin_setting_<? print $rand ?>').val();
  if( vg != ''){
	  gallery_setting_ShowHide<? print $rand ?>(vg)
  }
  
  
  
  mw_forms.make_fields()

    });
   
	
	
 
 
 
 
function gallery_setting_ShowHide<? print $rand ?>(id) {
	
	id = 'gallery_skin_setting<? print $rand ?>_'+id;
	 $('.gallery_skin_setting<? print $rand ?>').hide();
//	 alert(id);
 $("#"+id).show();
 
 
}

function gallery_rld<? print $rand ?>(id) {
	
mw.reload_module('media/gallery');
 
 
}
</script>





<span class="mw_sidebar_module_box_title">Gallery settings</span>
<div class="mw_admin_rounded_box">
  <div class="mw_admin_box_padding">
    <table width="100%" border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td colspan="2"><label>Title</label> <!--<input type="button" value="asdasdasd" onclick="gallery_rld<? print $rand ?>();" />-->
          <input name="media_name" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery"  value="<?php print option_get('media_name', $params['module_id']) ?>" />
          <!--           <input  value="<?php print option_get('media_name', $params['module_id']) ?>" />
                 --></td>
      </tr>
      <tr>
        <td colspan="2"><label>Description</label>
          <textarea name="media_description" cols=""  class="mw_option_field" refresh_modules="media/gallery"   option_group="<? print $params['module_id'] ?>" rows="2"><?php print option_get('media_description', $params['module_id']) ?></textarea></td>
      </tr>
      <tr>
 
        <td colspan="2">
        <label>Skin</label>
        <select name="skin" id="mw_gallery_skin_setting_<? print $rand ?>" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery"  onchange="gallery_setting_ShowHide<? print $rand ?>(this.value);" onfocus="gallery_setting_ShowHide<? print $rand ?>(this.value);">
            <option value="'" <? if( trim(option_get('skin', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >None</option>
            <option value="1" <? if( option_get('skin', $params['module_id']) == '1') : ?>  selected="selected" <? endif; ?> >1</option>
            <option value="2" <? if( option_get('skin', $params['module_id']) == '2') : ?>  selected="selected" <? endif; ?> >2</option>
            <option value="3" <? if( option_get('skin', $params['module_id']) == '3') : ?>  selected="selected" <? endif; ?> >Galleria</option>
          </select>
          
          
          
          
          
      
          
          
          
          
          
          
          
          
          </td>
      </tr>
      <tr>
        
        <td colspan="2">
        
        
        
         

          <div id="gallery_skin_setting<? print $rand ?>_1" class="gallery_skin_setting<? print $rand ?>">
           <label>Thumbnail size</label>
        <select name="tn_size" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery" >
            <option value="60" <? if( trim(option_get('tn_size', $params['module_id'])) == '60') : ?>  selected="selected" <? endif; ?> >60px</option>
            <option value="90" <? if( option_get('tn_size', $params['module_id']) == '90') : ?>  selected="selected" <? endif; ?> >90px</option>
            <option value="120" <? if( option_get('tn_size', $params['module_id']) == '120') : ?>  selected="selected" <? endif; ?> >120px</option>
            <option value="250" <? if( option_get('tn_size', $params['module_id']) == '250') : ?>  selected="selected" <? endif; ?> >250px</option>
          </select>
          
          </div>
          
          
          
          
                  <div id="gallery_skin_setting<? print $rand ?>_3" class="gallery_skin_setting<? print $rand ?>">
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                   <label>Gallery width</label>
                   
                   
                   <input name="galeria_skin_width" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery"  value="<?php print option_get('galeria_skin_width', $params['module_id']) ?>" />
                   
                   
       <!-- <select name="galeria_skin_width" id="mw_gallery_skin_setting_<? print $rand ?>" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery">
            <option value="500" <? if( trim(option_get('galeria_skin_width', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >500</option>
              <option value="700" <? if( trim(option_get('galeria_skin_width', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >700</option>
                  <option value="900" <? if( trim(option_get('galeria_skin_width', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >900</option>
                  <option value="1200" <? if( trim(option_get('galeria_skin_width', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >1200</option>
                  
          </select>-->
                  
                  
                  
                  
                   
                   <label>Gallery height</label>
                   
                   <input name="galeria_skin_height" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery"  value="<?php print option_get('galeria_skin_height', $params['module_id']) ?>" />
                   
       <!--            
        <select name="galeria_skin_height" id="mw_gallery_skin_setting_<? print $rand ?>" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery">
            <option value="500" <? if( trim(option_get('galeria_skin_height', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >500</option>
              <option value="700" <? if( trim(option_get('galeria_skin_height', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >700</option>
                  <option value="900" <? if( trim(option_get('galeria_skin_height', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >900</option>
                  <option value="1200" <? if( trim(option_get('galeria_skin_height', $params['module_id'])) == '') : ?>  selected="selected" <? endif; ?> >1200</option>
                  
          </select>
                  
                  -->
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
   <!--       
           
             <input name="galeria_skin_width" class="mw_option_field mw_option_slider" input_min="200" input_max="1200" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery"  value="<?php print option_get('galeria_skin_width', $params['module_id']) ?>" />
             
             <br />
<label>Gallery width</label>
           
             <input name="galeria_skin_height" class="mw_option_field mw_option_slider" input_min="200" input_max="1200" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="media/gallery"  value="<?php print option_get('galeria_skin_height', $params['module_id']) ?>" />
           
        -->
          
          </div>
          
        
        
        
        
        
        
        
        
        
        
        
        
       </td>
      </tr>
      <tr>
        <td></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <div style="display:none;">
      <label>Type</label>
      <select name="media_type">
        <option value="picture" <? if($pic['media_description'] == 'picture') :  ?>  selected="selected" <? endif; ?>  >picture</option>
        <option value="video" <? if($pic['media_description'] == 'video') :  ?>  selected="selected" <? endif; ?> >video</option>
      </select>
    </div>
    <? if($pic['media_type'] == 'video') :  ?>
    <b>Embed code:</b>
    <textarea name="embed_code" cols="" style="width: 200px;" rows="2"><?php print $pic['embed_code'] ?></textarea>
    <b>Original link:</b>
    <textarea name="original_link" cols="" style="width: 200px;" rows="2"><?php print $pic['original_link'] ?></textarea>
    <? endif; ?>
  </div>
</div>

<?
 
// $rand=intval($params['id']).rand().rand().rand();
?>
<script type="text/javascript">
 var save_media_item<? print $rand ;?> = function(){
  
  var media_pics_options<? print $rand ;?> = { 
      //  target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  media_pics_showRequest<? print $rand ;?>,  // pre-submit callback 
		type:      'post',
		url:       '<? print site_url('api/content/save_option') ?>',
		clearForm: false,
		async:false,
		resetForm: false   ,
        success:       media_pics_showResponse<? print $rand ;?>  // post-submit callback 

        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
	
 $("#mw_media_edit_<? print $rand ;?>").ajaxSubmit(media_pics_options<? print $rand ;?>);
 
 }
 
 
// pre-submit callback 
function media_pics_showRequest<? print $rand ;?>(formData, jqForm, options) { 
 
    return true; 
} 
 
// post-submit callback 
function media_pics_showResponse<? print $rand ;?>(responseText, statusText)  { 

	// Modal.close();
	$('.mw_modal').hide();
	if( mw.reload_module != undefined){
	 mw.reload_module('media/gallery');
	}
	// call_media_manager();
	
	 
	 
} 
function save_media_close<? print $rand ;?>()  { 


	 $('.mw_media_edit_<? print $rand ;?>').fadeOut();
	 
}

</script>
<!-- <tr>
                  <td><h4>Filename: <?php print character_limiter( $pic['filename'], 10) ?></h4>
                    </td>
                </tr>-->
<? endif; ?>
<div class="embed_media" id="video_embed_media" style="display: none">
  <h4>Add media by url or embed code: </h4>
  <div class="c" style="">&nbsp;</div>
  <table class="formtable">
    <tr>
      <td><label>Paste link</label></td>
      <td><input type="text" style=""  onchange="parse_embeds();" id="original_link" name="original_link" /></td>
    </tr>
    <tr>
      <td><label>Paste the video embed code</label></td>
      <td><textarea style="height: 53px;padding: 2px"  onchange="parse_embeds();" name="embed_code" id="embed_code"><? print $the_post['custom_fields']['embed_code']; ?></textarea></td>
    </tr>
    <tr>
      <td><label>Screenshot URL</label></td>
      <td><input  name="screenshot_url" id="screenshot_url"   type="text" value=""  /></td>
    </tr>
    <tr>
      <td><label>Name</label></td>
      <td><input name="media_name" id="media_name"   type="text" value=""  /></td>
    </tr>
    <tr>
      <td><label>Description</label></td>
      <td><input name="media_description" id="media_description"   type="text" value=""  /></td>
    </tr>
  </table>
  <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
  <input class="btn" type="button" name="save" value="save" onclick="upload_by_embed<? print $rand ?>()" />
  <input class="btn" type="button" name="refresh" value="refresh" onclick="call_media_manager<? print $rand ?>();" />
  <script type="text/javascript">
        $(document).ready(function() {
          	 if($("#embed_code").val()!=''){
                  parse_embeds();
          	 }
        });
		function parse_embeds(){

		q = $("#original_link").val();


        if(q.indexOf("<object")==-1 || q.indexOf("<embed")==-1 || q.indexOf("<iframe")==-1){


        if($.isEmbedly(q)){
    		$.embedly(q, { /*maxWidth: 600, wrapElement: false*/ }, function(oembed, dict){

    			if (oembed == null){
    				//$("#embed").html('<p class="text"> Not A Valid URL </p>');
                     $(".video_link").hide();
                     $(".video_embed").show();

    			}else {
    				$("#embed_code").val(oembed.code);
    				$("#screenshot_url").val(oembed.thumbnail_url);
                    //$(".video_preview").html("<img src='" + oembed.thumbnail_url + "' />");
                    $(".video_preview").html(oembed.code);
                    $("#media_name").val(oembed.title);
                   $("#media_description").val(oembed.description);
    			}
    		});
        }
        else{
             //$(".video_link").hide();
             //$(".video_embed").show();
             mw.box.alert("Video was not found on this address, please enter the embed code.")


        }

        }
         if(q.indexOf("<object")!=-1 || q.indexOf("<embed")!=-1 || q.indexOf("<iframe")!=-1){
           //alert(q.indexOf("<object"))
          //$(".video_preview").html(q)
        }



}
</script>
  <div class="video_preview"> </div>
</div>
