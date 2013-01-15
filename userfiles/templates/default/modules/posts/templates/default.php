<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="row clearfix">
  <ul class="portfolio-post-grid holder">
    <? if (!empty($data)): ?>
    <? foreach ($data as $item): ?>
    <li class="span6 portfolio-item" data-id="id-1" data-type="business"> <span class="4col"> <img src="<? print $item['image'] ?>" alt=""></span> <span class="portfolio-item-meta"><a href="<? print $item['link'] ?>"><? print $item['title'] ?></a><? print $item['description'] ?></span> </li>
    <? endforeach; ?>
    <? endif; ?>
  </ul>
</div>
