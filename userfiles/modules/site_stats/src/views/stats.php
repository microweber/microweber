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
                $('.show-more-stats').text('<?php _e('show less'); ?>');
            } else {
                $holder.slideUp().addClass('hidden');
                $('.show-more-stats').text('<?php _e('show more'); ?>');
            }
        });
    });
</script>


<div class="stats-view-holder hidden">
    <div class="stats-view row">
        <div class="col-xl-4 col-lg-6 col-md-6.stats-view .sources, .stats-view .contents, .stats-view .locations, .stats-view .visitors { mb-3">
            <script>
                $(document).ready(function () {
                    mw.tabs({
                        nav: '#demotabsnav .mw-ui-btn-nav-tabs a',
                        tabs: '#demotabsnav .mw-ui-box-content'
                    });
                });
            </script>

            <div class="card style-1 h-100" id="demotabsnav">
                <div class="card-header"><?php _e("Referrers"); ?></div>

                <div class="card-body overflow-auto sources has-tabs">
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

        <div class="col-xl-4 col-lg-6 col-md-6.stats-view .sources, .stats-view .contents, .stats-view .locations, .stats-view .visitors { mb-3">
            <div class="card style-1 h-100">
                <div class="card-header"><?php _e('Content'); ?></div>
                <div class="card-body overflow-auto contents">
                    <module type="site_stats/admin" view="content_list" period="<?php print $period; ?>"/>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6.stats-view .sources, .stats-view .contents, .stats-view .locations, .stats-view .visitors { mb-3">
            <div class="card style-1 h-100">
                <div class="card-header"><?php _e('Visitors'); ?></div>
                <div class="card-body overflow-auto visitors">
                    <module type="site_stats/admin" view="visitors_list" period="<?php print $period; ?>"/>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card style-1 h-100">
                <div class="card-header"><?php _e('Locations'); ?></div>
                <div class="card-body overflow-auto locations">
                    <module type="site_stats/admin" view="locations_list" period="<?php print $period; ?>"/>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card style-1 h-100">
                <div class="card-header"><?php _e('Browser language'); ?></div>
                <div class="card-body overflow-auto locations">
                    <module type="site_stats/admin" view="languages_list" period="<?php print $period; ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
