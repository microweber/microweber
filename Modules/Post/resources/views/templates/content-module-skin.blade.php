<?php

/*

type: layout

name: For content module - works with pages

description: For content module - works with pages

*/
?>

<div class="blog-posts-2 section content-module-wrapper">
    <?php if (!empty($data)): ?>

        <div class="container-fluid">
           <div class="row">


                <?php foreach ($data as $item): ?>
                    <?php $categories = content_categories($item['id']);

                    $itemCats = '';
                    if ($categories) {
                        foreach ($categories as $category) {
                            $itemCats .= '<p class="text-dark font-weight-bold d-block mb-2" itemprop="description">' . $category['title'] . '</p> ';
                        }
                    }
                    ?>
                    <div class="col-12 col-md-6 mb-3 py-4 mx-auto mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?> ">
                        <div class="h-100 d-flex flex-column">
                            <a href="<?php print $item['link'] ?>" itemprop="url">
                                <div class="img__wrap">
                                    <div class="img-as-background h-650">
                                        <img class="img_img" alt="image" src="<?php print $item['image']; ?>" itemprop="image" />
                                    </div>
                                    <div class="img__description_layer">
                                        <div class="row text-center align-self-center"></div>
                                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                            <h2 class="img__description" itemprop="name"> <small class="mb-2 d-block" itemprop="dateCreated"><?php echo date('d M Y', strtotime($item['created_at'])); ?></small> <?php print $item['title'] ?></h2>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
           </div>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
