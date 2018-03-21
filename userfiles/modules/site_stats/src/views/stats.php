<script>
    mw.lib.require('flag_icons');

    $(document).ready(function () {
        $('.sources .source-title').on('click', function () {
            $(this).parent().toggleClass('active');
            $(this).find('id').toggleClass('active');
        });
    });
</script>


<div class="stats-view">
    <div class="mw-ui-col">
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
                <div>Some statistic</div>
                <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
                    <a href="javascript:;" class="mw-ui-btn"><span class="number">726</span>
                        <small>Sites</small>
                    </a>
                    <a href="javascript:;" class="mw-ui-btn active"><span class="number">84</span>
                        <small>Social</small>
                    </a>
                    <a href="javascript:;" class="mw-ui-btn"><span class="number">10.7k</span>
                        <small>Search</small>
                    </a>
                </div>
            </div>

            <div class="sources mw-ui-box">
                <div class="mw-ui-box-content" style="">
                    <ul class="">
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                    </ul>
                </div>
                <div class="mw-ui-box-content" style="display: none;">
                    <ul class="">
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                    </ul>
                </div>
                <div class="mw-ui-box-content" style="display: none">
                    <ul class="">
                        <?php include('parts/sources.php'); ?>
                        <?php include('parts/sources.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            Some statistic
        </div>
        <div class="contents mw-ui-box">
            <?php include('parts/contents.php'); ?>
            <?php include('parts/contents.php'); ?>
            <?php include('parts/contents.php'); ?>
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            Some statistic
        </div>
        <div class="visitors mw-ui-box">
            <module type="site_stats/admin" view="visitors_list"/>
        </div>
    </div>
</div>

<div class="stats-view">
    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            Locations
        </div>
        <div class="locations mw-ui-box">
            <?php include('parts/locations.php'); ?>
            <?php include('parts/locations.php'); ?>
            <?php include('parts/locations.php'); ?>
        </div>
    </div>

    <div class="mw-ui-col">
        <div class="heading  mw-ui-box">
            Languages
        </div>
        <div class="locations mw-ui-box">
            <div class="item location">
                <div class="location-progressbar" style="width: 30%;"></div>
                <div class="mw-ui-row">
                    <div class="mw-ui-col">
                        <div class="title">Some page title from the our microweber website</div>
                    </div>

                    <div class=" mw-ui-col" style="width:30px;">
                        <div class="cnt">3</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>