<?php $thecat = $active_categories[count($active_categories) - 1] ?>
<?php $thecat =  $this->taxonomy_model->getSingleItem($thecat);
		  $thecat_body = false;
		  if(trim($thecat['content_body'])!= ''){
			  $thecat['content_body'] = html_entity_decode($thecat['content_body']);
			  if(trim(strip_tags($thecat['content_body']))!= ''){
				   $thecat_body = trim( $thecat['content_body']);
			  }
		  }

$cat_pic = $this->core_model->mediaGet($to_table = 'table_taxonomy', $to_table_id = $thecat['id'], $media_type = false, $order = "desc");

 	  ?>
<?php include (ACTIVE_TEMPLATE_DIR."offers_heading_nav.php"); ?>

<div class="holder">
  <div class="hleft">
    <?php include (ACTIVE_TEMPLATE_DIR."offers_category_description_box.php"); ?>
    <?php //include (ACTIVE_TEMPLATE_DIR."search_box.php"); ?>
    <?php // var_dump($active_categories);
      $related = array();
	  $related['selected_categories'] = array(1871);
	  $related['visible_on_frontend'] = 'y'; 
	  //$related['is_special'] = 'y';
	  $limit[0] = 0;
	  $limit[1] = 3;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
    <?php if(!empty($related)): ?>
    <div class="offers"> <a href="<?php print $this->taxonomy_model->getUrlForIdAndCache(1871) ; ?>/view:all" class="seeall right">Вижте цялото портфолио</a>
      <h2 class="gtitle">Портфолио</h2>
      <span class="hr">&nbsp;</span>
      <ul class="offers-list 3rd-elem">
        <?php foreach($related as $item): ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 250);
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);

	  ?>
        <li> <a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>"> <span style="background-image: url('<?php print $thumb ?>'); background-position:center center;">   </span> <big><?php print character_limiter($item['content_title'], 50, '...'); ?></big> <?php print (character_limiter($item['content_description'], 95, '...')); ?> </a> </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif;  ?>
    <div class="richtext"> <?php print $page['content_body'] ?> </div>
    <div class="list-offers"> <a href="<?php print $this->taxonomy_model->getUrlForIdAndCache(1872) ; ?>/view:all" class="seeall right">Виж всички предложения</a>
      <h2 class="gtitle">Хотели</h2>
      <span class="hr"></span>
      <div id="Slider">
        <?php // var_dump($active_categories);
      $related = array();
	   $related['selected_categories'] = array(1872);
	   $related['visible_on_frontend'] = 'y'; 
	  //$related['is_special'] = 'n';
	  $limit[0] = 0;
	  $limit[1] = 30;
	  $posts = $this->content_model->getContentAndCache($related, false,$limit ); ?>
        <?php if(!empty($posts)):

		  ?>
        <?php foreach ($posts as $the_post): ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId($the_post['id'], 250);
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $the_post['id']);	  ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
        <div class="list-offer">
          <div class="image-holder">
          <a style="background-image: url(<?php print $thumb ?>)" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>">
          </a></div>
          <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>"><?php print ($the_post['content_title']); ?></a></h3>
          <span class="stars"> <span class="star">&nbsp;</span> <span class="star">&nbsp;</span> <span class="star">&nbsp;</span> <span class="star">&nbsp;</span> <span class="star">&nbsp;</span> </span>
           <?php if(intval($more['price'] > 1)) : ?><p class="tag-price">Цена от: <?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></p> <?php endif; ?>
          <p>
            <?php if($the_post['content_description'] != ''): ?>
            <?php print (character_limiter($the_post['content_description'], 130, '...')); ?>
            <?php else: ?>
            <?php print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
            <?php endif; ?>
            <br />
            <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>" class="button-more">прочети повече</a> </p>
          <div class="c"></div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
        <div class="post">
          <p> Няма намерени резултати. </p> 
        </div>
        <?php endif; ?>
        <div class="c"></div>
      </div>
      <?php //include "articles_paging.php" ?>
      <div class="pagining"> <a class="seeall right">следващи</a> <a class="seeall left">предишни</a>
        <p>Страница: 1 от </p>
        <div class="c"></div>
      </div>
    </div>
    <div class="c"></div>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."kongresen_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder -->
