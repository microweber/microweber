<?php

/*

type: layout

name: blog layout

description: blog layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="content" class="TheContent">
  <div class="TheContent1 searchEngine">
    <div class="pad2 ishr" id="search_header">
      <h2 class="left">Categories</h2>
      <div class="right">
        <? if(empty($post)): ?>
        <h2>Job ads</h2>
        <div class="TheEngine">
          <label>Page</label>
          <? paging(); ?>
          <? include(TEMPLATE_DIR.'layouts/search_jobs/search_box.php'); ?>
        </div>
        <? else :?>
        <h2><? print $post['content_title']; ?></h2>
        <? endif; ?>
      </div>
    </div>
    <!-- /#search_header -->
    <div id="side_in">
      <div id="themap"> <em>Search jobs by categories</em> </div>
      <ul class="cats">
        <? category_tree(); ?>
      </ul>
    </div>
    <div id="cont_in">
      <div class="pad2">
        <? if(empty($post)): ?>
        <? include(TEMPLATE_DIR.'layouts/blog/list.php'); ?>
        <? else :?>
        <? include(TEMPLATE_DIR.'layouts/blog/read.php'); ?>
        <? endif; ?>
        <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
        <? if(empty($post)): ?>
        <h2>Lates jobs</h2>
        <div class="TheEngine">
          <label>Page</label>
          <? paging(); ?>
          <? include(TEMPLATE_DIR.'layouts/search_jobs/search_box.php'); ?>
        </div>
        <? else :?>
        <h2><? print $post['content_title']; ?></h2>
        <? endif; ?>
      </div>
    </div>
    <!-- /#cont_in -->
    <div class="c">&nbsp;</div>
    <div id="footer">
      <address>
      <a href="#">Conditions of Use</a> | <a href="#">Privacy Notice</a> &copy; 1999-2011, <a href="http://asiaoilworks.com">AsiaOilWorks.com</a>, or its affiliates | Powered by <a href="http://microweber.com" title="Microweber">Microweber</a> | Design by <a href="http://ooyes.net" title="Web Design">OoYes.net</a>
      </address>
    </div>
  </div>
  <div class="TheContentTop">&nbsp;</div>
  <div class="TheContentBottom">&nbsp;</div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
