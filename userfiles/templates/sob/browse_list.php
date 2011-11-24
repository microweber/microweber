<?php dbg(__FILE__); ?>
<div class="main"> 
<?php include "browse_nav.php" ?>
  <!-- /#popular-discussions -->
  <div class="posts">
    <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php // p($the_post); ?>
    <?php include "articles_list_single_post_item.php" ?>
    <?php endforeach; ?>
    <?php include "articles_paging.php" ?>
    <?php else : ?>
    <div class="post">
    <?php $type = $this->core_model->getParamFromURL ( 'type' ); ?>
    
      <p>
    <?php if(($type == 'none') or (!$type)): ?> There are no posts here. Try again later. <?php else: ?> There are no <?php print $type ?> here. Try again later.  <?php endif; ?>
       
       
        </p>
    </div>
    <?php endif; ?>
  </div>
  
 
</div>
<!-- /.main -->
<?php include "browse_side_nav.php" ?>
<?php dbg(__FILE__, 1); ?>
