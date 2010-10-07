<?php dbg(__FILE__); ?>
<div <?php if ($products_page == false): ?>class="post"<?php else: ?>class="profile-product"<?php endif; ?> id="post-id-<?php print $the_post['id']; ?>">
  <?php $thumb = $this->users_model->getUserThumbnail( $the_post['created_by'], 44); ?>
  <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
  <?php if($no_author_info  == false): ?>
  <?php if($no_author_no_votes  == false): ?>
  <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="author-img" style="background-image:url(<?php print $thumb; ?>)"> <?php print $author['username']; ?> </a>
  <?php endif; ?>
  <strong class="post-user-title"> <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"> <?php print $author['first_name']; ?> <?php print $author['last_name']; ?> </a> 
  
  <em>
   <?php if($the_post['content_subtype'] == 'none'): ?>
     posted an article:
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'trainings'): ?>
    posted a training:
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'products'): ?>
    posted a product:
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'services'): ?>
    posted a service:
    <?php endif; ?>


  </em>

  
  
  
   </strong>
  <?php endif; ?>
  <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 180, 150);  ?>
  <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="isMask">
    <span style="background-image:url(<?php print $thumb; ?>); background-position:center center;"></span>
    <strong class="pro-mask">&nbsp;</strong>
  </a>
  <div class="post-side-only">
  
  <?php if($article_list_no_type  == false): ?>
  

    <?php if($the_post['content_subtype'] == 'none'): ?>
    <span class="post-type post-type-article">article</span>
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'trainings'): ?>
    <?php /*
    <span class="post-type post-type-article">training</span>
    */ ?>
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'products'): ?>
    <?php /*
    <span class="post-type post-type-product">product</span>
    */ ?>
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'services'): ?>
    <span class="post-type post-type-service">service</span>
    <?php endif; ?>
    
    <?php endif; ?>
    
    
    <h2 class="post-title"><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
    <p>
      <?php if($the_post['content_description'] != ''): ?>
      <?php print (character_limiter($the_post['content_description'], 130, '...')); ?>
      <?php else: ?>
      <?php print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
      <?php endif; ?>
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
  </div>
  <div class="c">&nbsp;</div>

</div>
<?php dbg(__FILE__, 1); ?>
