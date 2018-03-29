<?php
only_admin_access();


?>

<?php
$comments_data = array(
    'order_by' => 'created_at desc',
    'limit' => '30',
);
$comments = get_comments($comments_data);

if (is_array($comments)) {


    $ccount = count($comments);

} else {
    $ccount = 0;
}
?>
<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-thunder"></i> <?php _e("Latest activity") ?></span>
        <a href="<?php print admin_url('view:content/action:posts'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline"><?php _e("Go to posts"); ?></a>
        <a href="#" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong><?php print $ccount; ?></strong> <?php print _e('New comments'); ?></a>
    </div>
    <div class="dr-list">
        <?php
        if (is_array($comments)) {
            foreach ($comments as $comment) {
                $params = array(
                    'id' => $comment['id']
                );
                $post = get_content($params);
                $post = $post[0];
                $comments_data = array(
                    'order_by' => 'created_at desc',
                    'rel_id' => $post['id']
                );
                $postComments = get_comments($comments_data);
                ?>

                <?php

                //var_dump($postComments); ?>
                <div class="dr-item">
                    <div class="dr-item-table">
                        <table>
                            <tr>
                                <td class="dr-item-image">
                                    <span style="background-image: url(<?php print pixum(100, 100); ?>)"></span>
                                </td>

                                <td class="dr-item-title">
                                    <?php print $post['title']; ?>
                                </td>
                                <td class="dr-item-price">
                                    33 <span class="mai-comment"></span> <?php echo _e('comments'); ?>
                                </td>
                                <td class="dr-item-date">
                                    10 days ago
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
            <?php }
        } ?>

    </div>
</div>