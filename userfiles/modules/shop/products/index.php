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

        $schema_org_item_type_tag = '';
        $tn_size = array('150');
        $show_fields = [];


        $limit = 20;
        if (isset($shop_options['data-limit'])) {
            $limit = $shop_options['data-limit'];
        }

        $filter = [];

        $getProducts = \MicroweberPackages\Product\Models\Product::filter($filter)->paginate($limit);

        $data = [];
        foreach ($getProducts as $product) {

            $mediaUrls = [];
            foreach ($product->media as $media) {
                $mediaUrls[] = $media->filename;
            }

            $data[] = [
                'id' => $product->id,
                'image' => $mediaUrls[0],
                'link' => $product->url,
                'title' => $product->title,
                'prices' => [$product->price],
            ];
        }

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
            include($template_file);
        } else {
            print lnotif("No template found. Please choose template.");
        }
    }
    ?>
</div>