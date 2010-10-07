
<div class="heading">
  <h1><?php print $page['content_title']; ?></h1>
</div>
<div class="holder">
  <div class="hleft">
    <div class="list-offers"> <a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>" class="seeall back">Върни се обратно</a>
      <h2 class="gtitle">Други предложения</h2>
 <div class="post">
          <p> Няма намерени резултати. </p>
        </div>
     
 
 
      <div id="Slider">
        <?php if(!empty($posts)):  ?>
        <?php $i = 0;   foreach ($posts as $the_post): ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId($the_post['id'], 250); 
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $the_post['id']);	  ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
        <div class="list-offer">
          <div class="image-holder"><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>"><img src="<?php print $thumb ?>"/></a></div>
          <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" title="<?php print addslashes($the_post['content_title']); ?>"><?php print ($the_post['content_title']); ?></a></h3>
          <p class="tag-price">Цена от: &euro; <?php print intval($more['price']); ?></p>
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
        <?php
  if (($i % 2) == 1)
  { echo '<span class="hr"></span>' ;}
  
?>
        <?php $i++; endforeach; ?>
        <?php else : ?>
        <div class="post">
          <p> Няма намерени резултати. </p>
        </div>
        <?php endif; ?>
        <div class="c"></div>
      </div>
      <div class="pagining"> <a class="seeall right" title="#">следващи</a> <a class="seeall left" title="#">предишни</a>
        <p>Страница: 1 от 36</p>  
        <div class="c"></div>
      </div>
    </div>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."offers_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder --> 