 
<?php if(!empty($posts_pages_links)): ?>
 

<div class="paging">
  <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
  <span <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?>><a  href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></span>
  <?php $i++; endforeach;  ?>
</div>
<?php endif ; ?>
