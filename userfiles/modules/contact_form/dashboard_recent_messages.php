<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>

<script type="text/javascript">
    mw.require('<?php print $config['url_to_module']; ?>manager/forms_data_manager.js');
</script>

<?php
$last_messages_count = mw()->forms_manager->get_entires('count=true');
?>

<div class="card mb-4">
        <div class="card-header">
            <img  src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/admin-dashboard-emails.png" alt="messages">

            <p> <strong><?php _e("Emails") ?></strong></p>
            <div><a href="<?php print admin_url('module/view?type=contact_form'); ?>" class="btn btn-link text-dark"><?php _e('View'); ?></a></div>
        </div>




        <div class="card-body">
            <?php
            $last_messages = mw()->forms_manager->get_entires('limit=5');
            if (!is_array($last_messages)) {
                $last_messages = [];
            }

            $view = new \MicroweberPackages\View\View(__DIR__ . DIRECTORY_SEPARATOR . 'admin_messages_list.php');
            $view->assign('last_messages', $last_messages);
            echo $view->__toString();

            $has_messages = count($last_messages);
            ?>

            <?php if ($has_messages): ?>
                <div class="text-center">
                    <a href="<?php print admin_url('module/view?type=contact_form'); ?>" class="btn btn-link"><?php _e("See all messages"); ?></a>
                </div>
            <?php endif; ?>
        </div>
</div>
