<?

$for = 'table_content';
$for_id = 0;

if(isset($params['for'])){
	$for = $params['for']; 
}
$for =  db_get_assoc_table_name($for);




if(!isset($params['for-id'])){
	$params['for-id'] = $params['id'];
}

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
	 mw.module_pictures.after_upload(data);
mw.reload_module('pictures/admin')
}





</script>
<script  type="text/javascript">
    mw.require('<? print $config['url_to_module'] ?>pictures.js');
   // 

</script>
<script  type="text/javascript">
$(document).ready(function(){
   mw.module_pictures.init('#admin-thumbs-holder-sort-<? print $rand ?>');
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
<div class="vSpace">&nbsp;</div>
<label class="mw-ui-label">Add Images <small>(The first image will be thumbnail)</small></label>
<div class="admin-thumbs-holder left" id="admin-thumbs-holder-sort-<? print $rand ?>">
  <? if(isarr( $media)): ?>
  <?php $default_title = _e("Image title", true); ?>
  <? foreach( $media as $item): ?>
  <div class="admin-thumb-item" id="admin-thumb-item-<? print $item['id'] ?>">
    <? $tn = thumbnail($item['filename'], 131, 131); ?>
    <span class="mw-post-media-img" style="background-image: url(<?php print $tn; ?>);"></span>
    <div class="mw-post-media-img-edit">
      <input
            type="text" autocomplete="off"
            value="<? if ($item['title'] !== ''){print $item['title'];} else{ print $default_title; }  ?>"
            onkeyup="mw.on.stopWriting(this, function(){mw.module_pictures.save_title('<? print $item['id'] ?>', this.value);});"
            onfocus="$(this.parentNode).addClass('active');"
            onblur="$(this.parentNode).removeClass('active');"
            name="media-description-<?php print $tn; ?>"
      />
      <a title="<?php _e("Delete"); ?>" class="admin-thumb-delete" href="javascript:;" onclick="mw.module_pictures.del('<? print $item['id'] ?>');">
      <?php _e("Delete"); ?>
      </a> </div>
  </div>
  <? endforeach; ?>
  <? endif;?>
  <div class="post-thumb-uploader" onclick="mw.wysiwyg.request_image('#after_upld_<? print $rand ?>');"> Add Image </div>
</div>

<div class="mw_clear" style="padding-bottom: 20px;"></div>
<? if(isset($params['live_edit']) == true): ?>
<strong>Skin/Template</strong>
<module type="admin/modules/templates"  />
<microweber module="settings/list"     for_module="<? print $config['module'] ?>" for_module_id="<? print $params['id'] ?>" >
<? else : ?>
<? endif; ?>
