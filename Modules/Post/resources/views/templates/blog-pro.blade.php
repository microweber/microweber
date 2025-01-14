<?php

/*

type: layout

name: Blog Pro

description: Blog Pro

*/
?>

<style>
    .blog-pro-category {
        padding: 5px 10px;
        background: black;
        color: white !important;
    }
</style>

<div class="row blog-posts-3">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<a href="' . $category['url'] . '" class="text-dark font-weight-bold d-inline-block mb-2 blog-pro-category" itemprop="category">' . $category['title'] . '</a> ';
                }
            }
            ?>
            <div class="mx-auto mx-md-0 col-12 col-xl-4 col-lg-6 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="h-100 d-flex flex-column">
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                        <a href="<?php print $item['link'] ?>" class="d-block" itemprop="url">
                            <div class="img-as-background h-350 p-1">
                                <img class="border" loading="lazy" src="<?php print $item['image']; ?>" style="position: relative !important;" itemprop="image"/>

                            </div>
                        </a>
                    <?php endif; ?>


                    <div class="pt-4 pb-3">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('category', $show_fields)): ?>
                            <div class="mb-2" itemprop="articleSection">
                                <?php print $itemCats; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                            <small class="mb-2 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="" itemprop="url">
                                <h4 class="text-start text-left" itemprop="name"><?php print $item['title'] ?></h4>
                            </a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p class="" itemprop="description"><?php print {{ \Illuminate\Support\Str::limit($item['description'], 250) }} ?></p>
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
