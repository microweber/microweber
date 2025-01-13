<?php

/*

type: layout

name: Posts 21

description: Posts 21

*/
?>

<style>
    @media screen and (max-width: 991px) {
        .mw-new-16-category-tag {
            font-size: 13px;
        }

        .mw-new-16-blog-title {
            font-size: 22px;
        }
    }

    .mw-new-16-title-tag {
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.1;
    }

    .mw-new-16-blog-content {
        align-items: flex-start;
        display: flex;
        flex-direction: column;
        padding-top: 30px;
    }

    .mw-new-16-blog-title, .mw-new-16-category-tag {
        color: #000;
        font-weight: 500;
        text-decoration: none;
    }

    .mw-new-16-category-tag {
        border: 2px solid #000;
        border-image: none 100% 1 0 stretch;
        border-radius: 30px;
        display: inline-block;
        font-size: 13px;
        letter-spacing: 1px;
        padding: 7px 19px;
        text-transform: uppercase;
    }

    .mw-new-16-blog-title {
        font-size: 25px;
        line-height: 1.4;
        margin-bottom: 10px;
        margin-top: 20px;
        transition: all .3s;
        transition-behavior: normal;
    }

    .mw-new-16-heading-overlay {
        background-color: #000;
        bottom: auto;
        height: 100%;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 2;
    }

    .mw-new-16-photo-animation {
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
        overflow: hidden;
        position: relative;
    }

    .mw-new-16-photo {
        height: 100%;
        object-fit: cover;
        object-position: 50% 0%;
        width: 100vw;
    }

    .mw-new-16-category-tag:hover {
        background-color: #000;
        color: #fff;
    }
</style>


<div class="row blog-posts-21">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <div class="col-md-6 col-sm-10 col-12 mx-auto" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                    <div class="mw-new-16-photo-animation">
                        <a href="<?php print $item['link'] ?>">
                            <img loading="lazy" src="<?php print $item['image']; ?>" itemprop="image"/>
                            <div class="mw-new-16-heading-overlay mw-new-16-_2"></div>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="mw-new-16-blog-content">
                    <?php $categories = content_categories($item['id']);

                    $itemCats = '';
                    if ($categories) {
                        foreach ($categories as $category) { ?>
                            <a href="<?php print $item['link'] ?>" class="mw-new-16-category-tag" itemprop="url"> <?php print $category['title']; ?>  </a>
                        <?php }
                    }
                    ?>

                    <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                        <a class="mw-new-16-blog-title" href="<?php print $item['link'] ?>" itemprop="url"><?php print $item['title'] ?></a>
                    <?php endif; ?>

                    <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                        <p class="" itemprop="description"><?php print $item['description'] ?></p>
                    <?php endif; ?>

                    <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                        <div class="my-3">
                            <small class="mb-2 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
