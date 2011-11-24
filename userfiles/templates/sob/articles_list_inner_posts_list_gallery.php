 <?php dbg(__FILE__); ?>
  <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php // p($the_post); ?>
    <?php include "articles_list_single_post_item_gallery.php" ?>

    <?php endforeach; ?>
    <?php include "articles_paging.php" ?>
    <?php else : ?>

      <div class="no-posts">
      <?php $type = $this->core_model->getParamFromURL ( 'action' ); ?>
      <?php if($type == false){
		$type   = $this->core_model->getParamFromURL ( 'type' );
	  }?>


        There is nothing published here <?php if(!empty($author)): ?>by <?php print $author['username']; ?><?php endif; ?> yet.
      </div>

    <?php endif; ?>
	 <?php dbg(__FILE__, 1); ?>