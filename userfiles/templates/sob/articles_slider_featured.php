<?php dbg(__FILE__); ?>
<?php if(empty($active_categories)){
		$active_categories =  $this->taxonomy_model->getMasterCategories(false);
		$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
	 }
	 
      $related = array();
	  $related['selected_categories'] = $active_categories;
	  $related ['content_type'] = 'post';
	  $related ['content_subtype'] = 'none';
	  $related ['is_special'] = 'y';
	  
	   
	  //$related['have_videos'] = 'y';
	   
	  
	  //
	  
	  
	  $limit[0] = 0;
	  $limit[1] = 5;
	  $related_data = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by', 'is_special' ) ); 
	  if(count($related_data)< 5){
		   $related ['is_special'] = 'n';
		   $related_data2 = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by', 'is_special' ) );
		   if(empty($related_data)){
			   $related_data = $related_data2;
		   } else {
		   $related_data = array_merge($related_data, $related_data2);
		   }
	  }
	  
	  
	// print count( $related);
	@shuffle($related_data); 
	@array_slice($related_data , 0 , 5);
	  ?>
<?php if(!empty($related_data)): ?>

<div id="home-featured-articles-wrapper">
  <div id="home-featured-articles">
    <div id="featured-articles-slider">
      <?php // p($related); ?>
      <?php foreach($related_data as $the_post): ?>
      <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 85);  ?>
      <div class="home-featured-article">
        <div class="fa"> <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"> <?php print $author['first_name']; ?> <?php print $author['last_name']; ?>
          <?php /*
<small>(<?php print $author['username']; ?>)</small>
 */ ?>
          </a> <span>Wrote about:</span> </div>
        <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="faimage">
            <img src="<?php print $thumb; ?>" alt="" />
        </a>
        <div class="featured-articles-slider-side-content">
          <h3><a title="<?php print $the_post['content_title']; ?>" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h3>
          <p>
            <?php if($the_post['content_description'] != ''): ?>
            <?php print (character_limiter($the_post['content_description'], 150, '...')); ?>
            <?php else: ?>
            <?php print character_limiter($the_post['content_body_nohtml'], 150, '...'); ?>
            <?php endif; ?>
            <br />
          </p>
        </div>
        <div class="c">&nbsp;</div>
        <div class="status-nav">
          <ul>
            <li><span class="date"><?php print date(DATETIME_FORMAT, strtotime($the_post['created_on'])); ?></span></li>
            <?php if(!empty($cats)): ?>
            <li><span>Category: <a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($cats[0]['id']); ?>"> <?php print $cats[0]['taxonomy_value'];  ?></a>
              <?php if(count($cats)> 1): ?>
              <?php $popup_html = '';
				foreach($cats as $c){
				$popup_html .= '<a href="'.$this->taxonomy_model->getUrlForIdAndCache($c['id']).'">'.$c['taxonomy_value'] . '</a><br>';

				}?>
              , <span style="display: none" class="more-cats" id="more-cats-<?php print $the_post['id']?>">
              <?php //print addslashes($popup_html) ?>
              <?php print $popup_html ?> </span> <a href='javascript:mw.box.element({element:"#more-cats-<?php print $the_post['id']?>", id:"<?php print $the_post['id']?>"});'><?php print count($cats) ?> more</a>
              <?php endif;  ?>
              </span></li>
            <?php endif;  ?>
            <li><span class="voteUp title-tip" title="<?php print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?> Votes">
              <?php print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?>
              </span></li>
            <li><a class="cmm title-tip" title="<?php print $this->comments_model->commentsGetCountForContentId($the_post['id']); ?> Comments" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#the_comments_anchor"><?php print $this->comments_model->commentsGetCountForContentId($the_post['id']); ?></a></li>
            <li><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">Read more</a></li>
          </ul>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <ul id="fa-ctrl">
    <li class="active">1</li>
    <li>2</li>
    <li>3</li>
    <li>4</li>
    <li>5</li>
  </ul>
</div>
<?php endif;  ?>
<?php dbg(__FILE__, 1); ?>
