
<div class="js-users-profile-address">

<ul class="nav nav-tabs" id="user-profile-address-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link" id="client-information-tab" data-bs-toggle="tab" href="#client-information" role="tab" aria-controls="client-information" aria-selected="false">Client Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="billing-address-tab" data-bs-toggle="tab" href="#billing-address" role="tab" aria-controls="billing-address" aria-selected="false">Billing Address</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="shipping-address-tab" data-bs-toggle="tab" href="#shipping-address" role="tab" aria-controls="shipping-address" aria-selected="false">Shipping Address</a>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane" id="client-information" role="tabpanel" aria-labelledby="client-information-tab">
        <div class="col-md-12 col-px-30">
            <h5 class="mb-3 font-weight-bold">Client information</h5>

            <div class="form-group">
                <label class="control-label">Display Name:</label>
                <input type="text" class="form-control" value="<?php echo $name;?>" required="required" name="name">
            </div>

            <div class="form-group">
                <label class="control-label">First Name:</label>
                <input type="text" class="form-control" value="<?php echo $first_name;?>" required="required" name="first_name">
            </div>

            <div class="form-group">
                <label class="control-label">Last Name:</label>
                <input type="text" class="form-control" value="<?php echo $last_name;?>" required="required" name="last_name">
            </div>

            <div class="form-group">
                <label class="control-label">Email:</label>
                <input type="email" class="form-control" value="<?php echo $email;?>" required="required" name="email">
            </div>

            <div class="form-group">
                <label class="control-label">Phone:</label>
                <input type="text" class="form-control" value="<?php echo $phone;?>" required="required" name="phone">
            </div>
        </div>
    </div>

    <div class="tab-pane" id="billing-address" role="tabpanel" aria-labelledby="billing-address-tab">

    <div class="col-md-12 col-px-30">
        <h5 class="mb-3 font-weight-bold">Billing Address</h5>

        <div class="form-group d-none">
            <label class="control-label">Address Name:</label>
            <input type="text" class="form-control" value="<?php echo $billing_address['name'];?>" name="addresses[1][name]">
        </div>

        <div class="form-group">
            <label class="control-label">Address:</label>
            <textarea class="form-control" placeholder="Street 1" name="addresses[1][address_street_1]"><?php echo $billing_address['address_street_1'];?></textarea>
        </div>

        <div class="form-group">
            <label class="control-label">City:</label>
            <input type="text" class="form-control" value="<?php echo $billing_address['city'];?>" name="addresses[1][city]">
        </div>

        <div class="form-group">
            <label class="control-label">Zip Code:</label>
            <input type="text" class="form-control" value="<?php echo $billing_address['zip'];?>" name="addresses[1][zip]">
        </div>

        <div class="form-group">
            <label class="control-label">State:</label>
            <input type="text" class="form-control" value="<?php echo $billing_address['state'];?>" name="addresses[1][state]">
        </div>
        <div class="form-group">
            <label class="control-label d-block">Country:</label>
            <?php if(!empty($countries)):?>

                <div class="dropdown bootstrap-select" style="width: 100%;">
                <select class="selectpicker" data-live-search="true" data-width="100%" data-size="5" name="addresses[1][country_id]" tabindex="-98">
                    <?php foreach ($countries as $country):?>
                        <option value="<?php echo $country['id']; ?>" <?php if ($billing_address['country_id'] == $country['id']): ?> selected="selected" <?php endif; ?>><?php echo $country['name']; ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <input type="hidden" class="form-control" value="billing" name="addresses[1][type]">
        </div>

    </div>
    </div>

    <div class="tab-pane" id="shipping-address" role="tabpanel" aria-labelledby="shipping-address-tab">


        <div class="col-md-12 col-px-30">
            <h5 class="mb-3 font-weight-bold">Shipping Address</h5>

            <div class="form-group d-none">
                <label class="control-label">Address Name:</label>
                <input type="text" class="form-control" value="<?php echo $shipping_address['name'];?>" name="addresses[0][name]">
            </div>

            <div class="form-group">
                <label class="control-label">Address:</label>
                <textarea class="form-control" placeholder="Street 1" name="addresses[0][address_street_1]"><?php echo $shipping_address['address_street_1'];?></textarea>
            </div>

            <div class="form-group">
                <label class="control-label">City:</label>
                <input type="text" class="form-control" value="<?php echo $shipping_address['city'];?>" name="addresses[0][city]">
            </div>

            <div class="form-group">
                <label class="control-label">Zip Code:</label>
                <input type="text" class="form-control" value="<?php echo $shipping_address['zip'];?>" name="addresses[0][zip]">
            </div>

            <div class="form-group">
                <label class="control-label">State:</label>
                <input type="text" class="form-control" value="<?php echo $shipping_address['state'];?>" name="addresses[0][state]">
            </div>

            <div class="form-group">
                <label class="control-label d-block">Country:</label>
                <?php if(!empty($countries)):?>

                <div class="dropdown bootstrap-select" style="width: 100%;">
                    <select class="selectpicker" data-live-search="true" data-width="100%" data-size="5" name="addresses[0][country_id]" tabindex="-98">
                        <?php foreach ($countries as $country):?>
                            <option value="<?php echo $country['id']; ?>" <?php if ($shipping_address['country_id'] == $country['id']): ?> selected="selected" <?php endif; ?>><?php echo $country['name']; ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <input type="hidden" class="form-control" value="shipping" name="addresses[0][type]">
        </div>


    </div>
    <button type="submit" class="btn btn-success js-users-profile-address-save"><i class="fa fa-save"></i> Save details</button>
</div>
</div>

<script>
    $(function () {
        $('.js-users-profile-address-save').click(function () {

            var userProfileData = $(".js-users-profile-address select, .js-users-profile-address input, .js-users-profile-address textarea, .js-users-profile-address select").serializeArray();

            $.post("<?php echo route('api.user.profile.update'); ?>", userProfileData)
            .done(function( data ) {
                mw.notification.success('<?php _e('Profile udpated.');?>');
            });

        });
        $('#user-profile-address-tabs li:last-child a').tab('show')
    })
</script>
