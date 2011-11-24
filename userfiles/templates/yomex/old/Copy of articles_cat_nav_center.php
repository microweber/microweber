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
            <?php ?>   
            
          </li>
        
          <?php endforeach; ?>
        </ul>
    

   
<?php endif; ?>
<?php // p($subcats ); ?>
<div class="c" style="padding-bottom: 20px;">&nbsp;</div>
<?php endif; ?>
