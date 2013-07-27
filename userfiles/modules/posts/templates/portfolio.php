<?php

/*

type: layout

name: Portfolio

description: Portfolio

*/
?>

<div class="row clearfix">
  <ul class="portfolio-post-grid holder">
    <?php if (!empty($data)): ?>
    <?php foreach ($data as $item): ?>
    <li class="span6 portfolio-item" data-id="id-<?php print $item['id'] ?>" data-type="business">
      <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
      <span class="4col"><img src="<?php print $item['image'] ?>" height="340" alt=""></span>
      <?php endif; ?>
      <span class="portfolio-item-meta">
      <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
      <a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a>
      <?php endif; ?>
      <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
      <?php print $item['description'] ?>
      <?php endif; ?>
      </span> </li>
    <?php endforeach; ?>
    <?php endif; ?>
  </ul>
  
  
  <?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print mw('content')->paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
    
 <?php endif; ?>
</div>
