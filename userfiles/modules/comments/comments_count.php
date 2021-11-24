<?php
if(!isset( $params['content-id'])){
    return;
}

$comments = get_comments('rel_id=' . $params['content-id']);
?>
<?php if ($comments): ?>
    <?php echo count($comments); ?>
<?php else: ?>
    0
<?php endif; ?>
