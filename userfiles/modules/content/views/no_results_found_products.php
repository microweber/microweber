<div class="no-items-found products">
    <?php

    $url = route('admin.product.create');
    ?>

    <div class="row">
        <div class="col-12">
            <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_products.svg'); ">
                <h4><?php _e('You don’t have any products'); ?></h4>
                <p><?php _e('Create your first product right now.');?> <br/>
                    <?php _e( 'You are able to do that in very easy way!'); ?></p>
                <br/>
                <a href="<?php print $url; ?>" class="btn btn-primary btn-rounded"><?php _e('Create a Product'); ?></a>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            $('.js-hide-when-no-items').hide();
            //                    $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.manage-toobar').hide();
            $('.top-search').hide();
        });
    </script>
</div>
