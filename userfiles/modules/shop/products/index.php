<script>mw.moduleCSS("<?php print modules_url(); ?>shop/products/styles.css"); </script>

<div class="<?php print $config['module_class']; ?>">
    <?php

    $shop_options = [];
    $get_shop_options = \MicroweberPackages\Option\Models\Option::where('option_group', $params['id'])->get();
    if (!empty($get_shop_options)) {
        foreach ($get_shop_options as $get_shop_option) {
            $shop_options[$get_shop_option['option_key']] = $get_shop_option['option_value'];
        }
    }


    $add_cart_text = 'Add to cart';
    if (isset($shop_options['data-add-to-cart-text'])) {
        $add_cart_text = $shop_options['data-add-to-cart-text'];
    }


    $params['content_type'] = 'product';
    $params['is_shop'] = 1;
	 

    $old_method = false;
    // OLD METHOD
    // Rendering shop/products (2.05s)
    if ($old_method) {

        $dir_name = normalize_path(modules_path());
        $posts_mod = $dir_name . 'posts' . DS . 'index.php';
        include($posts_mod);

    } else {

        // New METHOD
        //Rendering shop/products (534ms)
        //Rendering shop/products (187ms) cached

        $schema_org_item_type_tag = '';
        $tn_size = array('150');
        $show_fields = [];


        $limit = 20;
        if (isset($shop_options['data-limit'])) {
            $limit = $shop_options['data-limit'];
        }
        if (isset($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
        }

        $filter = [];
        if (isset($_GET['orderBy'])) {
            $filter['orderBy'] = $_GET['orderBy'];
        }
        if (isset($_GET['priceBetween'])) {
            $priceBetween = $_GET['priceBetween'];
            $priceBetween = str_replace('%2C', ',', $priceBetween);
            $filter['priceBetween'] = $priceBetween;
        }

        $getProducts = \MicroweberPackages\Product\Models\Product::filter($filter)->paginate($limit);

        if ($getProducts->total() == 0) {
            echo 'No products found';
            return;
        }

        $data = [];
        $minPrice = 0;
        $maxPrice = 0;
        $prices = [];
        $mediaUrls = [];
        foreach ($getProducts as $product) {


            foreach ($product->media as $media) {
                $mediaUrls[] = $media->filename;
            }

            $prices[] = $product->price;
            $img = false;
            if(isset($mediaUrls[0])){
                $img = $mediaUrls[0];
            }


            $data[] = [
                'id' => $product->id,
                'image' => $img,
                'link' => $product->url,
                'title' => $product->title,
                'prices' => [$product->price],
            ];
        }

        asort($prices, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
        $minPrice = $prices[0];
        $maxPrice = end($prices);

        $pages_count = $getProducts->total();
        $paging_param = 'page&laravel_pagination=1&laravel_pagination_limit='.$limit.'&laravel_total='.$pages_count;

        $module_template = false;
        if ($module_template == false and isset($params['template'])) {
            $module_template = $params['template'];
        }
        if ($module_template != false) {
            $template_file = module_templates($config['module'], $module_template);
        } else {
            $template_file = module_templates($config['module'], 'default');
        }

        if (is_file($template_file) != false) {

            echo '
                <script>
                    SHOP_PRODUCTS_MIN_PRICE = '. $minPrice.';
                    SHOP_PRODUCTS_MAX_PRICE = '. $maxPrice.';
                </script>
            ';

            include($template_file);
        } else {
            print lnotif("No template found. Please choose template.");
        }
    }
    ?>
</div>