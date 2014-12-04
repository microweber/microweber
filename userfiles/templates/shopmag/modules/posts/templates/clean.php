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
    <div class="module-posts-template-clean-item item-box pad">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <h3><a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
        <small class="date"><?php print $item['created_at'] ?></small>
        <?php endif; ?>
      <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <a href="<?php print $item['link'] ?>" class="bgimage module-posts-template-clean-image" style="background-image: url(<?php print $item['image']; ?> );"> </a>
      <?php endif; ?>
        <div class="mw-ui-row description-row">
            <div class="mw-ui-col">
              <div class="mw-ui-col-container">
                <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                    <p class="module-posts-template-clean-description"><?php print $item['description'] ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="mw-ui-col read-more-col">
              <div class="mw-ui-col-container">
                <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                    <a href="<?php print $item['link'] ?>" class="sm-icon-glasses pull-right tip" data-tip="<?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>"></a>
                <?php  endif; ?>
              </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
