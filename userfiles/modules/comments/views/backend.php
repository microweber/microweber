<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require('<?php print $config['url_to_module'] ?>style.css', true);
    mw.require('<?php print $config['url_to_module'] ?>comments_admin.js');
    mw.require('color.js', true);
</script>
<script type="text/javascript">
    mw.on.hashParam("search", function () {
        if (this !== '') {
            $('#mw_admin_posts_with_comments').attr('data-search-keyword', this);
        } else {
            $('#mw_admin_posts_with_comments').removeAttr('data-search-keyword');
        }
        $('#mw_admin_posts_with_comments').removeAttr('content_id');
        mw.reload_module('#mw_admin_posts_with_comments', function () {
            mw.$(".mw-ui-searchfield, input[type='search']").removeClass('loading');
        });
    });
    mw.on.hashParam("content_id", function () {
        mw.$("a.comments-group").removeClass("active");
        mw.$("a[href*='content_id=" + this + "']").addClass("active");
        if (this == 'settings') {
            $('.comments-settings').show();
            $('.comments-items').hide();
            $('.comments-templates').hide();
        } else if (this == 'templates') {
            $('.comments-settings').hide();
            $('.comments-items').hide();
            $('.comments-templates').show();
        } else {
            $('.comments-settings').hide();
            $('.comments-items').show();
            $('.comments-templates').hide();
        }
        if (this !== '' && this != '0') {
            $('#mw_comments_admin_dashboard').hide();
            $('#mw_admin_posts_with_comments').show();
            $('#mw_admin_posts_with_comments').attr('content_id', this);
            mw.reload_module('#mw_admin_posts_with_comments', function () {
                mw.adminComments.toggleMaster(mwd.querySelector('.comment-info-holder'));
            });
        } else {
            mw.$('#mw_admin_posts_with_comments').removeAttr('content_id');
            mw.$('#mw_admin_posts_with_comments').removeAttr('rel_id');
            $('#mw_admin_posts_with_comments').removeAttr('rel_type');
            mw.reload_module('#mw_admin_posts_with_comments');
        }
    });

    mw.on.hashParam("rel_id", function () {
        if (this !== '' && this != '0') {
            $('#mw_comments_admin_dashboard').hide();
            $('#mw_admin_posts_with_comments').show();
            $('#mw_admin_posts_with_comments').attr('rel_id', this);
            mw.reload_module('#mw_admin_posts_with_comments', function () {
                mw.adminComments.toggleMaster(mwd.querySelector('.comment-info-holder'));
            });
        } else {
            $('#mw_admin_posts_with_comments').removeAttr('rel_id');
            mw.reload_module('#mw_admin_posts_with_comments');
        }
    });

    mw.on.hashParam("comments_for_content", function () {

    });

</script>
<script type="text/javascript">
    mw.require("forms.js", true);
</script>
<script type="text/javascript">

</script>
<?php $mw_notif = (url_param('mw_notif'));
if ($mw_notif != false) {
    $mw_notif = mw()->notifications_manager->read($mw_notif);
}
mw()->notifications_manager->mark_as_read('comments');
?>
<?php if (is_array($mw_notif) and isset($mw_notif['rel_id'])): ?>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#mw_admin_posts_with_comments').attr('rel_id', "<?php print $mw_notif['rel_id'] ?>");
            $('#mw_admin_posts_with_comments').attr('rel_type', "<?php print $mw_notif['rel_type'] ?>");
            mw.reload_module('#mw_admin_posts_with_comments', function () {
                mw.adminComments.toggleMaster(mwd.querySelector('.comment-info-holder'));
            });
        });
    </script>
<?php endif; ?>
<div id="mw_edit_pages_content">
    <div id="mw_edit_page_left" class="mw_edit_page_default">
        <?php $info = module_info($config['module']); ?>
        <div class="mw-admin-sidebar">
            <?php mw()->modules->icon_with_title($info['module']); ?>
            <a class="mw-ui-btn comments-group mw-ui-btn-hover active" href="#content_id=0">
                <?php _e("My Comments"); ?>
            </a> <a class="mw-ui-btn comments-group mw-ui-btn-hover mw-ui-btn-hover-blue" href="#content_id=settings">
                <?php _e("Settings"); ?>
            </a>
            <h2><?php _e('Templates'); ?></h2>
            <a href="#content_id=templates" class="comments-group mw-ui-btn mw-ui-btn-hover">
                <?php _e("My templates"); ?>
            </a>

            <a href="javascript:Alert('Coming soon');" class="mw-ui-btn mw-ui-btn-green">
                <?php _e("Get more templates"); ?>
            </a>
        </div>
    </div>
    <div class="mw_edit_page_right" style="padding: 20px;width: 710px;">
        <div class="comments-tabs mw_simple_tabs mw_tabs_layout_stylish active">
            <div class="comments-tab comments-items" id="the_comments">
                <div id="comments-admin-side">
                    <div class="comments-admin-header">
                        <div class="comments-admin-header-info">
                            <h2>
                                <?php _e("My Comments"); ?>
                            </h2>
                            <small>
                                <?php _e("Read, moderate & publish comments"); ?>
                            </small>
                        </div>
                        <input
                                autocomplete="off"
                                type="search"
                                class="mw-ui-searchfield"
                                placeholder="<?php _e("Search comments"); ?>"
                                onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"
                        />
                    </div>
                    <module type="comments/manage" id="mw_admin_posts_with_comments"/>
                </div>
                <?php /*

          <div class="<?php print $config['module_class'] ?> mw_comments_admin_dashboard" id="mw_comments_admin_dashboard">
            <div class="new-comments"><module type="comments/manage" is_moderated="n" /></div>
            <div class="old-comments"><module type="comments/manage"  is_moderated="y" /></div>
          </div>
          <div class="<?php print $config['module_class'] ?> mw_comments_admin_for_post" id="mw_admin_posts_with_comments_edit"> </div>

          */ ?>
            </div>
            <div class="comments-tab comments-settings" style="display: none">
                <module type="comments/settings" id="mw_admin_comments_settings"/>
            </div>
            <div class="comments-tab comments-templates" style="display: none">
                <div class="comments-admin-header">
                    <div class="comments-admin-header-info">
                        <module type="admin/templates/browse" for="<?php print $config["the_module"] ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
