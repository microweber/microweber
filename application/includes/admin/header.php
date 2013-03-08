<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>default.css"/>
    <link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/mw_framework.css"/>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

   <!-- <link rel="prefetch prerender" href="<?php print admin_url(); ?>view:dashboard" />
    <link rel="prefetch prerender" href="<?php print admin_url(); ?>view:content" />
    <link rel="prefetch prerender" href="<?php print admin_url(); ?>view:shop" />
    <link rel="prefetch prerender" href="<?php print admin_url(); ?>view:settings" />-->






    <script type="text/javascript">
        mwAdmin = true;
        admin_url  = '<?php print admin_url(); ?>';
    </script>
    <script type="text/javascript" src="<? print site_url(); ?>apijs"></script>
    <script type="text/javascript">

      mw.require("<? print INCLUDES_URL; ?>js/jquery-1.9.1.js");
      mw.require("liveadmin.js");

      mw.require("jquery-ui.js");

      mw.require("<? print INCLUDES_URL; ?>css/wysiwyg.css");
      mw.require("<? print INCLUDES_URL; ?>css/admin.css");
      mw.require("wysiwyg.js");
      mw.require("tools.js");
      mw.require("url.js");
      mw.require("options.js");
      mw.require("events.js");
      mw.require("admin.js");

      mw.require("<? print INCLUDES_URL; ?>api/editor_externals.js");
      mw.require("keys.js");


      mw.require("css_parser.js");
      mw.require("custom_fields.js");



      //Experiments !!!!!!!!!!!!!!!!!!!!!
      mw.require("lab.js");






    </script>



</head>
<body  contextmenu="mw-context-menu" class="is_admin loading view-<?php print url_param('view')  ?> action-<?php print url_param('action')  ?>"> <?php include MW_ROOTPATH. "UI.php"; ?>
<div id="mw-admin-container">


