<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php


$tn = $tn_size;
if (!isset($tn[0]) or ($tn[0]) == 150) {
    $tn[0] = 220;
}
if (!isset($tn[1])) {
    $tn[1] = $tn[0];
}


?>
<?php
$only_tn = false;


$search_keys = array('title', 'created_at', 'description', 'read_more');

if (isset($show_fields) and is_array($show_fields) and !empty($show_fields)) {
    $only_tn = true;
    foreach ($search_keys as $search_key) {
        foreach ($show_fields as $show_field) {
            if ($search_key == $show_field) {
                $only_tn = false;
            }
        }
    }


}

?>

<script>mw.moduleCSS("<?php print modules_url(); ?>posts/css/style.css"); </script>

<div class="post-list post-list-template-default">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <div class="well clearfix post-single" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="mw-ui-row">
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                        <?php if ($only_tn == false): ?>
                            <div class="mw-ui-col post-list-image">
                                <div class="mw-ui-col-container">
                                    <a href="<?php print $item['link'] ?>" itemprop="url"><img itemprop="image"
                                                                                               src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>"

                                                                                               alt=""></a>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="mw-ui-col">
                                <a href="<?php print $item['link'] ?>" itemprop="url"><img itemprop="image"
                                                                                           src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>"

                                                                                           alt=""></a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($only_tn == false): ?>


                        <div class="mw-ui-col">

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <div class="post-single-title-date">
                                    <h2 class="lead" itemprop="name"><a
                                            href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h2>
                                </div>
                            <?php endif; ?>
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                                <small class="muted"><?php _e("Date"); ?>: <span
                                        itemprop="dateCreated"><?php print $item['created_at'] ?></span></small>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                <p class="description" itemprop="description"><?php print $item['description'] ?></p>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                                <a href="<?php print $item['link'] ?>" class="mw-ui-btn">
                                    <?php $read_more_text ? print $read_more_text : _e("Continue Reading"); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
