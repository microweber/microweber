<?php

$related = array();
if(empty($active_categories)){
	$active_categories =  $this->content_model->taxonomyGetMasterCategories(false);
	$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
}

$related['selected_categories'] = $active_categories;
$related['content_subtype'] = 'none';
if(!$authorId){
	$related['created_by'] = $authorId;
}
$limit[0] = 0;
$limit[1] = 10;
$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type','content_subtype', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) ); 

if(empty($related_posts)){
	$related['created_by'] = false;
	$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type','content_subtype', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) ); 

}

if (!empty($related_posts)) {
	shuffle($related_posts);
	array_slice($related_posts , 0 , 5);
}

?>

<div class="list">
      <ul>
      <?php if(!empty($related_posts)): ?>
      <?php foreach($related_posts as $the_post): ?>
      <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
      <li>
		<a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a>
      </li>
      <?php endforeach; ?>
      <?php else: ?>
      <li><a href="#">No posts</a></li>
      <?php endif; ?>
        
       
      </ul>
      <a href="<?php print site_url('userbase/action:articles/username:'); ?><?php print $this->users_model->getPrintableName($authorId, $mode = 'username'); ?>" class="btn right" style="margin: 0 15px 10px">See All Posts</a> 
</div>