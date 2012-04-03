<?php // var_dump($search_data) ; ?>    
  
 
<?php if(empty($search_data)): ?>


<?php else: ?>

<div class="richtext">
<h3 class="title"><?php print count($search_data);  ?> matches found for: <?php print $this->core_model->getParamFromURL ( 'keyword' ) ?></h3>   
</div>


<?php foreach ($search_data as $the_post): 
//var_dump($the_post);
?>
<?php $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId($the_post['id'], 160); ?>
  <div class="txt news wrap">
  <h2 class="title inTextTitle"><a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
  <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>" class="aimg" <?php if(!empty($thumb)): ?> style="background-image:url('<?php print $thumb; ?>')"  <?php endif; ?> ></a>
  <span class="date">Posted on <?php print $the_post['created_on']; ?></span>
  <p>
    <?php if($the_post['content_description'] != ''): ?>
	<?php print nl2br(character_limiter($the_post['content_description'], 25000, '...')); ?>
    <?php else: ?>
    <?php print character_limiter($the_post['content_description'], 250, '...'); ?>
    <?php endif; ?>
       <a href="<?php print CI::model ( 'content' )->contentGetHrefForPostId($the_post['id']) ; ?>" class="more">Read More</a>
   <!-- <br />-->
  </p>
</div>    
<?php endforeach; ?>  

<?php endif; ?>