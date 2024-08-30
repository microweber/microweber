<?php

if (MW_VERSION < '1.2.0') {
    event_bind('mw.admin.header.toolbar', function () {
        echo '<div class="mw-ui-col pull-right">
         <module type="multilanguage/change_language"></module>
    </div>';
    });
}
if (MW_VERSION >= '1.2.0') {
    event_bind('mw.admin.header.toolbar.ul', function () {
        echo '<module type="multilanguage" show_settings_link="true" template="admin_v1.2"></module>';
    });
}

event_bind('live_edit_toolbar_action_buttons', function () {
    echo '<module type="multilanguage/change_language"></module>';
});

