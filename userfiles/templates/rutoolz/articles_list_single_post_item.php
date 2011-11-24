kjhkjh

<div <? if ($products_page == false): ?>class="post"<? else: ?>class="profile-product"<? endif; ?> id="post-id-<? print $the_post['id']; ?>">
  <? $thumb = $this->users_model->getUserThumbnail( $the_post['created_by'], 80); ?>
  <? $author = $this->users_model->getUserById($the_post['created_by']); ?>
  
  <? if($no_author_no_votes  == false): ?>
  
  <!--<a href="javascript:voteUp('<? print base64_encode('table_content') ?>', '<? print base64_encode($the_post['id']) ?>', 'votes-for-post-<? print $the_post['id'] ?>');"    class="votes"><span id="votes-for-post-<? print $the_post['id'] ?>">  -->


<!--

  <a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#status-nav" class="votes">
  <span id="votes-for-post-<? print $the_post['id'] ?>">

  <?
 // var_dump($since);
  //print $this->votes_model->votesGetCount('table_content', $the_post['id'], $this->core_model->getParamFromURL ( 'voted' ));
  print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?>
  </span><br />
  votes

  </a>


-->

  <a href="<? print site_url('userbase/action:profile/username:'); ?><? print $author['username']; ?>" class="author-img" style="background-image:url(<? print $thumb; ?>)"> <? print $author['username']; ?> </a>
  <?  endif; ?>

  <strong class="post-user-title">
     <a href="<? print site_url('userbase/action:profile/username:'); ?><? print $author['username']; ?>">
          <? print $author['first_name']; ?> <? print $author['last_name']; ?>
     </a>
     <em>Posted an Article:</em>
  </strong>
  <? $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 120, 120);  ?>
  <a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="eimg"> <span style="background-image:url(<? print $thumb; ?>)"></span> </a>
  <div class="post-side-only">
    <h2 class="post-title"><a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><? print $the_post['content_title']; ?></a></h2>
    <p>
      <? if($the_post['content_description'] != ''): ?>
      <? print (character_limiter($the_post['content_description'], 130, '...')); ?>
      <? else: ?>
      <? print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
      <? endif; ?>
      <br />
      <!--<a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" rel="post" class="more">Read More</a>-->
      <? if($show_edit_and_delete_buttons == true): ?>
      ( <a href="<? print site_url('users/user_action:post/id:').$the_post['id']; ?>" class="user-post-edit-btn">Edit</a> | <a href="javascript:usersPostDelete('<? print $the_post['id']; ?>');" class="user-post-delete-btn">Delete</a> )
      <?  endif; ?>
      
      <? $cats = $this->taxonomy_model->getCategoriesForContent( $the_post['id']); 
			//	var_dump($cats);  
				?>
    </p>
  </div>
  <div class="c">&nbsp;</div>
        <div class="status-nav post-status-nav">
              <ul>
                <li><span class="date"><? print $the_post['created_on']; ?></span></li>
               
               <? if(!empty($cats)): ?>
                <li><span>Category: 
                <a href="<? print $this->taxonomy_model->getUrlForIdAndCache($cats[0]['id']); ?>">
           
                <? print $cats[0]['taxonomy_value'];  ?></a>
                <? if(count($cats)> 1): ?>
                <? 
				$popup_html = '';	
				foreach($cats as $c){
				$popup_html .= '<a href="'.$this->taxonomy_model->getUrlForIdAndCache($c['id']).'">'.$c['taxonomy_value'] . '</a><br>';
					
				}?>
                
                ,  
                
                   <a href='javascript:mwbox.displayHtml("<? print addslashes($popup_html) ?>", 200, 100)'><? print count($cats) ?> more</a>
               
                
                <? endif;  ?>
                </span></li>
                
                <? endif;  ?>
                
                <li><span class="voteUp"> <?  print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?></span></li>
                <li><a class="cmm" href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#the_comments_anchor"><? print $this->comments_model->commentsGetCountForContentId($the_post['id']); ?></a></li>
                <li><a href="<? print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">Read more</a></li>
              </ul>
          </div>
</div>
