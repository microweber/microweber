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
        $("[data-stat='<?php print $period ?>']").addClass("active").attr("checked", true);
        $("[data-stat='<?php print $period ?>']").next().addClass("active").attr("checked", true);
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
   <div class="card mb-4">
       <div class="card-body mb-3">
           <div class="card-header p-0 justify-content-between">


               <div class="dashboard-admin-cards d-flex align-items-center">
                   <div class="dashboard-icons-wrapper wrapper-icon-statistics">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="40" viewBox="0 96 960 960" width="40"><path d="M113.274 902.667q-30.274 0-51.774-21.559T40 829.275Q40 799 61.496 777.501q21.496-21.5 51.682-21.5 5.488 0 10.322.333 4.833.333 11.833 2.333l194.001-194q-2-7-2.334-11.834-.333-4.833-.333-10.321 0-30.186 21.559-51.682 21.559-21.496 51.833-21.496 30.274 0 51.774 21.589 21.5 21.59 21.5 51.906 0 1.838-2.667 21.838L578 672q7-2 11.833-2.333 4.834-.333 10.167-.333t10.167.333Q615 670 622 672l154-154q-2-7-2.333-11.833-.333-4.834-.333-10.322 0-30.186 21.559-51.682 21.559-21.496 51.833-21.496 30.274 0 51.774 21.559t21.5 51.833q0 30.274-21.496 51.774t-51.682 21.5q-5.488 0-10.322-.333-4.833-.334-11.833-2.334L670.666 720.667q2 7 2.334 11.833.333 4.834.333 10.322 0 30.186-21.559 51.682Q630.215 816 599.941 816q-30.274 0-51.774-21.496t-21.5-51.682q0-5.488.333-10.322.334-4.833 2.334-11.833L422 613.333q-7 2-11.833 2.334-4.834.333-10.334.333Q398 616 378 613.333L184 807.334q2 7 2.333 11.833.333 4.833.333 10.322 0 30.186-21.559 51.682-21.559 21.496-51.833 21.496ZM160 474.333l-21.835-47.165L91 405.333l47.165-21.835L160 336.333l21.835 47.165L229 405.333l-47.165 21.835L160 474.333Zm440-47.001-33.144-71.521-71.522-33.145 71.522-33.144L600 218l33.144 71.522 71.522 33.144-71.522 33.145L600 427.332Z"/></svg>
                   </div>

                   <div class="row" style="padding-top: 10px !important; padding-bottom: 10px !important;">
                       <p> <?php _e("Statistics") ?></p>

                       <div class="d-flex">
                           <h5 class="dashboard-numbers">
                               <?php  print $users_online; ?>

                           </h5>

                           <p class="mb-0 ms-2">Online</p>
                       </div>

                   </div>
               </div>


               <div class="form-selectgroup gap-2">
                   <label class="form-selectgroup-item" onclick="mw_stats_period_switch('<?php print $module_id; ?>','daily');" >
                       <input type="radio" data-stat='daily'  class="form-selectgroup-input">
                       <span class="mw-admin-action-links mw-adm-liveedit-tabs"><?php _e("Daily"); ?></span>
                   </label>

                   <label class="form-selectgroup-item" onclick="mw_stats_period_switch('<?php print $module_id; ?>','weekly');" >
                       <input type="radio" data-stat='weekly'  class="form-selectgroup-input">
                       <span class="mw-admin-action-links mw-adm-liveedit-tabs"><?php _e("Weekly"); ?></span>
                   </label>
                   <label class="form-selectgroup-item" onclick="mw_stats_period_switch('<?php print $module_id; ?>','monthly');" >
                       <input type="radio" data-stat='monthly'  class="form-selectgroup-input">
                       <span class="mw-admin-action-links mw-adm-liveedit-tabs"><?php _e("Monthly"); ?></span>
                   </label>

               </div>



           </div>
           <?php if($users_online){ ?>
               <div class="users-online">
                   <?php print $users_online; ?>
                   <span><?php _e("Users online") ?></span>
               </div>
           <?php } ?>
           <div class="graph-holder" style="min-height: 215px;">
               <div class="dashboard_stats"></div>
               <div class="lines">
                   <div class="line"></div>
                   <div class="line"></div>
                   <div class="line"></div>
               </div>
           </div>



           <div class="stats_box_footer">
               <div class="d-flex flex-wrap justify-content-between">
                   <div class="stats-box-colorscheme d-flex align-items-center justify-content-center justify-content-sm-start mx-3" data-bs-toggle="tooltip" aria-label="Views" data-bs-original-title="Views">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m438 630 198-198-57-57-141 141-56-56-57 57 113 113Zm42 240q122-112 181-203.5T720 504q0-109-69.5-178.5T480 256q-101 0-170.5 69.5T240 504q0 71 59 162.5T480 870Zm0 106Q319 839 239.5 721.5T160 504q0-150 96.5-239T480 176q127 0 223.5 89T800 504q0 100-79.5 217.5T480 976Zm0-472Z"/></svg>
                       <span class="  mx-2" style="font-weight: 600;"><?php print $views_count; ?>
                   </div>

                   <div class="stats-box-colorscheme d-flex align-items-center justify-content-center mx-3" data-bs-toggle="tooltip" aria-label="Visitors" data-bs-original-title="Visitors">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480 576q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160 896V784q0-34 17.5-62.5T224 678q62-31 126-46.5T480 616q66 0 130 15.5T736 678q29 15 46.5 43.5T800 784v112H160Zm80-80h480v-32q0-11-5.5-20T700 750q-54-27-109-40.5T480 696q-56 0-111 13.5T260 750q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560 416q0-33-23.5-56.5T480 336q-33 0-56.5 23.5T400 416q0 33 23.5 56.5T480 496Zm0-80Zm0 400Z"/></svg>
                       <span class="  mx-2" style="font-weight: 600;"><?php print $visits_count; ?>
                   </div>

<!--                   <div class="stats-box-colorscheme col-6 col-sm d-flex align-items-center justify-content-center">-->
<!--                       <i class="mdi mdi-shopping mdi-24px text-muted text-opacity-6"></i> <span class="  mx-2">--><?php //print $orders_count; ?><!--</span> <span>--><?php //_e('Orders'); ?><!--</span>-->
<!--                   </div>-->
<!---->
<!--                   <div class="stats-box-colorscheme col-6 col-sm d-flex align-items-center justify-content-center">-->
<!--                       <i class="mdi mdi-comment-account mdi-24px text-muted text-opacity-6"></i> <span class="  mx-2">--><?php //print $comments_count; ?><!--</span> <span>--><?php //_e('Comments'); ?><!--</span>-->
<!--                   </div>-->

                   <div class="stats-box-colorscheme col-12 col-sm d-flex align-items-center  justify-content-center justify-content-sm-end">
                       <a class="btn btn-link show-more-stats text-capitalize" style="padding: 0 !important;"><?php _e('Show more'); ?></a>
                   </div>
               </div>

           </div>
       </div>
   </div>
</div>
