<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 250;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>


<div class="post-list">
  <?php if (!empty($data)): ?>
  <?php foreach ($data as $item): ?>
  <div class="well clearfix post-single">
      <div class="row-fluid">
          <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <div class="span4">
                <a href="<?php print $item['link'] ?>"><img src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" class="img-rounded img-polaroid" alt="" ></a>
            </div>
          <?php endif; ?>
          <div class="span8">
              <div class="post-single-title-date">
                  <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                    <h2 class="lead"><a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h2>
                  <?php endif; ?>
                  <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                    <small class="muted"><?php _e("Date"); ?>: <?php print $item['created_on'] ?></small>
                  <?php endif; ?>
              </div>
              <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                <p class="description"><?php print $item['description'] ?></p>
              <?php endif; ?>

              <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                  <a href="<?php print $item['link'] ?>" class="btn">
                      <?php $read_more_text ? print $read_more_text : _e("Continue Reading"); ?>
                  </a>
              <?php endif; ?>
          </div>
      </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
<?php endif; ?>
