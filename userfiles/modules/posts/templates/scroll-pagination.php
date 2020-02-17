<?php

/*

type: layout

name: Scroll Pagination

description: Scroll Pagination

*/
$currentTags = url_param('tags',true);
// d($currentTags);
if ($currentTags == 'false') {
    $currentTags = false;
}
$postDataRequest = json_encode($params);
?>

<style>
    .js-blog-post-wrapper {
        border: 1px dashed #d2d2d2;
        padding: 150px;
        font-size: 35px;
        color: red;
    }
</style>
<script>
    nextPageNumber = 1;
    ajaxRequestIsSended = 0;
    postsDataRequest = JSON.parse(<?php echo json_encode($postDataRequest); ?>);

    <?php if ($currentTags): ?>
    postsDataRequest.tags = '<?php echo $currentTags; ?>';
    <?php endif; ?>

    $(window).on('scroll', function () {

        if ($(window).scrollTop() >= $('.js-blog-post-wrapper').last().offset().top - $('.js-blog-post-wrapper').outerHeight()) {

            if (ajaxRequestIsSended == 0) {
                loadBlogPosts(nextPageNumber);
            }
        }
    });

    $(document).ready(function () {
        loadBlogPosts(nextPageNumber);
    });

    function loadBlogPosts(page)
    {
        ajaxRequestIsSended = 1;

        postsDataRequest.page = page;
        postsDataRequest.current_page = page;

        $.ajax({
            url: mw.settings.api_url + 'posts/get',
            data: postsDataRequest,
            success: function(json) {

                if (json.data) {
                    ajaxRequestIsSended = 0;
                    nextPageNumber = page + 1;

                    var html = '';
                    for (i = 0; i < json.data.length; i++) {
                        html += '<div class="js-blog-post-wrapper">' + json.data[i].title + '</div>';
                    }

                    if ($('.js-blog-post-wrapper').length == 0) {
                        $('.js-blog-posts-holder').append(html);
                    } else {
                        // append after post wrapper
                        $('.js-blog-post-wrapper').last().after(html);
                    }

                    $('.js-blog-post-wrapper').last().hide().fadeIn();
                }

            }
        });
    }
</script>

<div class="js-blog-posts-holder"></div>
