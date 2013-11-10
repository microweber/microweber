<?php
only_admin_access();

?><?php if(isset($params['order-id']) == true): ?>
<?php
$client = get_orders('one=1&id='.intval($params['order-id']));
$orders = get_orders('order_by=created_on desc&order_completed=y&email='.$client['email']);
?>
<script type="text/javascript">
mw.require('forms.js');
</script>
<script type="text/javascript">



mw.client_edit = {
 enable:function(e){
   mw.$('.mw-client-information').removeClass('nonactive');
   mw.$('.mw-client-information input').eq(0).focus();
 },
 disable:function(){
  mw.$('.mw-client-information').addClass('nonactive');
},
save:function(){
 var URL = '<?php print api_link('shop/update_order') ?>';
 if(!mw.$('.mw-client-information').hasClass('nonactive')){
   var obj = mw.form.serialize('.mw-client-information');
   $.post(URL, obj ,function(data) {
    mw.reload_module('<?php print $config['module'] ?>');
  });
 }
         //  mw.client_edit.disable();
       }
     }


     add_client_image = function(img){
       mw.$("#client_image").val(img);
       mw.$(".mw-client-image-holder").html("<img src='" + img +"' />");
       mw.tools.modal.remove('mw_rte_image');
     }

     </script>

     <div class="mw-admin-wrap" style="padding: 0;width: 960px">
      <div class="mw-o-box">
        <div class="mw-o-box-header"> <span class="ico iusers"></span> <span><?php _e("Client Information"); ?></span> </div>
        <div class="mw-client-image left">
          <div class="mw-client-image-holder">
          
          
          <?php if(isset( $client['created_by']) and $client['created_by'] > 0): ?>
          <?php
		  $user_ord = get_user($client['created_by']);
		   ?>
          <?php if(isset($user_ord['thumbnail']) and trim($user_ord['thumbnail'])!=''): ?>
       <img style="vertical-align:middle" src="<?php print $user_ord['thumbnail'] ?>"  height="115" width="115" />  
      <?php endif; ?>
          <?php endif; ?>
          
            <input type="hidden" name="client_image" id="client_image" />
          </div>
          <center>
           <!-- <span onclick="mw.wysiwyg.request_image('#add_client_image');" class="mw-ui-btn mw-ui-btn-small">
              <?php _e("Upload Image"); ?>
            </span>-->
          </center>
        </div>
        <div class="right" style="width: 805px;padding-right: 12px;">
          <table border="0" cellpadding="0" cellspacing="0" width="805" class="mw-o-box-table mw-client-information nonactive">
            <thead>
              <tr>
                <th scope="col"><?php _e("Names"); ?></th>
                <th scope="col"><?php _e("Email"); ?></th>
                <th scope="col"><?php _e("Phone"); ?></th>
                <th scope="col"><?php _e("Country"); ?></th>
                <th scope="col"><?php _e("City"); ?></th>
                <th scope="col"><?php _e("State"); ?></th>
                <th scope="col"><?php _e("Zip"); ?></th>
              </tr>
            </thead>
            <tbody>
                  <tr class="last">
                    <td>
                    <input type="hidden" name="id"   value="<?php print $client['id'] ?>" />
                    <input class="left mw-ui-field" type="text" name="first_name" value="<?php print $client['first_name'] ?>" />
                    <input class="right mw-ui-field" type="text" name="last_name" value="<?php print $client['last_name'] ?>" />
                    <span class="val"><?php print $client['first_name'] ?></span> <span class="val"><?php print $client['last_name'] ?></span></td>
                    <td>
                    <input type="text" class="mw-ui-field" name="email" value="<?php print $client['email'] ?>" />
                    <span class="val"><?php print $client['email'] ?></span></td>
                    <td><input type="text" class="mw-ui-field" name="phone" value="<?php print $client['phone'] ?>" />
                    <span class="val"><?php print $client['phone'] ?></span></td>
                    <td><input type="text" class="mw-ui-field" name="country" value="<?php print $client['country'] ?>" />
                    <span class="val"><?php print $client['country'] ?></span></td>
                    <td><input type="text" class="mw-ui-field" name="city" value="<?php print $client['city'] ?>" />
                    <span class="val"><?php print $client['city'] ?></span></td>
                    <td><input type="text" class="mw-ui-field" name="state" value="<?php print $client['state'] ?>" />
                    <span class="val"><?php print $client['state'] ?></span></td>
                    <td><input type="text" class="mw-ui-field" name="zip" value="<?php print $client['zip'] ?>" />
                    <span class="val"><?php print $client['zip'] ?></span></td>
                  </tr>
                          </tbody>
                        </table>
                        <div class="vSpace"></div>
                        <div class="vSpace"></div>
                        <table border="0" cellpadding="0" cellspacing="0" width="805" class="mw-o-box-table mw-client-information nonactive">
                          <thead>
                            <tr>
                              <th scope="col"><?php _e("Address"); ?></th>
                              <th scope="col"><?php _e("Address 2"); ?></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr class="last">
                              <td width="50%"><input style="width:350px" class="mw-ui-field" type="text" name="address" value="<?php print $client['address'] ?>" />
                                <span class="val"><?php print $client['address'] ?></span></td>
                                <td width="50%"><input  style="width:350px" type="text" class="mw-ui-field" name="address2" value="<?php print $client['address2'] ?>" />
                                  <span class="val"><?php print $client['address2'] ?></span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class='mw-save-bar mw-save-bar-edit-client'> <span class="mw-ui-btn mw-ui-btn-small right" onclick="mw.client_edit.save();">
                            <?php _e("Save"); ?>
                          </span> <span class="mw-ui-btn mw-ui-btn-small right" onclick="mw.client_edit.enable(this);">
                          <?php _e("Edit Information"); ?>
                        </span> </div>
                      </div>
                      <div class="vSpace"></div>
                      <div class="vSpace"></div>
                      <h2><?php _e("Orders from"); ?> <?php print $client['first_name'] ?> <?php print $client['last_name'] ?></h2>
                      <?php if(is_array($orders )): ?>
                      <?php foreach($orders  as $item): ?>
                      <div class="mw-o-box mw-o-box-accordion mw-accordion-active">
                        <div class="mw-o-box-header"> <span class="ico iorder"></span>
                          <div class="left">
                            <h2><span style="color: #0D5C98"><?php print $item['id'] ?> |</span><span class="font-12 relative" style="top: -2px;left: 6px;"><?php print $item['created_on'] ?></span> </h2>
                          </div>
						  
						  
						  
                          <span class="mw-ui-btn mw-ui-btn-small unselectable right" onmousedown="mw.tools.accordion(this.parentNode.parentNode);"><?php _e("Show Order"); ?> <span class="mw-ui-arr"></span></span> <span class="hSpace right"></span> <a href="<?php print $config['url'] ?>/../action:orders#?vieworder=<?php print $item['id'] ?>" class="mw-ui-btn mw-ui-btn-blue mw-ui-btn-small unselectable right"><span class="mw-ui-arr mw-ui-arr-left mw-ui-arr-blue "></span> <?php _e("Go to this order"); ?></a> </div>
                          <div class="mw-o-box-content mw-accordion-content">
                            <?php $cart_items = get_cart('order_completed=any&order_id='.$item['id'].'&no_session_id=1'); 	?>
                            <?php if(is_array($cart_items)): ?>
                            <table cellspacing="0" cellpadding="0" class="mw-o-box-table" width="935">
                              <thead>
                                <tr>
                                  <th><?php _e("Product Name"); ?></th>
                                  <th><?php _e("Price"); ?></th>
                                  <th><?php _e("QTY"); ?></th>
                                  <th><?php _e("Total"); ?></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach($cart_items  as $cart_item): ?>
                                <tr class="mw-order-item mw-order-item-1">
                                  <td class="mw-order-item-id"><?php print $cart_item['title'] ?></td>
                                  <td class="mw-order-item-amount"><?php print $cart_item['price'] ?></td>
                                  <td class="mw-order-item-amount"><?php print $cart_item['qty'] ?></td>
                                  <td class="mw-order-item-count"><?php print currency_format($cart_item['price']*$cart_item['qty'],$item['currency']) ?></td>
                                </tr>
                              <?php endforeach ; ?>
                            </tbody>
                          </table>
                        <?php else : ?>
                        <?php _e("Cannot get order's items"); ?>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach ; ?>
              <?php endif; ?>
            </div>
          <?php else : ?>
          <?php _e("Please set order-id parameter"); ?>
        <?php endif; ?>
