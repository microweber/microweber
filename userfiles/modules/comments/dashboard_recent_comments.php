<?php
only_admin_access();



?>


<?php

$limit = 1000;

if(isset($params['limit'])){
    $limit = intval($params['limit']);
}


$comments_data = array(
    'order_by' => 'created_at desc',
    'rel_type' => 'content',
    'group_by' => 'rel_id',

    'limit' => $limit,
);
$comments_for_content = get_comments($comments_data);

if (is_array($comments_for_content)) {


    $ccount = count($comments_for_content);

} else {
    $ccount = 0;
}
?>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-comment"></i> <?php _e("Last comments") ?></span>
        <a href="<?php print admin_url('view:content/action:posts'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong><?php print $ccount; ?></strong> <?php print _e('New comments'); ?></a>
    </div>
    <div class="dr-list">
        <module type="comments/manage"  id="dashboard-recent-comments" limit="5" >
    </div>
</div>