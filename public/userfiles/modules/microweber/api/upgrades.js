window.onmessage = function (e) {

//    if ( e.origin !== "http://html5demos.com" ) {
//        return;
//    }


    if (typeof e.data != 'undefined') {


        if (typeof e.data.market_id != 'undefined' || typeof e.data.mw_version != 'undefined') {
            mw.notification.success("Installing item", 9000);

            if (typeof e.data.market_id != 'undefined') {
                var url = mw.settings.api_url + "mw_install_market_item";
            } else if (typeof e.data.mw_version != 'undefined') {
                var url = mw.settings.api_url + "mw_set_updates_queue";

            }

            $.post(url, e.data)
                .done(function (data) {
                    mw.notification.msg(data, 5000);

                    if (typeof(data.update_queue_set != 'undefined')) {


                        var update_queue_set_modal = mw.dialog({
                            content: '<div class="module" type="updates/worker" id="update_queue_process_alert"></div>',
                            overlay: false,
                            id: 'update_queue_set_modal',
                            title: 'Installing'
                        });


                        mw.reload_module('#update_queue_process_alert');
                        mw.reload_module('updates/list');
                    }

                });
        }
    }
    // document.getElementById("test").innerHTML = e.origin + " said: " + e.data;
};


mw.upgrades = mw.upgrades || {
        add: function ($id) {
            alert($id);
        }
    }
