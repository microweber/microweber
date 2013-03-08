<?  //$rand = uniqid(); ?>
<script  type="text/javascript">
    $r1 = '<? print $config['url_to_module'] ?>raphael-min.js';
    mw.require($r1,1);

    $r2 = '<? print $config['url_to_module'] ?>morris.min.js';
    mw.require($r2,1);
 </script>
<? $v = get_visits(); ?>
<? $v_weekly = get_visits('weekly');
$v_monthly = get_visits('monthly');
//print_r($v_monthly);
 ?>

<div id="stats">
  <h2>Traffic Statistic</h2>
  <ul id="stats_nav">
    <li><a href="javascript:;" data-stat='day' class="active">Daily</a></li>
    <li><a href="javascript:;" data-stat='week'>Weekly</a></li>
    <li><a href="javascript:;" data-stat='month'>Monthly</a></li>
  </ul>
  <div class="dashboard_stats" id="stats_{rand}"></div>
</div>
<div class="vSpace">&nbsp;</div>

 
 
<module="site_stats/dashboard_last" id="stats_dashboard_last" />
<script  type="text/javascript">

var curr_users = mwd.getElementById('real_users_online');
var curr_users_numb = parseFloat(curr_users.innerHTML);
var i = -1;
_countEm = 100;

_rendvisits = function(){
  i++;
  _countEm > 0 ? _countEm--:'';
  if(curr_users_numb>=i){
    setTimeout(function(){
     curr_users.innerHTML = i;
     _rendvisits();
    }, _countEm);
  }
}

curr_users_numb <= 10 ? _rendvisits() : '';



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
            {"period": "<? print $item['visit_date'] ?>", "total_visits": <? print $item['total_visits'] ?>, "unique_visits": <? print $item['unique_visits'] ?>} <? if(isset($v[$i+1])) : ?>, <? endif; ?>
          <?php $i++; endforeach; ?>
        <?php endif; ?>
    ],
    week:[
        <?php if(!empty($v_weekly)): ?>
          <?php $i=0; foreach($v_weekly as $item) : ?>
            {"period": "<? print $item['visit_date'] ?>", "total_visits": <? print $item['total_visits'] ?>, "unique_visits": <? print $item['unique_visits'] ?>} <? if(isset($v_weekly[$i+1])) : ?>, <? endif; ?>
          <?php $i++; endforeach; ?>
        <?php endif; ?>
    ],
    month:[
        <?php if(!empty($v_monthly)): ?>
          <?php $i=0; foreach($v_monthly as $item) : ?>
            {"period": "<? print $item['visit_date'] ?>", "total_visits": <? print $item['total_visits'] ?>, "unique_visits": <? print $item['unique_visits'] ?>} <? if(isset($v_monthly[$i+1])) : ?>, <? endif; ?>
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
