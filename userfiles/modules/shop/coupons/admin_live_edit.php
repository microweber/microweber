<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
    <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
</nav>

<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="settings">
        <!-- Settings Content -->
        <div class="module-live-edit-settings module-coupons-settings">
            <div class="mw-ui-field-holder add-new-button text-end text-right m-b-10">
                <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded js-add-new-coupon" href="#"><i class="fa fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
            </div>

            <module type="shop/coupons/edit_coupons"/>
        </div>
        <!-- Settings Content - End -->
    </div>

    <div class="tab-pane fade" id="templates">
        <module type="admin/modules/templates"/>
    </div>
</div>
