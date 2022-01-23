<?php

use MicroweberPackages\App\Http\RequestRoute;

if (!defined("MODULE_DB_COMMENTS")) {
    define('MODULE_DB_COMMENTS', 'comments');
}

require_once(__DIR__ . DS . 'vendor' . DS . 'autoload.php');


api_expose_admin('mark_comment_post_notifications_as_read', function ($params) {

    if (isset($params['comment_id'])) {
        $comm = get_content_by_id($params['comment_id']);
        if ($comm and isset($comm['rel_type']) and isset($comm['rel_id'])) {
            $data = array();
            $data['rel_type'] = $comm['rel_type'];
            $data['rel_id'] = $comm['rel_id'];
            $data['module'] = 'comments';

            return mw()->notifications_manager->mark_all_as_read($data);
        }


    }
});

api_expose('delete_comment_user', function ($params) {

    $comment = get_comments('single=1&id=' . $params['comment_id']);
    if (empty($comment)) {
        return;
    }

    $commentSessionId = false;
    if (isset($comment['session_id'])) {
        $commentSessionId = $comment['session_id'];
    }

    if (mw()->user_manager->session_id() == $commentSessionId) {

        return db_delete("comments", intval($params['comment_id']));

    }
});

api_expose('save_comment_user', function ($params) {

    $comment = get_comments('single=1&id=' . $params['comment_id']);
    if (empty($comment)) {
        return;
    }

    $commentSessionId = false;
    if (isset($comment['session_id'])) {
        $commentSessionId = $comment['session_id'];
    }

    if (mw()->user_manager->session_id() == $commentSessionId) {

        $commentData = RequestRoute::postJson(
            route('api.comment.post'),
            $params
        );

        return $commentData;
    }

});




event_bind(
    'module.content.manager.item', function ($item) {

    if (isset($item['id'])) {

        $new = get_comments('count=1&is_moderated=0&content_id=' . $item['id']);
        if ($new > 0) {
            $have_new = 1;
        } else {
            $have_new = 0;
            $new = get_comments('count=1&content_id=' . $item['id']);
        }
        $comments_link = admin_url('view:comments') . '/#content_id=' . $item['id'];

        if ($have_new) {

        }
        $link = "<a class='text-muted' href='{$comments_link}'  title='{$new}'>";
        $link .= "<span class='mdi mdi-comment mdi-18px'></span><span class='float-right mx-2'>{$new}</span>";
        $link .= "</a>";
        print $link;
    }
}
);


event_bind(
    'mw.admin.dashboard.content.2', function ($item) {
    print '<div type="comments/dashboard_recent_comments" class="mw-lazy-load-module" id="admin-dashboard-recent-comments" no_paging="true"></div>';
}
);

/*
event_bind(
    'module.content.edit.main', function ($item) {

    if (isset($item['id'])) {
        $new = get_comments('count=1&rel_type=content&rel_id=' . $item['id']);
        if ($new > 0) {
            $btn = array();
            $btn['title'] = 'Comments';
            $btn['class'] = 'mw-icon-comment';
            $btn['html'] = '<module type="comments/manage" no_post_head="true" content_id="' . $item['id'] . '"  />';
            mw()->module_manager->ui('content.edit.tabs', $btn);
        }
    }
}
);*/


event_bind(
    'module.comments.item.before', function ($item) {

    $commentSessionId = false;
    if (isset($item['session_id'])) {
        $commentSessionId = $item['session_id'];
    }

    if (mw()->user_manager->session_id() == $commentSessionId) {
        echo '<module type="comments/manage_user" no_post_head="true" comment_id="' . $item['id'] . '"  />';
    }
}
);

event_bind(
    'mw.admin.dashboard.links', function () {

    $admin_dashboard_btn = array();
    $admin_dashboard_btn['view'] = 'comments';

    $admin_dashboard_btn['icon_class'] = 'mdi mdi-comment-account';
    $notif_html = '';
    $notif_count = \Microweber\Comments\Models\Comment::get()->count();

    if ($notif_count > 0) {
        $notif_html = '<sup class="badge badge-success badge-sm badge-pill ml-2">' . $notif_count . '</sup>';
    }
    $admin_dashboard_btn['text'] = _e("Comments", true) . $notif_html;

    mw()->ui->module('admin.dashboard.menu', $admin_dashboard_btn);
});

event_bind('website.privacy_settings', function () {
    print '<module type="comments/privacy_settings" />';
});
