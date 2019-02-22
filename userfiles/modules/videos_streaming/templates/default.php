<div class="videos-streaming-holder">
    <?php if (isset($data) AND $data) {
        foreach ($data as $key => $video) {
            if ($key == 0) {
                $firstTitle = array_get($video, 'video_title');
                $firstID = array_get($video, 'video_id');
            }
        }
    } else {
        $firstTitle = $defaults['video_title'];
        $firstID = $defaults['video_id'];
    }
    ?>

    <script>
        $(document).ready(function () {
            $('.js-tutorials .videos-holder ul li:first-child').addClass('active');

            $('.js-tutorials .videos-holder ul li').on('click', function () {
                $('.js-tutorials .videos-holder ul li').removeClass('active');
                $(this).addClass('active');

                var thisVideoID = $(this).data('video-id');
                var thisVideoURL = ' https://player.vimeo.com/video/' + thisVideoID;
                var thisVideoTitle = $(this).data('video-title');

                $('.js-video-heading').text(thisVideoTitle);
                $('.js-video-iframe').attr('src', thisVideoURL);

                //Setting Hash to the URL
                if (history.pushState) {
                    history.pushState(null, null, '#' + thisVideoID);
                } else {
                    location.hash = '#' + thisVideoID;
                }

                if ($(window).width() < 1200) {
                    window.scrollTo(0, 180);
                }

            });

            setTimeout(function () {
                // on load of the page: switch to the currently selected video
                var hash = window.location.hash.replace('#', '');
                console.log($('.js-tutorials .videos-holder ul li[data-video-id="' + hash + '"]'));
                $('.js-tutorials .videos-holder ul li[data-video-id="' + hash + '"]').trigger('click');
            }, 1);

        });
    </script>

    <link href="<?php print modules_url(); ?>videos_streaming/assets/videos_streaming.css" rel="stylesheet" type="text/css"/>
</div>

<div class="mw-videos-streaming-module">
    <div class="">
        <div class="row">
            <div class="col-xs-12 p-0">
                <div class="heading">
                    <h1 class="js-video-heading"><?php print $firstTitle; ?></h1>
                </div>
            </div>
        </div>

        <div class="row js-tutorials videos-wrapper">
            <div class="col-lg-3 p-0 left-side">
                <div class="videos-holder">
                    <ul>
                        <?php if (isset($data)): ?>
                            <?php foreach ($data as $video): ?>
                                <li data-video-id="<?php print array_get($video, 'video_id'); ?>" data-video-title="<?php print array_get($video, 'video_title'); ?>">
                                    <div class="video-item">
                                        <div class="img">
                                            <?php if (array_get($video, 'video_file') == ''): ?>
                                                <img src="<?php print modules_url(); ?>videos_streaming/assets/img/logo_avatar.png" alt="<?php print array_get($video, 'video_title'); ?>"/>
                                            <?php else: ?>
                                                <img src="<?php print array_get($video, 'video_file'); ?>" alt="<?php print array_get($video, 'video_title'); ?>"/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="title">
                                            <div class="div-table">
                                                <div class="div-table-cell">
                                                    <?php print array_get($video, 'video_title'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 p-0 video-iframe right-side">
                <div style="padding:59.21% 0 0 0;position:relative;">
                    <iframe src="https://player.vimeo.com/video/<?php print $firstID; ?>?title=0&byline=0&portrait=0" class="js-video-iframe" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
                <script src="https://player.vimeo.com/api/player.js"></script>
            </div>
        </div>
    </div>
</div>