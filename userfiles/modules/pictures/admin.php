<script  type="text/javascript">
    mw.require('<? print $config['url_to_module'] ?>pictures.js');
</script>
<?

$for = 'table_content';
$for_id = 0;

if(isset($params['for'])){
	$for = $params['for']; 
}
$for =  db_get_assoc_table_name($for);


if(isset($params['for-id'])){
	$for_id = $params['for-id']; 
}


 ?>
<? $rand = uniqid(); ?>
<script  type="text/javascript">
function after_upld_<? print $rand ?>(a){

	 var data = {};
	 data.for = '<? print $for ?>';
	 data.src = a;
	 data.media_type = 'picture';
	 data.for_id = '<? print $for_id ?>';
	 mw.module.pictures.after_upload(data);

}





</script>


<script  type="text/javascript">
$(document).ready(function(){


    mw.module.pictures.init('#admin-thumbs-holder-sort-<? print $rand ?>');


});
</script> 



<?  if(!isset($data["thumbnail"])){
	   $data['thumbnail'] = '';

  }?>

<input name="thumbnail"  type="hidden" value="<? print ($data['thumbnail'])?>" />




<? 

if(intval($for_id) >0){
$media = get_pictures("to_table_id={$for_id}&to_table={$for}");
} else {
	$sid = session_id();
	$media = get_pictures("to_table_id={$for_id}&to_table={$for}&session_id={$sid}");
}

 ?>
<? if(isarr( $media)): ?>
<div class="admin-thumbs-holder left" id="admin-thumbs-holder-sort-<? print $rand ?>">
  <? foreach( $media as $item): ?>
  <div class="admin-thumb-item" id="admin-thumb-item-<? print $item['id'] ?>">
    <? $tn = thumbnail($item['filename'], 131, 131); ?>

    <span class="mw-post-media-img" style="background-image: url(<?php print $tn; ?>);"></span>
    <div class="mw-post-media-img-edit">

      <input
            type="text"
            value="<? print $item['title'] ?>"
            onkeyup="mw.on.stopWriting(this, function(){mw.module.pictures.save_title('<? print $item['id'] ?>', this.value);});"
            onfocus="$(this.parentNode).addClass('active');"
            onblur="$(this.parentNode).removeClass('active');"
            name="media-description-<?php print $tn; ?>"
      />

      <a title="<?php _e("Delete"); ?>" class="admin-thumb-delete" href="javascript:;" onclick="mw.module.pictures.del('<? print $item['id'] ?>');"><?php _e("Delete"); ?></a>
    </div>
  </div>
  <? endforeach; ?>

  <div class="post-thumb-uploader"onclick="mw.wysiwyg.request_image('#after_upld_<? print $rand ?>');"> Add Image </div> 

</div>



<div class="mw_clear" style="padding-bottom: 20px;"></div>
<? endif;?>
