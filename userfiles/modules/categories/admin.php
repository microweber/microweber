<?php if(isset($params['live_edit_sidebar'])): ?>
<?php include_once($config['path_to_module'].'admin_live_edit.php'); ?>
<?php elseif(isset($params['backend'])): ?>
<?php include_once($config['path_to_module'].'admin_backend.php'); ?>
<?php else: ?>
<?php include_once($config['path_to_module'].'admin_live_edit.php'); ?>
<?php endif; ?>
<script>

    $(document).ready(function () {


        mw.options.form('#<?php print $params['id']; ?>', function () {
            if (mw.notification) {
                mw.notification.success('<?php _ejs('Settings are saved') ?>');
            }
        });


    });

</script>

