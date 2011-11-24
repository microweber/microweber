<?php

/*

type: layout

name: Articles layout

description: Articles site layout









*/



?>

<?php include "header.php" ?>

      <?php if(!empty($post)) : ?>

      <?php include "articles_read.php" ?>

      <?php else : ?>

      <?php include "articles_list.php" ?>

      <?php endif; ?>

      <?php include "footer.php" ?>

