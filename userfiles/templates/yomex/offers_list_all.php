<?php include (ACTIVE_TEMPLATE_DIR."offers_heading_nav.php"); ?>
<?php if(!empty($active_categories)) : ?>
<?php //p($active_categories);
if(count($active_categories) > 1 ){
$thecat = $active_categories[count($active_categories) - 1] ;
} else {
	$thecat = $active_categories[count($active_categories) ] ;
}



?>
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
<?php $keyword = $this->core_model->getParamFromURL ( 'keyword' ); ?>
<?php if(!empty($active_categories)) : ?>
<?php $thecat = $active_categories[count($active_categories) - 1] ?>
<?php $thecat =  $this->taxonomy_model->getSingleItem($thecat);  ?>
<?php endif; ?>

<div class="holder">
  <div class="hleft">
    <?php include (ACTIVE_TEMPLATE_DIR."offers_category_description_box.php"); ?>
    <?php include (ACTIVE_TEMPLATE_DIR."search_box.php"); ?>
    
 
    
    <div class="list-offers mega all-offers">
      <?php //var_Dump($active_categories);
	 if(intval($page['id']) != 489):  //tova e za kongresen turizam ?>
      <?php if(($keyword) == false) : ?>
      <?php if(!empty($active_categories)) : 
	 //var_dump($thecat);
	 
	 $link_to_here = $this->content_model->getContentURLByIdAndCache($page['id']).'/categories:'.$thecat['taxonomy_value'];
	 ?>
      <!--  <a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($thecat['id']); ?>/view:all/is_special:y" class="seeall right">Вижте нашите специални предложения</a>-->
      <?php endif; ?>
      <h2 class="gtitle"><?php print $thecat['taxonomy_value'] ?> - Всички предложения</h2>
      
      <ul style="display:none">
      <li>
      <a href="<?php print  $link_to_here ?>/view:all/is_special:y" class="list-special">Специални предложения</a></li>
       <li><a href="<?php print  $link_to_here ?>/view:all" class="list-nospecial">Всички</a> </li>
       
      </ul>
      
      
      <?php else : ?>
      <h2 class="gtitle">Търсене: <?php print $keyword ?></h2>
      <?php endif; ?>
      
      
   
      
      <span class="hr"></span>
      <?php /*<div class="right"> <small class="order-items-control">
        <?php $custom_fields_criteria = array();
	   $custom_fields_criteria['stars'] = 5 ; 
	    $custom_fields_criteria = base64_encode(serialize( $custom_fields_criteria));
	  
	  ?>
        <a <?php if(($ord == 'updated_on')) : ?>  class="active" <?php endif; ?> class="seeall" href="<?php print $link_to_here ?>/view:all/custom_fields_criteria:<?php print $custom_fields_criteria ?>">5 stars</a>
        <?php $ord=  $this->core_model->getParamFromURL ( 'ord' );   ?>
        <a <?php if(($ord == 'updated_on')) : ?>  class="active" <?php endif; ?> href="<?php print $link_to_here ?>/view:all/ord:updated_on">Първо новите</a> | <a <?php if(($ord == 'content_title')) : ?>  class="active" <?php endif; ?> href="<?php print $link_to_here ?>/view:all/ord:content_title/ord-dir:asc">По азбучен ред</a></small> </div>*/?>
      <?php endif; ?>
      <?php if(!empty($posts)):  ?>
      <?php $i = 0;   foreach ($posts as $the_post): ?>
      <div class="post<?php if(($the_post['is_special']) == 'y'):  ?> box cek<?php endif; ?>" style="margin-top:20px;">
        <?php //$thumb = $this->content_model->contentGetThumbnailForContentId($the_post['id'], 250);
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $the_post['id']);	  ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 200);  ?>
        <div class="image-holder">
            <a style="background-image: url(<?php print $thumb ?>)" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>">
            </a></div>
        <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>"><?php print ($the_post['content_title']); ?></a>
          <?php $stars = intval($more['stars']); ?>
          <?php if($stars >0): ?>
          <span class="stars">
          <?php for ($i = 1; $i <= $stars; $i++) { ?>
          <span class="star">&nbsp;</span>
          <?php }  ?>
          </span>
          <?php endif; ?>
        </h3>
        <?php if(intval($more['price'] > 1)) : ?>
        <p class="tag-price">Цена от: <?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></p>
        <?php endif; ?>
        
        
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
        
        
        
      <?php /* <?php $cats_for_post =  $this->content_model->contentGetActiveCategoriesForPostIdAndCache($the_post['id'], $starting_from_category_id = 2161) ; ?>
        <?php //var_dump($test);  // ($content_id, $starting_from_category_id = 2161) ?>
        <?php if (!empty($cats_for_post)) : 
		  // array_pop($cats_for_post);
		  $cats_for_post = array_reverse($cats_for_post);
		 asort($cats_for_post);
		  array_shift($cats_for_post);
		  ?>
        <small class="tag-dest">
        <?php $cp = 0; foreach($cats_for_post as $cats_for_post_item): ?>
        <?php $cats_for_post_item_full = $this->taxonomy_model->getSingleItem($cats_for_post_item); ?>
        <a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>/categories:<?php print $cats_for_post_item_full['taxonomy_value']; ?>/view:all/"><?php print $cats_for_post_item_full['taxonomy_value']; ?></a>
        <?php if(!empty($cats_for_post[$cp+1])):  ?>,<?php endif; ?>
        <?php $cp++; endforeach;  ?>
        </small>
        <?php endif; ?>*/?>
        <p>
          <?php if($the_post['content_description'] != ''): ?>
          <?php print (character_limiter($the_post['content_description'], 130, '...')); ?>
          <?php else: ?>
          <?php print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
          <?php endif; ?>
          <br />
          <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>" class="button-more">прочети повече</a> </p>
        <div class="c" style="padding: 0;"></div>
      </div>
      <?php $i++; endforeach; ?>
      <?php else : ?>
      <div class="post">
        <p> Няма намерени резултати. </p>
      </div> 
      <?php endif; ?>
      <div class="c"></div>
      <?php include (ACTIVE_TEMPLATE_DIR."articles_paging_nojs.php"); ?>
    </div>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."offers_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder -->
