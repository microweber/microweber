<?php

use Microweber\View;

include __DIR__ . "/_admin_data.php";


$view_file = __DIR__ . DS . 'views/admin_add_shipping.php';
$view = new View($view_file);
$view->assign('params', $params);
$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);
$view->assign('has_data', $has_data);
print $view->display();


$view_file = __DIR__ . DS . 'views/admin_table_list.php';
$view = new View($view_file);
$view->assign('params', $params);
$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);
$view->assign('data', $data_active);
$view->assign('data_key', 'data_active');
$view->assign('active_or_disabled', 'active');

print $view->display();

$view_file = __DIR__ . DS . 'views/admin_table_list.php';
$view = new View($view_file);
$view->assign('params', $params);
$view->assign('config', $config);
$view->assign('countries', $countries);
$view->assign('countries_used', $countries_used);

$view->assign('data', $data_disabled);
$view->assign('data_key', 'data_disabled');
$view->assign('active_or_disabled', 'disabled');
print $view->display();


?>
<?php
/*
<script>
    $(document).ready(function () {



        $('.toggle-item', '#<?php print $params['id'] ?>' ).on('click', function (e) {

            if ($(e.target).hasClass('toggle-item') || (e.target).nodeName == 'TD') {
                $(this).find('.hide-item').toggleClass('hidden');
                $(this).closest('.toggle-item').toggleClass('closed-fields');
                e.stopPropagation();
                e.preventDefault();
            }



        });
    });
</script>*/

?>
