<?php

/*

type: layout

name: default

description: Posts list

*/
?>
<div class="content-list">
  <? if (!empty($data)): ?>
  <? foreach ($data as $item): ?>
  <div class="content-item content-<? print $item['id'] ?>">
    <h2 class="content-item-title"><? print $item['title'] ?></h2>
    <div class="content-item-image"><? print $item['thumbnail'] ?></div>
    <div class="content-item-description"><? print $item['description'] ?><? print $item['read_more'] ?></div>
  </div>
  <? endforeach; ?>
  <? else: ?>
  <div class="content-list-empty">No posts</div>
  <? endif; ?>
</div>
<? if (!empty($paging_links)): ?>
<div class="paging">
  <? foreach ($paging_links as $k => $v): ?>
  <span class="paging-item" data-page-number="<? print $k; ?>" ><a  data-page-number="<? print $k; ?>" data-paging-param="<? print $paging_param; ?>" href="<? print $v; ?>"  class="paging-link"><? print $k; ?></a></span>
  <? endforeach; ?>
</div>
<? endif; ?>
