<?
 
if(!isset($params['to_table_id'])){
	$params['to_table_id'] = $params['id']; 
}

 if(isset($params['to_table_id']) == true): ?>
<? $data = get_pictures($content_id = $params['to_table_id'], $for = 'post'); 
 
 
?>
<? if(isarr($data )): ?>
<div class="mw-gallery-holder">
  <? foreach($data  as $item): ?>
  <div class="mw-gallery-item mw-gallery-item-<? print $item['id']; ?>"><img src="<? print $item['filename']; ?>" /></div>
  <? endforeach ; ?>
</div>
<? endif; ?>
<? else : ?>
Please click on settings to upload your pictures.
<? endif; ?>
