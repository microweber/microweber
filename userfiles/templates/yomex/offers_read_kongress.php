<?php $post_more = $this->core_model->getCustomFields($table = 'table_content', $id = $post['id']); ?>
<?php include (ACTIVE_TEMPLATE_DIR."offers_heading_nav.php"); ?>

<div class="holder">
  <div class="hleft">
    <div class="list-offers gr"> <a href="#" class="seeall back">Върни се обратно</a>
      <h2 class="gtitle"> <?php print ($post['content_title']); ?>
        <?php $stars = intval($post_more['stars']); ?>
        <?php if($stars >0): ?>
        <span class="stars">
        <?php for ($i = 1; $i <= $stars; $i++) { ?>
        <span class="star">&nbsp;</span>
        <?php }  ?>
        </span>
        <?php endif; ?>

      </h2>
      <span class="hr"></span>
      <?php $pictures = $this->content_model->contentGetPicturesFromGalleryForContentId($post['id'], '345');    ?>
      <?php $pictures_small = $this->content_model->contentGetPicturesFromGalleryForContentId($post['id'], '170');  ?>
      <?php $pictures_big = $this->content_model->contentGetPicturesFromGalleryForContentId($post['id'], '600');  ?>
      <?php $pictures_big = $this->content_model->contentGetPicturesFromGalleryForContentId($post['id'], '600');  ?>
      <?php $image_ids = $this->content_model->mediaGetIdsForContentId($post['id'], $media_type = 'picture'); ?>
      <?php if(!empty($pictures)): ?>
      <div class="gallery">
        <?php $pic_info  = $this->core_model->mediaGetById($image_ids[0]) ; ?>
        <div class="gall-big"> <a href="<?php print $pictures_big[0] ?>"><img src="<?php print $pictures_big[0] ?>" title="<?php print addslashes($pic_info['media_name']); ?>" longdesc="<?php print $pictures_big[0] ?>" /></a> </div>
        <div class="gall-other">
          <?php $i = 0;  foreach($pictures as $pic): ?>
          <?php $pic_info  = $this->core_model->mediaGetById($image_ids[$i]) ; ?>
          <span> <a href="<?php print $pic ?>" hreflang="<?php print $pictures_big[$i] ?>"><img title="<?php print addslashes($pic_info['media_name']); ?>" src="<?php print $pictures_small[$i] ?>" alt="" /></a></span>
          <?php $i ++; endforeach; ?>
          <div class="c">&nbsp;</div>
          <div class="c">&nbsp;</div>
          <ul class="image-nav">
            <li><a href="#" title="#" class="left-arrow"></a></li>
            <li><a href="#" title="#" class="right-arrow"></a></li>
          </ul>
        </div>
        <div class="c"></div>
      </div>
      <?php endif; ?>
    </div>
    <?php if($post['custom_fields']['tabs_layout'] =='y') : ?>
    <?php include (ACTIVE_TEMPLATE_DIR."offers_read_tabs.php"); ?>
    <?php else: ?>
    <?php include (ACTIVE_TEMPLATE_DIR."offers_read_notabs.php"); ?>
    <?php endif; ?>
    <?php //include (ACTIVE_TEMPLATE_DIR."offers_small_list.php"); ?>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."kongresen_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
<!-- /#holder -->