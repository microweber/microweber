<!DOCTYPE HTML>
<html>
  <head>
  <script type="text/javascript">

    window.onerror = function(err, file, row){alert(err + "\nFile: " + file + "\nRow: " + row)}



    window.mw = window.mw ? window.mw : {};

    mw.settings = {
        site_url:'<?php print site_url(); ?>', //mw.settings.site_url
        includes_url: '<?php   print( INCLUDES_URL);  ?>',
 
    }

</script>
      <script type="text/javascript" src="<? print INCLUDES_URL; ?>js/jquery-latest.js"></script>
      <script type="text/javascript" src="<? print site_url(); ?>api.js"></script>
      <script type="text/javascript" src="<? print INCLUDES_URL; ?>api/editor_externals.js"></script>
      <link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/mw_framework.css"/>
  </head>
  <body>