<?php

/*

type: layout

name: Posts Slider 4

description: Posts Slider 4

*/
?>

@include('modules.post::partials.slick_options')

<div class="slick-arrows-1">
    <div class="py-4 blog-posts-4 slickslider slick-dots-relative">
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $item): ?>
                <?php $categories = content_categories($item['id']);

                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-outline-primary font-weight-bold d-block mb-2" itemprop="category">' . $category['title'] . '</small> ';
                    }
                }
                ?>

                <div class="mb-5 pe-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="row">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                        <div class="col-auto text-center">
                            <p itemprop="dateCreated" mb-0"><?php echo date('M', strtotime($item['created_at'])); ?></p>
                            <hr class="thin my-2" style="min-width: 65px;"/>
                            <p itemprop="dateCreated" -2"><?php echo date('d', strtotime($item['created_at'])); ?></p>
                        </div>
                        <?php endif; ?>


                        <div class="col pt-3 pb-5">
                            <?php echo $itemCats; ?>
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <a href="<?php print $item['link'] ?>" class="text-dark" itemprop="url"><h4 class="mb-2" itemprop="name"><?php print $item['title'] ?></h4></a>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                <p itemprop="description"><?php print $item['description'] ?></p>
                            <?php endif; ?>

                            <?php if (isset($item['created_by'])): ?>
                                <?php
                                $user = get_user_by_id($item['created_by']);
                                ?>
                                <div class="mb-4">
                                    <div class="d-flex d-sm-flex align-items-center">
                                        <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                                        <?php if (isset($user['thumbnail'])): ?>
                                            <div class="me-2">
                                                <div class="w-40">
                                                    <div class="img-as-background rounded-circle square">
                                                        <img loading="lazy" src="<?php echo thumbnail($user['thumbnail'], 1200, 1200); ?>" itemprop="image"/>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php endif; ?>

                                        <div>
                                            <small class="mb-1 font-weight-bold d-block">
                                                <?php if (isset($user['first_name'])): ?><?php echo $user['first_name']; ?><?php endif; ?>&nbsp;
                                                <?php if (isset($user['last_name'])): ?><?php echo $user['last_name']; ?><?php endif; ?>
                                            </small>
                                            <?php if (isset($user['user_information'])): ?><small class="mb-0 text-dark"><?php echo $user['user_information']; ?></small><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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
