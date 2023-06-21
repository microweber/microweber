<script>
    mw.lib.require('flag_icons', true);
</script>

<script>
    $(document).ready(function () {
        $('.sources .source-title').on('click', function () {
            $(this).parent().toggleClass('active');
            $(this).find('id').toggleClass('active');
        });

        $('.show-more-stats').on('click', function () {
            var $holder = $('.stats-view-holder');
            if ($holder.hasClass('hidden')) {
                $holder.slideDown().removeClass('hidden');
                $('.show-more-stats').text('<?php _ejs('show less'); ?>');
            } else {
                $holder.slideUp().addClass('hidden');
                $('.show-more-stats').text('<?php _ejs('show more'); ?>');
            }
        });
    });
</script>


<div class="stats-view-holder hidden">
    <div class="stats-view row">
        <div class="col-lg-6 col-12 mb-3">
            <script>
                $(document).ready(function () {
                    mw.tabs({
                        nav: '#demotabsnav .mw-ui-btn-nav-tabs a',
                        tabs: '#demotabsnav .mw-ui-box-content'
                    });
                });
            </script>

            <div class="card dashboard-statistics-card-wrappers " id="demotabsnav">
                <div class="card-header pb-0">
                    <label  class="form-label mt-3 mb-0">
                        <?php _e("Referrers"); ?>
                    </label>
                </div>

                <div class="card-body pe-0 overflow-y-scroll overflow-x-hidden sources has-tabs">
                    <div class="" style="">
                        <module type="site_stats/admin" view="referrers_list" period="<?php print $period; ?>"/>
                    </div>
                    <div class="" style="display: none;">
                        <module type="site_stats/admin" view="referrers_list" period="<?php print $period; ?>"/>
                    </div>
                    <div class="" style="display: none">
                        <module type="site_stats/admin" view="referrers_list" period="<?php print $period; ?>"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12 mb-3">
            <div class="card dashboard-statistics-card-wrappers">
                <div class="card-header pb-0">
                    <label  class="form-label mt-3 mb-0">
                        <?php _e('Content'); ?>
                    </label>
                </div>
                <div class="card-body pe-0 overflow-y-scroll overflow-x-hidden contents">
                    <module type="site_stats/admin" view="content_list" period="<?php print $period; ?>"/>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12 mb-3">
            <div class="card dashboard-statistics-card-wrappers">
                <div class="card-header pb-0">
                    <label  class="form-label mt-3 mb-0">
                        <?php _e('Visitors'); ?>
                    </label>

                </div>
                <div class="card-body pe-1 overflow-y-scroll overflow-x-hidden visitors">
                    <module type="site_stats/admin" view="visitors_list" period="<?php print $period; ?>"/>
                </div>
            </div>
        </div>
<!---->
<!--        <div class="col-12 mb-3">-->
<!--            <div class="card dashboard-statistics-card-wrappers">-->
<!--                <div class="card-header pb-0">-->
<!--                    <label  class="form-label mt-3 mb-0">-->
<!--                        --><?php //_e('Locations'); ?>
<!--                    </label>-->
<!--                </div>-->
<!--                <div class="card-body pe-0 overflow-y-scroll overflow-x-hidden locations">-->
<!--                    <module type="site_stats/admin" view="locations_list" period="--><?php //print $period; ?><!--"/>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

        <div class="col-lg-6 col-12 mb-3">
            <div class="card dashboard-statistics-card-wrappers">
                <div class="card-header pb-0">
                    <label  class="form-label mt-3 mb-0">
                        <?php _e('Browser language'); ?>
                    </label>

                </div>
                <div class="card-body pe-0 overflow-y-scroll overflow-x-hidden locations">
                    <module type="site_stats/admin" view="languages_list" period="<?php print $period; ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
