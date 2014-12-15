<?php  //$rand = uniqid(); ?>

<?php $v = get_visits(); ?>
<?php $v_weekly = get_visits('weekly');
$v_monthly = get_visits('monthly');
//print_r($v_monthly);


 

 ?>

 <?php

 if(!isset($params['subtype'])){
   $params['subtype'] = 'table';
 }
 
 ?>


<?php  if($params['subtype'] == 'graph'){ ?>

<?php
 $last_page_front =   session_get('last_content_id');

 if( $last_page_front == false){
	  if(isset($_COOKIE['last_page'])){
		  $last_page_front = $_COOKIE['last_page'];
	  }
 }

  if( $last_page_front  != false){
   // $past_page = site_url($last_page_front);

        $cont_by_url = mw()->content_manager->get_by_id($last_page_front , true);

    if(isset($cont_by_url) and $cont_by_url == false){
        $past_page=get_content("order_by=updated_at desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
		 $past_page = mw()->content_manager->link($last_page_front);
	}

  }
  else {
  	$past_page=get_content("order_by=updated_at desc&limit=1");
      $past_page = mw()->content_manager->link($past_page[0]['id']);

  }

 ?>

<div id="stats">
<a data-tipposition="bottom-center" data-tip="<?php _e("Go Live edit"); ?>" class="mw-ui-btn default-invert tip pull-right go-live-btn-admin" style="margin-top:11px;" href="<?php print $past_page; ?>?editmode=y"><span class="mw-icon-live"></span></a>
  <h2><?php _e("Traffic Statistic"); ?></h2>

  <div id="stats_nav" class="mw-ui-btn-nav">
    <a href="javascript:;" data-stat='day' class="mw-ui-btn active"><?php _e("Daily"); ?></a>
    <a href="javascript:;" data-stat='week' class="mw-ui-btn"><?php _e("Weekly"); ?></a>
    <a href="javascript:;" data-stat='month' class="mw-ui-btn"><?php _e("Monthly"); ?></a>
  </div>

    <div class="dashboard_stats" id="stats_{rand}"></div>

</div>



<script  type="text/javascript">
    $r1 = '<?php print $config['url_to_module'] ?>raphael-min.js';
    mw.require($r1,1);

    $r2 = '<?php print $config['url_to_module'] ?>morris.min.js';
    mw.require($r2,1);
 </script>

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
         var w = $(window).width();
         var h = $(window).height();
         setTimeout(function(){
            var w1 = $(window).width();
            var h1 = $(window).height();
            if(w==w1 && h==h1){
               var data = $("#stats_nav a.active").dataset("stat");
               mw.stat.draw(mw.statdatas[data]);
            }
         }, 299);
    });
});

</script>
<?php  } else if(isset($params['user-sid']) and $params['subtype'] == 'quick'){ ?>
<?php $users_last5 = get_visits_for_sid($params['user-sid']);

 ?>
  <?php if(!empty($users_last5)): ?>

  <span class="ipicon">IP</span> <?php print($users_last5[0]['user_ip']); ?>

  <table border="0" cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic mw-ui-table-fixed">
    <colgroup>
      <col>

      <col width="50%">
      <col width="20%">
    </colgroup>
    <thead>
      <tr>
        <th scope="col"><?php _e("Date"); ?></th>

        <th scope="col"><?php _e("Last page"); ?></th>
        <th scope="col"><?php _e("Page views"); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php $i=0; foreach($users_last5 as $item) : ?>
      <tr>

        <td class="stat-time tip" data-tip="<?php print $item['visit_date'] ?><br><?php print $item['visit_time'] ?>" data-tipposition="top-center">
           <div style="max-width: 60px;"><?php print mw('format')->ago($item['visit_date'].$item['visit_time']); ?></div>
        </td>

        <?php
            $last = explode('/',$item['last_page']);
            $size = count($last);
            if($last[$size-1] == ''){
              $last = $last[$size-2];
            }
            else{
               $last = $last[$size-1];
            }
        ?>
        <td class="stat-page"><a href="<?php print $item['last_page'] ?>" class="tip" data-tip="<?php print $item['last_page'] ?>" data-tipposition="top-center"><?php print $item['last_page']; ?></a></td>
        <td class="stat-views"><?php print $item['view_count'] ?></td>
      </tr>
      <?php $i++; endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
<?php  } else { ?>


<module="site_stats/dashboard_last" id="stats_dashboard_last" />


<?php } ?>
