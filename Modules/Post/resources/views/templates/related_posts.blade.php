<?php

/*

type: layout

name: Related Posts

description: Related Posts

*/
?>

<style>
    .site-content .heading {
        background: rgb(35, 144, 193);
        border-color: rgb(35, 144, 193);
        padding: 10px;
        position: relative;
        color: #fff !important;
        display: inline-block;
        margin-bottom: 0;
        align-self: self-start;
        width: auto;
    }

    .site-content .heading:after, .sidebar-area h2:not(.banner-title):after {
        position: absolute;
        content: "";
        top: 0;
        right: -8px;
        border-top: 8px solid #605ca8;
        border-color: inherit;
        background: transparent !important;
        border-top: 8px solid rgb(35, 144, 193);

        border-right: 8px solid transparent;

    }

    .sidebar-related-posts {
        padding-top: 1rem;
    }


    .site-content .heading:not(:last-child)+*:not(a), .site-content .heading:not(:last-child)+a+* {
        border-top: 3px solid rgb(35, 144, 193);
    }

    .sidebar-related-posts .image-container {
        width: 100px;
        height: 70px;
        overflow: hidden;
        flex-shrink: 0; /* Ensures the container keeps its size in a flex layout */
    }

    .sidebar-related-posts img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .site-content .pro-post-title {
        font-size: 1rem;
        margin-bottom: 0;

    }


</style>
<div class="site-content">
    <h6 class="heading">Related posts</h6>
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $index => $item): ?>
            <a href="<?php print $item['link'] ?>" class="sidebar-related-posts mb-0 mx-0" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="d-flex py-3 gap-3" <?php if ($index === 0) echo 'style="border-top: 3px solid rgb(35, 144, 193);"'; ?>>
                    <div class="image-container">
                        <img loading="lazy" src="<?php print $item['image']; ?>" itemprop="image"/>
                    </div>
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                        <p class="font-weight-bold pro-post-title" itemprop="name"><?php print $item['title'] ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($index < count($data) - 1): ?>
                    <hr class="my-2">
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



