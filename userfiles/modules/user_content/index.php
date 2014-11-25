<?php $posts_parent_page = get_option('data-parent', $params['id']); ?>
<?php if($posts_parent_page == false){
$posts_parent_page = CONTENT_ID;	
}

$edit_post_id = '0'; 

?>

<div id="mw-user-content-edit-list-holder">
  <module type="user_content"  view="list"  />
</div>
<div id="mw-user-content-add-edit-item-holder">
  <module type="user_content"  view="edit"   />
</div>
