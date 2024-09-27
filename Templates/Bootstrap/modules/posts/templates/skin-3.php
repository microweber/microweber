<?php

/*

type: layout

name: Posts 3

description: Posts 3

*/
?>

<div class="row blog-posts-3">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2  ">' . $category['title'] . '</small> ';
                }
            }
            ?>
            <div class="mx-auto mx-md-0 col-sm-10 col-md-6 col-lg-4 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="h-100 d-flex flex-column">
                    <a href="<?php print $item['link'] ?>" class="btn btn-link d-block">
                        <div class="img-as-background h-350">
                            <img src="<?php print thumbnail($item['image'], 450, 450); ?>" class=" "/>
                        </div>
                    </a>

                    <div class="pt-4 pb-3">
                        <small class="mb-2 d-block"><?php echo date('d M Y', strtotime($item['created_at'])); ?></small>
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="btn btn-link text-dark mb-2"><h4 class="text-start text-left"><?php print $item['title'] ?></h4></a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p class=""><?php print $item['description'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
