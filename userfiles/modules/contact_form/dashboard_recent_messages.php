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

<div class="card mb-4 dashboard-admin-cards">
    <div class="card-body px-xxl-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="dashboard-icons-wrapper wrapper-icon-messages">
                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/admin-dashboard-emails.png" alt="messages">
            </div>

            <div class="row ms-3 ">
                <p> <?php _e("Emails") ?></p>
                <?php
                if ($last_messages_count == 0) {  ?>
                    <small>You dont have any comments yet </small>
                <?php } else { ?>
                    <h5 class="dashboard-numbers">
                        <?php  print $last_messages_count; ?>

                    </h5>
                <?php  } ?>
            </div>
        </div>


           <div>
               <a href="<?php print admin_url('module/view?type=contact_form'); ?>" class="btn btn-link text-dark"><?php _e('View'); ?></a>
           </div>

    </div>
</div>
