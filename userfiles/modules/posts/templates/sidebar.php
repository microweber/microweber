<?php

/*

type: layout

name: sidebar

description: sidebar

*/
?>

<div class="portfolio-post-grid holder">
  <? if (!empty($data)): ?>
  <ul class="unstyled footer-list-news">
  <? foreach ($data as $item): ?>


            <li class="media">
              <a href="<? print $item['link'] ?>" class="pull-left block img-rounded" style="max-height: 100px;">
              <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                <img src="<? print thumbnail($item['image'], 100); ?>" alt="" style="width:100px;"  />
              <? endif; ?>
              </a>
              <div class="media-body extra-wrap">
              <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
               <strong><a href="<? print $item['link'] ?>" class="media-heading"><? print $item['title'] ?></a></strong>
               <? endif; ?>
               <? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                    <small><? print $item['created_on'] ?></small>
                <?php endif; ?>
                <? if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                    <a href="<? print $item['link'] ?>"><? $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?></a>
                <?php  endif; ?>
               </div>
            </li>






  <? endforeach; ?>
   </ul> 
  <? endif; ?>
</div>
<? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <? print paging("num={$pages_count}&paging_param={$paging_param}") ?>
    
 <? endif; ?>








