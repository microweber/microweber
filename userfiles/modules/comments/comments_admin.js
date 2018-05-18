

alert(1111);

mw.adminComments = {
    action: function (form, val) {
        var form = $(form);
        var field = form.find('.comment_state');
        var connected_id = mw.$('[name="connected_id"]', form[0]).val();
        field.val(val);
        var conf = true;
        if (val == 'delete') {
            var conf = confirm(mw.msg.to_delete_comment);
        }
        if (conf) {
            var id = form.attr('id');
            var data = form.serialize();
            $.post("<?php print api_link('post_comment'); ?>", data, function (data) {
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
                $.post(mw.settings.api_url+'mark_comments_as_old', mark_as_old, function (data) {

                });
            }
        }
    },
    mark_as_spam:function($comment_id){

        alert($comment_id);



    }
}