<?php
/*

  type: layout

  name: Posts 10

  description: Posts 10

 */
?>

<div class="row py-4 blog-posts-10">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $key => $item): ?>
            <?php $categories = content_categories($item['id']);

            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= '<small class="text-dark bg-body font-weight-bold d-inline-block mb-2">' . $category['title'] . '</small> ';
                }
            }
            ?>

            <?php $itemData = content_data($item['id']); ?>
            <?php if ($key == 0): ?>
                <div class="col-lg-6 col-lg-6" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="post-holder mb-4">
                        <div class="d-flex flex-column">
                            <a href="<?php print $item['link'] ?>" class="   d-block position-relative" itemprop="url">

                                <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <div class="img-as-background">
                                    <img loading="lazy" style="max-height: 750px; width: auto; position: relative !important;" src="<?php print $item['image']; ?>" itemprop="image"/>
                                </div>
                                <?php endif; ?>
                            </a>

                                <small class="mt-2 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                                <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                    <a href="<?php print $item['link'] ?>" class="text-dark text-decoration-none mb-2"><h3 itemprop="name"><?php print $item['title'] ?></h3></a>
                                <?php endif; ?>

                                <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                    <p itemprop="description" class=""><?php print $item['description'] ?></p>
                                <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="col-lg-6 col-lg-6">
            <div class="row">
                <?php foreach ($data as $key => $item): ?>
                    <?php $itemData = content_data($item['id']); ?>
                    <?php if ($key == 1 or $key == 2 or $key == 3 or $key == 4): ?>
                        <div class="col-md-6 post-holder">
                            <div class="d-flex flex-column">
                                <a href="<?php print $item['link'] ?>" class="   d-block position-relative" itemprop="url">
                                    <div class="position-absolute"><?php echo $itemCats; ?></div>
                                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                                    <div class="img-as-background">
                                        <img loading="lazy" height="150" width="250" src="<?php print $item['image']; ?>" style="position: relative !important;" itemprop="image"/>
                                    </div>
                                    <?php endif; ?>

                                </a>


                                    <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                                    <small class="mt-2 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                                    <?php endif; ?>

                                    <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                        <a href="<?php print $item['link'] ?>" class="   text-dark"><h4 class="text-start text-left" itemprop="name"><?php print $item['title'] ?></h4></a>
                                    <?php endif; ?>

                                    <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                        <p class="" itemprop="description"><?php print $item['description'] ?></p>
                                    <?php endif; ?>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
