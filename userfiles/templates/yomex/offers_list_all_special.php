<?php include (ACTIVE_TEMPLATE_DIR."offers_heading_nav.php"); ?>
<?php if(!empty($active_categories)) : ?>
<?php $thecat = $active_categories[count($active_categories) - 1] ?>
<?php $thecat =  $this->taxonomy_model->getSingleItem($thecat); 
		  $thecat_body = false;
		  if(trim($thecat['content_body'])!= ''){
			  $thecat['content_body'] = html_entity_decode($thecat['content_body']);
			  if(trim(strip_tags($thecat['content_body']))!= ''){
				   $thecat_body = trim( $thecat['content_body']);
			  }
		  }

 	  ?>
<?php endif; ?>

<div class="holder">
  <div class="hleft">
    <div class="offers"> <a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($thecat['id']); ?>/view:all" class="seeall right">Вижте всички предложения</a>
      <h2 class="gtitle">Специални предложения</h2>
      <span class="hr">&nbsp;</span>
      <?php if(!empty($posts)):  ?>
      <ul class="offers-list 3rd-elem">
        <?php $i = 0;   foreach ($posts as $the_post): ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId($the_post['id'], 250); 
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $the_post['id']);	  ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
        <li> <a  href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"> <span style="background-image: url('<?php print $thumb ?>')">
          <?php if(intval($more['price'] > 1)) : ?>
          <strong class="price"> <?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></strong>
          <?php endif; ?>
          </span> <big><?php print ($the_post['content_title']); ?></big>
          <?php $stars = intval($more['stars']); ?>
          <?php if($stars >0): ?>
          <span class="stars">
          <?php for ($s = 1; $s <= $stars; $s++) { ?>
          <span class="star">&nbsp;</span>
          <?php }  ?>
          </span>
          <?php if(mb_trim($more['accom_base'] != '') and ($more['accom_base'] != '--')) : ?>
          <small class="tag-accomodation">Настаняване:
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
          <?php endif; ?>
          <?php print (character_limiter($the_post['content_description'], 130, '...')); ?></a> </li>
        <?php $i++; endforeach; ?>
      </ul>
      <?php else : ?> 
      <div class="post">
        <p> Няма намерени резултати. </p>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."offers_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder -->
