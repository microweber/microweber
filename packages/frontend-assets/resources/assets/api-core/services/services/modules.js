import axios from 'axios';

export const Modules = {

    modulesListData: null,


    list:   function (cb) {

        if (this.modulesListData) {
            if (cb) {
                cb.call(undefined, this.modulesListData)
            }
            return this.modulesListData;
        }

        axios.get(route('api.module.list') + '?layout_type=module')
        .then((response) => {

            this.modulesListData = response.data;


            if (cb) {
                cb.call(undefined, this.modulesListData)
            }
        });



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

    },

    getModuleInfo:   function (module) {




        if ( this.modulesListData && this.modulesListData.modules) {
            var foundModule = this.modulesListData.modules.find(function (element) {

                return element.module == module;
            });


            return foundModule;

        }

    }

}


Modules.list();
