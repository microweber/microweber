import axios from 'axios';

export const Layouts = {

    layoutsListData: null,

    list: async function (cache = true) {

        if (this.layoutsListData && cache) {
            return this.layoutsListData;
        }

        var moreFilters = '';
        var liveEditIframeData = mw.top().app.canvas.getLiveEditData();

        if (liveEditIframeData && liveEditIframeData.template_name) {
            var template_name = liveEditIframeData.template_name;
            moreFilters += '&active_site_template=' + template_name;
        }

        await axios.get(route('api.module.list') + '?layout_type=layout&elements_mode=true&group_layouts_by_category=true' + moreFilters)
            .then((response) => {
                this.layoutsListData = response.data;
            });

        return this.layoutsListData;

    },

    layoutSkinsData: [],
    getSkins: async function () {
        var module = 'layouts';
        if (this.layoutSkinsData[module]) {
            return this.layoutSkinsData[module];
        }

        await axios.get(route('api.module.getSkins') + '?module=' + module)
            .then((response) => {
                this.layoutSkinsData[module] = response.data;
            });

        if (this.layoutSkinsData[module]) {
            return this.layoutSkinsData[module];
        }

    }

}
