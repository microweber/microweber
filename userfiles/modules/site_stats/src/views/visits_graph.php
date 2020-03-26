<?php
//d($visits_daily);
//d($visits_weekly);
//d($visits_monthly);
//d($params);
$module_id = $params['id'];
$period = 'daily';

if ($params['period']) {
    $period = $params['period'];
}
?>


<script>
    mw.admin.__statdata = <?php print json_encode($graph_data); ?>;
</script>

<script>
    Date.prototype.getWeekNumber = function () {
        var d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
        d.setUTCDate(d.getUTCDate() - d.getUTCDay());
        var yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    };
</script>


<script>
    function mw_stats_period_switch($module_id, $period) {
        if (typeof(mw_stats_period_switch_main) != 'undefined') {

            mw_stats_period_switch_main($period);
        } else {
            $('#' + $module_id).attr('period', $period);
            mw.tools.loading(50, 'fast');
            $(".dashboard_stats").fadeOut(function () {
                mw.reload_module('#' + $module_id, function(){
                    mw.tools.loading(false, 'fast');
                });
            })

        }
    }

    $(document).ready(function () {
        $("[data-stat='<?php print $period ?>']").addClass("active");
    });





</script>

<script>

    var series = [];

    $.each(mw.admin.__statdata, function (key, val) {
        var item = {
            name: key,
            data:[]
        };
        $.each(val, function () {
            item.data.push([this.date_key, parseFloat(this.date_value)])
        });
        series.push(item)
    });
    var options = {
        series: series,
        xaxis: {
            type: 'datetime'
        },
        chart: {
            type: 'line',
            height: 200,
            stacked: true,
            events: {
                selection: function (chart, e) {
                    console.log(new Date(e.xaxis.min))
                }
            },
        },
        colors: ['#0086db', '#005C97', '#CED4DC'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2.5,
        },
        markers: {
            show:false,
            size: 0.5,
            radius: 0,
            strokeColors: 'rgba(255,255,255,0)',
            strokeWidth: 0,
            fillOpacity:1
        },
        legend: {
            show: false,
            position: 'top',
            horizontalAlign: 'left'
        },

    };

    $(document).ready(function () {
        $.getScript('https://cdn.jsdelivr.net/npm/apexcharts', function () {
            var el = document.querySelector('.dashboard_stats');
            // var el = document.querySelector("#mw-dashboard-user-activity");
            var chart = new ApexCharts(el, options);
            chart.render();
        })
    })
</script>
<div id="mw-dashboard-user-activity">
    .dashboard_stats
</div>

<div id="stats">
    <div class="mw-ui-box">
        <div class="mw-ui-box-header">
            <span class="stats-title"><i class="mw-icon-connectmw-icon-bars"></i> <span><?php _e("Statistics") ?></span></span>

            <div id="stats_nav">
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','daily');" data-stat='daily' class="mw-ui-btn mw-ui-btn-outline"><?php _e("Daily"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','weekly');" data-stat='weekly' class="mw-ui-btn mw-ui-btn-outline "><?php _e("Weekly"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','monthly');" data-stat='monthly' class="mw-ui-btn mw-ui-btn-outline "><?php _e("Monthly"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','yearly');" data-stat='yearly' class="mw-ui-btn mw-ui-btn-outline "><?php _e("Yearly"); ?></a>
            </div>
            <div class="stats-legend">
                <span class="stats-legend-views"><?php _e("views") ?></span>
                <span class="stats-legend-visitors"><?php _e("visitors") ?></span>
            </div>
        </div>
        <div class="stat-box-content">
            <div class="users-online">
                <?php print $users_online; ?>
                <span><?php _e("Users online") ?></span>
            </div>

            <div class="graph-holder">
                <div class="dashboard_stats"></div>
                <div class="lines">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
        </div>
        <div class="stats_box_footer">
                <span class="sbf-item active">
                    <span class="mai-eye"></span>
                    Views
                    <span class="sbf-item-n"><?php print $views_count; ?></span>
                </span>
            <span class="sbf-item">
                    <span class="mai-user3"></span>
                    Visitors
                    <span class="sbf-item-n"><?php print $visits_count; ?></span>
                </span>
            <span class="sbf-item">
                    <span class="mai-order"></span>
                    Orders
                    <span class="sbf-item-n"><?php print $orders_count; ?></span>
                </span>
            <span class="sbf-item">
                    <span class="mai-comment"></span>
                    <?php _e('Comments'); ?>
                    <span class="sbf-item-n"><?php print $comments_count; ?></span>
            </span>
        </div>
    </div>
</div>
