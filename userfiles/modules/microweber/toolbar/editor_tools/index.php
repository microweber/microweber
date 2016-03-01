<!DOCTYPE HTML>
<html>
  <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta charset="UTF-8">

      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/components.css"/>
       <script>mw.require('<?php print mw_includes_url(); ?>api/editor_externals.js');</script>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/admin.css"/>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/popup.css"/>





      <script>
        window.RegisterChange = function(){
          if(self !== parent){
             parent.$(parent.document.getElementsByName(this.name)).trigger('change', arguments);
          }
        }
      </script>
  </head>
  <body class="mw-external-loading">
    {content}
  </body>
</html>