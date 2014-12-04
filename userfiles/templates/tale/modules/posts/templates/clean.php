<?php

/*

type: layout

name: Clean

description: Clean

*/
?>

<div class="module-posts-template-clean">
  <?php if (!empty($data)): ?>



    <?php foreach ($data as $item): ?>
    <div class="module-posts-template-clean-item">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <h5><a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h5>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
        <small class="date"><?php print $item['created_at'] ?></small>
        <?php endif; ?>
      <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a href="<?php print $item['link'] ?>" class="module-posts-template-clean-image" style="background-image: url(<?php print $item['image']; ?> );"> </a>
      <?php endif; ?>
      <div>


        <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
        <p><?php print $item['description'] ?></p>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
        <a href="<?php print $item['link'] ?>" class="btn btn-link btn-sm">
        <?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        </a>
        <?php  endif; ?>
      </div>
    </div>
    <?php endforeach; ?>

  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
