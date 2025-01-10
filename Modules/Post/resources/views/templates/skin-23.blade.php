<?php

/*

type: layout

name: Posts 22

description: Posts 22

*/
?>

<style>
    .mw-posts-23-projects-thumb {
        background: #F9F9F9;
        border: 2px solid #fff;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        margin-top: 24px;
        margin-bottom: 24px;
        padding: 40px;
        transition: all ease 0.5s;
    }

    .mw-posts-23-projects-thumb:hover {
        color: var(--mw-primary-color);
        border: 2px solid var(--mw-primary-color);
    }

    .mw-posts-23-projects-thumb:hover .mw-posts-23-projects-image,
    .mw-posts-23-projects-thumb:focus .mw-posts-23-projects-image {
        transform: rotate(0) translateY(0);
    }

    .mw-posts-23-projects-thumb .popup-image {
        display: block;
        width: 100%;
        height: 100%;
    }

    .mw-posts-23-projects-image {
        border-radius: 20px;
        display: block;
        width: 100%;
        transform: rotate(10deg) translateY(80px);
        transition: all ease 0.5s;
    }

    .mw-posts-23-projects-title {
        margin-bottom: 20px;

        a {
            color: var(--mw-heading-color);
        }
    }

    .mw-posts-23-projects-tag {
        font-weight: 500;
        color: var(--mw-primary-color);
        text-transform: uppercase;
        margin-bottom: 5px;
    }

</style>


<div class="row blog-posts-23">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <div class="col-lg-4 col-md-6 col-12" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="mw-posts-23-projects-thumb">

                    <div class="mw-posts-23-projects-info">

                        <div class="mw-post-22-post-badge">
                            <?php $categories = content_categories($item['id']);

                            $itemCats = '';
                            if ($categories) {
                                foreach ($categories as $category) { ?>
                                    <small class="mw-posts-23-projects-tag"><?php print $category['title']; ?></small>
                                <?php }
                            }
                            ?>
                        </div>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <h4 class="mw-posts-23-projects-title font-weight-bold"  itemprop="name">
                                <a itemprop="url" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a>
                            </h4>
                        <?php endif; ?>

                    </div>

                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                        <a class="popup-image" itemprop="url" href="<?php print $item['link'] ?>">
                            <img class="mw-posts-23-projects-image" loading="lazy" itemprop="image" src="<?php print $item['image']; ?>"/>
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>

