<?php

event_bind(
    'mw.admin.dashboard.content.3', function ($item) {
    print '<div type="contact_form/dashboard_recent_messages" class="mw-lazy-load-module" id="admin-dashboard-contact-form"></div>';
}
);

event_bind('website.privacy_settings', function () {
    print '<h2>Contact Form settings</h2><module type="contact_form/privacy_settings" />';
});