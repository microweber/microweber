import axios from 'axios';

export const Modules = {

    modulesListData: null,

    list: async function () {

        if (this.modulesListData) {
            return this.modulesListData;
        }

        await axios.get(`${mw.settings.site_url}api/module/list?layout_type=module`)
            .then((response) => {
            this.modulesListData = response.data;
        });

        return this.modulesListData;

    }

}
