<?php

/*

type: layout

name: Default

description: Grid Columns

*/
?>

<?php
$columns = get_option('columns', $params['id']);
if ($columns) {
    $columns = $columns;
} elseif (isset($params['data-columns'])) {
    $columns = $params['data-columns'];
} else {
    $columns = 'col-md-6 col-lg-4';
}


$columns_xl = get_option('columns-lg', $params['id']);
$thumb_quality = '1920';
if ($columns_xl != null OR $columns_xl != false OR $columns_xl != '') {
    if ($columns_xl == 'col-lg-12') {
        $thumbs_columns = 1;
    } else if ($columns_xl == 'col-lg-6') {
        $thumbs_columns = 2;
    } else if ($columns_xl == 'col-lg-4') {
        $thumbs_columns = 3;
    } else if ($columns_xl == 'col-lg-3') {
        $thumbs_columns = 4;
    } else if ($columns_xl == 'col-lg-2') {
        $thumbs_columns = 6;
    }

    $thumb_quality = 1920 / $thumbs_columns;
}
?>


    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="row big-news">
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $key => $item): ?>
                        <?php
                        $itemData = content_data($item['id']);
                        $itemTags = content_tags($item['id']);
                        ?>

                        <div class="<?php print $columns; ?>" data-aos="fade-up" data-aos-delay="<?php echo $key; ?>00" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                            <div class="post-holder">
                                <a href="<?php print $item['link'] ?>" itemprop="url">
                                    <div class="thumbnail-holder">
                                        <?php if ($itemTags): ?>
                                            <div class="tags">
                                                <?php foreach ($itemTags as $tag): ?>
                                                    <?php if ($key < 3): ?>
                                                        <span class="badge badge-primary"><?php echo $tag; ?></span>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                                            <div class="thumbnail" style="background: url('<?php print thumbnail($item['image'], 535, 285, true); ?>')">
                                                <!--<img src="<?php print thumbnail($item['image'], 535, 285, true); ?>"/>-->
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>

                                <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                    <a href="<?php print $item['link'] ?>"><h3 class="m-b-10"><?php print $item['title'] ?></h3></a>
                                <?php endif; ?>

                                <small><?php echo date('d M Y', strtotime($item['created_at'])); ?></small>

                                <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                    <p><?php print $item['description'] ?></p>
                                <?php endif; ?>

                                <a href="<?php print $item['link'] ?>" itemprop="url" class="button-8 m-t-20"><span>Read more</span></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
