<?php

/*

type: layout

name: Sophistika

description: Sophistika

*/
?>
<?php  $rand = uniqid(); ?>

<div class="clearfix module-posts-template-sophistika" id="posts-<?php print $rand; ?>">
  <?php if (!empty($data)): ?>

  <?php
        $count = -1;
        foreach ($data as $item):
        $count++;
    ?>
  <div class="module-posts-template-sophistika-item">
  <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <h3><a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
  <?php endif; ?>
   <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
    <small class="date"><span class="glyphicon glyphicon-calendar"></span> <?php print $item['created_on']; ?></small>
   <?php endif; ?>
   <hr class="hr1">
    <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <a href="<?php print $item['link'] ?>"><img src="<?php print thumbnail($item['image'], 890, 890); ?>" alt="" /></a> <br>
    <?php endif; ?>
    <div class="module-posts-template-sophistika-item-container">

      <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
      <p class="description"><?php print $item['description'] ?></p>
      <?php endif; ?>
      <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
      <div class="blog-post-footer"> <a href="<?php print $item['link'] ?>" class="btn btn-default pull-fleft">
        <?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        <i class="icon-chevron-right"></i></a> </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
