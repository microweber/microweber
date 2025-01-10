<?php

/*

type: layout

name: Posts 6

description: Posts 6

*/
?>

<div class="row py-4 blog-posts-6">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-outline-primary font-weight-bold d-inline-block mb-2 me-2" itemprop="category">' . $category['title'] . '</small> ';
                }
            }
            ?>

            <div class="mx-auto col-sm-10 mx-md-0 col-md-4 mb-5" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class=" px-5 h-100">
                    <div class="d-flex flex-column h-100">
                        <div>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <a href="<?php print $item['link'] ?>" class="text-dark text-decoration-none"><h4 class="mb-2"><?php print $item['title'] ?></h4></a>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                                <div class="mb-3">
                                    <!--                                --><?php //echo $itemCats; ?>
                                    <small><?php echo date_system_format($item['created_at']) ; ?></small>
                                </div>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                <p itemprop="description"><?php print $item['description'] ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if (isset($item['created_by'])): ?>
                            <?php
                            $user = get_user_by_id($item['created_by']);
                            ?>

                            <div class="d-flex d-sm-flex align-items-center">
                                <?php if (isset($user['thumbnail'])): ?>
                                    <div class="me-2">
                                        <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                                        <div class="w-40">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="<?php echo thumbnail($user['thumbnail'], 1200, 1200); ?>" />
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                <?php endif; ?>

                                <div>
                                    <small class="mb-1 font-weight-bold d-block">
                                        <?php if (isset($user['first_name'])): ?><?php echo $user['first_name']; ?><?php endif; ?>&nbsp;
                                        <?php if (isset($user['last_name'])): ?><?php echo $user['last_name']; ?><?php endif; ?>
                                    </small>
                                    <?php if (isset($user['user_information'])): ?><small class="mb-0 text-dark"><?php echo $user['user_information']; ?></small><?php endif; ?>
                                </div>
                            </div>

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
