<?php must_have_access(); ?>
<?php
$selectCategoriesType = '';

$isShop = false;
if (isset($_GET['categories_type'])) {
    if ($_GET['categories_type'] == 'shop') {
        $selectCategoriesType = 'shop';
        $isShop = true;
    } else if ($_GET['categories_type'] == 'blog') {
        $selectCategoriesType = 'blog';
    }
}
?>


<?php
if ($selectCategoriesType == 'shop' || $selectCategoriesType == 'blog') {
?>
    <?php if(isset($params['show_add_post_to_category_button'])){ ?>
    <module type="categories/manage" id="mw-cats-manage-admin" is_shop="<?php echo $isShop; ?>" show_add_post_to_category_button="true" />
    <?php } else {  ?>
    <module type="categories/manage" id="mw-cats-manage-admin" is_shop="<?php echo $isShop; ?>" />
    <?php } ?>

<?php } else { ?>

    <div>


        <div onclick="window.location.href='<?php echo app()->url_manager->current();?>&categories_type=blog'" class="col-12 text-start d-flex align-items-center flex-wrap admin-add-new-modal-buttons me-auto cursor-pointer">

            <div class="col-lg-2 mx-2 modal-add-new-buttons-img">
                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-post.svg" alt="">
            </div>

            <div class="col-lg-9 ps-3">
                <div>
                    <h3 class=" mb-0 font-weight-bolder">
                        <a href=""> Blog</a>
                    </h3>
                </div>
                <p class="  font-weight-bold mb-0 modal-add-new-buttons-p">
                    Manage your categories from blog
                </p>
            </div>
        </div>

        <div onclick="window.location.href='<?php echo app()->url_manager->current();?>&categories_type=shop'" class="col-12 text-start d-flex align-items-center flex-wrap admin-add-new-modal-buttons me-auto cursor-pointer">

            <div class="col-lg-2 mx-2 modal-add-new-buttons-img">
                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-product.svg" alt="">
            </div>

            <div class="col-lg-9 ps-3">
                <div>
                    <h3 class=" mb-0 font-weight-bolder">
                        <a href=""> Shop</a>
                    </h3>
                </div>
                <p class="  font-weight-bold mb-0 modal-add-new-buttons-p">
                    Manage your categories from shop
                </p>
            </div>
        </div>

    </div>


<?php
}
?>
