<?php

/*

type: layout

name: Grid

description: Grid

*/
?>
<?php  $rand = uniqid(); ?>

<div class="clearfix module-posts-template-mwpgrid module-posts-template-mwpgrid-liteness" id="posts-<?php print $rand; ?>">
  <?php if (!empty($data)): ?>

  <?php
        $count = -1;
            foreach ($data as $item):
        $count++;
		 
		
    ?>
  <div class="mwpgrid-item">
    <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
    <a class="mwpgrid-item-rock-image" href="<?php print $item['link'] ?>" style="background-image: url(<?php print $item['image']; ?>);"></a>
    <?php endif; ?>
    <div class="mwpgrid-item-container">
      <div class="module-posts-head">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <h3><a class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
        <small class="date"><span class="glyphicon glyphicon-calendar"></span> <?php print $item['created_on']; ?></small>
        <?php endif; ?>
      </div>
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
