<?php // p($items);

$posts = $search_data;


 ?>



  <?php $cat  = $this->content_model->taxonomyGetSingleItemById($this->core_model->getParamFromURL ( 'categories' )); ?>
<div class="heading">
  <h1><?php print $page['content_title']; ?></h1>
</div>
<div class="holder">
  <div class="hleft">
    <div class="offers"> <!--<a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>" class="seeall back">Върни се обратно</a>-->
      <h2 class="gtitle"><?php if(empty($cat)) : ?>Търсене<?php else: ?><?php print $cat['taxonomy_value'] ?><?php endif; ?></h2>
      <span class="hr">&nbsp;</span>
      <?php if(!empty($posts)):  ?>
      <ul class="offers-list 3rd-elem">
        <?php $i = 0;   foreach ($posts as $the_post): ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId($the_post['id'], 250); 
	  $more = $this->core_model->getCustomFields($table = 'table_content', $id = $the_post['id']);	  ?>
      
      <?php if(intval($more['price']) > 1): ?>
      
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
        <li> <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"> <span style="background-image: url('<?php print $thumb ?>')">
         <?php if(intval($more['price'] > 1)) : ?> 
         <strong class="price"><?php print intval($more['price']); ?> <?php echo  $more['curency'] ?></strong> 
         <?php endif; ?>
         </span> <big><?php print ($the_post['content_title']); ?></big><?php print (character_limiter($the_post['content_description'], 130, '...')); ?></a> </li>
        
        <?php endif; ?>
        <?php $i++; endforeach; ?>
      </ul>
      <?php else : ?>
      <div class="post">
        <p>Няма намерени резултати</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."search_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder --> 