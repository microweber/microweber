<script> mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&ex=.css', true)</script>
<script>mw.moduleCSS("<?php print modules_url(); ?>shop/products/styles.css"); </script>

<div class="<?php print $config['module_class']; ?>">
    <?php



    $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
    if ($add_cart_text == false) {
        $add_cart_text = 'Add to cart';
    }


    $params['content_type'] = 'product';
    $params['is_shop'] = 1;
	 
    $dir_name = normalize_path(modules_path());
    $posts_mod = $dir_name . 'posts' . DS . 'index.php';
    include($posts_mod);
    ?>
</div>