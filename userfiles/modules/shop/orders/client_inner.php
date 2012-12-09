<? if(isset($params['order-id']) == true): ?>
<? 
$client = get_orders('one=1&id='.intval($params['order-id']));
$orders = get_orders('order_by=created_on desc&is_paid=y&email='.$client['email']);
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
           var URL = 'somewhere/over/the/rainbow';
           if(!mw.$('.mw-client-information').hasClass('nonactive')){
             var obj = mw.form.serialize('.mw-client-information');
             $.post(URL, obj);
           }
           mw.client_edit.disable();
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
    <div class="mw-o-box-header"> <span class="ico iusers"></span> <span>Client Information</span> </div>

        <div class="mw-client-image left">
          <div class="mw-client-image-holder">
            <input type="hidden" name="client_image" id="client_image" />
          </div>

          <center><span onclick="mw.wysiwyg.request_image('#add_client_image');" class="mw-ui-btn mw-ui-btn-small"><?php _e("Upload Image"); ?></span></center>
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
                      <input class="left" type="text" name="first_name" value="<? print $client['first_name'] ?>" />
                      <input class="right" type="text" name="last_name" value="<? print $client['last_name'] ?>" />
                      <span class="val"><? print $client['first_name'] ?></span>
                      <span class="val"><? print $client['last_name'] ?></span>
                  </td>
                  <td>
                    <input type="text" name="email" value="<? print $client['email'] ?>" />
                    <span class="val"><? print $client['email'] ?></span>
                  </td>
                  <td>
                    <input type="text" name="phone" value="<? print $client['phone'] ?>" />
                    <span class="val"><? print $client['phone'] ?></span>
                  </td>
                  <td>
                    <input type="text" name="country" value="<? print $client['country'] ?>" />
                    <span class="val"><? print $client['country'] ?></span>
                  </td>
                  <td>
                    <input type="text" name="city" value="<? print $client['city'] ?>" />
                    <span class="val"><? print $client['city'] ?></span>
                  </td>
                  <td>
                    <input type="text" name="state" value="<? print $client['state'] ?>" />
                    <span class="val"><? print $client['state'] ?></span>
                  </td>
                  <td>
                    <input type="text" name="zip" value="<? print $client['zip'] ?>" />
                    <span class="val"><? print $client['zip'] ?></span>
                  </td>
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
                  <td width="50%">
                    <input style="width:380px" type="text" name="address" value="<? print $client['address'] ?>" />
                    <span class="val"><? print $client['address'] ?></span>
                  </td>
                  <td width="50%">
                    <input  style="width:380px"type="text" name="address2" value="<? print $client['address2'] ?>" />
                    <span class="val"><? print $client['address2'] ?></span>
                  </td>
                </tr>
              </tbody>
        </table>

   </div>

    <div class='mw-save-bar mw-save-bar-edit-client'>
      <span class="mw-ui-btn mw-ui-btn-small right" onclick="mw.client_edit.save();"><?php _e("Save"); ?></span>
      <span class="mw-ui-btn mw-ui-btn-small right" onclick="mw.client_edit.enable(this);"><?php _e("Edit Information"); ?></span>
    </div>



</div>



     <div class="vSpace"></div>
     <div class="vSpace"></div>


  <? if(isarr($orders )): ?>
  <? foreach($orders  as $item): ?>
  <div class="mw-o-box mw-o-box-accordion mw-accordion-active">
    <div class="mw-o-box-header"> <span class="ico iorder"></span>
      <div class="left">
        <h2><span style="color: #0D5C98"><? print $item['id'] ?> |</span><span class="font-12 relative" style="top: -2px;left: 6px;"><? print $item['created_on'] ?></span> </h2>
      </div>
      <span class="mw-ui-btn mw-ui-btn-small unselectable right" onmousedown="mw.tools.accordion(this.parentNode.parentNode);">Show Order <span class="mw-ui-arr"></span></span> <span class="hSpace right"></span> <a href="<? print template_var('url'); ?>/../action:orders#?vieworder=<? print $item['id'] ?>" class="mw-ui-btn mw-ui-btn-blue mw-ui-btn-small unselectable right"><span class="mw-ui-arr mw-ui-arr-left mw-ui-arr-blue "></span> Go to this order</a> </div>
    <div class="mw-o-box-accordion-content">
      <? $cart_items = get_cart('order_id='.$item['id'].'&no_session_id=1'); 	?>
      <? if(isarr($cart_items)): ?>
      <table cellspacing="0" cellpadding="0" class="mw-o-box-table" width="935">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>QTY</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <? foreach($cart_items  as $cart_item): ?>
          <tr class="mw-order-item mw-order-item-1">
            <td class="mw-order-item-id"><? print $cart_item['title'] ?></td>
            <td class="mw-order-item-amount"><? print $cart_item['price'] ?></td>
            <td class="mw-order-item-amount"><? print $cart_item['qty'] ?></td>
            <td class="mw-order-item-count"><? print $cart_item['price']*$cart_item['qty'] ?></td>
          </tr>
          <? endforeach ; ?>
        </tbody>
      </table>
      <? else : ?>
      Cannot get order's items
      <? endif; ?>
    </div>
  </div>
  <? endforeach ; ?>
  <? endif; ?>



</div>
<? else : ?>
Please set order-id parameter
<? endif; ?>
