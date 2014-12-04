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
<div class="mw-ui-row">
    <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <div class="mw-ui-col" style="width: 80px;">
         <div class="mw-ui-col-container">
            <a href="<?php print $item['link'] ?>" class="bgimg" style="background-image: url(<?php print thumbnail($item['image'], 150, 150); ?> );"> </a>
         </div>
       </div>
      <?php endif; ?>
    <div class="mw-ui-col">
        <div class="mw-ui-col-container">
            <div class="media-body extra-wrap">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <a class="sidebar-post-title" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
        <small class="date"><?php print $item['created_at'] ?></small>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
        <p class="sidebar-post-description"><?php print $item['description'] ?></p>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
        <a href="<?php print $item['link'] ?>" class="mw-ui-link">
        <?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        </a>
        <?php  endif; ?>
      </div>
        </div>
    </div>
</div>

    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
