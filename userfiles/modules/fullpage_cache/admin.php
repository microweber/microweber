<?php must_have_access(); ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <?php

    $sitemapUrls = new FullpageCacheHelper();
    $sitemapUrls->getUrls();

    ?>

    <script>

        nextPageIteration = 0;
        isPaused = false;
        function nextPageForCache(index) {
            if (isPaused === false) {
                setTimeout(function() {
                    openPageIframe(index);
                },600);
            }
        }

        $(document).ready(function () {
            $('#js-full-page-cache-pause').show();
            $('#js-full-page-cache-continue').hide();
            $('.js-fullpage-cache-button').click(function () {
                $('#js-full-page-cache-modal').modal('show');
                openPageIframe(0);
            });
        });

        function openPageIframe(index)
        {
            nextPageIteration = (index + 1);
            var modalHtml = '';

            modalHtml = '<h4 class="text-center">Caching pages 0 of ' + index + '</h4> <br />';
            modalHtml += '<iframe  onload="nextPageForCache('+nextPageIteration+');" src="<?php echo api_url(); ?>fullpage-cache-open-iframe?slug=waw&iteration='+index+'" style="border:0px;width:100%;height:500px;"></div>';

            $('#js-full-page-cache-modal-body').html(modalHtml);
        }

        function pauseFullpageCache()
        {
            isPaused = true;
            $('#js-full-page-cache-pause').hide();
            $('#js-full-page-cache-continue').show();
        }
        function continueFullpageCache()
        {
            isPaused = false;
            openPageIframe(nextPageIteration);
            $('#js-full-page-cache-pause').show();
            $('#js-full-page-cache-continue').hide();
        }
        function stopFullpageCache()
        {
            $('#js-full-page-cache-modal-body').html('Loading..');
        }
    </script>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="js-full-page-cache-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Full page cache wizard</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="js-full-page-cache-modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" id="js-full-page-cache-continue" onclick="continueFullpageCache()"><i class="mdi mdi-play"></i> Continue</button>
                    <button type="button" class="btn btn-outline-primary" id="js-full-page-cache-pause" onclick="pauseFullpageCache()">Pause</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="stopFullpageCache()">Stop Process</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $isFullpageCached = false;
    ?>

    <div class="card-body border-0">
        <div class="text-center <?php if ($isFullpageCached): ?>card-success<?php else: ?> card-danger <?php endif; ?>">
            <div class="card-body p-4">
                <i class="mdi mdi-rocket" style="font-size:62px;color:#4592ff;"></i>
                <h4>Increase speed of your website</h4>
                <h5>with Fullpage Cache Module</h5>
                <br>
                <?php if ($isFullpageCached) { ?>
                <br> <br>
                <h1 class="text-success"><i class="mw-standalone-icons mdi mdi-check-circle-outline"></i>
                    <h4><h5 class="text-success font-weight-bold"> Fullpage Cached</h5></h4>
                    <?php
                    } else { ?>
                        <br> <br>
                        <h1 class="text-danger"><i class="mw-standalone-icons mdi mdi-emoticon-sad-outline"></i></h1>
                        <h5 class="text-danger font-weight-bold text-uppercase"> The 3210 pages not cached.</h5><br/>
                    <?php } ?>
                    <br>
                    <br>

                    <?php if ($isFullpageCached) { ?>
                        <button type="button" class="btn btn-success js-fullpage-cache-button"> Recache</button>
                        <?php
                    } else {
                        ?>
                        <button type="button" class="btn btn-success js-fullpage-cache-button text-uppercase"> Run full page cache </button>
                        <?php
                    }
                    ?>

            </div>
        </div>
    </div>
