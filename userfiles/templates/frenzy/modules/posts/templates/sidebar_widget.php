<?php

/*

type: layout

name: sidebar widget

description: Posts list

*/
?>

<div class="sidebar-content post-widget">
  <ul>
    <? if (!empty($data)): ?>
    <? foreach ($data as $item): ?>
    <li class="sidebar-item">
      <? if(isset($item['image'])): ?>
      <a href="<? print $item['link'] ?>" class="image-polaroid" title="Title"> <img src="<? print thumbnail($item['image'] ,60)?>" alt="Image" /> </a>
      <? endif; ?>
      <h5><a href="<? print $item['link'] ?>"><? print $item['title'] ?></a></h5>
      <span class="post-date"><em><? print $item['created_on'] ?></em></span>
      <div class="rating-static star-small rate1"> <span class="awe-star"></span> <span class="awe-star"></span> <span class="awe-star"></span> <span class="awe-star"></span> <span class="awe-star"></span> </div>
    </li>
    <? endforeach; ?>
    <? endif; ?>
  </ul>
</div>
