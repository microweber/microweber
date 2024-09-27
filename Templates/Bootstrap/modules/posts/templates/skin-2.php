<?php

/*

type: layout

name: Posts 2

description: Posts 2

*/
?>

<div class="blog-posts-2">
    <?php if (!empty($data)): ?>

    <div class="row text-center" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);
            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<p class="text-dark font-weight-bold d-block mb-2  ">' . $category['title'] . '</p> ';
                }
            }
            ?>
            <div class="col-lg-4 col-md-12 mb-5">
                <div class="card">
                    <a href="<?php print $item['link'] ?>">
                        <div class="img-as-background h-350">
                            <img src="<?php print thumbnail($item['image'], 450, 500); ?>" class="img-thumbnail img-fluid card-img-top"/>
                        </div>
                    </a>
                    <div class="card-body">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <h5 class="card-title"><?php print $item['title'] ?></h5>
                        <?php endif; ?>


                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p class="card-text"><?php print $item['description'] ?></p>
                        <?php endif; ?>

                        <a href="<?php print $item['link'] ?>" class="btn btn-primary px-4">Read</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

        <!-- Pagination -->
            <?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
                <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
            <?php endif; ?>
    <?php endif; ?>
</div>











