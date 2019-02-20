mw.admin = mw.admin || {};
mw.admin.admin_package_manager = mw.admin.admin_package_manager || {}


mw.admin.admin_package_manager.set_loading = function (is_loading) {

    mw.tools.loading(mwd.querySelector('.js-install-package-loading-container-confirm'), is_loading)
    mw.tools.loading(mwd.querySelector('#mw-packages-browser-nav-tabs-nav'), is_loading)
    mw.tools.loading(mwd.querySelector('.admin-toolbar'), is_loading)

}


mw.admin.admin_package_manager.reload_packages_list = function () {
    mw.admin.admin_package_manager.set_loading(true)
    mw.clear_cache();
    setTimeout(function(){
        mw.reload_module('admin/packages')
        mw.admin.admin_package_manager.set_loading(false)
    }, 3000);



}

mw.admin.admin_package_manager.show_licenses_modal = function () {
    var data = {}
    licensesModal = mw.tools.open_module_modal('settings/group/licenses', data, {
        //  overlay: true,
        //  iframe: true,

        title: 'Licenses',
        skin: 'simple'
    })


}


mw.admin.admin_package_manager.install_composer_package_by_package_name = function ($key, $version) {

    mw.notification.success('Loading...', 15000);
    mw.load_module('updates/worker', '#mw-updates-queue');

    mw.admin.admin_package_manager.set_loading(true)


    var values = {require_name: $key, require_version: $version};

    $.ajax({
        url: mw.settings.api_url + "mw_composer_install_package_by_name",
        type: "post",
        data: values,
        success: function (msg) {


            mw.notification.msg(msg);
            mw.admin.admin_package_manager.set_loading(false)

        },

        error: function (jqXHR, textStatus, errorThrown) {

        }

    }).always(function (jqXHR, textStatus) {
        mw.admin.admin_package_manager.set_loading(false)
    })

}


