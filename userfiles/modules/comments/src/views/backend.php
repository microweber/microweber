<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Comments'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <!--<a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> --><?php //print _e('Templates'); ?><!--</a>-->
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <script type="text/javascript">
                    mw.require('<?php print modules_url() ?>comments/style.css');
                    mw.require('<?php print modules_url() ?>comments/edit_comments.js');
                    mw.require('color.js', true);
                </script>

                <script type="text/javascript">
                    mw.on.hashParam("search", function (pval) {
                        if (pval !== '') {
                            $('#mw_admin_posts_with_comments').attr('data-search-keyword', pval);
                        } else {
                            $('#mw_admin_posts_with_comments').removeAttr('data-search-keyword');
                        }
                        $('#mw_admin_posts_with_comments').removeAttr('content_id');
                        mw.reload_module('#mw_admin_posts_with_comments', function () {
                            mw.$(".mw-ui-searchfield, input[type='search']").removeClass('loading');
                        });
                    });

                    mw.on.hashParam("content_id", function (pval) {
                        mw.$("a.comments-group").removeClass("active");
                        mw.$("a[href*='content_id=" + pval + "']").addClass("active");

                        if (pval == 'settings') {
                            mw.$('.comments-settings').show();
                            mw.$('.comments-items').hide();
                            mw.$('.comments-templates').hide();
                        } else if (pval == 'templates') {
                            mw.$('.comments-settings').hide();
                            mw.$('.comments-items').hide();
                            mw.$('.comments-templates').show();
                        } else {
                            mw.$('.comments-settings').hide();
                            mw.$('.comments-items').show();
                            mw.$('.comments-templates').hide();
                        }

                        if (pval !== '' && pval != '0') {
                            $('#mw_comments_admin_dashboard').hide();
                            $('#mw_admin_posts_with_comments').show();
                            $('#mw_admin_posts_with_comments').attr('content_id', this);
                            selected_content_comment_id = this;
                            mw.reload_module('#mw_admin_posts_with_comments', function () {
                                mw.adminComments.toggleMaster(document.querySelector('.comment-info-holder'));
                                //  mw.$("#comment-item-inner-"+selected_comment_id).addClass("active");
                                mw.$(".comment-holder-comment-rel-id-" + selected_content_comment_id).addClass("active");
                                //  mw.$("#comment-n-"+selected_comment_id).addClass("active");
                            });
                        } else {
                            mw.$('#mw_admin_posts_with_comments').removeAttr('content_id');
                            mw.$('#mw_admin_posts_with_comments').removeAttr('rel_id');
                            $('#mw_admin_posts_with_comments').removeAttr('rel_type');
                            mw.reload_module('#mw_admin_posts_with_comments');
                        }
                    });
                    mw.on.hashParam("rel_id", function (pval) {
                        if (pval !== '' && pval != '0') {
                            mw.$('#mw_comments_admin_dashboard').hide();
                            mw.$('#mw_admin_posts_with_comments').show();
                            mw.$('#mw_admin_posts_with_comments').attr('rel_id', this);
                            mw.reload_module('#mw_admin_posts_with_comments', function () {
                                mw.adminComments.toggleMaster(document.querySelector('.comment-info-holder'));
                            });
                        } else {
                            mw.$('#mw_admin_posts_with_comments').removeAttr('rel_id');
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
                    mw.adminComments = {
                        action: function (form, val) {
                              form = $(form);
                            var field = form.find('.comment_state');
                            var connected_id = mw.$('[name="connected_id"]', form[0]).val();
                            field.val(val);
                            var conf = true;
                            if (val == 'delete') {
                                  conf = confirm(mw.msg.to_delete_comment);
                            }
                            if (conf) {
                                var id = form.attr('id');
                                var data = form.serialize();
                                $.post("<?php print route('api.comment.post'); ?>", data, function (data) {
                                    mw.reload_module('#mw_comments_for_post_' + connected_id, function () {
                                        $('#mw_comments_for_post_' + connected_id).find(".comments-holder,.new-comments,.old-comments").show();
                                    });
                                });
                            }
                        },
                        toggleEdit: function (id) {
                            mw.$(id).toggleClass('comment-edit-mode');
                            if (mw.$(id).hasClass("comment-edit-mode")) {
                                mw.$(id).find("textarea").focus();
                            }
                        },
                        display: function (e, el, what) {
                            mw.event.cancel(e);
                            var _new = mw.tools.firstParentWithClass(el, 'comment-post').querySelector('.new-comments');
                            var _old = mw.tools.firstParentWithClass(el, 'comment-post').querySelector('.old-comments');
                            if (what == 'all') {
                                $(_new).show();
                                $(_old).show();
                            }
                            else if (what == 'new') {
                                $(_new).show();
                                $(_old).hide();
                            }
                        },
                        toggleMaster: function (master, e) {
                            if (master === null) {
                                return false;
                            }
                            if (e != undefined) {
                                mw.event.cancel(e);
                            }
                            var _new = master.parentNode.querySelector('.new-comments');
                            var _old = master.parentNode.querySelector('.old-comments');
                            if ($(_new).is(":visible") || $(_old).is(":visible")) {
                                $([_new, _old]).hide();
                                $(master).removeClass("active");
                            }
                            else {
                                $([_new, _old]).show();
                                $(master).addClass("active");
                                var is_cont = $(master).attr('content-id')
                                if (typeof is_cont != "undefined") {
                                    var mark_as_old = {}
                                    mark_as_old.content_id = is_cont;
                                    $.post('<?php print api_link('mark_comments_as_old'); ?>', mark_as_old, function (data) {

                                    });
                                }
                            }
                        }
                    }
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
                                mw.adminComments.toggleMaster(document.querySelector('.comment-info-holder'));
                            });
                        });
                    </script>
                <?php endif; ?>

                <div class="comments-admin-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4><?php _e("Comments"); ?><br />
                            <small class="text-muted"><?php _e("Read, moderate & publish comments"); ?></small>
                        </h4>

                        <div class="input-group mb-0 prepend-transparent mx-2" style="width: 200px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                            </div>

                            <input type="search" class="form-control form-control-sm" placeholder="<?php _e("Search comments"); ?>" onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});">
                        </div>
                    </div>
                </div>

                <module type="comments/manage" id="mw_admin_posts_with_comments"/>
            </div>

            <div class="tab-pane fade" id="settings">
                <module type="comments/settings" id="mw_admin_comments_settings"/>
            </div>
            <div class="tab-pane fade" id="templates">
                <?php /*<module type="admin/modules/templates"/>
                <module type="admin/templates/browse" for="<?php print $config["the_module"] ?>"  />*/ ?>
            </div>
        </div>
    </div>
</div>
