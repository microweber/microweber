<?php dbg(__FILE__); ?>

<div <?php if ($products_page == false): ?>class="post"<?php else: ?>class="profile-product"<?php endif; ?> id="post-id-<?php print $the_post['id']; ?>">
  <?php $thumb = CI::model('users')->getUserThumbnail( $the_post['created_by'], 44); ?>
  <?php $author = CI::model('users')->getUserById($the_post['created_by']); ?>
  <?php if($no_author_info  == false): ?>
  <?php if($no_author_no_votes  == false): ?>
  <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="author-img" style="background-image:url(<?php print $thumb; ?>)"> <?php print $author['username']; ?> </a>
  <?php endif; ?>
  <strong class="post-user-title"> <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"> <?php print $author['first_name']; ?> <?php print $author['last_name']; ?> </a> <em>
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
  </em> </strong>
  <?php endif; ?>
  <?php $thumb = CI::model('content')->contentGetThumbnailForContentId( $the_post['id'], 80, 80);  ?>
  <a href="<?php print CI::model('content')->contentGetHrefForPostId($the_post['id']) ; ?>" class="eimg">
    <img src="<?php print $thumb; ?>" alt="" />
  </a>
  <div class="post-side-only">
    <?php if($article_list_no_type  == false): ?>
    <?php if($the_post['content_subtype'] == 'none'): ?>
    <span class="post-type post-type-article">article</span>
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'trainings'): ?>
    <span class="post-type post-type-article">training</span>
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'products'): ?>
    <span class="post-type post-type-product">product</span>
    <?php endif; ?>
    <?php if($the_post['content_subtype'] == 'services'): ?>
    <span class="post-type post-type-service">service</span>
    <?php endif; ?>
    <?php endif; ?>
    <h2 class="post-title"><a href="<?php print CI::model('content')->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
    <p>
      <?php if($the_post['content_description'] != ''): ?>
      <?php print (character_limiter($the_post['content_description'], 130, '...')); ?>
      <?php else: ?>
      <?php print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
      <?php endif; ?>

      <?php /*
      <a href="<?php print CI::model('content')->contentGetHrefForPostId($the_post['id']) ; ?>" rel="post" class="more">Read More</a>
      */ ?>
      <?php if($show_edit_and_delete_buttons == true): ?>
        <span class="edit-or-delete">
         <a href="<?php print site_url('users/user_action:post/id:').$the_post['id']; ?>" class="user-post-edit-btn">Edit</a> | <a href="javascript:usersPostDelete('<?php print $the_post['id']; ?>');" class="user-post-delete-btn">Delete</a>
        </span>
      <?php endif; ?>
      <?php $cats = CI::model('taxonomy')->getCategoriesForContent( $the_post['id']);
	 @array_pop($cats);
				//var_dump($cats);
				?>
    </p>
     <div class="c">&nbsp;</div>



  </div>
  <div class="c">&nbsp;</div>
  <div class="status-nav post-status-nav" >
    <ul>
      <li><span class="date"><?php print date(DATETIME_FORMAT, strtotime($the_post['created_on'])); ?></span></li>
      <?php if(!empty($cats)): ?>
      <li><span>Category: <a href="<?php print CI::model('taxonomy')->getUrlForId($cats[0]['id']); ?>"> <?php print $cats[0]['taxonomy_value'];  ?></a>
        <?php if(count($cats)> 1): ?>
        <?php $popup_html = '';
				foreach($cats as $c){
				$popup_html .= '<a href="'.CI::model('taxonomy')->getUrlForId($c['id']).'">'.$c['taxonomy_value'] . '</a><br>';

				}?>
        , <span style="display: none" class="more-cats" id="more-cats-<?php print $the_post['id']?>">
        <?php //print addslashes($popup_html) ?>
        <?php print $popup_html ?> </span> <a href='javascript:mw.box.element({element:"#more-cats-<?php print $the_post['id']?>", id:"<?php print $the_post['id']?>"});'><?php print count($cats) ?> more</a>
        <?php endif;  ?>
        </span></li>
      <?php endif;  ?>
      <li><span class="voteUp title-tip" title="<?php print CI::model('votes')->votesGetCount('table_content', $the_post['id'], '1 year'); ?> Votes">
        <?php print CI::model('votes')->votesGetCount('table_content', $the_post['id'], '1 year'); ?>
        </span></li>
      <li><a class="cmm title-tip" title="<?php print CI::model('content')->commentsGetCountForContentId($the_post['id']); ?> Comments" href="<?php print CI::model('content')->contentGetHrefForPostId($the_post['id']) ; ?>#the_comments_anchor"><?php print CI::model('content')->commentsGetCountForContentId($the_post['id']); ?></a></li>
      <li><a href="<?php print CI::model('content')->contentGetHrefForPostId($the_post['id']) ; ?>">Read more</a></li>
    </ul>
  </div>

</div>
<?php dbg(__FILE__, 1); ?>
