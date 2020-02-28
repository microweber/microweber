<?php only_admin_access() ?>

<?php


$check = __mw_check_core_system_update();
if ($check and is_array($check) and !isset($_COOKIE['mw_dont_show_update_notif'])) {
    ?>

    <a class="active" href="<?php print admin_url(); ?>view:settings#option_group=updates">
        <span class="mai-thunder"></span> <sup class="mw-notification-count">1</sup> <strong>
            <?php _e("Updates"); ?>
        </strong>
    </a>


    <script>
        $(document).ready(function () {
            if ($('.mw-admin-dashboard-main').length) {

                mw.modal({
                    height: 440,
                    width: 590,
                    id: 'mw-js-update-modal-notification-modal',
                    content: $('#mw-js-update-modal-notification').html()
                });


            }

            $('.js-modal-update-holder').parent().css({'padding': '0'});
        })


    </script>

    <script>
        mw.set_dont_show_update_cookie = function () {
            mw.cookie.set('mw_dont_show_update_notif', true);
            $('#mw-js-update-modal-notification-modal').remove();
        }
    </script>

    <div id="mw-js-update-modal-notification" style="display: none" class="">

        <style>
            .js-modal-update-holder h1 {
                font-size: 24px;
                font-weight: bold;
                padding: 0 40px;
                margin-bottom: 10px;
            }

            .js-modal-update-holder p {
                font-size: 15px;
                padding: 0 40px;
                margin-bottom: 10px;

            }

            .js-modal-update-holder a.link {
                font-size: 15px;
                padding: 0 40px;
                color: #0086db;
                margin-bottom: 10px;
                display: block;

            }
        </style>
        <div class="text-center js-modal-update-holder">
            <img src="<?php print modules_url(); ?>/updates/update_header.jpg" alt=""/>
            <h1>New version is available.</h1>
            <a href="https://github.com/microweber/microweber/blob/master/CHANGELOG.md" target="_blank" class="link">version <?php echo MW_VERSION; ?></a>
            <p>Please update the system to the latest bug-fixes and improvements.</p>
            <p>Regular updating of the system helps to improve the performance of your website and increase it’s security.</p>            <br>

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


