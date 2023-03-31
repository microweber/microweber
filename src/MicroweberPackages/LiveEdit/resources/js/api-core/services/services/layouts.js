import axios from 'axios';

export const Layouts = {

    layoutsListData: null,

    list: async function () {

        if (this.layoutsListData) {
            return this.layoutsListData;
        }

        await axios.get(route('api.module.list') + '?layout_type=layout&elements_mode=true&group_layouts_by_category=true')
            .then((response) => {
                this.layoutsListData = response.data;
            });


        return this.layoutsListData;

    }

}
