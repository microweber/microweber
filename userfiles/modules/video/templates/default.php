<?php
/*
type: layout
name: Default
description: Default
*/
?>

<style>
    #<?php echo $params['id']; ?> .mwembed-video {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
    }


    #<?php echo $params['id']; ?> .mwembed-video:after {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.40);
        color: #fff;
        border-radius: 50%;
        cursor: pointer;
        width: 80px;
        height: 80px;
        border: none;
        pointer-events: none;
        background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 0 48 48" width="30"><path d="M-838-2232H562v3600H-838z" fill="none"/><path d="M16 10v28l22-14z"/><path d="M0 0h48v48H0z" fill="none"/></svg>');
        background-repeat: no-repeat;
        background-position: center;
        content: '';
        z-index: 3;

    }

    #<?php echo $params['id']; ?> .mwembed-video:before {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 2;
        content: '';
    }


    #<?php echo $params['id']; ?> .playButton-d-none:before,  #<?php echo $params['id']; ?> .playButton-d-none:after {
        display: none;
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

<script>
    $(document).ready(function () {
      $('#<?php echo $params['id']; ?> video').on('pause', function () {
        $(this).parent().removeClass('playButton-d-none');
      }).on('play', function () {
        $(this).parent().addClass('playButton-d-none');
      });
    });


</script>
