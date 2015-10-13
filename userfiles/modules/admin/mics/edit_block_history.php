<?php

 if(!is_admin()){
	return; 
 }

$cont_id = false;
if(isset($params['post_id']) and intval($params['post_id']) != 0){
	$cont_id = intval($params['post_id']);
} else if(isset($params['page_id']) and intval($params['page_id']) != 0){
	$cont_id = intval($params['page_id']);
} else if(isset($params['for_url']) and ($params['for_url']) != false){
	$cont = mw()->content_manager->get_by_url($params['for_url']);
	if(isset($cont['id'])){
		$cont_id = $cont['id'];
	}
 
}




$url = mw()->url_manager->string(true);

	$history_files = false;
 
	if($cont_id != false){
   		$history_files = get_content_field('limit=300&order_by=id desc&fields=id,created_at&is_draft=1&all=1&url='.$url);
		$last_saved = get_content_by_id($cont_id);
		$last_saved_date = $last_saved['updated_at'];
		 
		$latest_drafs = get_content_field('limit=300&order_by=id desc&fields=id&created_at=[mt]'.$last_saved_date.'&is_draft=1&all=1&url='.$url.'&rel_id='.$cont_id);
 		
		$history_files_fields = get_content_field('group_by=field&order_by=id desc&fields=field,id,created_at&is_draft=1&all=1&url='.$url);
	 
 
	}




?>

<script  type="text/javascript">
var mw_history_reload_timer;


$( document ).ready(function() {
	$( window).unbind( "saveDraftCompleted.history_reload_timer" );

     $(window).bind( "saveDraftCompleted.history_reload_timer", function() {
	   window.clearTimeout(mw_history_reload_timer);
  		mw_history_reload_timer = window.setTimeout(function(){
			
			mw.history.init();
			
			},2000); 
	});
});


 


//
</script>

<?php

if(isset($latest_drafs) and is_array($latest_drafs)){
	//
$latest_drafs_vals = array();
foreach ($latest_drafs as $value) {
	$latest_drafs_vals[] =   $value['id'];
}
 
	 if(!empty($latest_drafs_vals)) { ?>
<script  type="text/javascript">


			mw.hasDraft = {

				draft:"<?php print implode(',',$latest_drafs_vals); ?>"
			}


        </script>
<?php }

}
?>
<?php if(is_array($history_files)): ?>
<?php

$latest_undo_vals = array();
foreach ($history_files as $value) {
	$latest_undo_vals[] =   $value['id'];
} ?>
<script  type="text/javascript">

     mw.historyActive = typeof mw.historyActive === 'number' ? mw.historyActive : 0;

			mw.undoHistory = {

				<?php  $i = 0 ; foreach ($history_files as $value) :  ?>
				<?php print $i ?>:<?php print intval($value['id']) ?>,
				<?php $i++; endforeach; ?>


			}


        </script>

<ul id="mw_history_files">
  <li><small>Saved drafts:</small></li>
  <?php foreach ($history_files as $item) : ?>
  <li rel="load-draft-<?php print ($item['id']) ?>">
    <?php //$mtime= filemtime($filename ); ?>
    <?php

	//$content_of_file = file_get_contents($filename);	?>
    <a title="Click to Restore" href="javascript:;" onclick="mw.history.load('<?php print ($item['id']) ?>')"> <?php print mw('format')->ago($item['created_at'], $granularity = 1); ?> </a> </li>
  <?php endforeach; ?>
  <?php if(is_array($history_files_fields)): ?>
  <?php foreach ($history_files_fields as $history_files_field) : ?>
  <?php 
	
	$fld = $history_files_field['field'];
	$history_files = get_content_field("field=".$fld.'&limit=50&order_by=id desc&fields=id,created_at&is_draft=1&all=1&url='.$url); ?>
  <?php if(is_array($history_files_fields)): ?>
  <li><small onclick="mw.$('ul', this.parentNode).toggleClass('semi_hidden');">for <em><?php print ($history_files_field['field']) ?></em></small>
    <ul class="semi_hidden">
      <?php foreach ($history_files as $item) : ?>
      <li rel="load-draft-<?php print ($item['id']) ?>">
        <?php //$mtime= filemtime($filename ); ?>
        <?php

	//$content_of_file = file_get_contents($filename);	?>
        <a title="Click to Restore" onclick="mw.history.load('<?php print ($item['id']) ?>')" href="javascript:;"> <?php print mw('format')->ago($item['created_at'], $granularity = 1); ?> </a> </li>
      <?php endforeach; ?>
    </ul>
  </li>
  <?php endif; ?>
  <?php endforeach; ?>
  <?php endif; ?>
</ul>
<?php else: ?>
<div style="padding:12px;">No history</div>
<?php endif; ?>
