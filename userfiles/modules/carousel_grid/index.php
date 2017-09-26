<?php print lnotif("Click to add images"); ?>

<?php

  $for = 'content';

  $data = get_pictures('id=' . $params['id']);

  var_dump($data);
  var_dump($params['id']);

  print rand();

?>

<br><br><br><br><br>