<?php

/*

type: layout

name: Portfolio

description: Portfolio

*/
?>

<div class="row clearfix">
  <ul class="portfolio-post-grid holder">
    <? if (!empty($data)): ?>
    <? foreach ($data as $item): ?>
    <li class="span6 portfolio-item" data-id="id-<? print $item['id'] ?>" data-type="business">
      <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <span class="4col"><img src="<? print $item['image'] ?>" height="340" alt=""></span>
      <? endif; ?>
      <span class="portfolio-item-meta">
      <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
      <a href="<? print $item['link'] ?>"><? print $item['title'] ?></a>
      <? endif; ?>
      <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
      <? print $item['description'] ?>
      <? endif; ?>
      </span> </li>
    <? endforeach; ?>
    <? endif; ?>
  </ul>
  
  
  <? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <? print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
    
 <? endif; ?>
</div>
