<?php

/*

type: layout

name: Posts pro-blog

description: Posts pro-blog

*/
?>

<style>
    .action-blog-arrow {
        font-weight: bold;
        border-bottom: 1px solid transparent;
        transition: all 0.7s;
    }

    .action-blog-arrow:hover {
      text-decoration: none!important;
        color: #FFA028!important;
        border-color: #FFA028;
        transition: all 0.7s;
    }

    .action-blog-arrow i:hover {
      background-color: unset!important;
    }

   .blog-posts-18 .img-as-background {
        height: 250px;
        width: 300px;

        img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
    }
    .skin-18--read-more-link:after{
        content: "\e658";
        color: inherit;
        font-family: 'icomoon-solid';
        vertical-align: middle;
        margin-inline-start: 9px;
    }
</style>

<div class="row blog-posts-pro-blog">


    <?php if (!empty($data)): ?>

         <?php $item = reset($data); ?>
        <div class="d-flex align-items-center justify-content-between">
            <h2 data-mwplaceholder="<?php _e('Enter title here'); ?>" class="mb-3">Our Latest Blog</h2>


            <div class="mw-post-22-post-badge">
                <?php $categories = content_categories($item['id']);



                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) { ?>
                        <a class="btn btn-secondary <?php if ($category['id'] == category_id()): ?> active <?php endif; ?>" href="<?php print category_link($category['id']) ?>"> <?php print $category['title']; ?> </a>
                    <?php }
                }
                ?>
            </div>

        </div>

        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="name">' . $category['title'] . '</small> ';
                }
            }
            ?>
            <div class="mx-auto mx-md-0 col-12 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="h-100 d-flex flex-wrap align-items-center ">
                    <div class="col-lg-8 col-12 pt-4 pb-3 order-lg-1 order-2">
                        <small style="color: #2b2b2b;" class="mb-4 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="" itemprop="url"><h4 class="text-start text-left" itemprop="name"><?php print $item['title'] ?></h4></a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p style="color: #2b2b2b;" class="" itemprop="description"><?php print {{ \Illuminate\Support\Str::limit($item['description'], 250) }} ?></p>
                        <?php endif; ?>

                        <div class="d-flex">

                            <a href="<?php print $item['link'] ?>" class=" d-flex align-items-center action-blog-arrow skin-18--read-more-link" itemprop="url">
                                <?php echo $read_more_text;?>
                            </a>
                        </div>

                    </div>

                    <div class="col-lg-3 col-12 justify-content-end ms-auto order-lg-2 order-1">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                            <a href="<?php print $item['link'] ?>" class="d-block" itemprop="url">
                                <div class="img-as-background">
                                    <img loading="lazy" src="<?php print $item['image']; ?>" style="position: relative !important;" itemprop="image"/>
                                </div>
                            </a>
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
