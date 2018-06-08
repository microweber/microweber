<?php



$comments_data = array(

    'rel_type' => 'content',
    'group_by' => 'rel_id',
    'count' => true,
);
$ccount = get_comments($comments_data);



?>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-comment"></i> <?php _e("Last comments") ?></span>
        <a href="<?php print admin_url('view:modules/load_module:comments'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong><?php print $ccount; ?></strong> <?php print _e('New comments'); ?></a>
    </div>
    <div class="dr-list">
        <module type="comments/manage"  id="dashboard-recent-comments" limit="5" >
    </div>
</div>