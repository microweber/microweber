<?php

event_bind('mw.admin.shop.settings.menu', function ($data) {
    print ' <div class="card-header col-12 col-md-6 col-xxl-4 p-0">
                <a href="?group=shop/taxes/admin" class="d-flex settings-holder-wrapper">
                   <div class="icon-holder"><i class="mdi mdi-account-cash-outline fs-1"></i></div>

                    <div class="card-title info-holder">
                        <div class="settings-info-holder-title">' . _e('Taxes', true) . '</div>
                        <small class="text-muted">'. _e('Fees and tax rates', true) .'</small>
                    </div>
                </a>
            </div>';
});
