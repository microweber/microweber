
<div <? if ($products_page == false): ?>class="post"<? else: ?>class="profile-product"<? endif; ?> id="post-id-<? print $the_post['id']; ?>">
  <? $thumb = $this->users_model->getUserThumbnail( $the_post['created_by'], 80); ?>
  <? $author = $this->users_model->getUserById($the_post['created_by']); ?>
  <h2 class="post-title"><a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><? print $the_post['content_title']; ?></a></h2>
  <p>
    <? if($the_post['content_description'] != ''): ?>
    <? print (character_limiter($the_post['content_description'], 130, '...')); ?>
    <? else: ?>
    <? print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
    <? endif; ?> 
    <br />
<a href="javascript:mw_content_edit_generate_and_insert_tinymce_content_code('<? print $the_post['id'] ;  ?>');">use</a>
   
<a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" target="_blank">preview</a>
    
  </p>
</div>
