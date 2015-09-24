<!DOCTYPE html>
<html>
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex">
        <script type="text/javascript">
        if (!window.CanvasRenderingContext2D) {
            var h = "<div id='UnsupportedBrowserMSG'><h1><?php _e("Your a need better browser to run Microweber>"); ?></h1></div>"
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
        <script type="text/javascript">
	    mw.require("<?php print mw_includes_url(); ?>api/libs/jquery_slimscroll/jquery.slimscroll.min.js");

        mw.require("liveadmin.js");
        mw.require("jquery-ui.js");
        mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
        mw.require("<?php print mw_includes_url(); ?>css/components.css");
        mw.require("<?php print mw_includes_url(); ?>css/admin.css");
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
        mw.require("content.js");
		mw.require("upgrades.js");

        <?php /*  mw.require("<?php print mw_includes_url(); ?>css/helpinfo.css");
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
            mw.require("<?php print mw_includes_url(); ?>css/ui.css");

            $(window).load(function () {
                if($(".bootstrap3ns").size() > 0){
                    mw.lib.require("bootstrap3ns");
                }
            });



        </script>
        <?php endif; ?>
        <?php event_trigger('admin_head'); ?>
        </head>
        <body  class="is_admin loading view-<?php print mw()->url_manager->param('view'); ?> action-<?php print mw()->url_manager->param('action'); ?>">
<?php
$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}
if ($last_page_front != false) {
    $cont_by_url = mw()->content_manager->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
        $past_page = mw()->content_manager->link($last_page_front);
    }
}
else {
    $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
    if(isset($past_page[0])){
        $past_page = mw()->content_manager->link($past_page[0]['id']);

    } else {
        $past_page = site_url();
    }
}



$shop_disabled = get_option('shop_disabled', 'website') == 'y';
  
 
?>
<?php /*<div id="admin-user-nav">


<a href="javascript:;" class="mw-icon-off pull-right"></a>
<a href="<?php print $past_page; ?>?editmode=y" class="mw-ui-btn mw-ui-btn-invert pull-right"><span class="mw-icon-live"></span><?php _e("Live Edit"); ?></a>

</div>*/ ?>
<?php if(is_admin()): ?>
<div id="mw-admin-container">
<div class="mw-ui-row main-admin-row">
<div class="mw-ui-col main-bar-column">
          <div id="main-bar" class="scroll-height-exception-master">
    <?php $view = url_param('view'); ?>
    <?php $action = url_param('action'); ?>
    <a href="<?php print admin_url(); ?>" id="main-bar-mw-icon" class="scroll-height-exception <?php if($view == 'dashboard' or (url_current() == admin_url()) or url_current() == rtrim(admin_url(), '/')){ print 'active'; } ?>">
            <?php if(mw()->ui->admin_logo != false) : ?>
            <img src="<?php print mw()->ui->admin_logo ?>" style="max-width:36px;" />
            <?php else: ?>
            <span class="mw-icon-mw"></span>
            <?php endif;  ?>
            <strong>
    <?php _e("Dashboard"); ?>
    </strong> </a>
    <ul id="mw-admin-main-menu">
              <li id="mw-admin-main-menu-back"> <a href="javascript:;"><span class="mw-icon-back"></span></a> </li>
              <li
					<?php if ($view == 'content' and $action==false): ?>
                    class="active"
					<?php elseif ($view == 'content' and $action!=false): ?>
                    class="active-parent"
					<?php endif; ?>
                    >
              <a href="<?php print admin_url(); ?>view:content" title=""> <span class="mw-icon-website"></span> <strong>
              <?php _e("Website"); ?>
              </strong> </a>
              <ul>
        <li  <?php if ($action == 'pages'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:content/action:pages">
          <?php _e("Pages"); ?>
          </a></li>
        <li <?php if ($action == 'posts'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:content/action:posts">
          <?php _e("Posts"); ?>
          </a></li>
        <?php if($shop_disabled == false): ?>
        <li <?php if ($action == 'products'): ?> class="active" <?php endif; ?>><a
                                                                        href="<?php print admin_url(); ?>view:content/action:products">
          <?php _e("Products"); ?>
          </a></li>
        <?php endif; ?>
        <li <?php if ($action == 'categories'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:content/action:categories">
          <?php _e("Categories"); ?>
          </a></li>
      </ul>
              </li>
              <?php if($shop_disabled == false): ?>
              <li <?php if ($view == 'shop' and $action==false): ?> class="active"
                    <?php elseif ($view == 'shop' and $action!=false): ?> class="active-parent" <?php endif; ?>>
              <a href="<?php print admin_url(); ?>view:shop" title=""> <span class="mw-icon-shop">
              <?php
                            	$notif_html = '';
                            	$notif_count = mw()->notifications_manager->get('module=shop&rel_type=cart_orders&is_read=0&count=1');
								
								 
                             	if( $notif_count > 0){
                                $notif_html = '<sup class="mw-notification-count">'.$notif_count.'</sup>';
                                if($view != 'shop'){   print $notif_html; } ;
                                ?>
              <?php }  ?>
              </span> <strong>
              <?php _e("My Shop"); ?>
              </strong> </a>
              <ul>
        <li <?php if ($action == 'orders'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:orders"><span class="relative">
          <?php _e("Orders"); ?>
          <?php if( $view =='shop' ){ print $notif_html; } ?>
          </span></a></li>
        <li <?php if ($action == 'clients'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:clients">
          <?php _e("Clients"); ?>
          </a></li>
        <li <?php if ($action == 'shipping'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:shipping">
          <?php _e("Shipping"); ?>
          </a></li>
        <li <?php if ($action == 'options'): ?> class="active" <?php endif; ?>><a
                                    href="<?php print admin_url(); ?>view:shop/action:options">
          <?php _e("Options"); ?>
          </a></li>
      </ul>
              </li>
              <?php endif; ?>
              <li id="mw-admin-main-menu-bottom">
        <ul>
                  <li class="user-menu-sub"> <a href="<?php print $past_page  ?>?editmode=y" class="go-live-edit-href-set" target="_blank"> <span class="mw-icon-live" style="font-size: 24px;"></span> <strong>
                  <?php _e("Live Edit"); ?>
                  </strong> </a> </li>
                  
                  
                  <?php if(mw()->ui->disable_marketplace != true): ?>
              
             
                  <li <?php if ($view == 'marketplace'): ?> class="active" <?php endif; ?>> <a href="<?php print admin_url(); ?>view:marketplace"> <span class="mw-icon-market-rocket" style="font-size: 24px;"></span> <strong>
                  <?php _e("Marketplace"); ?>
                  </strong> </a> </li>
                   <?php endif; ?>
                  
                  
                  
                  <li <?php if ($view == 'settings' or $view == 'modules'): ?> class="active" <?php endif; ?>> <a href="<?php print admin_url(); ?>view:settings"> <span class="mw-icon-gear " style="font-size: 24px;"></span> <strong>
                  <?php _e("Settings"); ?>
                  </strong> </a> </li>
                  <li id="mw-admin-main-menu-toggle"> <a href="javascript:;"><span class="mw-icon-menu"></span></a> </li>
                </ul>
      </li>
            </ul>
    <?php 
$past_page  = site_url().'?editmode=y';
$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}

if ($last_page_front != false) {
    $cont_by_url = mw()->content_manager->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
        $past_page = mw()->content_manager->link($last_page_front);
    }
} else {
    $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
	if(isset($past_page[0])){
    $past_page = mw()->content_manager->link($past_page[0]['id']);
	}
}




 ?>
    <script>
        $(function () {

           $( '.go-live-edit-href-set' ).bind('mousedown',function() {
			   var url_to_go =  $(this).attr('href');;
  					
					var n = url_to_go.indexOf("editmode");
					if(n == -1){
						url_to_go = url_to_go+'?editmode:y';
					}
 				//window.location.href=url_to_go;
				 $(this).attr('href' , url_to_go);;
  				 return false;
				 
			}); 
           


        });
    </script>
    <div id="user-menu" class="scroll-height-exception">
              <?php $user_id = user_id(); $user = get_user_by_id($user_id);

                            if(!empty($user)){
                              $img = user_picture($user_id);
                              if($img != ''){  ?>
              <a href="javascript:;" id="main-bar-user-menu-link" class="main-bar-user-menu-link-has-image"><span id="main-bar-profile-img" style="background-image: url('<?php print $img; ?>');"></span><span class="mw-icon-dropdown"></span></a>
              <?php } else { ?>
              <a href="javascript:;" id="main-bar-user-menu-link" class="main-bar-user-menu-link-no-image"><span class="mw-icon-user" id="main-bar-profile-icon"></span><span class="mw-icon-dropdown"></span></a>
              <?php } }  ?>
              <div id="main-bar-user-tip" style="display: none">
        <div class="mw-ui-btn-vertical-nav main-bar-user-tip-navigation"> <a href="<?php print admin_url('view:modules/load_module:users#edit-user=' . $user_id); ?>" class="mw-ui-btn">
          <?php _e("My Profile"); ?>
          </a> <a href="<?php print admin_url('view:modules/load_module:users'); ?>" class="mw-ui-btn">
          <?php _e("Manage Users"); ?>

          </a>

          <?php if(mw()->ui->enable_service_links): ?>
          <a href="javascript:;" onmousedown="mw.contactForm();" class="mw-ui-btn">
          <?php _e("Support"); ?>
          </a>
          <?php endif; ?>

            <a href="<?php print site_url(); ?>?editmode=y" class="mw-ui-btn go-live-edit-href-set">
          <?php _e("View Website"); ?>
          </a> <a href="<?php print api_url('logout'); ?>" class="mw-ui-btn">
          <?php _e("Log out"); ?>
          </a> </div>
      </div>
            </div>
  </div>
        </div>
<?php endif; ?>
