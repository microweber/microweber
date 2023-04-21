<?php
if (!user_can_access('module.comments.index')) {
    return;
}

$comments_data = array(
    'rel_type' => 'content',
    'group_by' => 'rel_id',
    'count' => true,
);

$ccount = get_comments($comments_data);

?>


<div class="card mb-4 dashboard-admin-cards">
    <div class="card-body px-xxl-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="dashboard-icons-wrapper wrapper-icon-emails">
                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/admin-dashboard-comments.png" alt="messages">
            </div>

            <div class="row">
                <p> <?php _e("Last comments") ?></p>

                <h5 class="dashboard-numbers">
                      <?php  print $ccount; ?>
                </h5>
            </div>
        </div>


        <div>
            <a href="<?php print admin_url('module/view?type=contact_form'); ?>" class="btn btn-link text-dark"><?php _e('View'); ?></a>
        </div>

    </div>
</div>
