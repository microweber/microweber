<?php



event_bind('mw.admin.shop.settings.menu', function ($data) {
    print ' <div class="card-header col-12 col-sm-6 col-lg-4">
                <a href="?group=shop/shipping/admin" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-truck-outline fs-1 me-2"></i></div>

                    <div class="card-title info-holder">
                        <span class="text-outline-primary font-weight-bold">' . _e('Shipping', true) . '</span><br/>
                        <small class="text-muted">'. _e('Delivery methods and suppliers', true) .'</small>
                    </div>
                </a>
            </div>';
});
