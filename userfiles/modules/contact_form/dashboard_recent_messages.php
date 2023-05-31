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
    <a class="dashboard-admin-cards-a" href="<?php print admin_url('contact-form'); ?>">

        <div class="card-body px-xxl-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="dashboard-icons-wrapper wrapper-icon-messages">
                     <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="40" viewBox="0 96 960 960" width="40"><path d="M146.666 896q-27 0-46.833-19.833T80 829.334V322.666q0-27 19.833-46.833T146.666 256h666.668q27 0 46.833 19.833T880 322.666v506.668q0 27-19.833 46.833T813.334 896H146.666ZM480 601.333 146.666 385.999v443.335h666.668V385.999L480 601.333Zm0-66.666 330.667-212.001H150l330 212.001ZM146.666 385.999v-63.333 506.668-443.335Z"/></svg>
                </div>

                <div class="row">
                    <p> <?php _e("Emails") ?></p>

                    <h5 class="dashboard-numbers">
                        <?php  print $last_messages_count; ?>
                    </h5>
                </div>
            </div>

           <div>
               <a href="<?php print admin_url('contact-form'); ?>" class="btn btn-link  "><?php _e('View'); ?></a>
           </div>
        </div>
    </a>
</div>
