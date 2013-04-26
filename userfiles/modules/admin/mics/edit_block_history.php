<?
 only_admin_access();


$cont_id = false;
if(isset($params['post_id']) and intval($params['post_id']) != 0){
	$cont_id = intval($params['post_id']);
} else if(isset($params['page_id']) and intval($params['page_id']) != 0){
	$cont_id = intval($params['page_id']);
}
$url = url_string(true);

	$history_files = false;

	if($cont_id != false){

		$history_files = get_content_field('order_by=id desc&fields=id,created_on&is_draft=1&all=1&url='.$url);

	}


			?>

<? if(isarr($history_files)): ?>
<? print count($history_files);?>

history files <br />
<? // p($history_files); ?>
<ul id="mw_history_files">
  <? 		foreach ($history_files as $item) : ?>
  <li rel="load-draft-<? print ($item['id']) ?>">
    <? //$mtime= filemtime($filename ); ?>
    <?

	//$content_of_file = file_get_contents($filename);	?>
    <a href="javascript: mw.history.load('<? print ($item['id']) ?>')">

    <?  print $item['id']  ?>
    (<? print ago($item['created_on'], $granularity = 1); ?>) </a> </li>
  <? 		endforeach; ?>
</ul>
<? endif; ?>