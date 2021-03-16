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

<div class="card style-1 mb-3">
    <div class="card-header">
        <h5><i class="mdi mdi-comment-account text-primary mr-3"></i> <strong><?php _e("Last comments") ?></strong></h5>
        <div><a href="<?php print admin_url('view:modules/load_module:comments'); ?>" class="btn btn-primary btn-sm"><?php print $ccount; ?>  <?php _e('New comments'); ?></a></div>
    </div>
    <div class="card-body">
        <module type="comments/manage"  id="dashboard-recent-comments" no_paging="true" limit="5" >
    </div>
</div>
