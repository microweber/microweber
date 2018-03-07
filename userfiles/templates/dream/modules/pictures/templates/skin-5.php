<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>
    <script>
        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{
                image: "<?php print ($item['filename']); ?>",
                description: "<?php print $item['title']; ?>"
            },
            <?php endforeach;  ?>
        ];
    </script>

    <div class="">
        <div class="masonry__container">
            <?php $count = -1; ?>
            <?php foreach ($data as $item): ?>
                <?php $count++; ?>
                <div class="col-md-4 col-sm-6 masonry__item">
                    <a href="javascript:;"
                       onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
                        <div class="hover-element hover-element-1" data-title-position="top,right">
                            <div class="hover-element__initial">
                                <img src="<?php print thumbnail($item['filename'], 1200, 1200, true); ?>" alt=""/>
                            </div>

                            <div class="hover-element__reveal" data-overlay="9">
                                <div class="boxed">
                                    adsa
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
