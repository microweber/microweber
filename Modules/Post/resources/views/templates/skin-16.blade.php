<?php

/*

type: layout

name: Posts 16

description: Posts 16

*/
?>

<style>
    .merry-blog-posts .merry-on-hover-button {
        display: none;
    }

    .merry-blog-posts .img-as-background:hover .merry-on-hover-button  {
        display: flex!important;
        position: relative;
        z-index: 2;
        color: #61efb3;
        font-size: 80px;
        text-decoration: none;
    }

    .merry-blog-posts .img-as-background:hover img {
        opacity: 0.5;
        z-index: 1;
        transition: 1s;

    }

    .merry-blog-posts .img-as-background:hover {
        display: flex!important;
        justify-content: center;
        align-items: center;
    }


</style>


<div class="row merry-blog-posts blog-posts-3">
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
            <div class="mx-auto mx-md-0 col-sm-10 col-md-6 col-xl-3 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="h-100 d-flex flex-column">
                    <a href="<?php print $item['link'] ?>" class="d-block" itemprop="url">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                            <div class="img-as-background h-350" itemprop="image">
                                <a class="merry-on-hover-button" href=""><i class="mw-micon-Google-Play"></i></a>
                                <img loading="lazy" src="<?php print $item['image']; ?>" style="position: relative !important;" itemprop="image" alt="<?php print $item['title']; ?>" />
                            </div>
                        <?php endif; ?>

                    </a>

                    <div class="pt-4 pb-3">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="" itemprop="url">
                                <h6 class="text-start text-left" itemprop="name"><?php print $item['title'] ?></h6>
                            </a>
                        <?php endif; ?>
<!---->
<!--                        --><?php //if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
<!--                            <p class="">--><?php //print $item['description'] ?><!--</p>-->
<!--                        --><?php //endif; ?>
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                            <small class="d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']); ?></small>
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
