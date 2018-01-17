<?php

/*

type: layout

name: Gallery With Categories

position: 57

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '4';
}
?>