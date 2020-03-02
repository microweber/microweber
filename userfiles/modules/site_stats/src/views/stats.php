<script>
    mw.lib.require('flag_icons', true);
</script>

<script>
    $(document).ready(function () {
        $('.sources .source-title').on('click', function () {
            $(this).parent().toggleClass('active');
            $(this).find('id').toggleClass('active');
        });

        $('.stats-view .sources, .stats-view .contents, .stats-view .locations, .stats-view .visitors').slimScroll({});

        $('.show-more-stats').on('click', function () {
            if ($('.stats-view-holder').hasClass('hidden')) {
                $('.stats-view-holder').slideDown();
                $('.stats-view-holder').removeClass('hidden');
                $(this).text('<?php _e('show less'); ?>');
            } else {
                $('.stats-view-holder').slideUp();
                $('.stats-view-holder').addClass('hidden');
                $(this).text('<?php _e('show more'); ?>');
            }
        });
    });
</script>

<div class="center m-b-20">
    <button class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline show-more-stats"><?php _e('show more'); ?></button>
</div>

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

            <div class="demobox" id="demotabsnav">
                <div class="heading  mw-ui-box">
                    <div><?php _e("Referrers"); ?></div>
                    <!--<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
                        <a href="javascript:;" class="mw-ui-btn"><span class="number">726</span>
                            <small>Sites</small>
                        </a>
                        <a href="javascript:;" class="mw-ui-btn active"><span class="number">84</span>
                            <small>Social</small>
                        </a>
                        <a href="javascript:;" class="mw-ui-btn"><span class="number">10.7k</span>
                            <small>Search</small>
                        </a>
                    </div>-->
                </div>

                <div class="sources mw-ui-box has-tabs">
                    <div class="mw-ui-box-content" style="">
                        <module type="site_stats/admin" view="referrers_list" period="<?php print $period; ?>"/>
                    </div>

                    <div class="mw-ui-box-content" style="display: none;">
                        <module type="site_stats/admin" view="referrers_list" period="<?php print $period; ?>"/>

                    </div>
                    <div class="mw-ui-box-content" style="display: none">
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
