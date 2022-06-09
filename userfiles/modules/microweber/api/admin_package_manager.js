mw.admin = mw.admin || {};
mw.admin.admin_package_manager = mw.admin.admin_package_manager || {}


mw.admin.admin_package_manager.set_loading = function (is_loading) {

    mw.tools.loading(document.querySelector('.js-install-package-loading-container-confirm'), is_loading, 'slow');
    mw.tools.loading(document.querySelector('#mw-packages-browser-nav-tabs-nav'), is_loading, 'slow');
    mw.tools.loading(document.querySelector('.admin-toolbar'), is_loading, 'slow');
    mw.tools.loading(document.querySelector('#update_queue_set_modal'), is_loading, 'slow');

}


mw.admin.admin_package_manager.reload_packages_list = function () {
    mw.admin.admin_package_manager.set_loading(true)
    setTimeout(function () {
        mw.notification.success('Reloading packages', 15000);
    }, 1000);
    mw.clear_cache();
    mw.admin.admin_package_manager.set_loading(true)

    setTimeout(function () {
        mw.reload_module('admin/developer_tools/package_manager/browse_packages', function () {
            mw.admin.admin_package_manager.set_loading(false)
            mw.notification.success('Packages are reloaded');
        })

    }, 1000);


}

mw.admin.admin_package_manager.show_licenses_modal = function () {

    var data = {}
    licensesModal = mw.tools.open_module_modal('settings/group/licenses', data, {
          overlay: true,
        //  iframe: true,
        height: 'auto',
        width: 960,
        title: 'Licenses',
        skin: 'simple'
    });
}


mw.admin.admin_package_manager.install_composer_package_by_package_name = function ($key, $version, $callback) {

    mw.notification.success('Loading...', 25000);
    //mw.load_module('updates/worker', '#mw-updates-queue');

    var update_queue_set_modal = mw.dialog({
        content: '<div class="module" type="updates/worker" id="update_queue_process_alert"></div>',
        overlay: false,
        id: 'update_queue_set_modal',
        title: 'Preparing'
    });

    mw.reload_module('#update_queue_process_alert');

    mw.admin.admin_package_manager.set_loading(50)

    var values = {require_name: $key, require_version: $version};

    mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax(values, $callback);





}

mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax_last_step_vals = null;


mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax = function (values, callback) {
    $.ajax({
        url: mw.settings.api_url + "mw_composer_install_package_by_name",
        type: "post",
        data: values,
        success: function (msg) {
            mw.admin.admin_package_manager.set_loading(true);

            if (typeof msg == 'object' && msg.try_again  && msg.unzip_cache_key) {
                if (msg.try_again) {
                    values.try_again_step = true;
                    values.unzip_cache_key =  msg.unzip_cache_key;
                    mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax_last_step_vals = values;
                    setTimeout(function(){
                        mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax(values, callback);

                    }, 500);



                    return;
                }
            } else {

                if (typeof callback === 'function') {
                    return callback(msg);
                }

                mw.notification.msg(msg);
                mw.admin.admin_package_manager.set_loading(false);

                mw.admin.admin_package_manager.reload_packages_list();
                mw.admin.admin_package_manager.set_loading(false);

                mw.$('#update_queue_set_modal').remove();

            }



        },

        error: function (jqXHR, textStatus, errorThrown) {


            mw.admin.admin_package_manager.set_loading(false);

            setTimeout(function(){
                mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax(mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax_last_step_vals);

            }, 500);
        }

    }).always(function (jqXHR, textStatus) {

        if (typeof callback === 'function') {
            return callback(jqXHR);
        }

        if(typeof(context) != 'undefined' ) {
            mw.spinner({ element: $(context).next() }).hide();
            $(context).show();
        }

        mw.$('#update_queue_set_modal').remove();

        mw.admin.admin_package_manager.set_loading(false);


    })

}

