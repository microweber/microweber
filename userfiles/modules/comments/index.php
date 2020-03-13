<?php

if (!isset($params['id'])) {
    return;
}

require_once(dirname(__FILE__) . DS . 'functions.php');
if (get_option('enable_comments', 'comments') == 'y') {
    $engine = get_option('engine', 'comments');
    if ($engine == 'disqus') {
        return include(dirname(__FILE__) . DS . 'engines/disqus.php');
    } elseif ($engine == 'facebook') {
        return include(dirname(__FILE__) . DS . 'engines/facebook.php');
    }

    $login_required = get_option('user_must_be_logged', 'comments') == 'y';
    $from_related_posts = false;
    $paging_param = $params['id'] . '_page';
    $current_page_from_url = url_param($paging_param);

    $disable_captcha = get_option('disable_captcha', 'comments') == 'y';
    if (!$disable_captcha) {
        $disable_captcha = get_option('disable_captcha', $params['id']) == 'y';
    }


    $data = $params;
    if (isset($params['content-id'])) {
        $data['rel_type'] = 'content';
        $data['rel_id'] = $params['content-id'];
    } elseif (isset($params['content_id'])) {
        $data['rel_type'] = 'content';
        $data['rel_id'] = $params['content_id'];
    }
    if (!isset($params['rel_type'])) {

        $data['rel_type'] = 'content';
    }

    if (!isset($data['rel_id']) and isset($params['content-id'])) {
        $data['rel_id'] = intval($params['content-id']);
        $data['rel_type'] = 'content';
    }
    if (isset($data['rel_type'])) {
        $data['rel'] = $data['rel_type'];
    }


    $are_disabled = get_option('disable_new_comments', $params['id']) == 'y';


    $display_comments_from_which_post = get_option('display_comments_from_which_post', $params['id']);
    if ($display_comments_from_which_post == 'current_post' and isset($data['rel_id'])) {

        unset($data['rel_id']);

    }
    if (isset($data['no-form'])) {
        $are_disabled = true;
    }
    if (!isset($data['rel_id']) or $data['rel_id'] == false) {


        if (defined('POST_ID') == true and intval(POST_ID) != 0) {
            $data['rel_id'] = POST_ID;
        }
    }
    if (!isset($data['rel_id'])) {
        if (defined('PAGE_ID') == true) {
            $data['rel_id'] = PAGE_ID;
        }
    }
    if (!isset($data['rel_id'])) {

        $data['rel_id'] = $params['id'];

    }

    if (isset($data['from-related-posts'])) {

        $from_related_posts = true;

    }


    $display_comments_from = get_option('display_comments_from', $params['id']);


    $enable_comments_paging = get_option('enable_comments_paging', $params['id']) == 'y';
    $global_per_page = get_option('paging', 'comments');
    $global_set_paging = get_option('set_paging', 'comments') == 'y';


    $hide_comment_form = false;
    $comments_data = array();
    $comments_data['rel_id'] = $data['rel_id'];
    $comments_data['rel_type'] = $data['rel_type'];


    if (isset($params['order']) and trim(strtolower($params['order'])) == 'reverse') {
        $comments_data['order_by'] = 'created_at desc';
    } elseif (isset($params['order'])) {
        $comments_data['order_by'] = $params['order'];

    } elseif (isset($data['order'])) {
        $comments_data['order_by'] = $data['order'];

    }


    if ($display_comments_from != false and $display_comments_from == 'current' and $display_comments_from_which_post != false and $display_comments_from_which_post != 'current_post') {
        $comments_data['rel_id'] = $data['rel_id'] = $display_comments_from_which_post;
        $comments_data['rel_type'] = $data['rel_type'] = 'content';
    }


    $form_title = false;
    $display_form_title_from_module_id = get_option('form_title', $params['id']);
    if ($display_form_title_from_module_id != false and trim($display_form_title_from_module_id) != '') {
        $form_title = $display_form_title_from_module_id;
    }

    if ($display_comments_from != false and $display_comments_from == 'recent') {
        $hide_comment_form = true;
        $comments_data = array();
        $comments_data['order_by'] = "created_at desc";
    }

    if ($display_comments_from != false and $display_comments_from == 'module') {
        $comments_data['rel_id'] = $data['rel_id'] = $params['id'];
        $comments_data['rel_type'] = $data['rel_type'] = 'modules';
        $display_comments_from_module_id = get_option('module_id', $params['id']);
        if ($display_comments_from_module_id != false and trim($display_comments_from_module_id) != '') {
            $comments_data['rel_id'] = $data['rel_id'] = $display_comments_from_module_id;
            $display_form_title_from_module_id = get_option('form_title', $display_comments_from_module_id);
            if ($display_form_title_from_module_id != false and trim($display_form_title_from_module_id) != '') {
                $form_title = $display_form_title_from_module_id;
            }
        }
    }

    $paging = false;
    $comments_per_page = get_option('comments_per_page', $params['id']);

    $disabled_comments_paging = get_option('enable_comments_paging', $params['id']) == 'n';

    if ($enable_comments_paging == false and $global_set_paging != false and $disabled_comments_paging == false) {
        if (intval($global_per_page) > 0) {
            $enable_comments_paging = 1;
            $comments_per_page = $global_per_page;
        }
    }

    if ($display_comments_from == false and $from_related_posts != false) {

    }

    if ($enable_comments_paging != false) {
        if (intval($comments_per_page) != 0) {
            $comments_data['limit'] = $comments_per_page;
            $paging_data = $comments_data;
            $paging_data['page_count'] = true;
            $paging = get_comments($paging_data);
        }
    }

    if ($current_page_from_url != false) {
        if (intval($current_page_from_url) > 0) {
            $comments_data['current_page'] = intval($current_page_from_url);
        }
    }

    if (isset($comments_data['rel_type'])) {
        $data['rel'] = $comments_data['rel_type'];
    }

    $comments = get_comments($comments_data);




    $cur_user_data = array();
    $cur_user = user_id();
    if ($cur_user != false) {
        $cur_user_data = get_user($cur_user);
    }

    $post = false;
    if ($data['rel_type'] == 'content') {
        $post = get_content_by_id($data['rel_id']);
    }
    $required_moderation = get_option('require_moderation', 'comments') == 'y';
    $avatars_enabled = get_option('avatar_enabled', 'comments') == 'y';
    $avatar_style = get_option('avatar_style', 'comments');


    if ($comments) {
        $comments_new = array();
        foreach ($comments as $comment) {
            $show_comment = false;



            if ( $comment['session_id'] == mw()->user_manager->session_id()) {
            //   $show_comment = true;
            }


            if (!$show_comment and isset($comment['is_spam']) and intval($comment['is_spam']) == 1) {
                continue;
            }

            if (!$show_comment and isset($comment['is_moderated']) and intval($comment['is_moderated']) == 0) {
                continue;
            }


//            else {
//
//
//
//                 if (
//                     !$required_moderation or intval($comment['is_moderated']) == 1
//                     or (!(mw()->user_manager->session_all() == false) and $comment['session_id'] == mw()->user_manager->session_id())
//                 ){}
//
//                     continue;
//            }


            if (isset($comment['comment_website'])) {
                $comment['comment_website'] = mw()->format->prep_url($comment['comment_website']);
            }

            $comments_new[] = $comment;

        }
       // if ($comments_new) {
            $comments = $comments_new;
       // }
    }
    
    $template = get_option('data-template', $params['id']);
    if (($template == false or ($template == '')) and isset($params['template'])) {
        $template = $params['template'];
    }

    $template_file = false;
    if ($template != false and strtolower($template) != 'none') {
        $template_file = module_templates($config['module'], $template);
    }
    if ($template_file == false) {
        $template_file = module_templates($config['module'], 'default');
    }
    ?>

    <script type="text/javascript">
        mw.require("url.js", true);
        mw.require("forms.js", true);
    </script>
    
    <script>
	function edit_comment_user(id) {
		var data = {};
	    data.id = id;
		edit_list_modal = mw.tools.open_module_modal('comments/edit_comment_user', data, {height:500,overlay: true, skin: 'simple'});
	}
	function delete_comment_user(id) {
		
		$.ajax({
			  type: "POST",
			  url: mw.settings.api_url + 'delete_comment_user',
			  data: "comment_id=" + id,
			  success: function() {
				mw.reload_module('comments');
				mw.notification.success('Comment deleted!');
			  }
		});
		
	}
	</script>

    <script type="text/javascript">
        mw.init_comment_form<?php print md5($params['id']) ?> = function () {
            var comm_hold = "login-comments-form-<?php print $params['id'] ?>";
            var comm_module_id = "<?php print $params['id'] ?>";
            var login_hold = "login-comments-form-<?php print $params['id'] ?>";

            mw.$('#' + comm_module_id + ' a.comments-login-link').click(function () {
                if ($('#' + login_hold).length == 0) {
                    $('#<?php print $params['id']; ?>').append('<span id=' + comm_hold + '></span>');
                }
                mw.load_module('users/login', '#' + login_hold, function (a) {
                    $(this).addClass("mw-comments-login")
                });

                return false;
            });

            mw.$('#<?php print $params['id'] ?> a.comments-register-link').click(function () {
                var login_hold = "login-comments-form-<?php print $params['id'] ?>";
                if ($('#' + login_hold).length == 0) {
                    $('#<?php print $params['id'] ?>').append('<span id="login-comments-form-<?php print $params['id'] ?>"></span>');
                }
                mw.load_module('users/register', '#' + login_hold, function () {
                    $(this).addClass("mw-comments-register")
                });

                return false;
            });

            mw.comments_is_saving = false;

            mw.$('form#comments-form-<?php print $params['id'] ?>').submit(function () {
                if (mw.comments_is_saving == true) {
                    return false;
                }

                mw.comments_is_saving = true;
                mw.form.post('form#comments-form-<?php print $params['id'] ?>', '<?php print api_link('post_comment'); ?>',
                    function (msg) {
                        mw.comments_is_saving = false;
                        var resp = this;
                        var data2 = (resp);
                        if (typeof(data2.error) != 'undefined') {
                            mw.response(mw.$('form#comments-form-<?php print $params['id'] ?>'), data2);
                        }
                        if (typeof(resp.error) != 'undefined') {
                            var err_hold = "error-comments-form-<?php print $params['id'] ?>";
                            if ($('#' + err_hold).length == 0) {
                                var html = "<span class='alert alert-error' id='" + err_hold + "'></span>";
                                $('#comments-form-<?php print $params['id'] ?>').append(html);
                            }
                            $('#' + err_hold).html(resp.error);
                        }
                        else {
                            mw.reload_module('#<?php print $params['id'] ?>');
                        }
                    });
                return false;
            });
        }

        $(document).ready(function () {
            mw.init_comment_form<?php print md5($params['id']) ?>();
        });
    </script>


    <?php
    /*------------------- Petko ---------------------------*/

    if ($form_title != false) {
        $formTitle = $form_title;
    } elseif ($display_comments_from != false and $display_comments_from == 'recent') {
        $formTitle = _e("Recent comments", true);
    } else {
        $post = false;
        if ($data['rel_type'] == 'content') {
            $post = get_content_by_id($data['rel_id']);
        }

        if ($post) {
            $formTitle = _e("Comments for", true) . '<strong>' . $post['title'] . '</strong>';
        } else {
            $formTitle = _e("Comments", true);
        }
    }


    $avatar_style = get_option('avatar_style', 'comments');
    $avatarStyle = 1;
    if (isset($comment_author['thumbnail']) and trim($comment_author['thumbnail']) != '') {
        $avatarPath = $comment_author['thumbnail'];
    } else {
        if ($avatar_style == '4') {
            $avatarPath = thumbnail(get_option('avatartype_custom', 'comments'), 60, 60);
        } else if ($avatar_style == '1' || $avatar_style == '3') {
            $avatarPath = thumbnail($config['url_to_module'] . '/img/comment-default-' . $avatar_style . '.jpg', 60, 60);
        } else if ($avatar_style == '2') {
            $avatarStyle = 2;
            $avatarPath = mw('format')->random_color();
        } else if (isset($comment_author['thumbnail']) and $comment_author['thumbnail'] != '') {
            $avatarPath = ($comment_author['thumbnail']);
        } else {

        }
    }
    ?>

    <?php
    if ($template_file != false and is_file($template_file)) {
        include($template_file);
    }
    ?>
    <?php
} else {

    print lnotif('Comments posting is disabled from the admin panel');
} ?>
