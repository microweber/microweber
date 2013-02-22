<?php

/*

type: layout

name: Blog

description: Blog

*/
?>

<div class="post-list">
  <? if (!empty($data)): ?>
  <? foreach ($data as $item): ?>
  <div class="well clearfix post-list-single-item">

      <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <a href="<? print $item['link'] ?>"><img src="<? print thumbnail($item['image'], 270); ?>" alt="" ></a>
      <? endif; ?>

      <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <h2 class="content-item-title"><? print $item['title'] ?></h2>
      <? endif; ?>

      <? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
        <small class="muted">Date: <? print $item['created_on'] ?></small>
      <? endif; ?>
      <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
        <p class="description"><? print $item['description'] ?></p>
      <? endif; ?>

    <? if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
        <a href="<? print $item['link'] ?>" class="btn pull-right">
            <? $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
        </a>
    <? endif; ?>
  </div>
  <? endforeach; ?>
  <? endif; ?>
</div>
<? if (!empty($paging_links)): ?>
<?php _d($paging_links); ?>
<div class="pagination">
  <ul>
    <? foreach ($paging_links as $k=>$item): ?>
    <li><a href="<? print $item ?>"><? print $k ?></a></li>
    <? endforeach; ?>
  </ul>
</div>
<? endif; ?>
