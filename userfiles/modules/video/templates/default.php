<?php
/*
type: layout
name: Default
description: Default
*/
?>

<?php if($lazyload) { ?>
    <script>
        $(document).ready(function() {
            $('.js-mw-embed-wrapper-<?php echo $params['id']; ?>').click(function() {
                $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').attr('src', $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').attr('data-src'));
                $('.js-mw-embed-iframe-<?php echo $params['id']; ?>').fadeIn();
                $(this).css('background-image', 'none');
            });
        });
    </script>
<?php } ?>

<?php echo $code; ?>
