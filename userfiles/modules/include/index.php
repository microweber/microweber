<?php

    $file =  get_option('file', $params['id']);
?>

<?php

  if($file != '' and file_exists(TEMPLATE_DIR. $file)){
      include TEMPLATE_DIR. $file;
  }

?>



