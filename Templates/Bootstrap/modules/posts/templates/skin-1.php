<?php

/*

type: layout

name: Posts Slider 1

description: Posts Slider 1

*/
?>

<?php include('slick_options.php'); ?>


<div class="slick-arrows-1">
    <div class="row py-4 blog-posts-1 slickslider slick-dots-relative">
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $item): ?>
                <?php $categories = content_categories($item['id']);

                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-dark font-weight-bold d-block mb-2  ">' . $category['title'] . '</small> ';
                    }
                }
                ?>

                <div class="mx-3 col-sm-10 col-md-6 col-lg-4 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="border   overflow-hidden h-100 d-flex flex-column">
                        <a href="<?php print $item['link'] ?>">
                            <div class="img-as-background h-350">
                                <img src="<?php print thumbnail($item['image'], 450, 500); ?>"/>
                            </div>
                        </a>
                        <div class="px-5 pt-3 pb-5 mt-md-auto mt-5">
                            <?php echo $itemCats; ?>
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <h4 class="mb-2"><?php print $item['title'] ?></h4>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                <p><?php print $item['description'] ?></p>
                            <?php endif; ?>

                            <?php $text = 'text-text' ; ?>

                            <div class="m-t-auto">
                                <a href="<?php print $item['link'] ?>" class="btn btn-link">
                                    <span>
                                        <?php
                                            if($read_more_text){
                                                print $read_more_text;
                                            } else {
                                                print 'Read more';
                                            }
                                        ?>
                                    </span>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
