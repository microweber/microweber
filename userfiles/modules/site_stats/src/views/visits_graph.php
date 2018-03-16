<?php

//
//d($visits_daily);
//d($visits_weekly);
//d($visits_monthly);
//d($params);
$module_id = $params['id'];
$period = 'daily';

if($params['period']){
    $period = $params['period'];

}
?>


<script>
    function mw_stats_period_switch($module_id,$period) {
        $('#'+$module_id).attr('period',$period);
        mw.reload_module('#'+$module_id);
    }

    $( document ).ready(function() {
        $( "[data-stat='<?php print $period ?>']" ).addClass( "active" );

    });



</script>




<div id="stats">

    <div class="mw-ui-box">
        <div class="mw-ui-box-header">
            <span><?php _e("Statistics") ?></span>

            <div id="stats_nav">
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','daily');" data-stat='daily'
                   class="mw-ui-btn mw-ui-btn-outline"><?php _e("Daily"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','weekly');" data-stat='weekly' class="mw-ui-btn mw-ui-btn-outline "><?php _e("Weekly"); ?></a>
                <a href="javascript:mw_stats_period_switch('<?php print $module_id; ?>','monthly');" data-stat='monthly'
                   class="mw-ui-btn mw-ui-btn-outline "><?php _e("Monthly"); ?></a>
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
            <div class="dashboard_stats"></div>
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
                    Comments
                    <span class="sbf-item-n"><?php print $comments_count; ?></span>
                </span>
        </div>
    </div>
</div>

<pre><?php

    print_R($graph_data)
    ?></pre>
<?php return; ?>

<script type="text/javascript">
    mw.statdatas = {
        day:<?php print json_encode($visits_daily); ?>,
        week:<?php print json_encode($visits_weekly); ?>,
        month:<?php print json_encode($visits_monthly); ?>
    }

</script>
<script type="text/javascript">


    mw.stat = {
        weekDays: [
            '<?php _e("Sun"); ?>',
            '<?php _e("Mon"); ?>',
            '<?php _e("Tue"); ?>',
            '<?php _e("Wed"); ?>',
            '<?php _e("Thu"); ?>',
            '<?php _e("Fri"); ?>',
            '<?php _e("Sat"); ?>'
        ],
        draw: function (data, type) {
            if (typeof(data[0]) != 'undefined') {
                var html = mw.stat.html(data, type);
                mw.$('.dashboard_stats').html(html)
            }
        },
        getMax: function (data) {
            return data.reduce(function (prev, current) {
                var calc_prev = parseInt(prev.total_visits, 10) + parseInt(prev.unique_visits, 10);
                var calc_current = parseInt(current.total_visits, 10) + parseInt(current.unique_visits, 10);
                return (calc_prev > calc_current) ? prev : current
            })
        },
        html: function (idata, type) {
            data = idata.reverse();
            data = data.slice(0, Math.min(13, data.length));
            var max = mw.stat.getMax(data), i, final = [];
            max = parseInt(max.total_visits, 10) + parseInt(max.unique_visits, 10);
            for (i = data.length - 1; i >= 0; i--) {
                var unique_visits = parseInt(data[i].unique_visits, 10)
                var views = parseInt(data[i].total_visits, 10);
                var total = unique_visits + views;

                var height_percent = (total / max) * 100;
                var unique_visits_percent = (unique_visits / total) * 100;
                var views_percent = (views / total) * 100;
                var tip = 'Unique visitors: ' + unique_visits + '<br>';
                tip += 'All views: ' + views + '<br>';
                tip += 'Date: ' + data[i].visit_date + '';
                var html = '<div class="mw-admin-stat-item tip" style="height:' + height_percent + '%;" data-tip="' + tip + '">';

                html += '<div class="mw-admin-stat-item-views" style="height:' + views_percent + '%;"></div>';
                html += '<div class="mw-admin-stat-item-uniques" style="height:' + unique_visits_percent + '%;"></div>';

                var date = new Date(data[i].visit_date)
                if (type == 'day') {
                    var day = mw.stat.weekDays[date.getUTCDay()];
                    html += '<div class="mw-admin-stat-item-date">' + day + '</div>';
                }

                html += '</div>';
                final.push(html);
            }
            return final.join('')
        }
    }


    $(document).ready(function () {

        mw.$("#stats_nav a").click(function () {
            var el = $(this);

            if (!el.hasClass("active")) {
                mw.$('.dashboard_stats').addClass('no-transition').height(0)
                mw.$("#stats_nav a").removeClass("active");
                el.addClass("active");
                var data = el.dataset("stat");
                mw.stat.draw(mw.statdatas[data], data);

                setTimeout(function () {
                    mw.$('.dashboard_stats').removeClass('no-transition')
                    mw.$('.dashboard_stats').height(125)
                }, 100)
            }
        });

        mw.stat.draw(mw.statdatas.day, 'day');

        setTimeout(function () {
            mw.$('.dashboard_stats').height(125)
        }, 500)

    });

</script>
