<?php

/*

type: layout

name: Country 1

description: Default

*/
?>

<div class="<?php print $config['module_class'] ?>">


    <?php if(!$disable_default_shipping_fields) :?>
        <div id="<?php print $rand; ?>">
            <?php $selected_country = mw()->user_manager->session_get('shipping_country'); ?>

            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <label for="country" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("Country"); ?></label>
                        <div class="col-xs-12 col-sm-6 col-md-8">
                            <select name="country" class="form-control">
                                <option value=""><?php _e("Country"); ?></option>
                                <?php foreach ($data as $item): ?>
                                    <option value="<?php print $item['shipping_country'] ?>" <?php if (isset($selected_country) and $selected_country == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <label for="city" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("Town / City"); ?></label>
                        <div class="col-xs-12 col-sm-6 col-md-8">
                            <input name="Address[city]" class="form-control" type="text" value=""/>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <label for="zip" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("ZIP / Postal Code"); ?></label>
                        <div class="col-xs-12 col-sm-6 col-md-8">
                            <input name="Address[zip]" class="form-control" type="text" value=""/>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <label for="state" class="col-xs-12 col-sm-6 col-md-4 control-label"><?php _e("State / Province"); ?></label>
                        <div class="col-xs-12 col-sm-6 col-md-8">
                            <input name="Address[state]" class="form-control" type="text" value=""/>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-10">
                    <div class="form-group">
                        <label for="address" class="col-xs-6 col-md-2 control-label"><?php _e("Address"); ?></label>
                        <div class="col-xs-6 col-md-10">
                            <input name="Address[address]" class="form-control" type="text" value="" placeholder="Street address, Floor, Apartment, etc..."/>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-10">
                    <div class="form-group">
                        <label for="note" class="col-xs-6 col-md-2 control-label"><?php _e("Note"); ?></label>
                        <div class="col-xs-6 col-md-10">
                            <input name="other_info" class="form-control" type="text" value="" placeholder="Additional Information ( Special notes for delivery - Optional )"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>

    <?php if($enable_custom_shipping_fields) :?>
        <module type="custom_fields" for-id="shipping"  for-id="shipping"  default-fields="message"   />
    <?php endif;?>
</div>
