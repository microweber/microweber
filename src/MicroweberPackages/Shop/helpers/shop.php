<?php


/**
 * Shop module api.
 *
 * @since             Version 0.1
 */
function get_cart($params = false)
{
    return mw()->shop_manager->get_cart($params);
}

function update_cart($data)
{
    return mw()->shop_manager->update_cart($data);
}

function empty_cart()
{
    return mw()->shop_manager->empty_cart();
}

function checkout_url()
{
    return mw()->shop_manager->checkout_url();
}

function delete_client($data)
{
    return mw()->shop_manager->delete_client($data);
}

function cart_sum($return_amount = true)
{
    return mw()->shop_manager->cart_sum($return_amount);
}

function cart_get_items_count()
{
    return mw()->shop_manager->cart_sum(false);
}

function cart_total()
{
    return mw()->shop_manager->cart_total();
}
function cart_totals($return='all')
{
    return mw()->cart_manager->totals($return);
}

function cart_get_tax()
{
    return mw()->cart_manager->get_tax();
}

function cart_get_discount()
{
    return mw()->cart_manager->get_discount();
}

function is_product_in_stock($content_id)
{
    return mw()->cart_manager->is_product_in_stock($content_id);
}


function cart_get_discount_text()
{
    return mw()->cart_manager->get_discount_text();
}

function checkout_ipn($data)
{
    return mw()->shop_manager->checkout_ipn($data);
}

function get_product_prices($content_id = false, $return_full_custom_fields_array = false)
{
    return mw()->shop_manager->get_product_prices($content_id, $return_full_custom_fields_array);
}

function get_product_price($content_id = false)
{
    return mw()->shop_manager->get_product_price($content_id);
}


function get_product_discount_price($content_id = false)
{
      $product = \MicroweberPackages\Product\Models\Product::query()->where('id', $content_id)->first();
      if($product){
          return $product->getSpecialPriceAttribute();
      } else {
          return false;
      }

}
function get_product_discount_percent($content_id = false)
{
    $product = \MicroweberPackages\Product\Models\Product::query()->where('id', $content_id)->first();
    if($product){
        return $product->getDiscountPercentage();
    } else {
        return false;
    }
}


function checkout($data)
{
    return mw()->shop_manager->checkout($data);
}

function checkout_confirm_email_test($params)
{
    return mw()->shop_manager->checkout_confirm_email_test($params);
}

function update_cart_item_qty($data)
{
    return mw()->shop_manager->update_cart_item_qty($data);
}

function remove_cart_item($data)
{
    return mw()->shop_manager->remove_cart_item($data);
}

/**
 * update_order.
 *
 * updates order by parameters
 *
 * @category       shop module api
 */
function update_order($params = false)
{
    return mw()->shop_manager->update_order($params);
}

function delete_order($data)
{
    return mw()->shop_manager->delete_order($data);
}

function get_orders($params = false)
{
    return mw()->shop_manager->get_orders($params);
}

function get_order_by_id($params = false)
{
    return mw()->shop_manager->get_order_by_id($params);
}

function payment_options($option_key = false)
{
    return mw()->shop_manager->payment_options($option_key);
}


function checkout_get_user_info()
{
    return mw()->checkout_manager->checkout_get_user_info();
}



function currency_format($amount, $curr = false)
{
    return mw()->shop_manager->currency_format($amount, $curr);
}
function get_currency_symbol($curr = false)
{
    return mw()->shop_manager->currency_symbol($curr);
}

function get_currency_code() {

    $curr = mw()->option_manager->get('currency', 'payments');
    if (!$curr) {
        $curr = 'USD';
    }

    return $curr;
}

function mw_shop_recover_shopping_cart($sid = false)
{
    return mw()->cart_manager->recover_cart($sid);
}

function is_shop_module_enabled_for_user()
{
    $shop_disabled = get_option('shop_disabled', 'website') == 'y';
    if ($shop_disabled) {
        return false;
    }

    if (!mw()->module_manager->is_installed('shop')) {
        return false;
    }


    if (!user_can_view_module(['module' => 'shop'])) {
        return false;
    }

    return true;
}
