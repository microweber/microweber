<?php
only_admin_access();





$display = new \Microweber\SiteStats\Controllers\Admin();

return $display->index($params);

?>

<module type="site_stats/dashboard_graph" />
<module type="site_stats/dashboard_recent_orders" />
<module type="site_stats/dashboard_recent_comments" />
