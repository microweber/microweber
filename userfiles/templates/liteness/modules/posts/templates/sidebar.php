<?php

/*

type: layout

name: sidebar

description: sidebar

*/
?>

<div class="module-posts-template-sidebar">
  <?php if (!empty($data)): ?>
  <ul>
    <?php foreach ($data as $item): ?>
    <li class="media">
      <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a href="<?php print $item['link'] ?>" class="pull-left sidebar-post-image" style="background-image: url(<?php print thumbnail($item['image'], 150, 150); ?> );"> </a>
      <?php endif; ?>
      <div class="media-body extra-wrap">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <h5><a class="link media-heading" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h5>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
        <small class="date"><?php print $item['created_on'] ?></small>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
        <p><?php print $item['description'] ?></p>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
        <a href="<?php print $item['link'] ?>" class="btn btn-link btn-sm">
        <?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        </a>
        <?php  endif; ?>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
