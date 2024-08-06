export class AdminPackageManager {
    constructor() {

    }


    set_loading(is_loading) {

        mw.tools.loading(document.querySelector('.js-install-package-loading-container-confirm'), is_loading, 'slow');
        mw.tools.loading(document.querySelector('#mw-packages-browser-nav-tabs-nav'), is_loading, 'slow');
        mw.tools.loading(document.querySelector('.admin-toolbar'), is_loading, 'slow');
        mw.tools.loading(document.querySelector('#update_queue_set_modal'), is_loading, 'slow');

    }

    reload_packages_list () {
        this.set_loading(true)
        mw.notification.success('Reloading packages', 15000);
        mw.clear_cache();
        window.location.reload();
    }

    show_licenses_modal () {

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

    install_composer_package_by_package_name ($key, $version, $callback) {

        mw.notification.success('Loading...', 25000);
        //mw.load_module('updates/worker', '#mw-updates-queue');

        var update_queue_set_modal = mw.dialog({
            content: '<div   id="update_queue_process_alert"></div>',
            overlay: false,
            height:'300px',
            id: 'update_queue_set_modal',
            title: 'Preparing'
        });

       // mw.reload_module('#update_queue_process_alert');

        this.set_loading(50)

        var values = {require_name: $key, require_version: $version};

        this.install_composer_package_by_package_name_do_ajax(values, $callback);

    }

    #last_step_vals = null;


    install_composer_package_by_package_name_do_ajax (values, callback) {
        $.ajax({
            url: mw.settings.api_url + "mw_composer_install_package_by_name",
            type: "post",
            data: values,
            success: msg => {
                this.set_loading(true);

                if (typeof msg == 'object' && msg.try_again  && msg.unzip_cache_key) {
                    if (msg.try_again) {
                        values.try_again_step = true;
                        values.unzip_cache_key =  msg.unzip_cache_key;
                        this.#last_step_vals = values;
                        setTimeout( () => {
                            this.install_composer_package_by_package_name_do_ajax(values, callback);

                        }, 500);



                        return;
                    }
                } else {

                    if (typeof callback === 'function') {
                        return callback(msg);
                    }

                    mw.notification.msg(msg);
                    this.set_loading(false);

                    this.reload_packages_list();
                    this.set_loading(false);

                    mw.$('#update_queue_set_modal').remove();

                }



            },

            error:   (jqXHR, textStatus, errorThrown) => {


                this.set_loading(false);

                setTimeout( () => {
                    this.install_composer_package_by_package_name_do_ajax(this.#last_step_vals);
                }, 500);
            }

        }).always(  (jqXHR, textStatus) => {

            if (typeof callback === 'function') {
                return callback(jqXHR);
            }



            mw.$('#update_queue_set_modal').remove();

            this.set_loading(false);


        })

    }


}
