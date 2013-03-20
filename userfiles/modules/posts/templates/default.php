<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="row clearfix">
  <div class="post-grid holder">
    <? if (!empty($data)): ?>
    <? foreach ($data as $item): ?>
    <div class="blog-post">
      <div class="blog-post-header">
        <? if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
        <h2 class="content-item-title"><? print $item['title'] ?></h2>
        <? endif; ?>
        
        <!--
    
     
      <a class="btn blog-fright" href="<? print $item['link'] ?>"><i> 14 </i></a> --></div>
      <div class="blog-post-body">
        <? if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
        <img src="<? print thumbnail($item['image'], 740); ?>" alt="" class="img-circle">
        <? endif; ?>
        <? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
        <div class="post-meta">Date: <? print $item['created_on'] ?></div>
        <? endif; ?>
        
        <!--        
-->
        <? if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
        <p class="p0"><? print $item['description'] ?></p>
        <? endif; ?>
      </div>
      <? if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
      <div class="blog-post-footer"> <a href="<? print $item['link'] ?>" class="btn btn-success blog-fleft">
        <? $read_more_text ? print $read_more_text : print 'Continue Reading'; ?>
        <i class="icon-chevron-right icon-white"></i></a>
        <div class="btn-group blog-fright"><a class="btn" href="#"><i class="team-social-twitter"></i></a> <a class="btn" href="#"><i class="team-social-facebook"></i></a> <a class="btn" href="#"><i class="team-social-skype"></i></a> <a class="btn" href="#"><i class="team-social-youtube"></i></a></div>
      </div>
      <? endif; ?>
    </div>
    <? endforeach; ?>
    <? endif; ?>
  </div>
  <? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <? print paging("num={$pages_count}&paging_param={$paging_param}") ?>
    
 <? endif; ?>
</div>
