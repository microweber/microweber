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
<?php endif ; ?>
<?php if(!empty($posts_pages_links)): ?>


    <div class="pagining nojs">
 
       
        



<a class="seeall right" title="#">следващи</a> <a class="seeall left" title="#">предишни</a>

<span class="pnojs">

      <?php $i = 1; foreach($posts_pages_links as $page_link) : ?>

  <a <?php if($posts_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?> href="<?php print $page_link ;  ?>" ><?php print $i ;  ?></a>

      <?php $i++; endforeach;  ?>

</span>

   <div class="c"></div>
</div>

<?php endif ; ?>