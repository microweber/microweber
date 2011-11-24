<?php

/*

type: layout

name: Games layout

description: Games site layout









*/



?>
<?php include "header.php" ?>
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
    <?php include "footer.php" ?>