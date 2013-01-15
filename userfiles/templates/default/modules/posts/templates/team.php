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
        <figure class="img-circle"><img src="<? print thumbnail($item['image'], 159); ?>" alt="" class="img-circle"></figure>
        <div><a href="<? print $item['title'] ?>" class="lead"><? print $item['title'] ?></a><br>
          <? print $item['description'] ?></div>
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
