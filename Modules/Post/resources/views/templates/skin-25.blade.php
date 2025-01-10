<?php

/*

type: layout

name: Posts 25

description: Posts 25

*/
?>

<style>
    .blog-post-25-categories {
        position: absolute;
        bottom: 10px;
        left: 30px;
        z-index: 1;
        display: none;
        opacity: 0;
        transition: all .8s;

    }

    .blog-post-25-categories .bg-body {
       background-color: var(--mw-primary-color) !important;
        color: var(--mw-text-on-dark-background-color) !important;
        transition: all .8s;
        padding: 10px 30px !important;

    }

    .blog-post-25-wrapper {
        position: relative;
        overflow: hidden;
        transition: all .8s;
    }

    .blog-posts-25-wrapper:hover .blog-post-25-categories {
        display: block;
        transition: all .8s;
        opacity: 1;
    }

</style>

<div class="row py-4 blog-posts-25">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark bg-body px-2 py-1   font-weight-bold d-inline-block mb-2 me-2  ">' . $category['title'] . '</small> ';
                }
            }
            ?>

            <div class="mx-auto col-sm-10 col-md-6 p-0 blog-posts-25-wrapper" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">

                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                    <a href="<?php print $item['link'] ?>" class="d-block position-relative h-100">
                        <img loading="lazy" style="object-fit: cover; height: 100%;" src="<?php print $item['image']; ?>" alt="<?php print $item['title'] ?>"/>

                        <?php if ($itemCats): ?>
                            <div class="blog-post-25-categories">
                                <?php echo $itemCats; ?>
                            </div>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
