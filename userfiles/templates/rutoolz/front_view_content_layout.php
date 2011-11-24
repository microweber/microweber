<?php

/*
type: layout
name: front view
description: front view
*/



?>

<?php include "layouts/header.php" ?>
 <?
 //p($page);
  p($post);
 
 
 ?>
 <? print $post['content_body']; ?>
<?php include "layouts/footer.php" ?>



