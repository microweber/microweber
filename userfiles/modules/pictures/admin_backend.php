
<script  type="text/javascript">
    mw.require('<?php print $config['url_to_module'] ?>pictures.js', true);

</script>
<?php



if(!isset($for_id)){
$for_id = 0;
}
if(isset($params['for'])){
	 $for = $params['for'];
} else {
 $for = 'modules';
}

if(!isset($for)){
$for = 'content';

}



$for =  db_get_assoc_table_name($for);




if(!isset($params['for-id'])){
	$params['for-id'] = $params['id'];
}

if(isset($params['for-id'])){
	$for_id = $params['for-id'];
}


if(isset($params['content-id'])){
	$for_id = $for_module_id = $params['content-id'];
	 $for = 'content';
}

 ?>
<?php  $rand = uniqid(); ?>
<script  type="text/javascript">





function after_upld_<?php print $rand; ?>(a, eventType){

	if(eventType != 'done' ){
			 var data = {};
			 data.for = '<?php print $for ?>';
			 data.src = a;
			 data.media_type = 'picture';
			 data.for_id = '<?php print $for_id ?>';
			 mw.module_pictures.after_upload(data);
	}
	if(eventType == 'done' ){
        if(typeof mw.tools === 'object'){
    	    mw.tools.modal.remove('mw_rte_image');
    	}
    	mw.reload_module('#<?php print $params['id'] ?>');
        if(self !== top && typeof parent.mw === 'object'){
             parent.mw.reload_module('pictures');
			 if(self !== top && typeof parent.mw === 'object'){
               parent.mw.reload_module('posts');
               parent.mw.reload_module('shop/products');
               parent.mw.reload_module('content', function(){
                 	mw.reload_module('#<?php print $params['id'] ?>');
                    parent.mw.reload_module('pictures');
               });
            }
        }
	}
}





</script>

<script  type="text/javascript">
$(document).ready(function(){
   mw.module_pictures.init('#admin-thumbs-holder-sort-<?php print $rand; ?>');
});
</script>
<?php  if(!isset($data["thumbnail"])){
	   $data['thumbnail'] = '';

  }?>

<input name="thumbnail"  type="hidden" value="<?php print ($data['thumbnail'])?>" />
<?php

if(intval($for_id) >0){
$media = get_pictures("rel_id={$for_id}&rel={$for}");
} else {
	$sid = session_id();
	$media = get_pictures("rel_id={$for_id}&rel={$for}&session_id={$sid}");
}



 ?>
<div class="vSpace">&nbsp;</div>
<label class="mw-ui-label"><?php _e("Add Images"); ?> <small>(<?php _e("The first image will be thumbnail"); ?>)</small></label>
<div class="admin-thumbs-holder left" id="admin-thumbs-holder-sort-<?php print $rand; ?>">
  <?php if(isarr( $media)): ?>
  <?php $default_title = _e("Image title", true); ?>
  <?php foreach( $media as $item): ?>
  <div class="admin-thumb-item admin-thumb-item-<?php print $item['id'] ?>" id="admin-thumb-item-<?php print $item['id'] ?>">
    <?php $tn = thumbnail($item['filename'], 131, 131); ?>
    <span class="mw-post-media-img" style="background-image: url(<?php print $tn; ?>);"></span>
    <div class="mw-post-media-img-edit">
      <input
            placeholder="<?php _e("Image Description"); ?>"
            <?php /*type="text"*/ ?> autocomplete="off"
            value="<?php if ($item['title'] !== ''){print $item['title'];} else{ print $default_title; }  ?>"
            onkeyup="mw.on.stopWriting(this, function(){mw.module_pictures.save_title('<?php print $item['id'] ?>', this.value);});"
            onfocus="$(this.parentNode).addClass('active');"
            onblur="$(this.parentNode).removeClass('active');"
            name="media-description-<?php print $tn; ?>"
      />
      <a title="<?php _e("Delete"); ?>" class="admin-thumb-delete" href="javascript:;" onclick="mw.module_pictures.del('<?php print $item['id'] ?>');">
      <?php _e("Delete"); ?>
      </a> </div>
  </div>
  <?php endforeach; ?>
  <?php endif;?>



  <script>mw.require("files.js", true);</script>
  <script>





  var uploader = mw.files.uploader({
         filetypes:"images",
         name:'basic-images-uploader'
  });


  $(document).ready(function(){

     mw.$("#backend_image_uploader").append(uploader);
     $(uploader).bind("FilesAdded", function(a,b){
        var i=0, l=b.length;
         for( ; i<l; i++){
           if($(".admin-thumbs-holder .admin-thumb-item").length > 0){
             $(".admin-thumbs-holder .admin-thumb-item:last").after('<div class="admin-thumb-item admin-thumb-item-loading"><span class="mw-post-media-img"></span><div class="mw-post-media-img-edit" style="text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">'+b[i].name+'</div></div>');
           }
           else{
             $(".admin-thumbs-holder").prepend('<div class="admin-thumb-item admin-thumb-item-loading"><span class="mw-post-media-img"></span><div class="mw-post-media-img-edit" style="text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">'+b[i].name+'</div></div>');
           }

         }
     });

     $(uploader).bind("FileUploaded done" ,function(e, a){

      after_upld_<?php print $rand; ?>(a.src, e.type);
     })
  });



  </script>

  <div class="relative post-thumb-uploader" id="backend_image_uploader"></div>


</div>
