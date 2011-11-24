 <?php dbg(__FILE__); ?>
  <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php // p($the_post); ?>
    <?php include "articles_list_single_post_item_products.php" ?>

    <?php endforeach; ?>
    <?php include "articles_paging.php" ?>
    <?php else : ?>

      <div class="no-posts">
      <?php $type = $this->core_model->getParamFromURL ( 'action' ); ?>
      <?php if($type == false){
		$type   = $this->core_model->getParamFromURL ( 'type' );
	  }?>


        There are no <?php echo $type ?> <?php if(!empty($author)): ?>from <?php print $author['username']; ?><?php endif; ?>
      </div>

    <?php endif; ?>
	 <?php dbg(__FILE__, 1); ?>