<?php

/*

type: layout

name: Posts 19

description: Posts 19

*/
?>

<style>
    .blog-posts-19 .post-19::after {
        content: "";
        width: 60px;
        position: absolute;
        bottom: -3px;
        left: 50%;
        transform: translateX(-50%);
        border-bottom: 4px solid rgb(32, 32, 32);
        margin: 0px auto;
        transition: width 0.5s ease 0s;
    }

     .blog-posts-19 .post-19:hover::after {
         width: 100%;
     }

</style>

<div class="row blog-posts-19">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="category">' . $category['title'] . '</small> ';
                }
            }
            ?>
            <div class="position-relative mx-auto mx-md-0 col-sm-10 col-md-6 col-lg-4 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="h-100 d-flex flex-column post-19">
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                    <a href="<?php print $item['link'] ?>" class="d-block" itemprop="url">
                        <div class="img-as-background h-350">
                            <img loading="lazy" src="<?php print $item['image']; ?>" style="position: relative !important; object-fit: cover;" itemprop="image"/>
                        </div>
                    </a>
                    <?php endif; ?>


                    <div class="pt-4 pb-3">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                        <small class="mb-2 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="" itemprop="name"><h6 class="text-start text-left font-weight-bold"><?php print $item['title'] ?></h6></a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p class="" itemprop="description"><?php print $item['description'] ?></p>
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
