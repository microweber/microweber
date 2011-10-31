<?php
/* Example using AJAX to prettify inline code */

require_once('helper.inc');


luminous::set('line-numbers', false);
luminous::set('include-javascript', false);
luminous::set('format', 'html-inline');

// define a quick and dirty AJAX interface
function do_ajax() {
  global $use_cache;
  $language = $_POST['language'];
  $code = $_POST['code'];
  
  // Arbitrarily sized security check
  if (strlen($code) < 500)
    echo luminous::highlight($language, $code, $use_cache);
  die(0); // we're done now.
}

if (!empty($_POST)) {
  // php is stupid.
  if(get_magic_quotes_gpc()) {
    foreach($_POST as &$p)
      $p = stripslashes($p);
  }
  do_ajax();
} 
?><!DOCTYPE html>
<html>
  <head>
    <title>Inline code highlighting with AJAX example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php 
    echo luminous::head_html();
    ?>
    <script type='text/javascript' src='../client/jquery-1.4.2.min.js'></script>
    <script type='text/javascript'>
    $(document).ready(function() {
      $('pre.code_eg').each(function(i, e) {
        $.post('ajax.php', {code : $(e).text(), language: 'c'},
               function (data) {
                 $new = $(data);
                 $(e).replaceWith($new);
               }
        );
      });
    });
    </script>
    <style type='text/css'>
    /* elegant fallback if JS is disabled */
    pre.code_eg {
      display:inline-block;
      font-weight:bold;
      background-color:#e0e0e0;
      margin-top: 0;
      margin-bottom:0;
      padding-left: 0.5em;
      padding-right:0.5em;
    }
    </style>
    
  </head>
  
  <body>
  <p>
  This example shows how to write some short single-line code snippets inside a paragraph, and then use JavaScript and AJAX to highlight it with Luminous. Viewing this page without JavaScript shows an elegant fallback.
  </p> 
  Lorem ipsum dolor sit amet, <pre class='code_eg'>#include &lt;stdio.h&gt;</pre> consectetur adipiscing elit. Pellentesque <pre class='code_eg'>int main() </pre> orci eros, pellentesque sed elementum eu, mattis nec neque. Vestibulum hendrerit leo vel mi tristique mollis. Mauris magna odio, porta ut fringilla iaculis, <pre class='code_eg'>printf("hello, world!\n");</pre>
  placerat eu urna. Vivamus non nisi nec <pre class='code_eg'>return 0;</pre> ante euismod vehicula. Curabitur nec enim tortor. Proin viverra ligula nec quam pulvinar vehicula. Vivamus turpis diam
  </body>
</html>