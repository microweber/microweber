<?php


$module_id = $params['id'];
$period = 'daily';

if (isset($params['period'])) {
    $period = $params['period'];

}

?>

<script>
    function mw_stats_period_switch_main($period) {
        mw.tools.loading(50, 'fast');
        $(".dashboard_stats").fadeOut(function () {
            mw.tools.loading(90, 'fast');
            $('#<?php print $module_id ?>').attr('period', $period);
            mw.reload_module('#<?php print $module_id ?>', function(){
                mw.tools.loading(false);
            });
        })
    }

    $(document).ready(function () {
        $("[data-stat='<?php print $period ?>']").addClass("active");

    });


</script>

<?php if (get_option('stats_disabled', 'site_stats') == 0): ?>
    <module type="site_stats/admin" view="visits_graph" id="admin_dashboard_visits_graph" period="<?php print $period; ?>"/>

    <?php include __DIR__ . '/stats.php'; ?>
<?php endif; ?>

<module type="site_stats/dashboard_graph"/>





