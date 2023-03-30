export const Layouts = {

    list:  function () {

        return new Promise(function (resolve, reject) {

         fetch(`${mw.settings.site_url}api/module/list?layout_type=layout&elements_mode=true&group_layouts_by_category=true`)
            .then(function (data) {
                resolve( data.json());
            });
        });
    }

}
