<?php
/*
type: layout
name: Default
description: Default
*/
?>

<?php if ($lazyload) { ?>
    <script>
        $(document).ready(function () {
            $('.js-mw-embed-wrapper-<?php echo $params['id']; ?>').click(function () {

                if ($('.js-mw-embed-iframe-<?php echo $params['id']; ?>').length > 0) {
                    $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').attr('src', $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').attr('data-src'));
                    $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').fadeIn();
                }

                if ($('.js-mw-embed-htmlvideo-<?php echo $params['id']; ?>').length > 0) {
                    $('.js-mw-embed-htmlvideo-<?php echo $params['id']; ?>').attr('src', $('.js-mw-embed-htmlvideo-<?php echo $params['id']; ?>').attr('data-src'));
                    $('.js-mw-embed-htmlvideo-<?php echo $params['id']; ?>').fadeIn();
                }

                $(this).css('background-image', 'none');
            });
        });
    </script>
<?php } ?>

<?php if ($provider == 'vimeo'): ?>
    <?php echo $code; ?>
<?php else: ?>
    <?php echo $code; ?>
<?php endif; ?>
