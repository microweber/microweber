<div class="mw-ui-box" id="order-status">
  <div class="mw-ui-box-header" ><span>
    <?php _e("Order Status"); ?>
    </span></div>
  <div class="mw-ui-box-content">
    <div class="order-status-selector">
      <ul class="mw-ui-inline-list">
        <li><span>
          <?php _e("What is the status of this order"); ?>
          ?</span></li>
        <li>
          <label class="mw-ui-check">
            <input <?php if ($ord['order_status'] == 'pending'): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="pending"/>
            <span></span><span>
            <?php _e("Pending"); ?>
            </span> </label>
        </li>
        <li>
          <label class="mw-ui-check">
            <input <?php if ($ord['order_status'] == 'completed' or $ord['order_status'] == ''): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="completed"/>
            <span></span><span>
            <?php _e("Completed Order"); ?>
            </span> </label>
        </li>
      </ul>
    </div>
    <div id="mw_order_status" style="overflow: hidden">
      <div style="margin-right: 10px;width: 238px;"
             class="mw-notification mw-warning right <?php if ($ord['order_status'] == 'completed'): ?>semi_hidden<?php endif; ?>">
        <div style="height: 55px;">
          <?php _e("Pending"); ?>
        </div>
      </div>
      <div style="margin-right: 10px;width: 238px;"
             class="mw-notification mw-success right <?php if ($ord['order_status'] != 'completed'): ?>semi_hidden<?php endif; ?>">
        <div style="height: 55px;"><span>
          <?php _e("Successfully Completed"); ?>
          </span></div>
      </div>
    </div>
  </div>
</div>
<?php event_trigger('mw.ui.admin.shop.order.edit.status.after', $ord); ?>
<?php $edit_order_custom_items = mw()->ui->module('mw.ui.admin.shop.order.edit.status.after'); ?>
<?php if (!empty($edit_order_custom_items)): ?>
<?php foreach ($edit_order_custom_items as $item): ?>
<?php $view = (isset($item['view']) ? $item['view'] : false); ?>
<?php $link = (isset($item['link']) ? $item['link'] : false); ?>
<?php $text = (isset($item['text']) ? $item['text'] : false); ?>
<?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
<?php $html = (isset($item['html']) ? $item['html'] : false); ?>
<?php if ($view==false and $link!=false){
                                    $btnurl = $link;
                                } else {
                                    $btnurl = admin_url('view:') . $view;
           } ?>
<div class="mw-ui-box" style="margin-bottom: 20px;">
  <div class="mw-ui-box-header">
    <?php if ($icon){ ?>
    <span class="<?php print $icon; ?>"></span>
    <?php } ?>
    <span><?php print $text; ?></span></div>
  <div class="mw-ui-box-content"><?php print $html; ?></div>
</div>
<?php endforeach; ?>
<?php endif; ?>
