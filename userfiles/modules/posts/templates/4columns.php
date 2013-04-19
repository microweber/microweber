<?php

/*

type: layout

name: 4 Columns

description: 4 Columns

*/
?>



<div class="clearfix container-fluid module-posts-template-columns module-posts-template-columns-4">
  <div class="row-fluid">
    <? if (!empty($data)): ?>
    <?
        $count = -1;
        foreach ($data as $item):
        $count++;
    ?>
    <?php if($count % 4 == 0) { ?><div class="v-space"></div><?php } ?>
    <div class="span3<?php if($count % 4 == 0) { ?> first <?php } ?>" >
        <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <a class="img-polaroid img-rounded" href="<? print $item['link'] ?>">
                <span class="valign">
                    <span class="valign-cell">
                        <img <?php if($item['image']==false){ ?>class="pixum"<?php } ?> src="<? print thumbnail($item['image'], 290, 120); ?>" alt="<?php print addslashes($item['title']); ?> - image" title="<?php print addslashes($item['title']); ?>" />
                    </span>
                </span>
            </a>
        <? endif; ?>
        <div class="module-posts-head">
        <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
            <h3><a class="lead" href="<? print $item['link'] ?>"><? print $item['title'] ?></a></h3>
        <? endif; ?>
        <? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
            <small class="muted">Posted on: <? print $item['created_on']; ?></small>
        <? endif; ?>
        </div>
        <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
            <p class="description"><? print $item['description'] ?></p>
        <? endif; ?>


      <? if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
      <div class="blog-post-footer">
        <a href="<? print $item['link'] ?>" class="btn pull-fleft">
        <? $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
        <i class="icon-chevron-right"></i></a>
      </div>
      <? endif; ?>
    </div>
    <? endforeach; ?>



    <? endif; ?>
  </div>
 <? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <? print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
    
 <? endif; ?>
</div>
