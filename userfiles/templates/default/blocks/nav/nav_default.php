<?php if(!empty($posts_pages_links)): ?>
<?php print $page_link ;  ?>

<ul class="paging">
  <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
  <li><a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></li>
  <?php $i++; endforeach;  ?>
</ul>
<?php endif ; ?>
