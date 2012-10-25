<?
 

  $rand = uniqid(); ?>
<script  type="text/javascript">
$r1 = '<? print $config['url_to_module'] ?>raphael-min.js';
mw.require($r1);

$r2 = '<? print $config['url_to_module'] ?>morris.min.js';
mw.require($r2);

 </script>
<? $v = get_visits();
 //d( $v);
 ?>
<script  type="text/javascript">
$(document).ready(function(){
	
 var day_data = [
 <? if(!empty($v)): ?>
 <? $i=0; foreach($v as $item) : ?>
  {"period": "<? print $item['visit_date'] ?>", "total_visits": <? print $item['total_visits'] ?>, "unique_visits": <? print $item['unique_visits'] ?>} <? if(isset($v[$i+1])) : ?>, <? endif; ?>
 <? $i++; endforeach; ?>
   <? endif; ?>
 
 
 
  
];
Morris.Line({
  element: 'stats_<? print $rand ?>',
  data: day_data,
  lineColors:['#9A9A9A', '#E6E6E6'],
  pointStrokeColors:['#5B5B5B', '#5B5B5B'],
  pointFillColors:['#ffffff','#5B5B5B'],
  xkey: 'period',
  ykeys: ['total_visits', 'unique_visits'],
  labels: ['Total visits', 'Unique visits'],
  /* custom label formatting with `xLabelFormat` */
  xLabelFormat: function(d) { return (d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear(); },
  /* setting `xLabels` is recommended when using xLabelFormat */
  xLabels: 'day'
});
 
   
});
</script>


<div id="stats">
    <h2>Traffic Statistic</h2>
    <ul id="stats_nav">
        <li class="active"><a href="#">Daily</a></li>
        <li><a href="#">Weekly</a></li>
        <li><a href="#">Monthly</a></li>
    </ul>
    <div class="dashboard_stats" id="stats_<? print $rand ?>"></div>

</div>

users_online:
<? $users_online = get_visits('users_online'); print intval($users_online) ?>
<? $users_last5 = get_visits('last5'); 

  //d($users_online) ?>
<? if(!empty($users_last5)): ?>
<table border="1">
  <tr>
    <th scope="col">Date</th>
    <th scope="col">IP</th>
    <th scope="col">Last page</th>
    <th scope="col">Page views</th>
  </tr>
  <? $i=0; foreach($users_last5 as $item) : ?>
  <tr>
    <td><? print $item['visit_date'] ?> <? print $item['visit_time'] ?></td>
    <td><? print $item['user_ip'] ?></td>
    <td><? print $item['last_page'] ?></td>
    <td><? print $item['view_count'] ?></td>
  </tr>
  <? $i++; endforeach; ?>
</table>
<? endif; ?>
