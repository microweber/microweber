<script>
export default {

    data() {
        return {
            selectedContentId: 0,
            selectedContentTitle: '',
            liveEditSearchContentField: null,
        }
    },
    mounted() {
        this.liveEditSearchContentField = new mw.autoComplete({
            element: "#mw-live-edit-search-content",
            placeholder: "Search content",
            ajaxConfig: {
                method: 'get',
                url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&order_by=updated_at desc&is_active=1&is_deleted=0&keyword=${val}'
            },
            map: {
                value: 'id',
                title: 'title',
                image: 'picture'
            },
            selected: [
                {
                    id: 0,
                    title: '',
                    placeholder: 'All'
                }
            ]
        });

        $(this.liveEditSearchContentField).on("change", function (e, val) {
            var urlTogo = false;
            if (typeof (val[0]) !== 'undefined') {
                urlTogo = val[0].url;
              //  mw.app.canvas.getFrame().src = urlTogo  ;
                mw.app.canvas.dispatch('liveEditCanvasBeforeUnload');
                mw.app.canvas.getWindow().location.assign(urlTogo)

            }
        })

        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            var liveEditIframe = mw.app.canvas.getWindow();
            var liveEditIframeData = mw.app.canvas.getLiveEditData();
            if (liveEditIframeData
                && liveEditIframeData.content
                && liveEditIframeData.content.id
                && liveEditIframeData.content.title
            ) {
                this.selectedContentId = liveEditIframeData.content.id;
                this.selectedContentTitle = liveEditIframeData.content.title;
                this.liveEditSearchContentField.select(
                    {
                        id: this.selectedContentId,
                        title: this.selectedContentTitle,
                    }
                ,false);
            }
        })






    },
    methods: {


    }
}
</script>


<template>
    <div id="mw-live-edit-search-content"></div>
</template>
