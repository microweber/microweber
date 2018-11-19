<?php
/*
type: layout
name: Default
description: Default offer template
*/
?>

<style>
    .mw-price-value-offer {
        font-size: 34px;
        font-weight: 300;
        white-space: nowrap;
        color: red;
    }

    .mw-price-value-retail {
        font-size: 34px;
        font-weight: 300;
        white-space: nowrap;
        text-decoration: line-through;
        text-decoration-color: red;
        margin-left: 10px;
    }

    .mw-price-expiry {
        margin-left: 10px;
        color: red;
    }
</style>
<script>
    // add_item: function (content_id, price, c)
    //mw.moduleCSS("<?php print modules_url(); ?>shop/offers/styles.css");
</script>

<div class="  <?php if (!isset($params['data-in-stock']) or $params['data-in-stock'] == false) {
    print 'mw-disabled';
} ?> ">
    <span>
  <?php if (is_string($data['price_name']) and trim(strtolower($data['price_name'])) == 'price'): ?>
      <span class="mw-price-key"><?php _e($data['price_name']); ?></span>
  <?php else: ?>
      <span class="mw-price-key"><?php print $data['price_name']; ?></span>
  <?php endif; ?>
        : <span class="mw-price-value-offer"><?php print currency_format($data['offer_price']); ?>

        </span><span class="mw-price-value-retail"><?php print currency_format($data['price']); ?></span>
        <?php if (isset($data['expires_at'])) { ?>
            <span class="mw-price-expiry">ending: <?php print date_system_format($data['expires_at']); ?></span>
        <?php } ?>

  </span>
</div>