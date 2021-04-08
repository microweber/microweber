<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="<?php print $config['module_class'] ?>" id="<?php print $module_wrapper_id ?>">

    <?php $selected_country = mw()->user_manager->session_get('shipping_country'); ?>

    <?php
    $checkout_session = session_get('checkout_v2');
    ?>
       <?php if(!$disable_default_shipping_fields) :?>
           <div class="col-12">
               <div class="p-3">
                   <div class="edit nodrop pb-2" field="checkout_shipping_information_title" rel="global"
                        rel_id="<?php print $params['id'] ?>">
                       <label class="font-weight-bold control-label"><?php _e("Address for delivery"); ?></label>
                       <small class="text-muted d-block mb-2"> <?php _e("Please fill the fields bellow"); ?></small>
                   </div>
                   <?php if ($data) { ?>
                       <div class="form-group">
                           <label><?php _e("Country"); ?></label>
                           <select required name="country" class="form-control shipping-country-select w-100">
                               <option value=""><?php _e("Select your country"); ?></option>
                               <?php foreach ($data as $item): ?>
                                   <option value="<?php print $item['shipping_country'] ?>" <?php if (isset($selected_country) and $selected_country == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
                               <?php endforeach; ?>
                           </select>
                       </div>
                   <?php } ?>
                   <div class="form-group">
                       <label><?php _e("Town / City"); ?></label>
                       <input required type="text" value="<?php if (!empty($checkout_session['city'])) echo $checkout_session['city']; ?>" class="form-control" name="Address[city]">
                   </div>

                   <div class="form-group">
                       <label><?php _e("ZIP / Postal Code"); ?></label>
                       <input required type="text" value="<?php if (!empty($checkout_session['zip'])) echo $checkout_session['zip']; ?>" class="form-control" name="Address[zip]"
                       >
                   </div>

                   <div class="form-group">
                       <label><?php _e("State / Province"); ?></label>
                       <input type="text" value="<?php if (!empty($checkout_session['state'])) echo $checkout_session['state']; ?>" class="form-control" name="Address[state]">
                   </div>

                   <div class="form-group">
                       <label><?php _e("Address / Street address"); ?></label>
                       <input required type="text" value="<?php if (!empty($checkout_session['address'])) echo $checkout_session['address']; ?>" class="form-control" name="Address[address]">
                   </div>


                   <div class="form-group">
                       <label><?php _e("Additional Information"); ?> <small class="text-muted">(<?php _e("free text special notes to find the right address"); ?></small>)</label>
                       <input  type="text" value="<?php if (!empty($checkout_session['other_info'])) echo $checkout_session['other_info']; ?>" class="form-control" name="other_info">
                   </div>
               </div>
           </div>
       <?php endif;?>

       <?php if($enable_custom_shipping_fields) :?>
           <module type="custom_fields" for-id="shipping"  for="module"  default-fields="message"/>
       <?php endif;?>
   </div>

