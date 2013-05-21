<?php

/*

type: layout

name: Columns

description: Columns

*/
?>



<div class="clearfix container-fluid module-posts-template-columns">
  <div class="row-fluid">
    <?php if (!empty($data)): ?>
    <?php foreach ($data as $item): ?>
 
    <div class="span4">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <a class="img-polaroid img-rounded" href="<?php print $item['link'] ?>">
                <img src="<?php print thumbnail($item['image'], 290, 210); ?>" alt="<?php print addslashes($item['title']); ?> - <?php _e("image"); ?>" title="<?php print addslashes($item['title']); ?>" />
            </a>
        <?php endif; ?>
        <div class="module-posts-head">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
            <h3><a class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
            <small class="muted">Posted on: <?php print $item['created_on']; ?></small>
        <?php endif; ?>
        </div>
        <?php if(!isset($show_fields) or  ($show_fields == false or in_array('description', $show_fields))): ?>
            <p class="description"><?php print $item['description'] ?></p>
        <?php endif; ?>


      <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
      <div class="blog-post-footer">
        <a href="<?php print $item['link'] ?>" class="btn pull-fleft">
        <?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        <i class="icon-chevron-right"></i></a>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>



    <?php endif; ?>
  </div>
 <?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
    
 <?php endif; ?>
</div>
