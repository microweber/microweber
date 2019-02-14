mw.admin = mw.admin || {};
mw.admin.admin_package_manager = mw.admin.admin_package_manager || {}









mw.admin.admin_package_manager.install_composer_package_by_package_name = function($key,$version) {

    mw.notification.success('Loading...');

    mw.tools.loading(mwd.querySelector('.js-install-package-loading-container'), true)

    var values =  { require_name: $key, require_version:$version };

    $.ajax({
        url: mw.settings.api_url + "mw_composer_install_package_by_name",
        type: "post",
        data: values ,
        success: function (msg) {


            mw.notification.msg(msg);
            mw.tools.loading(mwd.querySelector('.js-install-package-loading-container'), false)

        },


        error: function(jqXHR, textStatus, errorThrown) {

        }


    }).always(function(jqXHR, textStatus) {
        mw.tools.loading(mwd.querySelector('.js-install-package-loading-container'), false)

    })






}


