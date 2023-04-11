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

<div class="card mb-4">
    <div class="card-header justify-content-between">
        <h5 class="card-title"><i class="mdi mdi-comment-account text-primary mr-3"></i> <strong><?php _e("Last comments") ?></strong></h5>
        <div><a href="<?php print admin_url('module/view?type=comments'); ?>" class="btn btn-primary btn-sm"><?php print $ccount; ?>  <?php _e('New comments'); ?></a></div>
    </div>
    <div class="card-body">
        <module type="comments/manage"  id="dashboard-recent-comments" no_paging="true" limit="5" >
    </div>
</div>
