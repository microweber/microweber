<? if(empty($posts_pages_links)): ?>
<? if(!empty($posts_data)): ?>
<? $posts_pages_links = $posts_data['posts_pages_links'];   ?>
<? $posts_pages_curent_page = $posts_data['posts_pages_curent_page'];   ?>
<? endif ; ?>
<? endif ; ?>




<? if(!empty($posts_pages_links)): ?>

<? // print $page_link ;  ?>
<ul class="paging">
  <li class='isQuo'><a href='#' title="Previous" class='nextprev'>Prev</a></li>
  <li>
      <ul class="paging-content">
        <? $i = 1; foreach($posts_pages_links as $page_link) : ?>
        <li><a <? if($posts_pages_curent_page == $i) : ?>  class="active"  <?  endif; ?> href="<? print $page_link ;  ?>"><? print $i ;  ?></a></li>
        <? $i++; endforeach;  ?>
      </ul>
  </li>
  <li class='isQuo'><a href='#' title="Next" class='nextprev'>Next</a></li>
</ul>

<? endif ; ?>
<?php



?>