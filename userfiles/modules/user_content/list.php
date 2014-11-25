<?php 
$user_id = false;
if($user_id == false){
	$user_id = user_id();	
}

  $posts_parent_page = get_option('data-parent', $params['id']);  


$content_params = array();
$content_params['created_by'] = $user_id;
$content_params['content_type'] = 'post';
if($posts_parent_page != false){
	$content_params['parent'] = $posts_parent_page;
	
}
 
 ?>
<script>
mw.require('<?php print $config['url_to_module']; ?>user_content.js');
</script>





<?php $content = get_content($content_params);  ?>

<table class="table table-striped">
  <?php foreach( $content as  $item): ?>
  <tr>
    <td><?php print $item['id']; ?></td>
    <td><?php print $item['title']; ?></td>
    <td><?php print $item['url']; ?></td>
    <td><a class="btn btn-default module-user-content-edit-link" data-content-id='<?php print $item['id']; ?>' href="javascript:mw.user_content.edit(<?php print $item['id']; ?>);">edit</a></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php //print_r($content); ?>
