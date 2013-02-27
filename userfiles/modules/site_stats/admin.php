<?  //$rand = uniqid(); ?>
<script  type="text/javascript">
    $r1 = '<? print $config['url_to_module'] ?>raphael-min.js';
    mw.require($r1);

    $r2 = '<? print $config['url_to_module'] ?>morris.min.js';
    mw.require($r2);
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

<div id="users_online">

  <h2>Users Online</h2>
  <div class="users_online" id="real_users_online"><? $users_online = get_visits('users_online'); print intval($users_online); ?></div>


</div>
<div id="visits_info_table">
<h2><?php _e("User Info"); ?></h2>

<? $users_last5 = get_visits('last5');

  //d($users_online) ?>
<? if(!empty($users_last5)): ?>
<table border="0" cellspacing="0" cellpadding="0" class="stats_table">
  <thead>
    <tr>
      <th scope="col">Date</th>
        <? if(function_exists('ip2country')): ?>
      <th scope="col">Country</th>
      <? endif; ?>
      <th scope="col">IP</th>
    
      <th scope="col">Last page</th>
      <th scope="col">Page views</th>
    </tr>
  </thead>
  <tbody>
    <? $i=0; foreach($users_last5 as $item) : ?>
      <tr>
        <td><? print $item['visit_date'] ?> <? print $item['visit_time'] ?></td>
          <? if(function_exists('ip2country')): ?>
        <td><? print ip2country($item['user_ip']); ?></td>
 <? endif; ?>
        <td><? print $item['user_ip'] ?></td>
       
        <td><? print $item['last_page'] ?></td>
        <td><? print $item['view_count'] ?></td>
      </tr>
    <? $i++; endforeach; ?>
  </tbody>
</table>
<? endif; ?>

</div>





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
