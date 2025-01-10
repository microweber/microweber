<?php

/*

type: layout

name: Posts 11

description: Posts 11

*/
?>

<div class="row py-4">
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

            <div class="mx-auto col-sm-10 col-md-10 col-lg-8 mb-7" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="text-center">
                    <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                        <div class="mb-3">
                            <small class="" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                        </div>
                    <?php endif; ?>

                    <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                        <a href="<?php print $item['link'] ?>" class="text-dark"><h3 class="mb-2" itemprop="name"><?php print $item['title'] ?></h3></a>
                    <?php endif; ?>

                    <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                        <p itemprop="description"><?php echo $item['description'];?></p>
                    <?php endif; ?>

                    <?php if (isset($item['created_by'])): ?>
                        <?php
                        $user = get_user_by_id($item['created_by']);
                        ?>

                        <div class="d-flex d-sm-flex align-items-center justify-content-center">
                            <?php if (isset($user['thumbnail'])): ?>
                                <div class="me-2">
                                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                                        <div class="w-40">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="<?php echo thumbnail($user['thumbnail'], 1200, 1200); ?>" itemprop="image" />
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            <?php endif; ?>

                            <div>
                                <small class="mb-1 font-weight-bold d-block" itemprop="author">
                                    <?php if (isset($user['first_name'])): ?><?php echo $user['first_name']; ?><?php endif; ?>&nbsp;
                                    <?php if (isset($user['last_name'])): ?><?php echo $user['last_name']; ?><?php endif; ?>
                                </small>
                                <?php if (isset($user['user_information'])): ?><small class="mb-0 text-dark"><?php echo $user['user_information']; ?></small><?php endif; ?>
                            </div>
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
