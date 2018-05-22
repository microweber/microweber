


mw.edit_comments = {

    save_form: function (form_id) {
        var url = mw.settings.api_url + 'post_comment';

        mw.form.post(form_id, url)
        mw.notification.success('Comment saved')
    },

    mark_as_spam: function (comment_id) {
        var url = mw.settings.api_url + 'mark_comment_as_spam';
        var conf = confirm('Are you sure you want to mark this comment as spam?');
        if (conf) {
            var data = {};
            data.comment_id = comment_id;
            $.post(url, data, function (data) {
                mw.notification.success('Comment is marked as spam')

            });
        }
    },

    delete: function (comment_id) {
        var url = mw.settings.api_url + 'post_comment';
        var conf = confirm('Are you sure you want to delete this comment?');
        if (conf) {
            var data = {};
            data.id = comment_id;
            data.action = 'delete';
            $.post(url, data, function (data) {
                mw.notification.success('Comment deleted')
            });
        }
    },

}


//
// $(document).ready(function () {
//     $('.new-close').on('click', function (e) {
//         e.stopPropagation();
//         var item = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['comment-holder', 'message-holder', 'order-holder']);
//         $(item).removeClass('active')
//         $('.mw-accordion-content', item).stop().slideUp(function () {
//
//         });
//     });
//
//
//     $('.mw-reply-btn').on('click', function (e) {
//         $(this).prev().show();
//         $(this).hide();
//     });
//
//     $('.js-edit-comment-btn').on('click', function (e) {
//         e.preventDefault();
//         var commentID = $(this).data('id');
//         $(this).hide();
//         $('#comment-' + commentID + ' .js-save-comment-btn').show();
//         //  $('#comment-' + commentID + ' .comment_body .js-comment').hide();
//         $('#comment-' + commentID + ' .comment_body textarea').show();
//         $('#comment-' + commentID + ' .js-comment-edit-details-toggle').toggle();
//     });
//
//     $('.js-save-comment-btn').on('click', function (e) {
//         e.preventDefault();
//         var commentID = $(this).data('id');
//         $(this).hide();
//         $('#comment-' + commentID + ' .js-edit-comment-btn').show();
//         // $('#comment-' + commentID + ' .comment_body .js-comment').show();
//         $('#comment-' + commentID + ' .comment_body textarea').hide();
//         $('#comment-' + commentID + ' .js-comment-edit-details-toggle').toggle();
//
//         $('#comment-' + commentID + ' .comment_body .js-comment').text($('#comment-' + commentID + ' .comment_body textarea').val());
//
//     });
//
//
//     $('js-reply-comment-form').on('submit', function(e){
//
//         e.preventDefault();
//         var form = $(this);
//         if (form) {
//             mw.edit_comments.save_form(form);
//         }
//     });
//
//
//
//     $('.js-reply-comment-btn').on('click', function (e) {
//         e.preventDefault();
//
//
//     });
// });