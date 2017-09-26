<?php print lnotif("Click to add images"); ?>

<?php


  $data = get_pictures('rel_type=modules&rel_id=' . $params['id']);

  var_dump($data);
  var_dump($params['id']);

  print rand();

?>

<br><br><br><br><br>