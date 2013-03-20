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
  <div class="well clearfix post-single">
      <div class="row">
          <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <div class="span3">
                <a href="<? print $item['link'] ?>"><img src="<? print thumbnail($item['image'], 270); ?>" alt="" ></a>
            </div>
          <? endif; ?>
          <div class="span4">
              <div class="post-single-title-date">
                  <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                    <h2 class="lead"><a href="<? print $item['link'] ?>"><? print $item['title'] ?></a></h2>
                  <? endif; ?>
                  <? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                    <small class="muted">Date: <? print $item['created_on'] ?></small>
                  <? endif; ?>
              </div>
              <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                <p class="description"><? print $item['description'] ?></p>
              <? endif; ?>

              <? if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                  <a href="<? print $item['link'] ?>" class="btn">
                      <? $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
                  </a>
              <? endif; ?>
          </div>
      </div>
  </div>
  <? endforeach; ?>
  <? endif; ?>
</div>
<? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <? print paging("num={$pages_count}&paging_param={$paging_param}") ?>
    
 <? endif; ?>
