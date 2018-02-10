<?php

$v = get_visits();
$v_weekly = get_visits('weekly');
$v_monthly = get_visits('monthly');

if (!isset($params['subtype'])) {
    $params['subtype'] = 'table';
}

if ($params['subtype'] == 'graph') {
    $last_page_front = session_get('last_content_id');

    if ($last_page_front == false) {
        if (isset($_COOKIE['last_page'])) {
            $last_page_front = $_COOKIE['last_page'];
        }
    }

    if ($last_page_front != false) {
        // $past_page = site_url($last_page_front);

        $cont_by_url = mw()->content_manager->get_by_id($last_page_front, true);

        if (isset($cont_by_url) and $cont_by_url == false) {
            $past_page = get_content("order_by=updated_at desc&limit=1");
            $past_page = mw()->content_manager->link($past_page[0]['id']);
        } else {
            $past_page = mw()->content_manager->link($last_page_front);
        }

    } else {
        $past_page = get_content("order_by=updated_at desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);

    }

    ?>

    <div id="stats">



        <div class="mw-ui-box">
            <div class="mw-ui-box-header">
                <span><?php _e("Statistics") ?></span>
                <div id="stats_nav">
                    <a href="javascript:;" data-stat='day' class="mw-ui-btn mw-ui-btn-outline active"><?php _e("Daily"); ?></a>
                    <a href="javascript:;" data-stat='week' class="mw-ui-btn mw-ui-btn-outline "><?php _e("Weekly"); ?></a>
                    <a href="javascript:;" data-stat='month' class="mw-ui-btn mw-ui-btn-outline "><?php _e("Monthly"); ?></a>
                </div>
                <div class="stats-legend">
                    <span class="stats-legend-views"><?php _e("views") ?></span>
                    <span class="stats-legend-visitors"><?php _e("visitors") ?></span>
                </div>
            </div>
            <div class="mw-ui-box-content">
                <div class="users-online">
                    <?php
                        $users_online = get_visits('users_online');
                        print intval($users_online);
                    ?>
                    <span><?php _e("Users online") ?></span>
                </div>
                <div class="dashboard_stats" id="stats_{rand}"></div>
            </div>
            <div class="stats_box_footer">
                <span class="sbf-item active">
                    <span class="mai-eye"></span>
                    Views
                    <span class="sbf-item-n">41,099</span>
                </span>
                <span class="sbf-item">
                    <span class="mai-user3"></span>
                    Visitors
                    <span class="sbf-item-n">41,099</span>
                </span>
                <span class="sbf-item">
                    <span class="mai-order"></span>
                    Orders
                    <span class="sbf-item-n">41,099</span>
                </span>
                <span class="sbf-item">
                    <span class="mai-comment"></span>
                    Comments
                    <span class="sbf-item-n">41,099</span>
                </span>
            </div>
        </div>



    </div>


    <style>
    .dashboard_stats{
        height: 155px;
        vertical-align: bottom;
        margin-bottom: 20px
    }

        .mw-admin-stat-item{
            display: inline-block;
            width: 30px;
            cursor: default;
            margin:0 12px;
        }
        .mw-admin-stat-item-views{
            background: #0086db
        }
        .mw-admin-stat-item-uniques{
            background: #005c97
        }

        .mw-admin-stat-item:last-child .mw-admin-stat-item-views{
            background: #ff9c00
        }
        .mw-admin-stat-item:last-child .mw-admin-stat-item-uniques{
            background: #df642b
        }

    </style>


    <script type="text/javascript">


        mw.stat = {
            weekDays: [
                '<?php _e("Sun"); ?>' ,
                '<?php _e("Mon"); ?>' ,
                '<?php _e("Tue"); ?>' ,
                '<?php _e("Wed"); ?>' ,
                '<?php _e("Thu"); ?>' ,
                '<?php _e("Fri"); ?>' ,
                '<?php _e("Sat"); ?>'
            ],
            draw: function (data, type) {
                if (typeof(data[0]) != 'undefined') {
                    var html = mw.stat.html(data, type);
                    mw.$('.dashboard_stats').html(html)
                }
            },
            getMax:function (data) {
                return data.reduce(function(prev, current) {
                    var calc_prev = parseInt(prev.total_visits, 10)+ parseInt(prev.unique_visits, 10);
                    var calc_current = parseInt(current.total_visits, 10) + parseInt(current.unique_visits, 10);
                    return (calc_prev > calc_current) ? prev : current
                })
            },
            html:function (idata, type) {
                data = idata.reverse();
                data = data.slice(0, Math.min(13, data.length));
                var max = mw.stat.getMax(data), i, final = [];
                max =  parseInt(max.total_visits, 10) + parseInt(max.unique_visits, 10);
                for( i = data.length-1; i >= 0; i--){
                    var unique_visits = parseInt(data[i].unique_visits, 10)
                    var views = parseInt(data[i].total_visits, 10);
                    var total = unique_visits + views;

                    var height_percent = (total/max) * 100;
                    var unique_visits_percent = (unique_visits/total) * 100;
                    var views_percent = (views/total) * 100;
                    var tip = 'Unique visitors: ' + unique_visits + '<br>';
                    tip += 'All views: ' + views + '<br>';
                    tip += 'Date: ' + data[i].visit_date + '';
                    var html = '<div class="mw-admin-stat-item tip" style="height:'+height_percent+'%;" data-tip="'+tip+'">';

                    html += '<div class="mw-admin-stat-item-views" style="height:'+views_percent+'%;"></div>';
                    html += '<div class="mw-admin-stat-item-uniques" style="height:'+unique_visits_percent+'%;"></div>';

                    var date =  new Date(data[i].visit_date)
                    if( type == 'day' ){
                        var day =  mw.stat.weekDays[date.getUTCDay()];
                        html +='<div class="mw-admin-stat-item-date">'+day+'</div>' ;
                    }

                    html += '</div>';

                    final.push(html);

                }
                return final.join('')
            }
        }


        mw.statdatas = {
            day:<?php print json_encode($v); ?>,
            week:<?php print json_encode($v_weekly); ?>,
            month:<?php print json_encode($v_monthly); ?>
        }


        $(document).ready(function () {

            mw.$("#stats_nav a").click(function () {
                var el = $(this);
                if (!el.hasClass("active")) {
                    mw.$("#stats_nav a").removeClass("active");
                    el.addClass("active");
                    var data = el.dataset("stat");
                    mw.stat.draw(mw.statdatas[data], data);
                }
            });

            mw.stat.draw(mw.statdatas.day, 'day');

        });

    </script>
<?php } else if (isset($params['user-sid']) and $params['subtype'] == 'quick') { ?>
    <?php $users_last5 = get_visits_for_sid($params['user-sid']);

    ?>

<?php }  ?>