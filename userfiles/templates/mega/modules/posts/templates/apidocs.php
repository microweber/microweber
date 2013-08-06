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


<div class="post-list-apidocs">
  <?php if (!empty($data)): ?>
  <?php foreach ($data as $item): ?>
  <div class="bbox post-single">
      <div class="bbox-content">

              <div class="post-single-title-date">
                    <h2 class="blue">
                  <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                    <a class="blue" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a>

                  <?php endif; ?>

                  </h2>
                  <hr>
              </div>
              <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                <p class="description"><?php print $item['description'] ?></p>
              <?php endif; ?>

              <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                  <a href="<?php print $item['link'] ?>" class="btn btn-small">
                      <?php $read_more_text ? print $read_more_text : _e("Continue Reading"); ?>
                  </a>
              <?php endif; ?>

      </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
<?php endif; ?>
