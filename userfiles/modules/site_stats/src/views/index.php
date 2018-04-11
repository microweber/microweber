<?php


$module_id = $params['id'];
$period = 'daily';

if(isset($params['period'])){
    $period = $params['period'];

}

?>

<script>
    function mw_stats_period_switch_main($period) {
        $(".dashboard_stats").fadeOut(function () {
            $('#<?php print $module_id ?>').attr('period', $period);
            mw.reload_module('#<?php print $module_id ?>');
        })
     }

    $( document ).ready(function() {
        $( "[data-stat='<?php print $period ?>']" ).addClass( "active" );

    });



</script>



<module type="site_stats/admin" view="visits_graph"  id="admin_dashboard_visits_graph" period="<?php print $period ; ?>" />

<?php include __DIR__ . '/stats.php'; ?>

<module type="site_stats/dashboard_graph" />
<module type="site_stats/dashboard_recent_orders" />
<module type="site_stats/dashboard_recent_comments" />