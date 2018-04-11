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
    mw.admin.__statdata = <?php print json_encode($graph_data);   ?>;

</script>
<script>
    function mw_stats_period_switch($module_id,$period) {
        if(typeof(mw_stats_period_switch_main)!= 'undefined'){

            mw_stats_period_switch_main($period);
        } else {
            $('#'+$module_id).attr('period',$period);
            $(".dashboard_stats").fadeOut(function () {
                mw.reload_module('#'+$module_id);
            })

        }
    }

    $( document ).ready(function() {
        $( "[data-stat='<?php print $period ?>']" ).addClass( "active" );
    });



    mw.admin.stat = mw.admin.stat || function(options){
        this.weekDays = mw._lang.weekDays.short;
        this.monthDays = mw._lang.months.short;
        this.options = options || {};
        this.data = this.options.data;
        this.element = $(this.options.element)[0];
        if(!this.data) return;
        if(!this.element) return;


        this.element.__mwstat = this;
        this.reload = function(id, options){

        }
        this.merge = function(force){
            if(this._data && !force) return this._data;
            this._data = [];
            var x, i=0, n = 0, keys = Object.keys(this.data), firstData;
            for( x in this.data) break;
            for( i ; i < this.data[x].length; i++){
                var item = {}
                for( n ; n < keys.length ; n++ ){
                    var key = keys[n];
                    item['date'] = this.data[key][i]['date_key'];
                    item[key] = this.data[key][i]['date_value'];
                }
                n = 0;
                this._data.push(item)
            }

            return this._data.reverse();
        }
        this.prepare = function(){
            this.merge(true)
        }
        this.drawSingle = function(i){
            var max = this.getMax(), i;
            max = parseInt(max.views, 10) + parseInt(max.visits, 10);
            var unique_visits = parseInt(this.merge()[i].visits, 10)
            var views = parseInt(this.merge()[i].views, 10);
            var total = unique_visits + views;

            var height_percent = (total / max) * 100;
            var unique_visits_percent = (unique_visits / total) * 100;
            var views_percent = (views / total) * 100;
            var tip = mw.lang('Unique visitors') + ': ' + unique_visits + '<br>';
            tip += mw.lang('All views') + ': ' + views + '<br>';
            tip += mw.lang('Date') + ': ' + this.merge()[i].date + '';
            var html = '<div class="mw-admin-stat-item tip" style="height:' + height_percent + '%;" data-tip="' + tip + '">';
            html += '<div class="mw-admin-stat-item-views"   style="height:' + views_percent + '%;"></div>';
            html += '<div class="mw-admin-stat-item-uniques"  style="height:' + unique_visits_percent + '%;"></div>';

            var date = new Date(this.merge()[i].date);

            html += this.createDateLabel(date);
            html += '</div>';
            return html;
        }
        this.createDateLabel = function (date) {
            var type = this.options.period, html='';
            if (type == 'daily') {
                var day = this.weekDays[date.getUTCDay()];
                html += '<div class="mw-admin-stat-item-date">' + day + '</div>';
            }
            else if(type == 'weekly'){
                var day = ((0 | date.getDate() / 7) + 1) + ' / ' + this.monthDays[date.getUTCMonth()];
                html += '<div class="mw-admin-stat-item-date">' + day + '</div>';
            }
            else if(type == 'monthly'){
                var day = this.monthDays[date.getUTCMonth()];
                html += '<div class="mw-admin-stat-item-date">' + day + '</div>';
            }
            else if(type == 'yearly'){
                var day = date.getUTCFullYear();
                html += '<div class="mw-admin-stat-item-date">' + day + '</div>';
            }
            return html;
        }
        this.draw = function(){
            var final = [];
            for (i = this.merge().length - 1; i >= 0; i--) {
                final.push(this.drawSingle(i));
            }
            return final.join('')
        }
        this.getMax = function () {
            return this.merge().reduce(function (prev, current) {
                var calc_prev = parseInt(prev.views, 10) + parseInt(prev.visits, 10);
                var calc_current = parseInt(current.views, 10) + parseInt(current.visits, 10);
                return (calc_prev > calc_current) ? prev : current
            })
        }
        this.show = function () {
            var time = 0;
            mw.$( '.mw-admin-stat-item', this.element ).each(function () {
                var el = this;
                setTimeout(function () {
                        $(el).addClass('active')
                }, time+=40)
            })
        }

        this.init = function(){
            this.prepare();
            this.element.innerHTML = this.draw()
            var scope = this;
            setTimeout(function () {
               // mw.$( scope.element ).removeClass('no-transition').height(125)
                mw.$( scope.element ).addClass('no-transition').height(125);
                scope.show()
            }, 100)

        }

        this.init();

    }

    $(document).ready(function(){
        DasboardStats = new mw.admin.stat({
            data:mw.admin.__statdata,
            element:'.dashboard_stats',
            period:"<?php print $period; ?>"
        });
    })





</script>



<div id="stats">

    <div class="mw-ui-box">
        <div class="mw-ui-box-header">
            <span><?php _e("Statistics") ?></span>

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
