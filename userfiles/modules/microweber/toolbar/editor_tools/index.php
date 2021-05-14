<!DOCTYPE HTML>
<html <?php print lang_attributes(); ?>>
  <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta charset="UTF-8">

      <script src="<?php print(mw()->template->get_apijs_combined_url()); ?>"></script>

      <script>
          mw.lib.require('jqueryui');
      </script>

      <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>
      <link type="text/css" rel="stylesheet" media="all" href="<?php print(mw()->template->get_admin_system_ui_css_url()); ?>"/>
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
