<?php
/* Is Parallax */
$is_parallax = get_option('is_parallax', $params['id']);
if ($is_parallax === null OR $is_parallax === false OR $is_parallax == '') {
    $is_parallax = 'yes';
}
?>