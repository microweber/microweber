<?php if(!empty($active_categories)) : ?>
<?php $thecat = $active_categories[count($active_categories) - 1] ?>
<?php $thecat =  $this->taxonomy_model->getSingleItem($thecat); 
		  $thecat_body = false;
		  if(trim($thecat['content_body'])!= ''){
			  $thecat['content_body'] = html_entity_decode($thecat['content_body']);
			  if(trim(($thecat['content_body']))!= ''){
				   $thecat_body = trim( $thecat['content_body']);
			  }
		  }
		  
$cat_pic = $this->core_model->mediaGet($to_table = 'table_taxonomy', $to_table_id = $thecat['id'], $media_type = false, $order = "desc");
//p($cat_pic);
 	  ?>
<?php // p($thecat ); ?>
<?php if($thecat_body != ''): ?>

<div class="box cat-description richtext">
  <?php if(!empty($cat_pic)): ?>
  <?php $cat_thumb = $this->core_model->mediaGetThumbnailForMediaId($cat_pic['pictures'][0]['id'], $size_width = 220, $size_height = false);
 ?>
  <span class="img" style="background-image: url(<?php print $cat_thumb; ?>);" title="<?php print addslashes( $thecat['taxonomy_value'] ); ?>"> </span>
  <?php endif; ?>
  <h2><?php print $thecat['taxonomy_value'] ?></h2>
  <?php print $thecat_body ; ?></div>
  
  

  <?php $subcats = $this->taxonomy_model->taxonomyGetChildrenItems($thecat['id'], $taxonomy_type = 'category', $orderby = array('position', 'asc')) ; ?>

  <?php if(!empty( $subcats)): ?>
  <div id="innerFslider" class="box">
  <div id="Fslider">
    <a href="#" id="fsliderleft">&nbsp;</a>
    <a href="#" id="fsliderright">&nbsp;</a>
    <div id="Fslidercontent">
<?php /*
      <h2 class="blue-title border-bottom">Избрахме за Вас</h2>
*/ ?>
      <div id="sliderAction">
      <ul style="left: 0;" class="notXfader">
  <?php foreach($subcats as $sc): ?>
   <?php if(!empty($sc)): ?>
  <?php $scat_pic = $this->core_model->mediaGet($to_table = 'table_taxonomy', $to_table_id = $sc['id'], $media_type = false, $order = "desc"); ?>
  <li>
     <?php if(!empty($scat_pic)): ?>
    <?php $cat_thumb = $this->core_model->mediaGetThumbnailForMediaId($scat_pic['pictures'][0]['id'], $size_width = 200, $size_height = false); ?>
 <a class="wert" href="<?php print $this->taxonomy_model->getUrlForIdAndCache($sc['id']); ?>"><img src="<?php print $cat_thumb; ?>" title="<?php print addslashes( $sc['taxonomy_value'] ); ?>" /></a>
  <?php endif; ?>


<?php endif; ?>

   <h2 align="center"><a href="<?php print $this->taxonomy_model->getUrlForIdAndCache($sc['id']); ?>"><?php print $sc['taxonomy_value'] ?></a></h2>

   <?php /*

   $thecat_body = false;
		  if(trim($sc['content_body'])!= ''){
			  $sc['content_body'] = html_entity_decode($sc['content_body']);
			  if(trim(strip_tags($sc['content_body']))!= ''){
				   $thecat_body = trim( $sc['content_body']);
			  }
			  print  character_limiter($thecat_body, 50);
		  }


          */

    ?>
   
   

     <?php if($scat_pic): ?>
  

   
   
  </li>
  <?php endif; ?>
  
  


  
  <?php endforeach; ?>
      </ul>
    </div>
    <!-- /sliderAction -->
    </div>
    <!-- /Fslidercontent -->
  </div>
  </div>
  <?php endif; ?>
  <?php // p($subcats ); ?>
  
  
<div class="c" style="padding-bottom: 20px;">&nbsp;</div>
<?php endif; ?>
<?php endif; ?>
