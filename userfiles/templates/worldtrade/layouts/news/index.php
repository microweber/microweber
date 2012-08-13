<?php

/*

type: layout

name: news layout

description: news site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 
 
 
?>

<div id="middle">
  
  <? if(!empty($post)) : ?>
  <? include   "inner.php"; ?>
  <? else : ?>
  <h1 class="title_product pink_color font_size_18"><? print $page['content_title'] ?></h1>
  <br/>
  <? foreach($posts  as $item) :?>
  <div class="rounded_box">
    <div class="news_small_box"> <a href="<? print post_link($item['id']) ?>"><img border="0" src="<? print thumbnail($item['id'], 120) ?>" alt="" class="left"/></a>
      <h3 class="right font_size_18 pink_color"><a href="<? print post_link($item['id']) ?>"><? print $item['content_title'] ?></a><br />
        <small>публикувана на <? print $item['created_on'] ?></small></h3>
      <p class="right text"><? print $item['content_description'] ?> <span class="clener"></span> <a href="<? print post_link($item['id']) ?>" class="rounded white_btn right"> <span class="in1"> <span class="in2 pink_color">Прочети повече</span> </span> </a> </p>
      <div class="clener"></div>
    </div>
    <div class="lt"></div>
    <div class="rt"></div>
    <div class="lb"></div>
    <div class="rb"></div>
  </div>
  <? endforeach; ?>
  
  
    <div class="paging left padding_L8">
    <br />
  <br />
  <microweber module="content/paging">
  <br />
    <div class="clener h10"></div>
  </div>
  <? endif; ?>

<br />
<br />


  <a href="https://www.facebook.com/pages/World-trade/83385238436" target="_blank" class="right"> <img src="<? print TEMPLATE_URL ?>images/other/news/facebook.jpg" alt="" /> </a>
  <div class="clener"></div>
</div>
<div class="all_width"></div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
