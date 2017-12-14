<?php

/*

type: layout

name: Blog - Posts

description: Skin 2

*/
?>

<?php if (!empty($data)): ?>
    <?php foreach ($data as $item): ?>
        <div class="blog-item blog-item-1" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
            <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                <a href="<?php print $item['link'] ?>" itemprop="url">
                    <img src="<?php print thumbnail($item['image'], 1200); ?>" alt="">
                </a>
            <?php endif; ?>

            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                <a href="<?php print $item['link'] ?>" itemprop="url"><h4><?php print $item['title'] ?></h4></a>
            <?php endif; ?>

            <?php if ($item['created_by']): ?>
                <div class="blog-item__author">
                    <span><em>by</em></span>
                    <span class="h6"><?php print user_name($item['created_by']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                <p itemprop="description"><?php print $item['description'] ?></p>
            <?php endif; ?>
            <hr>
        </div>

        <div class="clearfix"></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <div class="pagination-container">
        <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
    </div>
<?php endif; ?>
