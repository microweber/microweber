<!DOCTYPE HTML>
<html>
  <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>default.css"/>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/components.css"/>
       <script>mw.require('<?php print MW_INCLUDES_URL; ?>api/editor_externals.js');</script>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/admin.css"/>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/popup.css"/>





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