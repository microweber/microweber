<script type="text/javascript">
    mw.require("shop.js", true);
    mw.require("events.js", true);
</script>
<script type="text/javascript">
    $(document).ready(function () {
        mw.on.moduleReload('cart_fields_<?php print $params['id'] ?>', function () {
            mw.reload_module('#<?php print $params['id'] ?>');
        });
    })

</script>

<?php


/*<script>mw.moduleCSS("<?php print modules_url(); ?>shop/cart_add/styles.css"); </script>
*/
//template_stack_add(modules_url()."shop/cart_add/styles.css");

?>

<?php
$for_id = false;
$for = 'content';
if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'post' and defined('POST_ID')) {
    $params['content-id'] = POST_ID;
    $for = 'content';
}

if (isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'page' and defined('PAGE_ID')) {
    $params['content-id'] = PAGE_ID;
    $for = 'content';
}

if (isset($params['content_id'])) {
    $params['content-id'] = $params['content_id'];
    $for = 'content';
}
if (isset($params['product_id'])) {
    $params['content-id'] = $params['product_id'];
    $for = 'content';
}

$module_template = get_option('template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}


if ($module_template != false and $module_template != 'none') {
    $template_file = module_templates($config['module'], $module_template);

} else {
    $template_file = module_templates($config['module'], 'default');

}
if (isset($params['content-id'])) {
    $for_id = $params['content-id'];
}


if (isset($params['for'])) {
    $for = $params['for'];
}
if (isset($params['button_text'])) {
    $button_text = $params['button_text'];
} else {
    $button_text = false;
}

if ($for_id == false and defined('CONTENT_ID')) {
    $for_id = CONTENT_ID;

}
$content_data = content_data($for_id);
$in_stock = true;


if (isset($content_data['qty']) and $content_data['qty'] != 'nolimit' and intval($content_data['qty']) == 0) {

    $in_stock = false;
}

$data = $prices_data =  false;

if (isset($for_id) !== false and isset($for) !== false) {
    $prices_data = mw()->shop_manager->get_product_prices($for_id, true);
 //  dd($prices_data);
    if ($prices_data) {
        $data = array();
        foreach ($prices_data as $price_data) {
            if (isset($price_data['name'])) {
                $data[ $price_data['name']] = $price_data['value'];
            }
        }
        //$data2 = get_custom_fields("field_type=price&for={$for}&for_id=" . $for_id . "");
      // dd($prices_data,$data,$data2);
    }

//dd($data);
//    $data = get_custom_fields("field_type=price&for={$for}&for_id=" . $for_id . "");
//    dd($data);
    /* if ($for == 'content' and intval($for_id) == 0) {
         $for_id = 0;
     }
     $data = get_custom_fields("field_type=price&for={$for}&for_id=" . $for_id . "");





     $event_params = array();
     $event_params['for'] = $for;
     $event_params['for_id'] = $for_id;
     $event_params['data'] = $data;

     $override = $this->app->event_manager->trigger('mw.shop.cart.get_prices_for_product', $event_params);
     if (is_array($override)) {
         foreach ($override as $resp) {
             if (is_array($resp) and !empty($resp)) {
                 $data = array_merge($data, $resp);
             }
         }
     }*/

}
//d($data);
/*$custom_prices = false;


$event_params = array();
$event_params['for'] = $for;
$event_params['for_id'] = $for_id;
$event_params['data'] = $data;

$custom_prices_from_modules = $this->app->event_manager->trigger('mw.shop.cart_add.custom_prices', $event_params);
if (is_array($custom_prices_from_modules)) {
    $custom_prices = array();
    foreach ($custom_prices_from_modules as $resp) {
        if (is_array($resp) and !empty($resp)) {
            $custom_prices = array_merge($custom_prices, $resp);
        }
    }
}*/


//$custom_prices = mw()->shop_manager->get_product_custom_prices($for_id, true);
//dd(' cart_add $custom_prices',$custom_prices,$prices_data);
//$price_offers = false;
//
//// check for offer prices
//if (mw()->module_manager->is_installed('shop/offers') and function_exists('offers_get_by_product_id')) {
//    $price_offers = offers_get_by_product_id($for_id);
//}


$prices_includes_taxes = false;
// TODO: move to taxmanager function
$ex_tax = '';
$taxes_enabled = get_option('enable_taxes', 'shop');
//if ($taxes_enabled) {
//    $defined_taxes = mw()->tax_manager->get();
//    if (!empty($defined_taxes)) {
//        if (count($defined_taxes) == 1) {
//
//            $ex_tax =  $defined_taxes[0]['tax_name'];
//        } else {
//            $ex_tax = 'ex tax';
//        }
//    }
//}




?>
<?php if (isset($for_id) !== false and isset($for) !== false): ?>

    <div class="mw-add-to-cart-holder mw-add-to-cart-<?php print $params['id'] ?>">
        <?php if ($for == 'content' and intval($for_id) == 0) {
            $for_id = 0;
        } ?>
        <?php //$data = get_custom_fields("field_type=price&for={$for}&for_id=" . $for_id . ""); ?>
        <?php if (is_array($data) == true): ?>
            <input type="hidden" name="for" value="<?php print $for ?>"/>
            <input type="hidden" name="for_id" value="<?php print $for_id ?>"/>
        <?php endif; ?>

        <?php if (isset($template_file) and is_file($template_file) != false) : ?>
            <?php include($template_file); ?>
        <?php else: ?>
            <?php print lnotif('No default template for ' . $config['module'] . ' is found'); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
