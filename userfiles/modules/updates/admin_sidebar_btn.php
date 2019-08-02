<?php only_admin_access() ?>

<?php


$check = __mw_check_core_system_update();
if ($check and is_array($check) and !isset($_COOKIE['mw_dont_show_update_notif'])) {
    ?>

    <a class="active" href="<?php print admin_url(); ?>view:settings#option_group=updates">
        <span class="mai-notification"><sup class="mw-notification-count">new</sup></span> <strong>
            <?php _e("Updates"); ?>
        </strong>
    </a>


    <script>
        $(document).ready(function () {
            if ($('.mw-admin-dashboard-main').length) {

                mw.modal({
                    height: 250,
                    width: 300,
                    id: 'mw-js-update-modal-notification-modal',
                    content: $('#mw-js-update-modal-notification').html()
                });


            }
        })


    </script>

    <script>
        mw.set_dont_show_update_cookie = function () {
            mw.cookie.set('mw_dont_show_update_notif', true);
            $('#mw-js-update-modal-notification-modal').remove();
        }
    </script>

    <div id="mw-js-update-modal-notification" style="display: none" class="">


        <div class="text-center"><h1> New version is available.</h1>
            <h3>You can update the system to get the latest bug-fixes and improvements</h3>
            <br>

            <a class="mw-ui-btn mw-ui-btn-big mw-ui-btn-notification"
               href="<?php print admin_url(); ?>view:settings#option_group=updates">Click here to update</a>
            <br>
            <br>
            <small class="text-muted"><a href="javascript:mw.set_dont_show_update_cookie()" class="mw-ui-link">Don't
                    show again</a></small>
        </div>
    </div>
    <?php
}


