<!DOCTYPE HTML>
<html <?php print lang_attributes(); ?>>
  <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta charset="UTF-8">



      <?php print \MicroweberPackages\Admin\Facades\AdminManager::headTags();    ?>

      <script>
          mw.lib.require('jqueryui');
      </script>


      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/components.css"/>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/admin.css"/>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/popup.css"/>

      <script>mw.require('<?php print mw_includes_url(); ?>api/editor_externals.js');</script>

      <script>
        window.RegisterChange = function(){
          if (self !== parent) {
             parent.$(parent.document.getElementsByName(this.name)).trigger('change', arguments);
          }
        }
      </script>
  </head>
  <body class="mw-external-loading">
    {content}
  </body>
</html>
