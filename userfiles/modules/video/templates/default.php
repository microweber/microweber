<?php
/*
type: layout
name: Default
description: Default
*/
?>

<style>
    .mwembed-video {
        max-width: 100%;
    }
</style>

<?php if ($lazyload) { ?>
    <script>
        $(document).ready(function () {
            $('.js-mw-embed-wrapper-<?php echo $params['id']; ?>').click(function () {

                var frame = $('.js-mw-embed-iframe-<?php echo $params['id']; ?>');
                var htmlVideo = $('.js-mw-embed-htmlvideo-<?php echo $params['id']; ?>');

                if (frame.length > 0) {
                    frame.attr('src', frame.attr('data-src'));
                    frame.fadeIn();
                }

                if (htmlVideo.length > 0) {
                    htmlVideo.attr('src', htmlVideo.attr('data-src'));
                    htmlVideo.fadeIn();
                }

                $(this).css('background-image', 'none');
            });
        });
    </script>
<?php } ?>

<?php echo $code; ?>

