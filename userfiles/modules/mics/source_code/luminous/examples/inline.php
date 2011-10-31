<?php
include 'helper.inc';
luminous::set('format', 'html-inline');
luminous::set('include-javascript', false);
?><!DOCTYPE HTML>

<!DOCTYPE html>
<html>
  <head>
    <title>Inline code highlighting with AJAX example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php 
    echo luminous::head_html();
    ?>
  </head>
  
  <body>
  Lorem ipsum dolor sit amet, <?php echo luminous::highlight('c', '#include <stdio.h>') ;?> consectetur adipiscing elit. Pellentesque <?php echo luminous::highlight('c', 'int main()');?> orci eros, pellentesque sed elementum eu, mattis nec neque. Vestibulum hendrerit leo vel mi tristique mollis. Mauris magna odio, porta ut fringilla iaculis, <?php echo luminous::highlight('c', 'printf("hello, world!\n");');?>
  placerat eu urna. Vivamus non nisi nec <?php echo luminous::highlight('c', 'return 0;');?> ante euismod vehicula. Curabitur nec enim tortor. Proin viverra ligula nec quam pulvinar vehicula. Vivamus turpis diam
  </body>
</html>