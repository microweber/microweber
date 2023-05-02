<?php



event_bind('mw.admin.shop.settings.menu', function ($data) {
    print ' <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
                <a href="?group=shop/shipping/admin" class="d-flex settings-holder-wrapper">
                            <div class="icon-holder"><i class="mdi mdi-truck-outline fs-1"></i></div>

                    <div class="card-title info-holder">
                        <div class="settings-info-holder-title">' . _e('Shipping', true) . '</div>
                        <small class="text-muted">'. _e('Delivery methods and suppliers', true) .'</small>
                    </div>
                </a>
            </div>';
});
