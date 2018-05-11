<?php if (!empty($data)): ?>

    <div class="">
        <div class="masonry__filters text-center" data-filter-all-text="Show All"></div>
        <div class="masonry__container">
            <?php foreach ($data as $item): ?>
                <?php
                $categories = content_categories($item['id']);
                $filter_cats = '';
                if ($categories) {
                    if (is_array($categories)) {
                        $cnt_cats = count($categories) - 1;
                    }

                    foreach ($categories as $key => $category) {
                        $filter_cats .= $category['title'];
                        if ($cnt_cats != $key) {
                            $filter_cats .= ', ';
                        }
                    }
                }
                //$filter_cats = strtolower($filter_cats);
                //$filter_cats = str_replace(' ', '', $filter_cats);
                ?>

                <div class="col-md-4 col-sm-6 masonry__item" data-masonry-filter="<?php print $filter_cats; ?>"
                     itemscope
                     itemtype="<?php print $schema_org_item_type_tag ?>">
                    <a href="<?php print $item['link'] ?>" itemprop="url">
                        <div class="hover-element hover-element-1" data-title-position="top,right">
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                                <div class="hover-element__initial">
                                    <img src="<?php print thumbnail($item['image'], 1200); ?>" alt=""/>
                                </div>
                            <?php endif; ?>
                            <div class="hover-element__reveal" data-overlay="9">
                                <div class="boxed">
                                    <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                        <h5><?php print $item['title'] ?></h5>
                                    <?php endif; ?>
                                    <span><em>by <?php print user_name($item['created_by']) ?></em></span>
                                </div>
                            </div>
                        </div>
                        <!--end hover element-->
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>

<script>
    $(document).ready(function () {
        mr.sliders.documentReady($)
    })
</script>
