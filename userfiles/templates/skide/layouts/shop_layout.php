<?php

/*

type: layout

name: shop layout

description: shop site layout

content_type: shop







*/



?>
<?php include  TEMPLATE_DIR."header.php" ?>
    <div class="wrap">
      <div id="main_content">
        <?php include TEMPLATE_DIR. "games_sidebar.php" ?>
        <?php if(!empty($post)) : ?>
        <?php else : ?>
        <?php print ($page['content_body']); ?>
        <?php endif; ?>
        <?php if(!empty($post)) : ?>
        <?php include TEMPLATE_DIR. "games_read.php" ?>
        <?php else : ?>
        <?php include TEMPLATE_DIR. "games_list.php" ?>
        <?php endif; ?>
      </div>
    </div>
    <?php include  TEMPLATE_DIR."footer.php" ?>