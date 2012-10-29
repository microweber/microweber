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
  $("#admin-thumbs-holder-sort-<? print $rand ?>").sortable({
    update: function(){
     serial=$('#admin-thumbs-holder-sort-<? print $rand ?>').sortable('serialize');
      $.ajax({
        url: mw.settings.api_url+'reorder_media',
        type:"post",
        data:serial,
        error:function(){
        //
		
		  alert("theres an error with AJAX")
        }
      })
    }
	});
});
</script> 



<?  if(!isset($data["thumbnail"])){
	   $data['thumbnail'] = '';

  }?>

<input name="thumbnail"  type="hidden" value="<? print ($data['thumbnail'])?>" />
<span class="mw-ui-btn" onclick="mw.wysiwyg.request_image('#after_upld_<? print $rand ?>');">Thumbnail image</span>
<div class="post-thumb-uploader"> </div>
<? $media = get_pictures("to_table_id={$for_id}&to_table={$for}"); 
 
 
 ?>
<? if(isarr( $media)): ?>
<div class="admin-thumbs-holder" id="admin-thumbs-holder-sort-<? print $rand ?>">
  <? foreach( $media as $item): ?>
  <div class="admin-thumb-item" id="admin-thumb-item-<? print $item['id'] ?>">
    <? $tn = thumbnail($item['filename'], 200, 150); ?>
    <img src="<? print  $tn ?>" /> <a href="javascript:mw.module.pictures.del('<? print $item['id'] ?>');">delete</a> </div>
  <? endforeach; ?>
</div>
<? endif;?>
