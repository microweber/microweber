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
    mw.require('<?php print modules_url() ?>microweber/api/libs/apexcharts/apexcharts.min.js');
</script>

<script>
    mw.admin.__statdata = <?php print json_encode($graph_data); ?>;

    Date.prototype.getWeekNumber = function () {
        var d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
        d.setUTCDate(d.getUTCDate() - d.getUTCDay());
        var yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    };
    Date.prototype.getWeekOfMonth = function() {
        var firstWeekday = new Date(this.getFullYear(), this.getMonth(), 1).getDay();
        var offsetDate = this.getDate() + firstWeekday - 1;
        return Math.floor(offsetDate / 7);
    }

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







    var series = [];

    var count = 0;
    $.each(mw.admin.__statdata, function (key, val) {
        count ++;

        var item = {
            name: key,
            data:[]
        };
        if (count === 1) {
            item.type = "area";
        } else if(count === 2) {

        }
        $.each(val, function (i) {
            item.data.push([this.date_key, parseInt(this.date_value)])
        });
        series.push(item)
    });

    var formatter = {
        currentYear: null,
        currentMonth: null,
        yearly: function (val, timestamp) {
            var date = new Date(timestamp);
            var month = date.getMonth();
            return date.getFullYear();
        },
        weekly: function (val, timestamp) {
            var date = new Date(timestamp);
            var month = date.getMonth();
            var year = date.getFullYear();

            var week = date.getWeekOfMonth();
            var build = /*'Week ' + */week;
            if (month !== this.currentMonth1) {
                this.currentMonth = month;
                build += ('/' + mw.msg.months.short[month]);
            }
           /* if (year !== this.currentYear1) {
                this.currentYear = year;
                build += ('/' + year);
            }*/
            return build;
        },
        monthly: function (val, timestamp) {
            var date = new Date(timestamp);
            var month = date.getMonth();
            return mw.msg.months.regular[month];
        },
        daily: function (val, timestamp) {
            var date = new Date(timestamp);
            var month = date.getMonth();
            var day = date.getDate();
            day = (day < 10) ? "0" + day : day;
            var build = day + ' ' + mw.msg.months.short[month];
            var year = date.getFullYear();
            /*if (year !== this.currentYear1) {
                this.currentYear = year;
                build += ('/' + year);
            }*/
            return build;
        }
    };

    var options = {
        series: series,

        yaxis: {
            show: false,
        },
        xaxis: {
            tickAmount: Math.min(series[0].data.length, series[1].data.length),
            tickPlacement: 'between',
            type: 'datetime',
            tooltip: {
                enabled: false
            },
            labels: {
                formatter: function(val, timestamp) {
                    var type = $('.active[data-stat]').attr('data-stat');
                    return formatter[type](val, timestamp)
                }
            }

        },
        chart: {
            type: 'line',
            height: 200,
            zoom: {
                enabled: false
            }
        },
        colors: [ "#5A8DEE", "#5A8DEE", "#FDAC41" ],
        toolbar: {
            show: false
        },
        axisTicks: {
            show: false
        },
        grid: {
            show: null,
            tools: {
                download: false,
                selection: false,
                zoom: false,
                zoomin: false,
                zoomout: false,
                pan: false,
                reset: false
            }
        },
        fill: {
            type: "gradient",
            gradient: {
                inverseColors: !1,
                shade: "light",
                type: "vertical",
                gradientToColors: ["#E2ECFF", "#5A8DEE"],
                opacityFrom: .7,
                opacityTo: .55,
                stops: [0, 80, 100]
            }
        },

        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2.5,
            dashArray: [0, 8]
        },
        markers: {
            show:false,
            size: 0.5,
            radius: 0,
            strokeColors: 'rgba(255,255,255,0)',
            strokeWidth: 1,
            fillOpacity:1,
            hover: {
                size: undefined,
                sizeOffset: 6,
                strokeColors: 'rgba(255,255,255,1)',
            }
        },
        legend: {
            show: false,
            position: 'top',
            horizontalAlign: 'left'
        },
        tooltip: {
            x: {
                show: true
            }
        },
        axisBorder: {
            show: false
        },

        labels: {
            show: !0,
            style: {
                colors: "#828D99"
            }
        },


    };



    $(document).ready(function () {
        var el = document.querySelector('.dashboard_stats');
        var chart = new ApexCharts(el, options);
        chart.render();

    })
</script>


<div id="stats">
    <div class="card style-1 mb-3">
        <div class="card-header">

            <h5><i class="mdi mdi-signal-cellular-3 text-primary mr-3"></i> <strong><span><?php _e("Statistics") ?></span></strong></h5>
            <div id="stats_nav" class="nav btn-hover-style-2">
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','daily');" data-stat='daily' class="btn btn-outline-secondary btn-sm justify-content-center"><?php _e("Daily"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','weekly');" data-stat='weekly' class="btn btn-outline-secondary btn-sm justify-content-center"><?php _e("Weekly"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','monthly');" data-stat='monthly' class="btn btn-outline-secondary btn-sm justify-content-center"><?php _e("Monthly"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','yearly');" data-stat='yearly' class="btn btn-outline-secondary btn-sm justify-content-center"><?php _e("Yearly"); ?></a>
            </div>

        </div>
        <div class="card-body stat-box-content">
            <?php if($users_online){ ?>
            <div class="users-online">
                <?php print $users_online; ?>
                <span><?php _e("Users online") ?></span>
            </div>
            <?php } ?>
            <div class="graph-holder">
                <div class="dashboard_stats"></div>
                <div class="lines">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
            <hr class="thin">
            <div class="stats_box_footer">
                <div class="row d-flex justify-content-between">
                    <div class="stats-box-colorscheme col-3 col-sm d-flex align-items-center justify-content-center justify-content-sm-start">
                        <i class="mdi mdi-eye mdi-24px text-muted text-opacity-6"></i> <span class="text-primary mx-2"><?php print $views_count; ?></span> <span><?php _e('Views'); ?></span>
                    </div>

                    <div class="stats-box-colorscheme col-3 col-sm d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-account-multiple mdi-24px text-muted text-opacity-6"></i> <span class="text-primary mx-2"><?php print $visits_count; ?></span> <span><?php _e('Visitors') ?></span>
                    </div>

                    <div class="stats-box-colorscheme col-3 col-sm d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-shopping mdi-24px text-muted text-opacity-6"></i> <span class="text-primary mx-2"><?php print $orders_count; ?></span> <span><?php _e('Orders'); ?></span>
                    </div>

                    <div class="stats-box-colorscheme col-3 col-sm d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-comment-account mdi-24px text-muted text-opacity-6"></i> <span class="text-primary mx-2"><?php print $comments_count; ?></span> <span><?php _e('Comments'); ?></span>
                    </div>

                    <div class="stats-box-colorscheme col-12 col-sm d-flex align-items-center  justify-content-center justify-content-sm-end">
                        <a class="btn btn-outline-secondary btn-sm btn-rounded show-more-stats"><?php _e('Show more'); ?></a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
