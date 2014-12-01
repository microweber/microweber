<?php

/*

type: layout

name: 3 Columns

description: 3 Columns

*/
?>


<?php


$tn = $tn_size;
if(!isset($tn[0]) or ($tn[0]) == 150){
     $tn[0] = 400;
}
if(!isset($tn[1])){
     $tn[1] = $tn[0];
}


?>



<div class="clearfix container module-posts-template-columns module-posts-template-columns-3">
  <div class="row">
    <?php if (!empty($data)): ?>
    <?php
        $count = -1;
        foreach ($data as $item):
        $count++;
    ?>

    <?php if($count % 3 == 0 and $count != 0) { ?><div class="v-space"></div><?php } ?>

    <div class="col-sm-4<?php if($count % 3 == 0) { ?> first <?php } ?>" >
        <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <a class="module-posts-image" href="<?php print $item['link'] ?>">
                <img <?php if($item['image']==false){ ?>class="pixum"<?php } ?> src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>" alt="<?php print addslashes($item['title']); ?> - <?php _e("image"); ?>" title="<?php print addslashes($item['title']); ?>" />
            </a>
        <?php endif; ?>
        <div class="module-posts-head">
        <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
            <h3><a class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
        <?php endif; ?>
        <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
            <small class="muted"><?php _e("Posted on"); ?>: <?php print $item['created_on']; ?></small>
        <?php endif; ?>
        </div>
        
        <?php if(!isset($show_fields) or ($show_fields == false or in_array('description', $show_fields))): ?>
            <p class="description"><?php print $item['description'] ?></p>
        <?php endif; ?>


      <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
      <div class="blog-post-footer">
        <a href="<?php print $item['link'] ?>" class="btn btn-default pull-fleft">
        <?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        <i class="icon-chevron-right"></i></a>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>



    <?php endif; ?>
  </div>
 <?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
    
 <?php endif; ?>
</div>
