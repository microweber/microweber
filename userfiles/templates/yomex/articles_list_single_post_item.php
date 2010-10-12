

<div <?php if ($products_page == false): ?>class="post"<?php else: ?>class="profile-product"<?php endif; ?> id="post-id-<?php print $the_post['id']; ?>">
  <?php $thumb = $this->users_model->getUserThumbnail( $the_post['created_by'], 80); ?>
  <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
  
  <?php if($no_author_no_votes  == false): ?>
  
  <!--<a href="javascript:voteUp('<?php print base64_encode('table_content') ?>', '<?php print base64_encode($the_post['id']) ?>', 'votes-for-post-<?php print $the_post['id'] ?>');"    class="votes"><span id="votes-for-post-<?php print $the_post['id'] ?>">  -->


<!--

  <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#status-nav" class="votes">
  <span id="votes-for-post-<?php print $the_post['id'] ?>">

  <?php // var_dump($since);
  //print $this->votes_model->votesGetCount('table_content', $the_post['id'], $this->core_model->getParamFromURL ( 'voted' ));
  print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?>
  </span><br />
  votes

  </a>


-->

  <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="author-img" style="background-image:url(<?php print $thumb; ?>)"> <?php print $author['username']; ?> </a>
  <?php endif; ?>

  <strong class="post-user-title">
     <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>">
          <?php print $author['first_name']; ?> <?php print $author['last_name']; ?>
     </a>
     <em>Posted an Article:</em>
  </strong>
  <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 120, 120);  ?>
  <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="eimg"> <span style="background-image:url(<?php print $thumb; ?>)"></span> </a>
  <div class="post-side-only">
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
      
      <?php $cats = $this->taxonomy_model->getCategoriesForContent( $the_post['id']);
			//	var_dump($cats);
				?>
    </p>
  </div>
  <div class="c">&nbsp;</div>
        <div class="status-nav post-status-nav">
              <ul>
                <li><span class="date"><?php print date(DATETIME_FORMAT, strtotime($the_post['created_on'])); ?></span></li>
               
               <?php if(!empty($cats)): ?>
                <li><span>Category: 
                <a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($cats[0]['id']); ?>">
           
                <?php print $cats[0]['taxonomy_value'];  ?></a>
                <?php if(count($cats)> 1): ?>
                <?php $popup_html = '';	
				foreach($cats as $c){
				$popup_html .= '<a href="'.$this->taxonomy_model->getUrlForIdAndCache($c['id']).'">'.$c['taxonomy_value'] . '</a><br>';
					
				}?>
                
                ,  
                  <span style="display: none" class="more-cats" id="more-cats-<?php print $the_post['id']?>">
                      <?php //print addslashes($popup_html) ?>
                      <?php print $popup_html ?>
                  </span>
                   <a href='javascript:mw.box.element({element:"#more-cats-<?php print $the_post['id']?>", id:"<?php print $the_post['id']?>"});'><?php print count($cats) ?> more</a>

                
                <?php endif;  ?>
                </span></li>

                <?php endif;  ?>
                
                <li><span class="voteUp"> <?php print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?></span></li>
                <li><a class="cmm" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#the_comments_anchor"><?php print $this->comments_model->commentsGetCountForContentId($the_post['id']); ?></a></li>
                <li><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">Read more</a></li>
              </ul>
          </div>
</div>
