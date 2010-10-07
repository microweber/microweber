<?php dbg(__FILE__); ?>

<?php $image_ids = $this->content_model->mediaGetIdsForContentId($post['id'], $media_type = 'picture'); ?>
<?php if(!empty($image_ids  )) :?>
<div class="article-gallery">
  <?php foreach($image_ids as $img_id): ?>
  <?php $thumb = $this->core_model->mediaGetThumbnailForMediaId($img_id, $size = 128); ?>
  <?php $big = $this->core_model->mediaGetThumbnailForMediaId($img_id, $size = 600); ?>

  <a href="<?php print $big ?>"><img src="<?php print $thumb; ?>" alt="" /></a>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php dbg(__FILE__, 1); ?>
