<?php
must_have_access();

$mw_notif = (url_param('mw_notif'));
if ($mw_notif != false) {
    $mw_notif = mw()->notifications_manager->read($mw_notif);
}
?>

<?php if (is_array($mw_notif) and isset($mw_notif['rel_id']) and $mw_notif['rel_id'] != 0): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            window.location.href = '<?php echo route('admin.order.show', $mw_notif['rel_id']); ?>';
        });
    </script>
<?php else : ?>
    <?php
    $here = dirname(__FILE__);
    $here = $here . DS . 'admin_views' . DS;
    $active_action = url_param('action'); ?>

    <?php $is_shop = 'y'; ?>
    <?php
    if ($active_action != false) {

        $active_action = sanitize_path($active_action);

        $vf = $here . $active_action . '.php';

        if (is_file($vf)) {
            $display_file = ($vf);
        }
    }
    ?>

    <?php if (isset($display_file) and is_file($display_file)): ?>
        <?php include($display_file); ?>
    <?php else : ?>
        <module type="content/backend" id="mw-content-backend" is_shop="y"/>
    <?php endif; ?>
<?php endif; ?>
