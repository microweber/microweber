<?php $subcats = CI::model ( 'taxonomy' )->getItems($active_categories[0], $taxonomy_type = 'category');   ?>
<?php //var_dump($subcats); ?>
<?php if(!empty($subcats)): ?>

<div class="articles-categories-sidebar" id="side_nav">
  <!--<h2><?php print $page['content_title'] ?></h2>-->
  <?php //thisp age

$link = false;

$link = CI::model ( 'content' )->getContentURLById($page['id']).'/categories:{id}' ;

$active = '  class="active"   ' ;

$actve_ids = $active_categories; 

if( empty($actve_ids ) == true){  

$actve_ids = array($page['content_subtype_value']);

}

CI::model ( 'content' )->content_helpers_getCaregoriesUlTree($page['content_subtype_value'], "<a href='$link'  {active_code}    >{taxonomy_value}</a>" , $actve_ids = $actve_ids, $active_code = $active, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false); ?>
</div>
<?php endif; ?>
<?php if(!empty($posts)): ?>
<div class="nbj_wraap<?php if(!empty($subcats)): ?> nbj_wraap_short<?php endif; ?>">
<?php foreach ($posts as $the_post):

//var_dump($the_post);

?>

<?php $more = false;
 $more = $this->core_model->getCustomFields('table_content', $the_post['id']);
	$the_post['custom_fields'] = $more;
	?>




<?php if( $full_pic  == false) : ?>












<?php $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId($the_post['id'], 160); ?>
<div class="txt news wrap<?php if(!empty($subcats)): ?> articles_has_sidebar<?php endif; ?>">


<? if(trim($more['external_link']) != '') : ?>

  <h2 class="title inTextTitle"><a href="<?php print prep_url($more['external_link']); ?>" target="_blank"><?php print $the_post['content_title']; ?></a></h2>
  <a href="<?php print prep_url($more['external_link']); ?>" class="aimg"  target="_blank"  <?php if(!empty($thumb)): ?> style="background-image:url('<?php print $thumb; ?>')"  <?php endif; ?> ></a>
  
       <?php else: ?>
     <h2 class="title inTextTitle"><a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
  <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>" class="aimg" <?php if(!empty($thumb)): ?> style="background-image:url('<?php print $thumb; ?>')"  <?php endif; ?> ></a>
      <?php endif; ?>
      

  
  
  <?php else: ?>
  <?php $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId($the_post['id'], 'original'); ?>
  <div class="txt news wrap<?php if(!empty($subcats)): ?> articles_has_sidebar<?php endif; ?>">
   
   <? if(trim($more['external_link']) != '') : ?>

   <h2 class="title inTextTitle"><a href="<?php print prep_url($more['external_link']); ?>" target="_blank"><?php print $the_post['content_title']; ?></a></h2>
    <a href="<?php print prep_url($more['external_link']); ?>" target="_blank"><img src="<?php print $thumb; ?>" alt="<?php print addslashes($the_post['content_title']); ?>" align="left" vspace="5" hspace="5" height="120" style="margin:5px;" border="0" /></a>
    
       <?php else: ?>
      <h2 class="title inTextTitle"><a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
       <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>"><img src="<?php print $thumb; ?>" alt="<?php print addslashes($the_post['content_title']); ?>" align="left" vspace="5" hspace="5" height="120" style="margin:5px;" border="0" /></a>
        <?php endif; ?>
   
  
    
    
    
    
   
    
    
    
    <!-- <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>" class="aimg" <?php if(!empty($thumb)): ?> style="background-image:url('<?php print $thumb; ?>')"  <?php endif; ?> ></a>-->
    <?php endif;  ?>
    <p>
      <?php if($the_post['content_description'] != ''): ?>
      <?php print nl2br(character_limiter($the_post['content_description'], 25000, '...')); ?>
      <?php else: ?>
      <?php print character_limiter($the_post['content_description'], 250, '...'); ?>
      <?php endif; ?>
      
      <? if(trim($more['external_link']) != '') : ?>
       <a href="<?php print prep_url($more['external_link']); ?>" target="_blank" class="more">Read More</a>
       <?php else: ?>
      <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>" class="more">Read More</a>
      <?php endif; ?>
      
      <!-- <br />-->
    </p>
  </div>
  <?php endforeach; ?>
</div>
<div style="height:12px"></div>
<?php if(!empty($posts_pages_links)): ?>
<?php print $page_link ;  ?>
<ul class="paging">
  <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
  <li><a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></li>
  <?php $i++; endforeach;  ?>
</ul>
<?php endif ; ?>
<?php else : ?>
<div class="txt news wrap"> </div>
<?php endif; ?>
<div class="clear" style="height:25px">
  <!--  -->
</div>
