commentToggle = window.commentToggle || function (e) {

        var item = mw.tools.firstParentOrCurrentWithAllClasses(e.target, ['comment-holder']);
        if (!mw.tools.hasClass(item, 'active')) {
            var curr = $('.order-data-more', item);
            $('.order-data-more').not(curr).stop().slideUp();
            $('.comment-holder').not(item).removeClass('active');
            $(curr).stop().slideToggle();
            $(item).toggleClass('active');
        }

    }

$(document).ready(function () {
    $('.new-close').on('click', function (e) {
        e.stopPropagation();
        var item = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['comment-holder', 'message-holder', 'order-holder']);
        $(item).removeClass('active')
        $('.mw-accordion-content', item).stop().slideUp(function () {

        });
    });


    $('.mw-reply-btn').on('click', function (e) {
        $(this).prev().show();
        $(this).hide();
    });

    $('.js-edit-comment-btn').on('click', function (e) {
        e.preventDefault();
        var commentID = $(this).data('id');
        $(this).hide();
        $('#comment-' + commentID + ' .js-save-comment-btn').show();
        $('#comment-' + commentID + ' .comment_body .js-comment').hide();
        $('#comment-' + commentID + ' .comment_body textarea').show();

    });

    $('.js-save-comment-btn').on('click', function (e) {
        e.preventDefault();
        var commentID = $(this).data('id');
        $(this).hide();
        $('#comment-' + commentID + ' .js-edit-comment-btn').show();
        $('#comment-' + commentID + ' .comment_body .js-comment').show();
        $('#comment-' + commentID + ' .comment_body textarea').hide();

        $('#comment-' + commentID + ' .comment_body .js-comment').text($('#comment-' + commentID + ' .comment_body textarea').val());

    });
});


save_comment_form = function (form_id) {
    var url = mw.settings.api_url + 'post_comment';

    mw.form.post(form_id, url)
    mw.notification.success('Comment saved')
}