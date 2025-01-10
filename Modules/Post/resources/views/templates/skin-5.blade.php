<?php
/*

  type: layout

  name: Posts 5

  description: Posts 5

 */
?>

<div class="row py-4 blog-posts-5">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $key => $item): ?>
            <?php $itemData = content_data($item['id']); ?>
            <?php if ($key == 0): ?>
                <div class="col-lg-6 col-lg-8 mx-auto py-2" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="post-holder mb-4">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                            <small class="d-block   mb-4" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>"><h3 itemprop="name"><?php print $item['title'] ?></h3></a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p itemprop="description"><?php print $item['description'] ?></p>
                        <?php endif; ?>

                        <?php if (isset($item['created_by'])): ?>
                            <?php
                            $user = get_user_by_id($item['created_by']);
                            ?>

                            <div class="d-flex d-sm-flex align-items-center mt-6">
                                <?php if (isset($user['thumbnail'])): ?>
                                    <div class="me-3">
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                                <div class="w-40 mx-auto">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="<?php echo thumbnail($user['thumbnail'], 1200, 1200); ?>" itemprop="image" />
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php endif; ?>

                                <div>


                                    <p class="mb-1 font-weight-bold d-block">
                                        <?php if (isset($user['first_name'])): ?><?php echo $user['first_name']; ?><?php endif; ?>&nbsp;
                                        <?php if (isset($user['last_name'])): ?><?php echo $user['last_name']; ?><?php endif; ?>
                                    </p>

                                    <?php if (isset($user['user_information'])): ?><p class="mb-0"><?php echo $user['user_information']; ?></p><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="col-lg-6 col-lg-8 mx-auto py-2">
            <?php foreach ($data as $key => $item): ?>
                <?php $itemData = content_data($item['id']); ?>
                <?php if ($key == 1 or $key == 2): ?>
                    <div class="post-holder mb-4">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                        <small class="d-block   mb-4" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="text-dark"><h3 itemprop="name"><?php print $item['title'] ?></h3></a>
                        <?php endif; ?>

                        <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                            <p itemprop="description"><?php print $item['description'] ?></p>
                        <?php endif; ?>

                        <?php if (isset($item['created_by'])): ?>
                            <?php
                            $user = get_user_by_id($item['created_by']);
                            ?>

                            <div class="d-flex d-sm-flex align-items-center mt-6">
                                <?php if (isset($user['thumbnail'])): ?>
                                    <div class="me-3">
                                        <div class="w-40">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="<?php echo thumbnail($user['thumbnail'], 80, 80); ?>" itemprop="image"/>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <p class="mb-1 font-weight-bold d-block">
                                        <?php if (isset($user['first_name'])): ?><?php echo $user['first_name']; ?><?php endif; ?>&nbsp;
                                        <?php if (isset($user['last_name'])): ?><?php echo $user['last_name']; ?><?php endif; ?>
                                    </p>
                                    <?php if (isset($user['user_information'])): ?><p class="mb-0"><?php echo $user['user_information']; ?></p><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
