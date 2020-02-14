<?php

/*

type: layout

name: Scroll Pagination

description: Scroll Pagination

*/
$currentTags = url_param('tags');
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
    var nextPageNumber = 2;
    var ajaxCount = 0;
    var ajaxRequestIsSended = 0;
    var postsDataRequest = JSON.parse(<?php echo json_encode($postDataRequest); ?>);

    $(window).on('scroll', function () {

        if ($(window).scrollTop() >= $('.js-blog-post-wrapper').last().offset().top - $('.js-blog-post-wrapper').outerHeight()) {

            if (ajaxRequestIsSended == 0) {

                ajaxCount = ajaxCount + 1;
                ajaxRequestIsSended = 1;

                postsDataRequest.page = nextPageNumber;
                postsDataRequest.current_page = nextPageNumber;

                <?php if ($currentTags): ?>
                postsDataRequest.tags = '<?php echo $currentTags; ?>';
                <?php endif; ?>

                $.ajax({
                    url: mw.settings.api_url + 'posts/get',
                    data: postsDataRequest,
                    success: function(json) {

                        if (json.data) {
                            ajaxRequestIsSended = 0;
                            nextPageNumber = nextPageNumber + 1;

                            var html = '';
                            for (i = 0; i < json.data.length; i++) {
                                html += '<div class="js-blog-post-wrapper">' + json.data[i].title + '</div>';
                            }

                            $('.js-blog-post-wrapper').last().after(html);
                            $('.js-blog-post-wrapper').last().hide().fadeIn();
                        } else {
                            $('.js-blog-post-wrapper').last().after('<br />No more posts.');
                        }
                    }
                });

            }
        }
    });
</script>
<?php
foreach ($data as $post):
?>
<div class="js-blog-post-wrapper">
    <?php echo $post['title']; ?>
</div>
<?php
endforeach;
?>


