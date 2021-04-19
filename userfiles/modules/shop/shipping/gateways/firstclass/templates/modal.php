<?php

/*

type: layout

name: Default

description: Default

*/
?>


<script>
    $(document).ready(function () {

        if (!!$.fn.selectpicker) {
            $('.selectpicker').selectpicker();
        }

    });
</script>


<div class="<?php print $config['module_class'] ?>" id="<?php print $module_wrapper_id ?>">


    <?php $selected_country = mw()->user_manager->session_get('shipping_country'); ?>


    <?php
    $checkout_session = session_get('checkout');
    ?>


       <div class="my-4 edit nodrop" field="checkout_shipping_information_title" rel="global"
            rel_id="<?php print $params['id'] ?>">
           <small class="pull-right text-muted">*<?php _e("Fields are required"); ?></small>
           <label class="control-label"><?php _e("Shipping Information"); ?></label>
           <small class="text-muted d-block mb-2"> <?php _e("Add your shipping information"); ?></small>
       </div>
       <?php if(!$disable_default_shipping_fields) :?>
           <div class="row">
               <?php if ($data) { ?>
                   <div class="col-12 col-md-6">
                           <label for="exampleInputEmail1"><?php _e("Country"); ?></label>
                           <select required name="country" class="selectpicker shipping-country-select w-100">
                               <option value=""><?php _e("Country"); ?></option>
                               <?php foreach ($data as $item): ?>
                                   <option value="<?php print $item['shipping_country'] ?>" <?php if (isset($selected_country) and $selected_country == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
                               <?php endforeach; ?>
                           </select>
                   </div>
               <?php } ?>

               <div class="col-12 col-md-6">
                   <div class="form-group">
                       <label for="exampleInputEmail1"><?php _e("Town / City"); ?></label>
                       <input required type="text" value="<?php if (!empty($checkout_session['city'])) echo $checkout_session['city']; ?>" class="form-control" name="Address[city]"
                              placeholder="<?php _e("Town / City"); ?>">
                   </div>
               </div>
           </div>

           <div class="row">
               <div class="col-12 col-md-6">
                   <div class="form-group">
                       <label for="exampleInputEmail1"><?php _e("ZIP / Postal Code"); ?></label>
                       <input required type="text" value="<?php if (!empty($checkout_session['zip'])) echo $checkout_session['zip']; ?>" class="form-control" name="Address[zip]"
                              placeholder="<?php _e("ZIP / Postal Code"); ?>">
                   </div>
               </div>

               <div class="col-12 col-md-6">
                   <div class="form-group">
                       <label for="exampleInputEmail1"><?php _e("State / Province"); ?></label>
                       <input type="text" value="<?php if (!empty($checkout_session['state'])) echo $checkout_session['state']; ?>" class="form-control" name="Address[state]"
                              placeholder="<?php _e("State / Province"); ?>">
                   </div>
               </div>
           </div>

           <div class="row">
               <div class="col-12 col-md-6">
                   <div class="form-group">
                       <label for="exampleInputEmail1"><?php _e("Address / Street address"); ?></label>
                       <input required type="text" value="<?php if (!empty($checkout_session['address'])) echo $checkout_session['address']; ?>" class="form-control" name="Address[address]"
                              placeholder="<?php _e("Address / Street address, Floor, Apartment, etc..."); ?>">
                   </div>

               </div>
               <div class="col-12 col-md-6">
                   <div class="form-group">
                       <label for="exampleInputEmail1"><?php _e("Additional Information"); ?></label>
                       <input  type="text" value="<?php if (!empty($checkout_session['other_info'])) echo $checkout_session['other_info']; ?>" class="form-control" name="other_info"
                               placeholder="<?php _e("Additional Information ( Special notes for delivery - Optional )"); ?>">
                   </div>
               </div>
           </div>
       <?php endif;?>

       <?php if($enable_custom_shipping_fields) :?>
           <module type="custom_fields" for-id="shipping"  for="module"  default-fields="message"   />
       <?php endif;?>
   </div>

