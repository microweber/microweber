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
    <div class="stats-view">
        <div class="mw-ui-flex-item">
            <script>
                $(document).ready(function () {
                    mw.tabs({
                        nav: '#demotabsnav .mw-ui-btn-nav-tabs a',
                        tabs: '#demotabsnav .mw-ui-box-content'
                    });
                });
            </script>

            <div class="demobox card style-1 mb-3" id="demotabsnav">
                <div class="heading card-header">
                    <div><?php _e("Referrers"); ?></div>
                </div>

                <div class="card-body sources has-tabs">
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

        <div class="mw-ui-flex-item">
            <div class="heading  mw-ui-box">
                <?php _e('Content'); ?>
            </div>
            <div class="contents mw-ui-box">
                <module type="site_stats/admin" view="content_list" period="<?php print $period; ?>"/>
            </div>
        </div>

        <div class="mw-ui-flex-item">
            <div class="heading  mw-ui-box">
                <?php _e('Visitors'); ?>
            </div>
            <div class="visitors mw-ui-box">
                <module type="site_stats/admin" view="visitors_list" period="<?php print $period; ?>"/>
            </div>
        </div>

        <div class="mw-ui-flex-item">
            <div class="heading  mw-ui-box">
                <?php _e('Locations'); ?>
            </div>
            <div class="locations mw-ui-box">
                <module type="site_stats/admin2" view="locations_list" period="<?php print $period; ?>"/>
            </div>
        </div>

        <div class="mw-ui-flex-item">
            <div class="heading  mw-ui-box">
                <?php _e('Browser language'); ?>
            </div>
            <div class="locations mw-ui-box">
                <module type="site_stats/admin2" view="languages_list" period="<?php print $period; ?>"/>
            </div>
        </div>
    </div>
</div>
