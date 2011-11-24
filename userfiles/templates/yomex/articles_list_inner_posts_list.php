 <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php // p($the_post); ?>
    <?php include "articles_list_single_post_item.php" ?>
        
    <?php endforeach; ?>
    <?php include "articles_paging.php" ?>
    <?php else : ?>
    <div class="post">
      <p> Няма намерени резултати. </p>
    </div>
    <?php endif; ?> 