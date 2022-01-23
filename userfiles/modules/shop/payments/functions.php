<?php



event_bind('mw.admin.shop.settings.menu', function ($data) {
    print ' <div class="col-12 col-sm-6 col-lg-4">
                <a href="#option_group=shop/payments/admin" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-cash-usd-outline mdi-20px"></i></div>

                    <div class="info-holder">
                        <span class="text-outline-primary font-weight-bold">' . _e('Payments', true) . '</span><br/>
                        <small class="text-muted">'. _e('Select and set up a payment provider', true) .'</small>
                    </div>
                </a>
            </div>';
});
