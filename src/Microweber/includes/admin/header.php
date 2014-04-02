<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>default.css"/>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/mw_framework.css"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >

    <script type="text/javascript">
        if (!window.CanvasRenderingContext2D) {
          var h = "<div id='UnsupportedBrowserMSG'><h1><?php _e("Your a need better browser to run <b>Microweber</b>"); ?></h1></div>"
          + "<div id='download_browsers_holder'><h2><?php _e("Update your browser"); ?></h2><p id='choose_browsers'>"
          +"<a id='u__ie' target='_blank' href='http://windows.microsoft.com/en-us/internet-explorer/download-ie'></a>"
          +"<a id='u__ff' target='_blank' href='http://www.mozilla.org/en-US/firefox/new/'></a>"
          +"<a id='u__chr' target='_blank' href='https://www.google.com/intl/en/chrome/'></a>"
          +"<a id='u__sf' target='_blank' href='http://support.apple.com/kb/DL1531'></a>"
          +"</p></div>";
          document.write(h);
          document.body.id = 'UnsupportedBrowser';
          document.body.className = 'UnsupportedBrowser';
        }
        mwAdmin = true;
        admin_url  = '<?php print admin_url(); ?>';
    </script>
    <script type="text/javascript" src="<?php print MW_INCLUDES_URL; ?>js/jquery-1.9.1.js"></script>
     <script type="text/javascript" src="<?php print site_url(); ?>apijs"></script>
     <script type="text/javascript">
      mw.require("liveadmin.js");
      mw.require("jquery-ui.js");
      mw.require("<?php print MW_INCLUDES_URL; ?>css/wysiwyg.css");
      mw.require("<?php print MW_INCLUDES_URL; ?>css/admin.css");
      mw.require("wysiwyg.js");
      mw.require("tools.js");
      mw.require("url.js");
      mw.require("options.js");
      mw.require("events.js");
      mw.require("admin.js");
      mw.require("editor_externals.js");
      mw.require("keys.js");
      mw.require("css_parser.js");
      mw.require("custom_fields.js");
      mw.require("session.js");
   <?php /*  mw.require("<?php print MW_INCLUDES_URL; ?>css/helpinfo.css");
      mw.require("helpinfo.js");*/ ?>

    </script>
    <?php if(!isset($_REQUEST['no_toolbar'])): ?>
    <script type="text/javascript">
 		$(document).ready(function() {
            if(self === top){
        		window.onhashchange = function() {
        			mw.cookie.set('back_to_admin', window.location.href);
        		}
    		    mw.cookie.set('back_to_admin', window.location.href);
    		}
			
			
		  mw.$("#mw-quick-content,#mw_edit_pages_content,#mw-admin-content").click(function(){
			   if(mw.helpinfo != undefined){
				    mw.cookie.set('helpinfo',false, 4380); 
				   $(".helpinfo_helper").fadeOut();
			     
			   }
		  });  
	 
			
			
			
			
		});
    </script>
    <?php endif; ?>
    </head>
    <body  contextmenu="mw-context-menu" class="is_admin loading view-<?php print mw('url')->param('view')  ?> action-<?php print mw('url')->param('action')  ?>">






<div id="mw-admin-container">
