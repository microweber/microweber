<?php
/* Overlay with Color */
$is_color = get_option('is_color', $params['id']);
if ($is_color === null OR $is_color === false OR $is_color == '') {
    $is_color = false;
}
?>
<?php if ($is_color != false): ?>
    <style>
        <?php print '#' . $params['id'] ?>  .imagebg:before {
            background: <?php print $is_color; ?>;
        }
    </style>
<?php endif; ?>
