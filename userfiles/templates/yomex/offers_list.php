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
    <?php include (ACTIVE_TEMPLATE_DIR."search_box.php"); ?>
    <?php $related = array();
	  $related['selected_categories'] = $active_categories;
	  $related['is_special'] = 'y';
	  $related['visible_on_frontend'] = 'y'; 
	  $limit[0] = 0;
	  $limit[1] = 3;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
    <?php if(!empty($related)): ?>
    <div class="offers"> <a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>/view:all/ord:is_special/ord-dir:desc" class="seeall right">Виж всички специални предложения</a>
      <h2 class="gtitle">Специални предложения</h2>
      <span class="hr">&nbsp;</span>
      <ul class="offers-list 3rd-elem">
        <?php foreach($related as $item): ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId($item['id'], 250);
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $item['id']);

	  ?>
        <li> <a href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>"> <span style="background-image: url('<?php print $thumb ?>'); background-position:center center;"> 
        
        <?php if(intval($more['price'] > 1)) : ?>
        <strong class="price"><?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></strong> 
        <?php endif; ?>
        </span> <big><?php print character_limiter($item['content_title'], 50, '...'); ?>  <?php $stars = intval($more['stars']); ?>
          <?php if($stars >0): ?>
          <span class="stars">
          <?php for ($i = 1; $i <= $stars; $i++) { ?>
          <span class="star">&nbsp;</span>
          <?php }  ?>
          </span>
          <?php endif; ?> </big> <?php print (character_limiter($item['content_description'], 95, '...')); ?> </a> </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif;  ?>
    <div class="list-offers"> <a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>/view:all/is_special:n" class="seeall right">Виж всички предложения</a>
      <h2 class="gtitle">Всички предложения</h2>
      <span class="hr"></span>
      <div id="Slider">
        <?php // var_dump($active_categories);
      $related = array();
	  $related['selected_categories'] = $active_categories;
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
          <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>"><?php print ($the_post['content_title']); ?></a> <?php $stars = intval($more['stars']); ?>
          <?php if($stars >0): ?>
          <span class="stars">
          <?php for ($i = 1; $i <= $stars; $i++) { ?>
          <span class="star">&nbsp;</span>
          <?php }  ?>
          </span>
          <?php endif; ?> </h3>
          
          
         
          
         
           
           
         
            <?php if(intval($more['price'] > 1)) : ?><p class="tag-price">Цена от: <?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></p> <?php endif; ?>
           
            <?php if(mb_trim($more['accom_base'] != '') and ($more['accom_base'] != '--')) : ?>
        <small class="tag-accomodation"> 
		<?php switch($more['accom_base']){
		case 'BB':
		print 'BB – нощувка и закуска';
		break;
		
		case 'HB':
		print 'HB – полупансион';
		break;
		
		case 'FB':
		print 'FB – пълен пансион';
		break;
		
		case 'AI':
		print 'AI – всичко включено';
		break;
		
		case 'UAI':
		print 'UAI – ултра всичко включено';
		break;
	
		}?>
        
        </small>
        <?php endif; ?>
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
  <?php include (ACTIVE_TEMPLATE_DIR."offers_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder --> 