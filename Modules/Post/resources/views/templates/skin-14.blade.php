<?php

/*

type: layout

name: Posts 14

description: Posts 14

*/
?>

<div class="row blog-posts-3">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="name">' . $category['title'] . '</small> ';
                }
            }
            ?>
            <div class="mx-auto mx-md-0 col-12 col-lg-6 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="h-100 d-flex flex-column">
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                    <a itemprop="url" href="<?php print $item['link'] ?>" class="d-block px-md-0 px-2">
                        <div class="img-as-background">
                            <img loading="lazy" itemprop="image" src="<?php print $item['image']; ?>" style="top: unset!important; position: relative !important;"/>
                        </div>
                    </a>
                    <?php endif; ?>


                    <div class="pt-4 pb-3">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="  px-md-0 px-2"><h4 class="text-start text-left"  itemprop="name"><?php print $item['title'] ?></h4></a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p itemprop="description"><?php print $item['description'] ?></p>
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
