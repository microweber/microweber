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

$cat_pic = $this->core_model->mediaGet($to_table = 'table_taxonomy', $to_table_id = $thecat['id'], $media_type = false, $order = "desc");
//p($cat_pic);
 	  ?>
<?php // p($thecat ); ?>

<div class="box cat-description">
  <?php if(!empty($cat_pic)): ?>
  <?php $cat_thumb = $this->core_model->mediaGetThumbnailForMediaId($cat_pic['pictures'][0]['id'], $size_width = 220, $size_height = false);
 ?>
  <span class="img" style="background-image: url(<?php print $cat_thumb; ?>);" title="<?php print addslashes( $thecat['taxonomy_value'] ); ?>"> </span>
  <?php endif; ?>
  <h2><?php print $thecat['taxonomy_value'] ?></h2>
  <?php print $thecat_body ; ?></div>
<?php $subcats = $this->taxonomy_model->taxonomyGetChildrenItems($thecat['id'], $taxonomy_type = 'category', $orderby = array('position', 'asc')) ; ?>
<?php if(!empty( $subcats)): ?>

      <?php /*
      <h2 class="blue-title border-bottom">Избрахме за Вас</h2>
*/ ?>
   
        <ul class="help">
          <?php foreach($subcats as $sc): ?>
          <?php if(!empty($sc)): ?>
          <?php $scat_pic = $this->core_model->mediaGet($to_table = 'table_taxonomy', $to_table_id = $sc['id'], $media_type = false, $order = "desc"); ?>
          <li>

            <?php $cat_thumb = $this->core_model->mediaGetThumbnailForMediaId($scat_pic['pictures'][0]['id'], $size_width = 20, $size_height = false); ?>



            <?php endif; ?>
            <a class="sotw" <?php if(!empty($scat_pic)): ?>style="background-image: url(<?php print $cat_thumb; ?>)"<?php endif;?> href="<?php print $this->taxonomy_model->getUrlForIdAndCache($sc['id']); ?>"><?php print $sc['taxonomy_value'] ?></a>
   
   
   
   
   
   
   
   
   
   
   
     <?php // var_dump($active_categories);
      $related = array();
	  $related['selected_categories'] = array($sc['id']);
	  $related['visible_on_frontend'] = 'y'; 
	  //$related['is_special'] = 'n';
	  $limit[0] = 0;
	  $limit[1] = 5;
	  $posts = $this->content_model->getContentAndCache($related, false,$limit ); ?>
        <?php if(!empty($posts)): 
		 
		  ?>
          <ul>
        <?php foreach ($posts as $the_post): ?>
        
        <li>
       
          <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>"><?php print ($the_post['content_title']); ?></a></h3>
          
 
       
            <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>" class="button-more">прочети повече</a>  
        
         </li>
        <?php endforeach; ?>
        </ul>
        <?php else : ?>
 
        <?php endif; ?>
        
        
        
        
        
        
        
        
        
        
        
        
        
          </li>
       
          <?php endforeach; ?>
        </ul>
    

   
<?php endif; ?>
<?php // p($subcats ); ?>
<div class="c" style="padding-bottom: 20px;">&nbsp;</div>
<?php endif; ?>
