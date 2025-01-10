<?php

/*

type: layout

name: Posts 17

description: Posts 17

*/
?>

<style>
    .blog-16-merry-card {
        padding: 10px;
        border: 2px solid #181E4E;
    }


    .merry-blog-posts-2 .img-as-background:hover {
        transform: scale(1.05);
        transition: 1.3s;

    }


</style>

<div class="row blog-posts-3 merry-blog-posts-2 justify-content-center ">
    <?php if (!empty($data)): ?>
        <div class="col-12 d-flex flex-wrap justify-content-center">
            <?php foreach ($data as $item): ?>
                <?php $categories = content_categories($item['id']);

                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="category">' . $category['title'] . '</small> ';
                    }
                }
                ?>

                <div class="col-sm-10 col-md-6 col-xl-4 px-4 mb-5" itemscope itemtype="http://schema.org/BlogPosting">
                    <div class="h-100 blog-16-merry-card">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="d-block" itemprop="url">
                                <div class="img-as-background h-350">
                                    <img loading="lazy" src="<?php print $item['image']; ?>" style="position: relative !important;" itemprop="image">
                                </div>
                            </a>
                        <?php endif; ?>

                        <div class="pt-4 pb-3 px-4">
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <a href="<?php print $item['link'] ?>" class="" itemprop="url">
                                    <h6 class="text-start text-left" itemprop="name"><?php print $item['title'] ?></h6>
                                </a>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                <p class="" itemprop="description"><?php print $item['description'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
