import axios from 'axios';

export const Modules = {

    modulesListData: null,

    list: async function () {

        if (this.modulesListData) {
            return this.modulesListData;
        }

        await axios.get(route('api.module.list') + '?layout_type=module')
            .then((response) => {
                this.modulesListData = response.data;
            });

        return this.modulesListData;

    },
    modulesSkinsData: [],
    getSkins: async function (module) {
        if (this.modulesSkinsData[module]) {
            return this.modulesSkinsData[module];
        }

        await axios.get(route('api.module.getSkins') + '?module=' + module)
            .then((response) => {
                this.modulesSkinsData[module] = response.data;
            });

        if (this.modulesSkinsData[module]) {
            return this.modulesSkinsData[module];
        }

    }

}
