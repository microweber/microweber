<?php

/*

type: layout

name: Team

description: Team

*/
?>

<div   class="tab-pane">
  <? if (!empty($data)): ?>
  <ul class="thumbnails thumbnails_3">
    <? foreach ($data as $item): ?>
    <li class="span2">
      <div class="thumbnail_3">
        <? if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <a href="<? print $item['link'] ?>">
        <figure class="img-circle"><img src="<? print thumbnail($item['image'], 159); ?>" alt="" class="img-circle"></figure>
        </a>
        <? endif; ?>
        <div class="team-item-info">
          <? if($show_fields == false or in_array('title', $show_fields)): ?>
          <a href="<? print $item['link'] ?>" class="lead"><? print $item['title'] ?></a><br>
          <? endif; ?>
          <? if($show_fields == false or in_array('description', $show_fields)): ?>
          <? print $item['description'] ?>
          <? endif; ?>
        </div>
      </div>
    </li>
    <? endforeach; ?>
  </ul>
  <? endif; ?>
</div>
<? if (!empty($paging_links)): ?>
<div class="pagination indent-1 pagination-right">
  <ul>
    <? foreach ($paging_links as $k=>$item): ?>
    <li><a href="<? print $item ?>"><? print $k ?></a></li>
    <? endforeach; ?>
  </ul>
</div>
<? endif; ?>
