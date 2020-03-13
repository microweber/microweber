<?php

only_admin_access();
?>
<?php $get_log_file_url = mw()->update->get_log_file_url(); ?>

<script>
    var mw_apply_upd_timeout = false;
    var mw_apply_upd_ajax = false;
    var timeout = 3000;

    $(document).ready(function () {

        mw_apply_upd_set_log_display();

        var $sel = mw.$('.mw-select-updates-list');

        if ($sel.length) {
            //     mw_apply_upd();
            //    move_loading_bar();
        }


    });

    function mw_apply_upd_set_log_display() {

        if (typeof mw_apply_upd_ajax == 'object') {
            mw_apply_upd_ajax.onreadystatechange = null;

            mw_apply_upd_ajax.abort();

        }
        if ($("#mw-update-res-log").is(":visible")) {
            mw_apply_upd_ajax = $.get('<?php print $get_log_file_url ?>', function (data) {

                $('#mw-update-res-log').html(data);

                var height =  $('#mw-update-res-log').get(0).scrollHeight;
                $('.mw_modal_container', '#update_queue_set_modal').animate({
                    scrollTop: height
                }, 500);



            });
        } else {
            if (typeof mw_apply_upd_ajax == 'object') {

                try {
                    mw_apply_upd_ajax.onreadystatechange = null;

                    mw_apply_upd_ajax.abort();
                }
                catch(error) {

                }

            }
        }


    }

    var mw_apply_upd_timeout = setInterval(function () {
        mw_apply_upd_set_log_display()
    }, 1000);


    function move_loading_bar() {
        return;
        $('#myProgress').show();
        var elem = document.getElementById("myBar");
        var width = 1;
        var id = setInterval(frame, 200);

        function frame() {
            if (width >= 100) {
                clearInterval(id);
            } else {
                width++;
                elem.style.width = width + '%';
                elem.innerHTML = width * 1 + '%';
            }
        }
    }


    mw_apply_upd_step = 1;

    function mw_apply_upd() {


        $.ajax({
            url: '<?php print api_link(); ?>mw_apply_updates_queue',
            type: "post",
            data: {step: mw_apply_upd_step},

            error: function (request, status, error) {
                $('#mw-update-res-log').append('<?php _e('Working...'); ?>');
                mw_apply_upd();
                //setTimeout(mw_apply_upd, timeout);
            },
            success: function (resp) {

                mw_apply_upd_step++;


                var msg = '';
                if (resp == 'done') {
                    var msg = 'done';
                }

                if (typeof (resp.try_again) != 'undefined') {
                    mw_apply_upd();
                    return;
                }


                if (typeof (resp.message) != 'undefined') {
                    var msg = resp.message;
                }


                //   alert(typeof (resp.message));
                $('#mw-update-res-log').html(msg);
                if (msg == 'done') {
                    $('#mw-update-res-log').addClass('done');
                    $('.show-on-install-complete').addClass('done');
                    $('.hide-on-install-complete').hide();
                    mw.trigger('mw_updates_done')

                } else {
                    //  mw_apply_upd_timeout = setTimeout(mw_apply_upd, timeout);
                }
                clearTimeout(mw_apply_upd_timeout);


            }
        });

    }

</script>
<style>
    #mw-update-res-log {
        word-wrap: break-word;
        word-break: break-all;
        background: #efecec;
        overflow-x: scroll;
    }

    #mw-update-res-log.done {
        height: 250px;
        overflow-y: scroll;
        overflow-x: hidden;

    }

    .show-on-install-complete {
        display: none;
    }

    .show-on-install-complete.done {
        display: block;
    }

    #myBar {
        width: 10%;
        height: 30px;
        background-color: #4CAF50;
        text-align: center; /* To center it horizontally (if you want) */
        line-height: 30px; /* To center it vertically */
        color: white;
    }

    #myProgress {
        display: none;
    }


</style>
<div>
    <div class="show-on-install-complete">
        <center><h1>Installation complete</h1>
        </center>
    </div>
    <div id="myProgress" class="hide-on-install-complete">
        <div id="myBar">0%</div>
    </div>

    <pre id="mw-update-res-log" style=""></pre>
    <div class="show-on-install-complete">
        <center>
            <button class="mw-ui-btn mw-ui-btn-invert" onclick="$('#update_queue_set_modal').remove();">Close</button>
        </center>
    </div>
</div>