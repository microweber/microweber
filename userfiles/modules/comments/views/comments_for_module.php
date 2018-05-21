<?php

return print('This file is deprecated ' . __FILE__);


only_admin_access();
$comments_data = array();
if (isset($params['rel_type'])) {
    $comments_data['rel_type'] = $params['rel_type'];
}

if (isset($params['rel_id'])) {
    $comments_data['rel_id'] = $params['rel_id'];
}


$comments = get_comments($comments_data);


$item = module_info($config['module_name']);


$moderation_is_required = get_option('require_moderation', 'comments') == 'y';

?>

<div class="comment-post">
    <div class="comment-info-holder" content-id="<?php print $item['id']; ?>"
         onclick="mw.adminComments.toggleMaster(this, event)"> <span class="img"> <img
                    src="<?php print thumbnail(($item['icon']), 48, 48); ?>" alt=""/>
            <?php // $new = get_comments('count=1&is_moderated=n&rel=content&rel_id='.$content_id);
            $comments_data2 = $comments_data;
            $comments_data2['count'] = 1;
            $comments_data2['is_new'] = 'y';
            $new = get_comments($comments_data2);


            $comments_data3 = $comments_data;
            $comments_data3['group_by'] = 'rel,rel_id,from_url';

            $links = get_comments($comments_data3);

            ?>
            <?php if ($new > 0) { ?>
                <span class="comments_number"><?php print $new; ?></span>
            <?php } ?>
    </span>
        <div class="comment-post-content-side">
            <?php if (isset($comments[0]) and isset($comments[0]['comment_subject']) and trim($comments[0]['comment_subject']) != ''): ?>
                <h3><a href="javascript:;" class="mw-ui-link"><?php print $comments[0]['comment_subject'] ?></a></h3>

            <?php else: ?>
                <h3><a href="javascript:;" class="mw-ui-link"><?php print $item['name'] ?></a></h3>
            <?php endif; ?>

            <?php if (is_array($links)): ?>

                <?php foreach ($links as $link): ?>
                    <a class="comment-post-url"
                       href="<?php print $link['from_url'] ?>"><?php print $link['from_url'] ?></a><br/>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="comments-holder">
        <?php include($config["path_to_module"] . 'admin_items.php'); ?>
    </div>
</div>
<?php if (!empty($comments)): ?>
    <div class="comments-show-btns"><span class="mw-ui-btn comments-show-all"
                                          onclick="mw.adminComments.display(event,this, 'all');"><?php print ($count_old + $count_new); ?><?php _e('All'); ?></span><?php if ($count_new > 0): ?>
            <span class="mw-ui-btn mw-ui-btn-green comments-show-new"
                  onclick="mw.adminComments.display(event,this, 'new');"><?php print $count_new; ?><?php _e("New"); ?></span> <?php endif; ?>
    </div>
<?php endif; ?>
</div>
