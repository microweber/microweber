<?php

/*

type: layout

name: Big posts list

description: Posts list

*/
?>

<div class="post-lists">
  <? if (!empty($data)): ?>
  <? foreach ($data as $item): ?>
  <article class="latest-article">
    <div class="article-info">
      <div class="author-info"> <span 
									data-original-title="<? print $item['title'] ?>"
									data-image="img/assets/avatar/avatar-1.png"
									data-author-desc="Ut dignissim aliquet nibh tristique. Donec ullamcorper nulla quis Praesent a tellus vitae nisl vehicula semper." 
									class="ent-pencil"> </span> </div>
      <div class="time"> <span 
									class="ent-calendar" 
									data-original-title="Posted On" 
									data-time="11:00 PM" data-date='{"day":"11", "month":"December", "year":"2012"}'> </span> </div>
      <div class="comment"> <span class="ent-comment" 
									data-original-title="Comments 122"
									data-comment-latest='{"author":"Author Name","authorurl":"http://google.com", "comment":"Ut dignissim aliquet nibh tristique. Donec ullamcorper nulla quis Praesent a tellus vitae nisl vehicula semper.","avatar":"img/assets/avatar/avatar-3.png"}' > </span> </div>
    </div>
    <? if(isset($item['image'])): ?>
    <div class="article-thumbnail"> <a href="<? print $item['link'] ?>" title="Thumbnail"><img src="<? print $item['image'] ?>" alt="Image"/></a> </div>
    <? endif; ?>
    <div class="article-content">
      <div class="article-header">
        <h3><a href="<? print $item['link'] ?>" title="Title"><? print $item['title'] ?></a></h3>
      </div>
      <div class="article-excerpt">
        <p><? print $item['description'] ?> <a href="<? print $item['link'] ?>" title="read more">continue reading &rarr;</a> </p>
      </div>
    </div>
  </article>
  <div class="separator"></div>
  <? endforeach; ?>
  <? endif; ?>
  <? if (!empty($paging_links)): ?>
  <div class="paging">
    <? foreach ($paging_links as $k => $v): ?>
    <span class="paging-item" data-page-number="<? print $k; ?>" ><a  data-page-number="<? print $k; ?>" data-paging-param="<? print $paging_param; ?>" href="<? print $v; ?>"  class="paging-link"><? print $k; ?></a></span>
    <? endforeach; ?>
  </div>
  <? endif; ?>
</div>
