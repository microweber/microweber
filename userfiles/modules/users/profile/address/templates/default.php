<ul class="nav nav-tabs" id="user-profile-address-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link" id="billing-address-tab" data-toggle="tab" href="#billing-address" role="tab" aria-controls="billing-address" aria-selected="false">Billing Address</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="shipping-address-tab" data-toggle="tab" href="#shipping-address" role="tab" aria-controls="shipping-address" aria-selected="false">Shipping Address</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane" id="billing-address" role="tabpanel" aria-labelledby="billing-address-tab">

    <div class="col-md-12 col-px-30">
        <h5 class="mb-3 font-weight-bold">Billing Address</h5>

        <div class="form-group d-none">
            <label class="control-label">Address Name:</label>
            <input type="text" class="form-control" value="" name="addresses[1][name]">
        </div>

        <div class="form-group">
            <label class="control-label">Company Name:</label>
            <input type="text" class="form-control" value="" name="addresses[1][company_name]">
        </div>

        <div class="form-group">
            <label class="control-label">Company ID:</label>
            <input type="text" class="form-control" value="" name="addresses[1][company_id]">
        </div>

        <div class="form-group">
            <label class="control-label">VAT number:</label>
            <input type="text" class="form-control" value="" name="addresses[1][company_vat]">
        </div>

        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="company_vat_registered" value="" name="addresses[1][company_vat_registered]">
                <label class="custom-control-label" for="company_vat_registered">VAT registered</label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">Address:</label>
            <textarea class="form-control" placeholder="Street 1" name="addresses[1][address_street_1]"></textarea>
        </div>

        <div class="form-group">
            <label class="control-label">City:</label>
            <input type="text" class="form-control" value="" name="addresses[1][city]">
        </div>

        <div class="form-group">
            <label class="control-label">Zip Code:</label>
            <input type="text" class="form-control" value="" name="addresses[1][zip]">
        </div>

        <div class="form-group">
            <label class="control-label">State:</label>
            <input type="text" class="form-control" value="" name="addresses[1][state]">
        </div>

        <div class="form-group">
            <label class="control-label d-block">Country:</label>
            <div class="dropdown bootstrap-select" style="width: 100%;">
                <select class="selectpicker" data-live-search="true" data-width="100%" data-size="5" name="addresses[1][country_id]" tabindex="-98">
                </select>
            </div>

            <input type="hidden" class="form-control" value="shipping" name="addresses[1][type]">
        </div>

    </div>
    </div>

    <div class="tab-pane" id="shipping-address" role="tabpanel" aria-labelledby="shipping-address-tab">


        <div class="col-md-12 col-px-30">
            <h5 class="mb-3 font-weight-bold">Shipping Address</h5>

            <div class="form-group d-none">
                <label class="control-label">Address Name:</label>
                <input type="text" class="form-control" value="" name="addresses[0][name]">
            </div>

            <div class="form-group">
                <label class="control-label">Address:</label>
                <textarea class="form-control" placeholder="Street 1" name="addresses[0][address_street_1]"></textarea>
            </div>

            <div class="form-group">
                <label class="control-label">City:</label>
                <input type="text" class="form-control" value="" name="addresses[0][city]">
            </div>

            <div class="form-group">
                <label class="control-label">Zip Code:</label>
                <input type="text" class="form-control" value="" name="addresses[0][zip]">
            </div>

            <div class="form-group">
                <label class="control-label">State:</label>
                <input type="text" class="form-control" value="" name="addresses[0][state]">
            </div>

            <div class="form-group">
                <label class="control-label d-block">Country:</label>
                <div class="dropdown bootstrap-select" style="width: 100%;">
                    <select class="selectpicker" data-live-search="true" data-width="100%" data-size="5" name="addresses[0][country_id]" tabindex="-98">
                    </select>
                </div>
            </div>

            <input type="hidden" class="form-control" value="shipping" name="addresses[0][type]">
        </div>


    </div>
</div>

<script>
    $(function () {
        $('#user-profile-address-tabs li:last-child a').tab('show')
    })
</script>
