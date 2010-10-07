<?php dbg(__FILE__); ?>
<?php $more = false;
 $more = $this->core_model->getCustomFields('table_content', $the_post['id']);
	 if(!empty($more)){
		ksort($more);
	 }  ?>

<div <?php if ($products_page == false): ?>class="post"<?php else: ?>class="profile-product"<?php endif; ?> id="post-id-<?php print $the_post['id']; ?>">
  <?php $thumb = $this->users_model->getUserThumbnail( $the_post['created_by'], 44); ?>
  <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>


  <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="author-img" style="margin-right:10px;background-image:url(<?php print $thumb; ?>)"> <?php print $author['username']; ?> </a>
  <div class="post-side-only">
    <h2 class="post-title"><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
    <p>
      <?php if($the_post['content_description'] != ''): ?>
      <?php print (character_limiter($the_post['content_description'], 130, '...')); ?>
      <?php else: ?>
      <?php print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
      <?php endif; ?>
      <br />

    <br />

    <!--<a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" rel="post" class="more">Read More</a>-->
    <?php if($show_edit_and_delete_buttons == true): ?>
    ( <a href="<?php print site_url('users/user_action:post/id:').$the_post['id']; ?>" class="user-post-edit-btn">Edit</a> | <a href="javascript:usersPostDelete('<?php print $the_post['id']; ?>');" class="user-post-delete-btn">Delete</a> )
    <?php endif; ?>
    <?php $cats = $this->content_model->taxonomyGetCategoriesForContentId( $the_post['id']);
	 @array_pop($cats);
				//var_dump($cats);
				?>
    </p>
    <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
  </div>
  <div class="c">&nbsp;</div>




 <?php $image_ids = $this->content_model->mediaGetIdsForContentId($the_post['id'], $media_type = 'picture'); ?>
      <?php $i = 0 ; if(!empty($image_ids  )) :?>
    <div class="article-list-gallery">
      <?php foreach($image_ids as $img_id): ?>
      <?php if($i < 5): ?>
      <?php $thumb = $this->core_model->mediaGetThumbnailForMediaId($img_id, $size = 128); ?>
      <?php $big = $this->core_model->mediaGetThumbnailForMediaId($img_id, $size = 600); ?>
      <a href="<?php /*print $big*/ ?><?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#image-<?php print $i+1 ?>"><img src="<?php print $thumb; ?>" alt="" /></a>
      <?php endif; ?>
      <?php $i++; endforeach; ?>
     <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
      <a class="more" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">See all photos</a> </div>
    <?php endif; ?>


</div>
<?php dbg(__FILE__, 1); ?>
