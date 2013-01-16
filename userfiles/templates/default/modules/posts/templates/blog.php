<?php

/*

type: layout

name: Blog

description: Blog

*/
?>

<div class="portfolio-post-grid holder">
  <? if (!empty($data)): ?>
  <? foreach ($data as $item): ?>
  <div class="blog-post">
    <div class="blog-post-header">
      <h2><a href="<? print $item['link'] ?>"><? print $item['title'] ?></a></h2>
      <a class="btn blog-fright" href="<? print $item['link'] ?>"><i> 14 </i></a> </div>
    <div class="blog-post-body"> <img src="<? print thumbnail($item['image'], 740); ?>" alt="" class="img-circle"> 
      <!--        <div class="post-meta">Posted by: <a href="#">owltemplates </a> | Posted in: <a href="#">template,</a> <a href="#">wordpress,</a> <a href="#">premium</a> </div>
-->
      <p class="p0"><? print $item['description'] ?></p>
    </div>
    <div class="blog-post-footer"> <a href="<? print $item['link'] ?>" class="btn btn-success blog-fleft">Continue Reading <i class="icon-chevron-right icon-white"></i></a>
      <div class="btn-group blog-fright"><a class="btn" href="#"><i class="team-social-twitter"></i></a> <a class="btn" href="#"><i class="team-social-facebook"></i></a> <a class="btn" href="#"><i class="team-social-skype"></i></a> <a class="btn" href="#"><i class="team-social-youtube"></i></a></div>
    </div>
  </div>
  <? endforeach; ?>
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
