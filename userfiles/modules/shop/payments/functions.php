<?php



event_bind('mw.admin.shop.settings.menu', function ($data) {
    print ' <div class="col-4">
                <a href="?group=shipping" class="d-flex my-3">
                            <div class="icon-holder"><i class="mdi mdi-cash-usd-outline mdi-20px"></i></div>

                    <div class="info-holder">
                        <span class="text-primary font-weight-bold">' . _e('Payments', true) . '</span><br/>
                        <small class="text-muted">Select and set up a payment provider</small>
                    </div>
                </a>
            </div>';
});