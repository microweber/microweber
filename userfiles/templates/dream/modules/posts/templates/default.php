<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="row">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $item): ?>
            <div class="col-md-4 col-sm-6" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <a href="<?php print $item['link'] ?>" itemprop="url">
                    <div class="card card-4">
                        <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                            <div class="card__image">
                                <img alt="<?php print $item['title'] ?>" src="<?php print thumbnail($item['image'], 800, 533, true); ?>"/>
                            </div>
                        <?php endif; ?>
                        <div class="card__body boxed boxed--sm bg--white">
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <h6>
                                    <?php $categories = content_categories($item['id']);
                                    if ($categories):
                                        foreach ($categories as $key => $category): ?>
                                            <?php if ($key < 2): ?>
                                                <a class="category" href="<?php print category_link($category['id']); ?>"><?php print $category['title']; ?></a>
                                            <?php endif; ?>
                                            <?php if ($key == 2): ?>...<?php endif; ?>
                                        <?php endforeach; endif; ?>

                                </h6>

                                <div class="card__title">
                                    <h5><?php print $item['title'] ?></h5>
                                    <?php if(!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                                        <small class="date"><?php print $item['created_at'] ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($show_fields) and is_array($show_fields) and  in_array('description', $show_fields)): ?>

                                <p itemprop="description"><?php print character_limiter($item['description'], 150); ?></p>

                            <?php endif; ?>



                            <hr>
                            <div class="card__lower pull-left">
                                <span>by</span>
                                <span class="h6"><?php print user_name($item['created_by']) ?></span>
                            </div>
                            <?php if($show_fields != false and ($show_fields != false and  in_array('read_more', $show_fields))): ?>

                                <a href="<?php print $item['link'] ?>" class="mw-more pull-right"><?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?></a>

                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
<script>
    $(document).ready(function () {
        mr.sliders.documentReady($)
    })
</script>
