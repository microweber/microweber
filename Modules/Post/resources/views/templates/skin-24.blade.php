<?php
/*

  type: layout

  name: Posts 24

  description: Posts 24

 */
?>

<style>

    .zoom-on-hover {
        transition: transform 0.6s ease-out;
        position: relative;
    }

    .zoom-on-hover:hover img {
        transform: scale(1.02);
        transition: transform 0.6s ease-out;

    }

    .zoom-on-hover:hover .img-as-background::after {
        width: 100%;
        left: 0;
        right: auto;
        z-index: 9;
    }

    .zoom-on-hover .img-as-background::after {
        content: "";
        width: 0;
        height: 4px;
        bottom: 0;
        position: absolute;
        left: auto;
        right: 0;
        z-index: -1;
        transition: width .6s cubic-bezier(.25,.8,.25,1) 0s;
        background: var(--mw-primary-color);

    }

    .news-category {
        background: var(--mw-primary-color);
        position: absolute;
        z-index: 9;
        top: 0;
        left: 0;
        padding: 4px 12px;
        display: inline-block;
        color: var(--mw-text-on-dark-background-color);
    }

    .mw-posts-24-title {
        opacity: .7;
    }

    .mw-posts-24-title:hover {
        opacity: 1;
    }
</style>

<div class="row py-4 blog-posts-10">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $key => $item): ?>


            <?php $itemData = content_data($item['id']); ?>
            <?php if ($key == 0): ?>
                <div class="col-lg-6" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="post-holder zoom-on-hover mb-4">
                        <?php $categories = content_categories($item['id']);

                        $itemCats = '';
                        if ($categories) {
                            foreach ($categories as $category) {
                                $itemCats .= '<div class="news-category">' . $category['title'] . '</div> ';
                            }
                        }
                        ?>

                        <div class="d-flex flex-column">
                            <a href="<?php print $item['link'] ?>" class="   d-block position-relative" itemprop="url">

                                <div class="position-absolute">
                                    <?php echo $itemCats; ?>
                                </div>

                                <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <div class="img-as-background">
                                    <img loading="lazy" style="max-height: 750px; width: auto; position: relative !important;" src="<?php print $item['image']; ?>"  itemprop="image"/>
                                </div>
                                <?php endif; ?>
                            </a>

                          <div>
                              <small class="mt-3 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                              <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                  <a href="<?php print $item['link'] ?>" class=" mb-2">
                                      <h5 class="mw-posts-24-title font-weight-bold" itemprop="name"><?php print $item['title'] ?></h5>
                                  </a>
                              <?php endif; ?>

                              <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                  <p itemprop="description" class=""><?php print $item['description'] ?></p>
                              <?php endif; ?>
                          </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="col-lg-6">

            <?php foreach ($data as $key => $item): ?>
                <?php $itemData = content_data($item['id']); ?>
                <?php if ($key == 1 or $key == 2 or $key == 3 or $key == 4): ?>
                    <div class="post-holder zoom-on-hover d-flex flex-column flex-lg-row  mb-3">

                        <?php $categories = content_categories($item['id']);

                        $itemCats = '';
                        if ($categories) {
                            foreach ($categories as $category) {
                                $itemCats .= '<div class="news-category">' . $category['title'] . '</div> ';
                            }
                        }
                        ?>
                        <div class="w-100">
                            <a href="<?php print $item['link'] ?>" class=" position-relative" itemprop="url">
                                <div class="position-absolute">
                                    <?php echo $itemCats; ?>
                                </div>
                                <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>

                                    <div class="img-as-background">
                                        <img loading="lazy" src="<?php print $item['image']; ?>" style="position: relative !important;" itemprop="image"/>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>

                       <div class="w-100 px-4">
                           <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                               <a href="<?php print $item['link'] ?>">
                                   <h5 class="mw-posts-24-title font-weight-bold mb-3" itemprop="name"><?php print $item['title'] ?></h5>
                               </a>
                           <?php endif; ?>

                           <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>

                               <small class="my-2 d-block" itemprop="dateCreated"><?php echo date_system_format($item['created_at']) ; ?></small>
                           <?php endif; ?>

                           <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                               <p class="" itemprop="description"><?php print $item['description'] ?></p>
                           <?php endif; ?>
                       </div>

                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
