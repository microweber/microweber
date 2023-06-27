<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
    <a class="btn btn-outline-primary justify-content-center  active" data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>
    <a class="btn btn-outline-primary justify-content-center " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
</nav>

<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="settings">
        <!-- Settings Content -->
        <div class="module-live-edit-settings module-coupons-settings">
            <div class="add-new-button text-end mb-3">
                <a class="btn btn-link js-add-new-coupon me-2 mw-admin-action-links text-decoration-none tblr-body-color" href="#"><?php _e('Add new'); ?></a>
            </div>

            <module type="shop/coupons/edit_coupons"/>
        </div>
        <!-- Settings Content - End -->
    </div>

    <div class="tab-pane fade" id="templates">
        <module type="admin/modules/templates"/>
    </div>
</div>
