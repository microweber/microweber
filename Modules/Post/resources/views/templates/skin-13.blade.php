<?php

/*

type: layout

name: Posts 13

description: Posts 13

*/
?>

<div class="row py-4 blog-posts-13">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark bg-body px-2 py-1 font-weight-bold d-inline-block mb-2 me-2" itemprop="category">' . $category['title'] . '</small> ';
                }
            }
            ?>

            <div class="mx-auto col-sm-10 mx-md-0 col-md-6 col-lg-4 mb-7" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class=" overflow-hidden h-100 d-flex flex-column bg-body hover-  ">
                    <div class="d-flex flex-column h-100 pt-3 pb-2">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                            <small class="mb-3 mt-3 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']); ?></small>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="text-dark text-decoration-none"><h4 class="mb-2" itemprop="name"><?php print $item['title'] ?></h4></a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p itemprop="description"><?php print $item['description'] ?></p>
                        <?php endif; ?>

<!--                        <div class="d-flex align-items-center">-->
<!--                            <div class="d-flex align-items-center me-2"><i class="mw-micon-Eye-2 icon-size-24px text-muted me-2"></i>-->
<!--                                <module type="site_stats/view_count" content-id="10"/>-->
<!--                            </div>-->
<!--                            <div class="d-flex align-items-center me-2"><i class="mdi mdi-forum icon-size-24px text-muted me-2"></i>-->
<!--                                <module type="comments/comments_count" content-id="--><?php //echo $item['id']; ?><!--" class="d-inline"/>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                        <a href="<?php print $item['link'] ?>" class="d-block position-relative" itemprop="url">
                            <div class="img-as-background square-75">
                                <img loading="lazy" src="<?php print $item['image']; ?>" itemprop="image" />
                            </div>
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
