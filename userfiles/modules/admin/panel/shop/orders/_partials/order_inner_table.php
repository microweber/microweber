<table class="mw-ui-table mw-ui-table-spacious" cellspacing="0" cellpadding="0" width="100%" id="order-information-table">
            <thead>
              <tr>
                <th colspan="2"><?php _e("Product Information"); ?></th>
                <!--  <th><?php _e("Custom fields"); ?></th>-->
                <th><?php _e("Price"); ?></th>
                <th><?php _e("QTY"); ?></th>
                <th><?php _e("Total"); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $subtotal = 0; ?>
              <?php $index = -1; foreach ($cart_items as $item) : ?>
              <?php
                $index++;
				$i = $index;
                $item_total = floatval($item['qty']) * floatval($item['price']);
                $subtotal = $subtotal + $item_total;
                $grandtotal = $subtotal + $ord['shipping'];
                ?>
              <tr
                    data-index="<?php print $index; ?>"
                    class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-item-index-<?php print $index; ?>">
                    
                    <td>
					<center>
					
					<?php  if(isset($cart_items[$i]['item_image']) and $cart_items[$i]['item_image'] != false): ?>

                  <?php 
	  
	  $p = $cart_items[$i]['item_image']; ?>
            <?php if ($p != false): ?>
            <a data-index="<?php print $i; ?>" class="bgimage mw-order-item-image mw-order-item-image-<?php print $i; ?>" style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);" href="<?php print ($p); ?>" target="_blank"></a>
            <?php endif; ?>
            <?php else: ?>
            <?php $p = get_picture($cart_items[$i]['rel_id']); ?>
            <?php if ($p != false): ?>
            <span data-index="<?php print $i; ?>" class="bgimage mw-order-item-image mw-order-item-image-<?php print $i; ?>" style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);"></span>
            <?php endif; ?>
                  
                <?php endif; ?>  
                
                </center>
                
                </td>
                    
                <td   class="mw-order-item-id"><a href="<?php print content_link($item['rel_id']) ?>" target="_blank"><span><?php print $item['title'] ?></span></a>
                  <?php if ($item['rel_type'] == 'content'): ?>
                  <?php $data_fields = mw()->content_manager->data($item['rel_id']); ?>
                  <?php if (isset($data_fields['sku']) and $data_fields['sku'] != ''): ?>
                  <small class="mw-ui-label-help">
                  <?php _e("SKU"); ?>
                  : <?php print $data_fields['sku']; ?></small>
                  <?php endif; ?>
                  <?php endif; ?>
                  
                  
                  
                  
                  
                  
             
                  
                  
                  </td>
                <!--  <td class="mw-order-item-fields"></td>-->
                <td class="mw-order-item-amount nowrap"><?php print  currency_format($item['price'], $ord['currency']); ?></td>
                <td class="mw-order-item-count"><?php print $item['qty'] ?></td>
                <td class="mw-order-item-count" width="100"><?php print  currency_format($item_total, $ord['currency']); ?></td>
              </tr>
              <?php    if (isset($item['custom_fields']) and $item['custom_fields'] != false): ?>
              <tr>
                <td colspan="4"><?php print $item['custom_fields'] ?></td>
              </tr>
              <?php endif ?>
              <?php endforeach; ?>
              <tr class="mw-ui-table-footer">
                <td colspan="3">&nbsp;</td>
                <td><?php _e("Subtotal"); ?></td>
                <td class="mw-ui-table-green"><?php print  currency_format($subtotal, $ord['currency']); ?></td>
              </tr>
              <tr class="mw-ui-table-footer">
                <td colspan="3">&nbsp;</td>
                <td><?php _e("Shipping price"); ?></td>
                <td class="mw-ui-table-green"><?php print  currency_format($ord['shipping'], $ord['currency']); ?></td>
              </tr>
              <?php if (isset($ord['taxes_amount']) and $ord['taxes_amount'] != false): ?>
              <tr class="mw-ui-table-footer">
                <td colspan="3">&nbsp;</td>
                <td><?php _e("Tax"); ?></td>
                <td class="mw-ui-table-green"><?php print  currency_format($ord['taxes_amount'], $ord['currency']); ?></td>
              </tr>
              <?php endif ?>
              <tr class="mw-ui-table-footer last">
                <td colspan="3">&nbsp;</td>
                <td class="mw-ui-table-green"><strong>
                  <?php _e("Total:"); ?>
                  </strong></td>
                <td class="mw-ui-table-green"><strong><?php print  currency_format($ord['amount'], $ord['currency']); ?></strong></td>
              </tr>
            </tbody>
          </table>