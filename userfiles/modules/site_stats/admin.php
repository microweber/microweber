<?php  //$rand = uniqid(); ?>
<script  type="text/javascript">
    $r1 = '<?php print $config['url_to_module'] ?>raphael-min.js';
    mw.require($r1,1);

    $r2 = '<?php print $config['url_to_module'] ?>morris.min.js';
    mw.require($r2,1);
 </script>
<?php $v = get_visits(); ?>
<?php $v_weekly = get_visits('weekly');
$v_monthly = get_visits('monthly');
//print_r($v_monthly);
 ?>

<div id="stats">
  <h2><?php _e("Traffic Statistic"); ?></h2>
  <ul id="stats_nav">
    <li><a href="javascript:;" data-stat='day' class="active"><?php _e("Daily"); ?></a></li>
    <li><a href="javascript:;" data-stat='week'><?php _e("Weekly"); ?></a></li>
    <li><a href="javascript:;" data-stat='month'><?php _e("Monthly"); ?></a></li>
  </ul>
  <div class="dashboard_stats" id="stats_{rand}"></div>
</div>
<div class="vSpace">&nbsp;</div>

 
 
<module="site_stats/dashboard_last" id="stats_dashboard_last" />
<script  type="text/javascript">





mw.stat = {
  draw:function(data, obj){

    var el = obj || mwd.getElementById('stats_{rand}');
    $(el).empty().removeClass('graph-initialised');
    Morris.Line({
      element: el,
      data: data,
      lineColors:['#9A9A9A', '#E6E6E6'],
      pointStrokeColors:['#5B5B5B', '#5B5B5B'],
      pointFillColors:['#ffffff','#5B5B5B'],
      xkey: 'period',
      ykeys: ['total_visits', 'unique_visits'],
      labels: ['Total visits', 'Unique visits'],
      xLabelFormat: function(d) { return (d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear(); },
      xLabels: 'day'
    });
  }
}


mw.statdatas = {
    day:[
        <?php if(!empty($v)): ?>
          <?php $i=0; foreach($v as $item) : ?>
            {"period": "<?php print $item['visit_date'] ?>", "total_visits": <?php print $item['total_visits'] ?>, "unique_visits": <?php print $item['unique_visits'] ?>} <?php if(isset($v[$i+1])) : ?>, <?php endif; ?>
          <?php $i++; endforeach; ?>
        <?php endif; ?>
    ],
    week:[
        <?php if(!empty($v_weekly)): ?>
          <?php $i=0; foreach($v_weekly as $item) : ?>
            {"period": "<?php print $item['visit_date'] ?>", "total_visits": <?php print $item['total_visits'] ?>, "unique_visits": <?php print $item['unique_visits'] ?>} <?php if(isset($v_weekly[$i+1])) : ?>, <?php endif; ?>
          <?php $i++; endforeach; ?>
        <?php endif; ?>
    ],
    month:[
        <?php if(!empty($v_monthly)): ?>
          <?php $i=0; foreach($v_monthly as $item) : ?>
            {"period": "<?php print $item['visit_date'] ?>", "total_visits": <?php print $item['total_visits'] ?>, "unique_visits": <?php print $item['unique_visits'] ?>} <?php if(isset($v_monthly[$i+1])) : ?>, <?php endif; ?>
          <?php $i++; endforeach; ?>
        <?php endif; ?>
    ]
}


$(document).ready(function(){

  $("#users_online").dblclick(function(){
    
	   
	   
	   TEST = mw.reload_module_interval('#stats_dashboard_last', 1000);
	   
	   
	   
   });

     mw.$("#stats_nav a").click(function(){ 
      var el = $(this);
      if(!el.hasClass("active")){
        mw.$("#stats_nav a").removeClass("active");
        el.addClass("active");
        var data = el.dataset("stat");
        mw.stat.draw(mw.statdatas[data]);
      }
    });


       mw.stat.draw(mw.statdatas.day);


    $(window).resize(function(){
        //var data = $("#stats_nav a.active").dataset("stat");
        //mw.stat.draw(mw.statdatas[data]);
    });


});





</script> 
