<?php
/*
type: layout
name: Shop layout
description: Shop site layout




*/

?>
<?php $no_class_richtext_in_content = true; ?>
<?php include "header.php" ?>
      <?php if(!empty($post)) : ?>
      <?php include "shop_items_view.php" ?>
      <?php else : ?>
      <?php include "shop_items_list.php" ?>
      <?php endif; ?>
      <?php include "footer.php" ?>
