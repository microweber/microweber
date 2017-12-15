<?php

/*

type: layout

name: Posts - Footer

description: Skin 5

*/
?>

<div class="">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <div class="row tab-post" style="margin-bottom: 10px;">
                <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <a href="<?php print $item['link'] ?>">
                            <img src="<?php print thumbnail($item['image'], 50); ?>" width="50" alt=""/>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="col-md-10 col-sm-10 col-xs-9">
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                        <a href="<?php print $item['link'] ?>" class="tab-post-link"><?php print $item['title'] ?></a>
                    <?php endif; ?>
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                        <small><?php print date('M d, Y', strtotime($item['created_at'])); ?></small>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
