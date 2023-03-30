export const Modules = {

    list:  function () {

        return new Promise(function (resolve, reject) {

         fetch(`${mw.settings.site_url}api/module/list?layout_type=module`)
            .then(function (data) {
                resolve( data.json());
            });
        });
    }

}
