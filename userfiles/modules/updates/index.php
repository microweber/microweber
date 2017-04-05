<?php only_admin_access();

if (url_param('add_module')) {

}

$install = url_param('add_module');
mw()->notifications_manager->delete_for_module('updates');


?>
<script type="text/javascript">
    mw.require('forms.js', true);
</script>
<script type="text/javascript">

    mw.bind_update_form_submit = function () {


        mw.$('.mw-select-updates-list').submit(function () {


            if (mw.$(".update-items input:checked").length === 0) {
                Alert("Please select at least one item to update.")
                return false;
            }


            if (!mw.$("#installsubmit").hasClass("disabled")) {


                mw.tools.disable(mwd.getElementById('installsubmit'), '<?php _e("Installing"); ?>...', true);

                mw.form.post({
                    // url: '<?php print api_link(); ?>mw_apply_updates',
                    // url: '<?php print api_link(); ?>mw_apply_updates_queue',
                    url: '<?php print api_link(); ?>mw_set_updates_queue',
                    error: function () {
                        mw.tools.enable(mwd.getElementById('installsubmit'));
                        Alert("<?php _e("There was a Problem connecting to the Server"); ?>");
                    },
                    done: function () {


                        mw.load_module('updates/worker', '#mw-updates-queue');


                        //mw.tools.enable(mwd.getElementById('installsubmit'));
//                        Alert("Updates are successfully installed.");
//                        $('#number_of_updates').fadeOut();
//                            mw.reload_module('#mw-updates', function(){
//                            mw.bind_update_btns();
//                        });
                    },
                    selector: mw.$('.mw-select-updates-list')
                });


            }

            return false;

        });

    }
    mw.bind_update_btns = function () {


        mw.$('.mw-check-updates-btn').click(function () {
            if (!$(this).hasClass("disabled")) {

                var el = this;

                mw.tools.disable(el, '<?php _e("Checking"); ?>...', true);

                $("#mw-updates").attr('force', 'true');

                $(mwd.body).addClass("loading")

                mw.reload_module("#mw-updates", function (a, b) {
                    $(mwd.body).removeClass("loading");
                    mw.tools.enable(el);
                    mw.bind_update_form_submit();

                    if (this.querySelectorAll("tr.update-items").length == 0) {
                        mw.notification.success("<b> <?php _e("No new updates"); ?>.</b>");
                    } else {
                        mw.notification.warning("<b>" + this.querySelectorAll("tr.update-items").length + " <?php _e("new updates"); ?>.</b>");
                    }
                });


            }


        });

    }
    $(document).ready(function () {

        mw.bind_update_btns();
        mw.bind_update_form_submit();


        $(window).bind("mw_updates_done", function () {

            mw.tools.enable(mwd.getElementById('installsubmit'));
            Alert("Updates are successfully installed.");
            $('#number_of_updates').fadeOut();
            mw.reload_module('#mw-updates', function () {
                mw.bind_update_btns();
            });


        });


    });


</script>
<style type="text/css">
    #mw-updates-holder {
        padding: 0 20px;
        max-width: 960px;
    }

    #mw-update-table {
    }

    #updates-list-info {
        padding: 15px 0;
    }

    .mw-check-updates-btn {
        margin: 10px 0;
    }
</style>
<?php $notif_count = mw_updates_count() ?>

<div id="mw-updates-holder">
    <div class="mw-sided">
        <div class="mw-side-left" style="width: 170px;">
            <h2 class="mw-side-main-title relative"><span class="mw-icon-updates"></span><span>
        <?php _e("Updates"); ?>
        </span>
                <?php if ($notif_count != 0) : ?>
                    &nbsp;<sup class="mw-notification-count" id="number_of_updates"><?php print $notif_count ?></sup>
                <?php endif; ?>
            </h2>
            <span class="mw-check-updates-btn mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert"
                  title="<?php _e("Current version"); ?> <?php print MW_VERSION ?>">
      <?php _e("Check for updates"); ?>
      </span></div>
        <div class="mw-side-left" id="updates-list-info"><span
                    style="font-size: 18px;"><?php print user_name(); ?></span>,
            <?php _e("we are constantly trying to improve Microweber"); ?><br>
            <?php _e("Our team and many contributors around the world are working hard every day to provide you with a stable system and new updates"); ?>
            <?php _e("Please excuse us in case you find any problems and"); ?>
            <a href="//microweber.com/contact-us?user=<?php print user_name(); ?>" class="mw-ui-link">
                <?php _e("write us a message"); ?>
            </a>
            <?php _e("for all things you wish to see in Microweber or in any"); ?>
            <?php _e("Module"); ?>.
        </div>
    </div>
    <module type="updates/list" id="mw-updates"/>

    <div id="mw-updates-queue"></div>

</div>
