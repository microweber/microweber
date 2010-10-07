<?php if(empty($posts_pages_links)): ?>
<?php if(!empty($posts_data)): ?>
<?php $posts_pages_links = $posts_data['posts_pages_links'];   ?>
<?php $posts_pages_curent_page = $posts_data['posts_pages_curent_page'];   ?>
<?php endif ; ?>
<?php endif ; ?>

<?php if(!empty($posts_pages_links)):
if($posts_pages_curent_page == false){
	$posts_pages_curent_page = $this->core_model->getParamFromURL ( 'curent_page' );
}

if($posts_pages_curent_page == false){
	$posts_pages_curent_page = 1;
}

 ?>

<?php // print $page_link ;  ?>
<ul class="paging">
<li><span class="paging-label">Browse pages:</span></li>
<li class='isQuo'><a href='#' title="First page" class='quo laquo2'><span>&nbsp;</span></a></li>
<li class='isQuo'><a href='#' title="Previous page" class='quo laquo'><span>&nbsp;</span></a></li>
<li>
    <ul class="paging-content">
      <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
      <li><a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></li>
      <?php $i++; endforeach;  ?>
    </ul>
</li>
<li class='isQuo'><a href='#' title="Next page" class='quo raquo'><span>&nbsp;</span></a></li>
<li class='isQuo'><a href='#' title="Last page" class='quo raquo2'><span>&nbsp;</span></a></li>
</ul>

<?php endif ; ?>
