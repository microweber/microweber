<?
 
$posts_pages_links = $this->template ['posts_pages_links'];
$posts_pages_curent_page = url_param('curent_page'); 


if(intval($posts_pages_curent_page) == 0){
$posts_pages_curent_page  = 1;	
}


//p($posts_pages_curent_page);



?>

<?php if(!empty($posts_pages_links)): ?>
 

<ul class="paging">
  <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>
  <li><a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>"><?php print $i ;  ?></a></li>
  <?php $i++; endforeach;  ?>
</ul>
<?php endif ; ?>
