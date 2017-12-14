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
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <a href="<?php print $item['link'] ?>">
                        <img src="<?php print thumbnail($item['image'], 50); ?>" width="50" alt=""/>
                    </a>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-9">
                    <a href="<?php print $item['link'] ?>" class="tab-post-link"><?php print $item['title'] ?></a>
                    <small><?php print date('M d, Y', strtotime($item['created_at'])); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
