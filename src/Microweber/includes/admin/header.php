<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>default.css"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script type="text/javascript">
        if (!window.CanvasRenderingContext2D) {
            var h = "<div id='UnsupportedBrowserMSG'><h1><?php _e("Your a need better browser to run <b>Microweber</b>"); ?></h1></div>"
                + "<div id='download_browsers_holder'><h2><?php _e("Update your browser"); ?></h2><p id='choose_browsers'>"
                + "<a id='u__ie' target='_blank' href='http://windows.microsoft.com/en-us/internet-explorer/download-ie'></a>"
                + "<a id='u__ff' target='_blank' href='http://www.mozilla.org/en-US/firefox/new/'></a>"
                + "<a id='u__chr' target='_blank' href='https://www.google.com/intl/en/chrome/'></a>"
                + "<a id='u__sf' target='_blank' href='http://support.apple.com/kb/DL1531'></a>"
                + "</p></div>";
            document.write(h);
            document.body.id = 'UnsupportedBrowser';
            document.body.className = 'UnsupportedBrowser';
        }
        mwAdmin = true;
        admin_url = '<?php print admin_url(); ?>';
    </script>
    <script type="text/javascript" src="<?php print MW_INCLUDES_URL; ?>js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php print site_url(); ?>apijs"></script>
    <script type="text/javascript">
        mw.require("liveadmin.js");
        mw.require("jquery-ui.js");
        mw.require("<?php print MW_INCLUDES_URL; ?>css/wysiwyg.css");
        mw.require("<?php print MW_INCLUDES_URL; ?>css/mw_framework.css");
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
        mw.require("<?php print MW_INCLUDES_URL; ?>api/libs/jquery_slimscroll/jquery.slimscroll.min.js");
        <?php /*  mw.require("<?php print MW_INCLUDES_URL; ?>css/helpinfo.css");
           mw.require("helpinfo.js");*/ ?>

    </script>
    <?php if (!isset($_REQUEST['no_toolbar'])): ?>
        <script type="text/javascript">
            $(document).ready(function () {
                if (self === top) {
                    window.onhashchange = function () {
                        mw.cookie.set('back_to_admin', window.location.href);
                    }
                    mw.cookie.set('back_to_admin', window.location.href);
                }


                mw.$("#mw-quick-content,#mw_edit_pages_content,#mw-admin-content").click(function () {
                    if (mw.helpinfo != undefined) {
                        mw.cookie.set('helpinfo', false, 4380);
                        $(".helpinfo_helper").fadeOut();

                    }
                });


            });


            mw.require("<?php print MW_INCLUDES_URL; ?>css/ui.css");
        </script>
    <?php endif; ?>
    <?php event_trigger('admin_head'); ?>
</head>
<body contextmenu="mw-context-menu"
      class="is_admin loading view-<?php print mw('url')->param('view'); ?> action-<?php print mw('url')->param('action'); ?>">
<?php
$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}
if ($last_page_front != false) {
    $cont_by_url = mw('content')->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = get_content("order_by=updated_on desc&limit=1");
        $past_page = mw('content')->link($past_page[0]['id']);
    } else {
        $past_page = mw('content')->link($last_page_front);
    }
} else {
    $past_page = get_content("order_by=updated_on desc&limit=1");
    $past_page = mw('content')->link($past_page[0]['id']);
}

?>
<div id="mw-admin-container">
    <div class="mw-ui-row main-admin-row">
        <div class="mw-ui-col main-bar-column">
            <div id="main-bar"><!--<a href="javascript:;" id="main-bar-mw-icon"><span class="mw-icon-microweber"></span></a>-->
                <?php $view = url_param('view'); ?>
                <?php $action = url_param('action'); ?>
                <ul id="main-menu">
                    <li id="main-menu-back">
                        <a href="javascript:;"><span class="mw-icon-back"></span></a>
                    </li>
                    <li><a href="<?php print $past_page; ?>?editmode=y" title=""> <span class="mw-icon-live"></span>
                            <strong>Live Edit</strong> </a></li> 
                    <li 
					<?php if ($view == 'content' and $action==false): ?> 
                    class="active" 
					<?php elseif ($view == 'content' and $action!=false): ?> 
                    class="active-parent"  
					<?php endif; ?>
                    ><a
                            href="<?php print admin_url(); ?>view:content" title=""> <span
                                class="mw-icon-website"></span> <strong>Website</strong> </a>
                        <ul>
                            <li  <?php if ($action == 'pages'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:content/action:pages">Pages</a></li>
                            <li <?php if ($action == 'posts'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:content/action:posts">Posts</a></li>
                            <li <?php if ($action == 'categories'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:content/action:categories">Categories</a></li>
                        </ul>
                    </li> 
                    <li <?php if ($view == 'shop' and $action==false): ?> class="active"
                    <?php elseif ($view == 'shop' and $action!=false): ?> class="active-parent"   <?php endif; ?>><a
                            href="<?php print admin_url(); ?>view:shop" title=""> <span class="mw-icon-shop"></span>
                            <strong>My Shop</strong> </a>
                        <ul>
                            <li <?php if ($action == 'orders'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:orders">Orders</a></li>
                            <li <?php if ($action == 'clients'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:clients">Clients</a></li>
                            <li <?php if ($action == 'shipping'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:shipping">Shipping</a></li>
                            <li <?php if ($action == 'options'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:options">Options</a></li>
                        </ul>
                    </li>
                    <li <?php if ($view == 'modules'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:modules" title=""> <span class="mw-icon-module"></span>
                            <strong>Modules</strong> </a></li>
                    <li <?php if ($view == 'settings'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:settings" title=""> <span class="mw-icon-gear"></span>
                            <strong>Settings</strong> </a></li>
                    <li id="main-menu-toggle">
                        <a href="javascript:;"><span class="mw-icon-menu"></span></a>
                    </li>
                </ul>
            </div>
        </div>
